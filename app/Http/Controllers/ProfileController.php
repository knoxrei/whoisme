<?php

namespace App\Http\Controllers;

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

        return view('profile.show', [
            'user' => $user,
            'recentContributions' => $recentContributions,
            'recentPosts' => $recentPosts,
            'recentComments' => $recentComments,
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
                'alpha_dash',
            ];
        }

        $request->validate($rules);

        // Update User table if username changed
        if ($canChangeUsername && $request->has('username') && $request->username !== $user->username) {
            $user->update([
                'username' => $request->username,
            ]);
            $identification->increment('username_changes');
        }

        // Update Identification table
        $data = [
            'bio' => $request->bio,
            'website' => $request->website,
            'gender' => $request->gender,
            'location' => $request->location,
        ];

        if ($user->identification->has_custom_color_unlocked && $request->has('custom_color')) {
            $data['custom_color'] = $request->custom_color;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($identification->avatar_path) {
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
            ->when(!Auth::check() || Auth::id() !== $user->id, fn($q) =>
                $q->where('visibility', 'public')->whereNull('password')->where('is_self_destruct', false)
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
            ->when(!Auth::check() || Auth::id() !== $user->id, fn($q) =>
                $q->whereHas('pastebin', fn($p) =>
                    $p->where('visibility', 'public')->whereNull('password')->where('is_self_destruct', false)
                )
            )->count();

        return view('profile.posts', compact('user', 'comments', 'title', 'nextCursor', 'total'));
    }

    /**
     * Display a public categorized list of community users.
     */
    public function usersList()
    {
        $title = 'User';

        // Retrieve all users eager loaded with identification, ordered by reputation descending
        $users = User::with(['identification'])
            ->leftJoin('identifications', 'users.id', '=', 'identifications.user_id')
            ->select('users.*')
            ->orderBy('identifications.reputation', 'desc')
            ->get();

        // Initialize groups
        $staff = [];
        $premium = [];
        $advertisers = [];
        $members = [];

        foreach ($users as $u) {
            $role = $u->identification->role ?? \App\Enum\Role::MEMBER;

            // Skip banned users from the public list for clean visual
            if ($role === \App\Enum\Role::BANNED) {
                continue;
            }

            // Add helper attributes for display style
            $u->avatar_url = $u->identification && $u->identification->avatar_path
                ? asset('storage/' . $u->identification->avatar_path)
                : asset('storage/avatars/default.png');
                
            $u->display_style = $u->identification && $u->identification->role
                ? $u->identification->role->userStyleWithBanner($u->username, $u->identification->color_username ?? '#ffffff')
                : $u->username;

            // Grouping logic with maximum 10 entries per group for lightweight loading
            if ($role === \App\Enum\Role::OWNER || $role === \App\Enum\Role::MODERATOR) {
                if (count($staff) < 10) {
                    $staff[] = $u;
                }
            } elseif ($role === \App\Enum\Role::RICH || $role === \App\Enum\Role::PRIME || $role === \App\Enum\Role::VIP) {
                if (count($premium) < 10) {
                    $premium[] = $u;
                }
            } elseif ($role === \App\Enum\Role::ADVERTISER) {
                if (count($advertisers) < 10) {
                    $advertisers[] = $u;
                }
            } else {
                if (count($members) < 10) {
                    $members[] = $u;
                }
            }
        }

        return view('profile.users-list', compact('title', 'staff', 'premium', 'advertisers', 'members'));
    }
}
