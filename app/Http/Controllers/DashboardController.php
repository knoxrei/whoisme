<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        if (!$user->identification || !$user->identification->role) {
            $user->identification()->updateOrCreate(
                ['user_id' => $user->id],
                ['role' => Role::MEMBER]
            );
            $user->refresh();
        }

        $role = $user->identification->role;

        // Fetch User Statistics (separated from Blade)
        $totalPastes = $user->pastebins()->count();
        $totalViews = $user->pastebins()->sum('views_count') ?? 0;
        $totalDownloads = $user->pastebins()->sum('download_count') ?? 0;
        $approvedEdits = $user->edits()->where('status', 'approved')->count();
        
        // Fetch Real Recent Pastes
        $recentPastes = $user->pastebins()->latest()->take(5)->get();

        // Fetch Top Contributors (Global Leaderboard by paste count)
        $topContributors = User::with(['identification'])
            ->withCount('pastebins')
            ->orderBy('pastebins_count', 'desc')
            ->take(5)
            ->get();

        // Calculations
        $usernameChangesRemaining = max(0, $role->allowedUsernameChanges() - ($user->identification->username_changes ?? 0));

        // Check if role is High (Owner or Moderator)
        if ($role === Role::OWNER || $role === Role::MODERATOR) {
            $systemTotalUsers = User::count();
            $systemTotalPastes = \App\Models\Pastebin::count();
            $systemPendingEdits = \App\Models\PastebinEdit::where('status', 'pending')->count();
            $systemPendingUpgrades = \App\Models\UpgradeRequest::where('status', 'pending')->count();
            $systemPendingReports = \App\Models\ReportPastebin::where('status', 'pending')->count();
            $systemActiveStaff = User::whereHas('identification', function ($query) {
                $query->whereIn('role', [Role::OWNER, Role::MODERATOR]);
            })->count();
            
            // Get recent moderation logs / pending edits
            $recentSystemEdits = \App\Models\PastebinEdit::with(['user', 'pastebin'])
                ->latest()
                ->take(5)
                ->get();
            
            return view('dashboard.high', [
                'title' => 'Admin Control Panel',
                'user' => $user,
                'role' => $role,
                'systemTotalUsers' => $systemTotalUsers,
                'systemTotalPastes' => $systemTotalPastes,
                'systemPendingEdits' => $systemPendingEdits,
                'systemPendingUpgrades' => $systemPendingUpgrades,
                'systemPendingReports' => $systemPendingReports,
                'systemActiveStaff' => $systemActiveStaff,
                'recentSystemEdits' => $recentSystemEdits,
            ]);
        }
        
        // Default to Noob Dashboard
        return view('dashboard.noob', [
            'title' => 'Dashboard',
            'user' => $user,
            'role' => $role,
            'totalPastes' => $totalPastes,
            'totalViews' => $totalViews,
            'totalDownloads' => $totalDownloads,
            'approvedEdits' => $approvedEdits,
            'recentPastes' => $recentPastes,
            'topContributors' => $topContributors,
            'usernameChangesRemaining' => $usernameChangesRemaining,
        ]);
    }

    public function pastes(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = $user->identification->role;

        $visibility = $request->query('visibility');

        $query = $user->pastebins()->latest();

        if (in_array($visibility, ['public', 'private'])) {
            $query->where('visibility', $visibility);
        }

        $pastebins = $query->paginate(15);

        return view('dashboard.pastes', [
            'title' => 'My Pastes',
            'user' => $user,
            'role' => $role,
            'pastebins' => $pastebins,
            'currentVisibility' => $visibility,
        ]);
    }

    public function suggestions(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = $user->identification->role;

        $status = $request->query('status');
        $isStaff = in_array($role->value, ['owner', 'moderator']);

        if ($isStaff) {
            $query = \App\Models\PastebinEdit::with(['user', 'pastebin'])->latest();

            if (in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $status);
            }

            $suggestions = $query->paginate(15);

            return view('dashboard.suggestions', [
                'title' => 'Global Audit Panel',
                'user' => $user,
                'role' => $role,
                'suggestions' => $suggestions,
                'currentStatus' => $status,
                'isStaff' => true,
            ]);
        }

        // Normal User context
        $tab = $request->query('tab', 'incoming');

        if ($tab === 'outgoing') {
            // Outgoing suggestions: Edits submitted by the user
            $query = \App\Models\PastebinEdit::where('user_id', $user->id)->with(['pastebin'])->latest();
        } else {
            // Incoming suggestions: Edits submitted by other users on user's owned pastebins
            $query = \App\Models\PastebinEdit::whereHas('pastebin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['user', 'pastebin'])->latest();
        }

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $suggestions = $query->paginate(15);

        return view('dashboard.suggestions', [
            'title' => 'My Suggestions',
            'user' => $user,
            'role' => $role,
            'suggestions' => $suggestions,
            'currentStatus' => $status,
            'currentTab' => $tab,
            'isStaff' => false,
        ]);
    }

    public function upgrades(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = $user->identification->role;

        if ($role !== Role::OWNER && $role !== Role::MODERATOR) {
            abort(403, 'Unauthorized.');
        }

        $status = $request->query('status');
        $query = \App\Models\UpgradeRequest::with(['user.identification'])->latest();

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $requests = $query->paginate(15);

        return view('dashboard.upgrades', [
            'title' => 'Manage Upgrade Requests',
            'user' => $user,
            'role' => $role,
            'requests' => $requests,
            'currentStatus' => $status,
        ]);
    }

    public function approveUpgrade(\App\Models\UpgradeRequest $upgrade)
    {
        $role = auth()->user()->identification->role;
        if ($role !== Role::OWNER && $role !== Role::MODERATOR) {
            abort(403, 'Unauthorized.');
        }

        if ($upgrade->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $upgrade->user->identification()->update([
            'role' => $upgrade->requested_role,
            'expired_at' => now()->addMonth(),
        ]);

        $upgrade->update([
            'status' => 'approved',
        ]);

        return back()->with('success', "Upgrade request for @{$upgrade->user->username} has been approved.");
    }

    public function rejectUpgrade(\App\Models\UpgradeRequest $upgrade)
    {
        $role = auth()->user()->identification->role;
        if ($role !== Role::OWNER && $role !== Role::MODERATOR) {
            abort(403, 'Unauthorized.');
        }

        if ($upgrade->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $upgrade->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', "Upgrade request for @{$upgrade->user->username} has been rejected.");
    }

    public function reports(Request $request)
    {
        $role = auth()->user()->identification->role;
        if ($role !== Role::OWNER && $role !== Role::MODERATOR) {
            abort(403, 'Unauthorized.');
        }

        $status = $request->query('status');
        $query = \App\Models\ReportPastebin::with(['pastebin', 'user.identification'])->latest();

        if (in_array($status, ['pending', 'resolved', 'dismissed'])) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(15);

        return view('dashboard.reports', [
            'title' => 'Manage Reported Threads',
            'user' => auth()->user(),
            'role' => $role,
            'reports' => $reports,
            'currentStatus' => $status,
        ]);
    }

    public function dismissReport(\App\Models\ReportPastebin $report)
    {
        $role = auth()->user()->identification->role;
        if ($role !== Role::OWNER && $role !== Role::MODERATOR) {
            abort(403, 'Unauthorized.');
        }

        $report->update(['status' => 'dismissed']);

        return back()->with('success', 'Report has been successfully dismissed.');
    }

    public function instantEditThread(Request $request, \App\Models\ReportPastebin $report)
    {
        $role = auth()->user()->identification->role;
        if ($role !== Role::OWNER && $role !== Role::MODERATOR) {
            abort(403, 'Unauthorized.');
        }

        if (!$report->pastebin) {
            return back()->with('error', 'The reported thread has already been deleted.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);

        $report->pastebin->update([
            'title'       => $request->title,
            'content'     => $request->content,
            'description' => $request->description,
        ]);

        // Mark the report as resolved after editing
        $report->update(['status' => 'resolved']);

        return back()->with('success', 'Thread has been instantly edited and the report marked as resolved.');
    }

    public function users(Request $request)
    {
        $this->authorize('manage', User::class);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $role = $user->identification->role;

        $search = trim((string) $request->query('q', ''));
        $filterRole = $request->query('filter_role');

        $query = User::with(['identification'])->withCount('pastebins')->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

                if (ctype_digit($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        if ($filterRole) {
            $query->whereHas('identification', function ($q) use ($filterRole) {
                $q->where('role', $filterRole);
            });
        }

        $users = $query->paginate(15);

        $users->getCollection()->transform(function ($u) {
            $uRole = $u->identification->role ?? Role::MEMBER;
            
            $u->can_edit = auth()->user()->can('update', $u);
            $u->can_ban = auth()->user()->can('ban', $u);
            $u->can_delete = auth()->user()->can('delete', $u);
            
            $u->is_banned = $uRole === Role::BANNED;
            
            $u->avatar_url = $u->identification && $u->identification->avatar_path
                ? asset('storage/' . $u->identification->avatar_path)
                : asset('storage/avatars/default.png');
                
            $u->display_style = $u->identification && $u->identification->role
                ? $u->identification->role->userStyleWithBanner($u->username, $u->identification->color_username ?? '#ffffff')
                : $u->username;
                
            $u->role_label = $u->identification && $u->identification->role
                ? $u->identification->role->label()
                : 'Member';
                
            $u->role_color = $u->identification && $u->identification->role
                ? $u->identification->role->color()
                : '#808080';
                
            $u->role_value = $uRole->value;
            $u->color_username = $u->identification->color_username ?? '#ffffff';
            $u->website = $u->identification->website ?? '';
            $u->bio = $u->identification->bio ?? '';
            
            return $u;
        });

        return view('dashboard.users', [
            'title' => 'User Management Panel',
            'user' => $user,
            'role' => $role,
            'users' => $users,
            'search' => $search !== '' ? $search : null,
            'filterRole' => $filterRole,
            'totalUsers' => $users->total(),
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'color_username' => 'nullable|string|max:7',
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|string|max:255',
        ]);

        // Security check: Only Owner can assign OWNER or MODERATOR roles
        $requestedRole = Role::tryFrom($request->role);
        if (($requestedRole === Role::OWNER || $requestedRole === Role::MODERATOR) && auth()->user()->identification?->role !== Role::OWNER) {
            return back()->with('error', 'Only the System Owner can assign staff roles (Owner/Moderator).');
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
        ]);

        $user->identification()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'role' => $request->role,
                'color_username' => $request->color_username ?? '#ffffff',
                'bio' => $request->bio,
                'website' => $request->website,
            ]
        );

        if ($requestedRole === Role::ADVERTISER) {
            \App\Models\Advertiser::firstOrCreate(
                ['user_id' => $user->id],
                ['company_name' => $user->username . ' Ads', 'balance' => 0.00]
            );
        }

        return back()->with('success', "Account profile for @{$user->username} has been updated successfully.");
    }

    public function toggleBan(User $user)
    {
        $this->authorize('ban', $user);
        
        $identification = $user->identification;
        $currentRole = $identification?->role ?? Role::MEMBER;
        
        if ($currentRole === Role::BANNED) {
            $user->identification()->updateOrCreate(
                ['user_id' => $user->id],
                ['role' => Role::MEMBER]
            );
            $msg = "User @{$user->username} signature has been unbanned.";
        } else {
            $user->identification()->updateOrCreate(
                ['user_id' => $user->id],
                ['role' => Role::BANNED]
            );
            $msg = "User @{$user->username} signature has been banned.";
        }

        return back()->with('success', $msg);
    }

    public function deleteUser(User $user)
    {
        $this->authorize('delete', $user);

        // Delete related files and references
        foreach ($user->pastebins as $paste) {
            // Delete associated image gallery files from storage
            foreach ($paste->images as $image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // Delete cover
            if ($paste->cover_path && $paste->cover_path !== 'defaultCover.png') {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($paste->cover_path);
            }

            $paste->comments()->delete();
            $paste->edits()->delete();
            $paste->delete();
        }

        $user->identification()->delete();
        $user->delete();

        return back()->with('success', "User account for @{$user->username} has been permanently purged from the system.");
    }

    public function createAdvertiser(Request $request)
    {
        $role = auth()->user()->identification->role;
        if ($role !== Role::OWNER) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->identification()->create([
            'user_id' => $user->id,
            'role' => Role::ADVERTISER,
            'avatar_path' => 'upload/defaultAvatar.png',
            'website' => 'N/A',
            'bio' => 'Advertiser manually added by Owner',
        ]);

        \App\Models\Advertiser::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name ?: $user->username . ' Ads',
            'balance' => $request->balance ?: 0.00,
        ]);

        return back()->with('success', "Advertiser @{$user->username} has been manually registered and activated successfully.");
    }
}
