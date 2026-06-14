<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the specified user's profile.
     */
    public function show($username)
    {
        $user = User::where('username', $username)
            ->with(['identification', 'followers.identification', 'following.identification'])
            ->withCount(['pastebins', 'followers', 'following', 'comments'])
            ->firstOrFail();

        $this->authorize('view', $user->identification);

        // Increment profile view count
        $sessionKey = 'profile_viewed_' . $user->id;
        if (!session()->has($sessionKey)) {
            $user->identification->increment('views');
            session()->put($sessionKey, true);
        }

        $profileComments = $user->profileComments()
            ->with('user.identification')
            ->latest()
            ->get();

        $recentContributions = $user->edits()
            ->where('status', 'approved')
            ->with('pastebin')
            ->latest()
            ->take(5)
            ->get();

        $recentPosts = $user->pastebins()
            ->latest()
            ->take(5)
            ->get();

        $recentComments = \App\Models\Comment::where('user_id', $user->id)
            ->with('pastebin')
            ->latest()
            ->take(5)
            ->get();

        // Advertiser-specific data
        $advertiserData = null;
        if ($user->identification?->role === \App\Enum\Role::ADVERTISER) {
            $advertiser = \App\Models\Advertiser::where('user_id', $user->id)
                ->with(['campaigns.ads' => fn($q) => $q->where('status', 'active')->with('statistics')])
                ->first();

            if ($advertiser) {
                // Collect only ACTIVE ads across all campaigns
                $activeAds = collect();
                foreach ($advertiser->campaigns as $campaign) {
                    foreach ($campaign->ads as $ad) {
                        $ad->campaign_name = $campaign->name;
                        $activeAds->push($ad);
                    }
                }

                // Aggregate stats from active ads only
                $totalClicks      = 0;
                $totalImpressions = 0;
                foreach ($activeAds as $ad) {
                    foreach ($ad->statistics as $stat) {
                        $totalClicks      += $stat->clicks;
                        $totalImpressions += $stat->impressions;
                    }
                }

                // Active banners: active ads that have a media_url
                $activeBanners = $activeAds->filter(fn($ad) => $ad->media_url)->values();

                $advertiserData = [
                    'advertiser'       => $advertiser,
                    'activeBanners'    => $activeBanners,
                    'activeAds'        => $activeAds,
                    'totalClicks'      => $totalClicks,
                    'totalImpressions' => $totalImpressions,
                    'totalCampaigns'   => $advertiser->campaigns->count(),
                    'totalActiveAds'   => $activeAds->count(),
                ];
            }
        }

        return view('profile.show', [
            'user'                => $user,
            'recentContributions' => $recentContributions,
            'recentPosts'         => $recentPosts,
            'recentComments'      => $recentComments,
            'advertiserData'      => $advertiserData,
            'profileComments'     => $profileComments,
        ]);
    }

    /**
     * Show the form for editing the authenticated user's profile.
     */
    public function edit()
    {
        $user = Auth::user()->load('identification');

        $this->authorize('update', $user->identification);

        return view('profile.edit', [
            'user' => $user,
            'identification' => $user->identification,
        ]);
    }

    /**
     * Update the authenticated user's profile in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $identification = $user->identification;

        $this->authorize('update', $identification);

        $canChangeUsername = $identification->username_changes < $identification->role->allowedUsernameChanges();

        $rules = [
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'gender' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'avatar' => [
                'nullable',
                'image',
                'max:2048', // 2MB
                $user->identification->role->canHaveGifAvatar() ? 'mimes:jpeg,png,jpg,gif' : 'mimes:jpeg,png,jpg',
            ],
            'custom_color' => [
                'nullable',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
                Rule::requiredIf($user->identification->has_custom_color_unlocked),
            ],
        ];

        if ($canChangeUsername && $request->has('username') && $request->username !== $user->username) {
            $rules['username'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
                'regex:/^\S+$/',
            ];
        }

        $messages = [
            'username.regex' => 'The username must not contain any spaces.',
        ];

        $request->validate($rules, $messages);

        // Update User table if username changed
        if ($canChangeUsername && $request->has('username') && $request->username !== $user->username) {
            $user->update([
                'username' => $request->username,
            ]);
            $identification->increment('username_changes');
        }

        // Update Identification table
        $data = [
            'bio' => $request->bio ?? 'N/A',
            'website' => $request->website ?? 'N/A',

        ];

        if ($user->identification->has_custom_color_unlocked && $request->has('custom_color')) {
            $data['custom_color'] = $request->custom_color;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($identification->avatar_path && $identification->avatar_path != 'upload/defaultAvatar.png') {
                Storage::disk('public')->delete($identification->avatar_path);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            $data['avatar_path'] = $path;
        }

        $identification->update($data);

        return redirect()->route('profile.show', $user->username)
            ->with('success', 'Profile updated successfully.');
    }

    public function ban(User $user)
    {
        $currentUserRole = auth()->user()->identification->role;
        if (!in_array($currentUserRole->value, ['owner', 'moderator'])) {
            abort(403, 'Unauthorized.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        if ($currentUserRole->value === 'moderator' && in_array($user->identification->role->value, ['owner', 'moderator'])) {
            return back()->with('error', 'Moderators cannot ban other staff members.');
        }

        $user->identification->update([
            'role' => \App\Enum\Role::BANNED,
        ]);

        return back()->with('success', "User @{$user->username} has been banned.");
    }

    public function unban(User $user)
    {
        $currentUserRole = auth()->user()->identification->role;
        if (!in_array($currentUserRole->value, ['owner', 'moderator'])) {
            abort(403, 'Unauthorized.');
        }

        $user->identification->update([
            'role' => \App\Enum\Role::MEMBER,
        ]);

        return back()->with('success', "User @{$user->username} has been unbanned.");
    }

    /**
     * Find and view all pastebins of a user with privacy controls.
     */
    public function allPastebins($username, Request $request)
    {
        $user = User::where('username', $username)->firstOrFail();

        $query = $user->pastebins()->latest();

        // Security filter: guests/other users can only see public, unsecured pastes
        if (!Auth::check() || Auth::id() !== $user->id) {
            $query->where('visibility', 'public')
                  ->whereNull('password')
                  ->where('is_self_destruct', false);
        }

        // Cursor-based for load-more speed
        $cursor = $request->input('cursor');
        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $pastes = $query->limit(15)->get();

        $nextCursor = null;
        if ($pastes->count() === 15) {
            $nextCursor = $pastes->last()->id;
        }

        $title = "@{$user->username}'s Pastebins";

        // AJAX Load More
        if ($request->ajax() || $request->input('ajax')) {
            $html = view('profile.partials.pastebin-rows', compact('pastes'))->render();
            return response()->json([
                'html'        => $html,
                'next_cursor' => $nextCursor,
            ]);
        }

        // Total count for header (only on first load)
        $total = $user->pastebins()
            ->when(
                !Auth::check() || Auth::id() !== $user->id,
                fn($q)
                => $q->where('visibility', 'public')->whereNull('password')->where('is_self_destruct', false)
            )->count();

        return view('profile.pastebins', compact('user', 'pastes', 'title', 'nextCursor', 'total'));
    }

    /**
     * Find and view all posts (comments) of a user with privacy controls.
     */
    public function allPosts($username, Request $request)
    {
        $user = User::where('username', $username)->firstOrFail();

        $query = \App\Models\Comment::where('user_id', $user->id)
            ->with('pastebin')
            ->latest();

        // Security filter: guests/other users can only see comments belonging to public, unsecured pastes
        if (!Auth::check() || Auth::id() !== $user->id) {
            $query->whereHas('pastebin', function ($q) {
                $q->where('visibility', 'public')
                  ->whereNull('password')
                  ->where('is_self_destruct', false);
            });
        }

        // Cursor-based for load-more speed
        $cursor = $request->input('cursor');
        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $comments = $query->limit(15)->get();

        $nextCursor = null;
        if ($comments->count() === 15) {
            $nextCursor = $comments->last()->id;
        }

        $title = "@{$user->username}'s Posts";

        // AJAX Load More
        if ($request->ajax() || $request->input('ajax')) {
            $html = view('profile.partials.post-rows', compact('comments'))->render();
            return response()->json([
                'html'        => $html,
                'next_cursor' => $nextCursor,
            ]);
        }

        // Total count for header (only on first load)
        $total = \App\Models\Comment::where('user_id', $user->id)
            ->when(
                !Auth::check() || Auth::id() !== $user->id,
                fn($q)
                => $q->whereHas(
                    'pastebin',
                    fn($p)
                    => $p->where('visibility', 'public')->whereNull('password')->where('is_self_destruct', false)
                )
            )->count();

        return view('profile.posts', compact('user', 'comments', 'title', 'nextCursor', 'total'));
    }

    /**
     * Display a public categorized list of community users.
     */
    public function usersList(Request $request)
    {
        $title = 'User';
        $perPage = 10;
        $search = trim((string) $request->query('q', ''));

        $staff = $this->usersListBaseQuery($search)
            ->whereIn('identifications.role', [Role::OWNER->value, Role::MODERATOR->value])
            ->paginate($perPage, ['*'], 'staff_page')
            ->withQueryString();

        $premium = $this->usersListBaseQuery($search)
            ->whereIn('identifications.role', [Role::RICH->value, Role::PRIME->value, Role::VIP->value])
            ->paginate($perPage, ['*'], 'premium_page')
            ->withQueryString();

        $advertisers = $this->usersListBaseQuery($search)
            ->where('identifications.role', Role::ADVERTISER->value)
            ->paginate($perPage, ['*'], 'advertisers_page')
            ->withQueryString();

        $members = $this->usersListBaseQuery($search)
            ->where(function ($q) {
                $q->whereNotIn('identifications.role', [
                    Role::OWNER->value,
                    Role::MODERATOR->value,
                    Role::RICH->value,
                    Role::PRIME->value,
                    Role::VIP->value,
                    Role::ADVERTISER->value,
                    Role::BANNED->value,
                ])->orWhereNull('identifications.role');
            })
            ->paginate($perPage, ['*'], 'members_page')
            ->withQueryString();

        foreach ([$staff, $premium, $advertisers, $members] as $paginator) {
            $paginator->getCollection()->transform(fn (User $u) => $this->decorateUserForList($u));
        }

        $totalResults = $staff->total() + $premium->total() + $advertisers->total() + $members->total();

        return view('profile.users-list', compact(
            'title',
            'staff',
            'premium',
            'advertisers',
            'members',
            'search',
            'totalResults',
        ));
    }

    /**
     * Base query for the public users list (non-banned, ordered by reputation).
     */
    private function usersListBaseQuery(?string $search = null)
    {
        $query = User::query()
            ->with('identification')
            ->leftJoin('identifications', 'users.id', '=', 'identifications.user_id')
            ->where(function ($q) {
                $q->where('identifications.role', '!=', Role::BANNED->value)
                    ->orWhereNull('identifications.role');
            })
            ->select('users.*')
            ->orderByDesc('identifications.reputation');

        if ($search !== null && $search !== '') {
            $this->applyUserSearchFilter($query, $search);
        }

        return $query;
    }

    /**
     * Apply username / email / id search to a user query.
     */
    private function applyUserSearchFilter($query, string $search): void
    {
        $term = trim($search);

        if ($term === '') {
            return;
        }

        $query->where(function ($q) use ($term) {
            $q->where('users.username', 'like', "%{$term}%")
                ->orWhere('users.email', 'like', "%{$term}%");

            if (ctype_digit($term)) {
                $q->orWhere('users.id', (int) $term);
            }
        });
    }

    /**
     * Attach display helpers used by the users list view.
     */
    private function decorateUserForList(User $user): User
    {
        $user->avatar_url = $user->identification && $user->identification->avatar_path
            ? asset('storage/' . $user->identification->avatar_path)
            : asset('storage/avatars/default.png');

        $user->display_style = $user->identification && $user->identification->role
            ? $user->identification->role->userStyleWithBanner(
                $user->username,
                $user->identification->color_username ?? '#ffffff'
            )
            : $user->username;

        return $user;
    }
}
