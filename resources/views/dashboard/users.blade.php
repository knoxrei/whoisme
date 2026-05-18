<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- Title & Filter Panel -->
        <div class="border border-red-900/40 bg-gradient-to-b from-red-950/10 to-[#0a0a0a] p-6 rounded-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-white tracking-tight uppercase">
                    Clearance Control Panel
                </h1>
                <p class="text-gray-500 text-xs font-mono mt-1">
                    Manage accounts, assign staff roles, customize profiles, and enforce bans over the dox community.
                </p>
            </div>
            
            <!-- Filters -->
            <form method="GET" action="{{ route('dashboard.users') }}" class="flex flex-wrap items-center gap-3 font-mono text-[10px]">
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search username/email..." 
                        class="bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-1.5 text-white text-xs w-48 focus:outline-none">
                </div>

                <div>
                    <select name="filter_role" onchange="this.form.submit()" 
                        class="bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-1.5 text-xs text-gray-400 appearance-none cursor-pointer">
                        <option value="">All Clearance Level</option>
                        @foreach(\App\Enum\Role::cases() as $r)
                            <option value="{{ $r->value }}" {{ $filterRole === $r->value ? 'selected' : '' }}>
                                {{ $r->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($search || $filterRole)
                    <a href="{{ route('dashboard.users') }}" class="px-3 py-1.5 border border-red-900/40 text-red-500 hover:text-white uppercase tracking-widest rounded-sm">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-950/20 border border-green-900/30 text-green-500 text-xs font-mono font-bold rounded-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-950/20 border border-red-900/30 text-red-500 text-xs font-mono font-bold rounded-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- User Accounts Table -->
        <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left font-mono">
                    <thead>
                        <tr class="text-gray-500 text-[9px] uppercase tracking-widest border-b border-red-900/10">
                            <th class="pb-3 font-black">User / Signature</th>
                            <th class="pb-3 font-black">Email</th>
                            <th class="pb-3 font-black">Role / Clearance</th>
                            <th class="pb-3 font-black">Dox Count</th>
                            <th class="pb-3 font-black">Joined</th>
                            <th class="pb-3 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900/10">
                        @forelse($users as $u)
                            <tr class="text-xs">
                                <td class="py-4 pr-4 align-middle">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar -->
                                        <div class="w-8 h-8 rounded-full border border-red-900/20 overflow-hidden bg-[#050505] flex-shrink-0">
                                            <img src="{{ $u->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                                        </div>
                                        <div>
                                            <a href="{{ route('profile.show', $u->username) }}" class="font-bold hover:text-red-500 transition-colors block">
                                                {!! $u->display_style !!}
                                            </a>
                                            <span class="text-[8px] text-gray-600 block mt-0.5">UID: {{ $u->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pr-4 align-middle text-gray-400">{{ $u->email }}</td>
                                <td class="py-4 pr-4 align-middle">
                                    <span style="color: {{ $u->role_color }}; border-color: {{ $u->role_color }}40" 
                                        class="px-2 py-0.5 border bg-black rounded-sm text-[8px] font-black uppercase tracking-widest">
                                        {{ $u->role_label }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4 align-middle text-gray-300 font-bold">
                                    {{ $u->pastebins_count ?? $u->pastebins()->count() }}
                                </td>
                                <td class="py-4 text-[10px] text-gray-500 align-middle">{{ $u->created_at->format('d M Y') }}</td>
                                <td class="py-4 text-right align-middle">
                                    <div class="flex justify-end gap-2">
                                        <!-- Edit Trigger -->
                                        @if($u->can_edit)
                                            <button type="button" 
                                                onclick="openEditModal(this)"
                                                data-id="{{ $u->id }}"
                                                data-username="{{ $u->username }}"
                                                data-email="{{ $u->email }}"
                                                data-role="{{ $u->role_value }}"
                                                data-color="{{ $u->color_username }}"
                                                data-website="{{ $u->website }}"
                                                data-bio="{{ $u->bio }}"
                                                class="text-[9px] font-black bg-blue-950/20 hover:bg-blue-900/20 text-blue-400 px-3 py-1 border border-blue-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                Edit
                                            </button>
                                        @endif

                                        <!-- Ban/Unban Trigger -->
                                        @if($u->can_ban)
                                            @if($u->is_banned)
                                                <form id="ban-form-{{ $u->id }}" action="{{ route('dashboard.users.toggle-ban', $u) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" 
                                                        onclick="confirmAction('Confirm unbanning user {{ $u->username }}? Their clearance will be reverted to MEMBER.', () => document.getElementById('ban-form-{{ $u->id }}').submit(), 'SECURITY AUTHORIZATION')"
                                                        class="text-[9px] font-black bg-green-950/20 hover:bg-green-900/20 text-green-500 px-3 py-1 border border-green-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                        Unban
                                                    </button>
                                                </form>
                                            @else
                                                <form id="ban-form-{{ $u->id }}" action="{{ route('dashboard.users.toggle-ban', $u) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" 
                                                        onclick="confirmAction('WARNING: Confirm banning user {{ $u->username }}? This signature will be locked out from posting new content.', () => document.getElementById('ban-form-{{ $u->id }}').submit(), 'ACCESS TERMINATION')"
                                                        class="text-[9px] font-black bg-red-950/20 hover:bg-red-900/20 text-red-500 px-3 py-1 border border-red-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                        Ban
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        <!-- Delete Trigger -->
                                        @if($u->can_delete)
                                            <form id="delete-form-{{ $u->id }}" action="{{ route('dashboard.users.delete', $u) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                    onclick="confirmAction('CRITICAL DANGER: Permanently delete user {{ $u->username }} and purge all their pastes, images, comments, and references? This action is absolutely irreversible!', () => document.getElementById('delete-form-{{ $u->id }}').submit(), 'MAINFRAME PURGE CONTROLS')"
                                                    class="text-[9px] font-black bg-red-600 hover:bg-red-700 text-white px-3 py-1 border border-red-600 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                    Purge
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-xs text-gray-600 italic">
                                    No accounts captured under this query state.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Block -->
            @if($users->hasPages())
                <div class="mt-6 pt-6 border-t border-red-900/10 flex justify-between items-center text-[10px] font-mono">
                    <div>
                        <span class="text-gray-600 uppercase tracking-widest">Showing</span> 
                        <span class="text-gray-300 font-bold">{{ $users->firstItem() }}-{{ $users->lastItem() }}</span> 
                        <span class="text-gray-600 uppercase tracking-widest">of</span> 
                        <span class="text-gray-300 font-bold">{{ $users->total() }}</span>
                    </div>
                    
                    <div class="flex gap-2">
                        @if($users->onFirstPage())
                            <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">Prev</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white uppercase tracking-widest rounded-sm transition-colors duration-150">Prev</a>
                        @endif

                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white uppercase tracking-widest rounded-sm transition-colors duration-150">Next</a>
                        @else
                            <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
        <div class="border border-red-900/40 bg-gradient-to-b from-red-950/10 to-[#0a0a0a] rounded-sm max-w-lg w-full p-6 space-y-6 relative">
            <div class="flex justify-between items-center border-b border-red-900/10 pb-4">
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Edit Account Clearance</h3>
                <button type="button" onclick="closeEditModal()" class="text-red-500 hover:text-white font-black text-xs font-mono">✕ CLOSE</button>
            </div>

            <form id="edit-user-form" method="POST" action="">
                @csrf
                <div class="space-y-4 text-xs font-mono">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-gray-500 uppercase font-black text-[9px] tracking-widest block">Username</label>
                            <input type="text" name="username" id="modal-username" required 
                                class="w-full bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-2 text-white text-xs">
                        </div>
                        <div class="space-y-2">
                            <label class="text-gray-500 uppercase font-black text-[9px] tracking-widest block">Email</label>
                            <input type="email" name="email" id="modal-email" required 
                                class="w-full bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-2 text-white text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-gray-500 uppercase font-black text-[9px] tracking-widest block">System Role</label>
                            <select name="role" id="modal-role" class="w-full bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-2 text-white text-xs appearance-none">
                                @foreach(\App\Enum\Role::cases() as $r)
                                    @if($r === \App\Enum\Role::OWNER || $r === \App\Enum\Role::MODERATOR)
                                        @if($role === \App\Enum\Role::OWNER)
                                            <option value="{{ $r->value }}">{{ $r->label() }}</option>
                                        @endif
                                    @else
                                        <option value="{{ $r->value }}">{{ $r->label() }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-gray-500 uppercase font-black text-[9px] tracking-widest block">Username Color</label>
                            <div class="flex gap-2">
                                <input type="color" name="color_username" id="modal-color-picker" 
                                    class="w-8 h-8 bg-transparent border border-red-900/20 cursor-pointer rounded-sm"
                                    oninput="document.getElementById('modal-color-hex').value = this.value">
                                <input type="text" id="modal-color-hex" placeholder="#ffffff" maxlength="7"
                                    class="flex-1 bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-2 text-white text-xs"
                                    oninput="document.getElementById('modal-color-picker').value = this.value">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-500 uppercase font-black text-[9px] tracking-widest block">Website</label>
                        <input type="text" name="website" id="modal-website" 
                            class="w-full bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-2 text-white text-xs">
                    </div>

                    <div class="space-y-2">
                        <label class="text-gray-500 uppercase font-black text-[9px] tracking-widest block">Bio Description</label>
                        <textarea name="bio" id="modal-bio" rows="3" 
                            class="w-full bg-black border border-red-900/20 focus:border-red-600 rounded-sm px-3 py-2 text-white text-xs resize-none"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-red-900/10 pt-4 mt-6">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-red-900/20 text-gray-500 hover:text-white rounded-sm text-[10px] uppercase tracking-widest">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-700 hover:bg-red-800 text-white rounded-sm text-[10px] uppercase tracking-widest font-black">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const id = button.getAttribute('data-id');
            const username = button.getAttribute('data-username');
            const email = button.getAttribute('data-email');
            const role = button.getAttribute('data-role');
            const color = button.getAttribute('data-color') || '#ffffff';
            const website = button.getAttribute('data-website') || '';
            const bio = button.getAttribute('data-bio') || '';

            // Update form action url
            const form = document.getElementById('edit-user-form');
            form.action = `/dashboard/users/${id}/update`;

            // Fill modal fields
            document.getElementById('modal-username').value = username;
            document.getElementById('modal-email').value = email;
            document.getElementById('modal-role').value = role;
            document.getElementById('modal-color-picker').value = color;
            document.getElementById('modal-color-hex').value = color;
            document.getElementById('modal-website').value = website;
            document.getElementById('modal-bio').value = bio;

            // Show modal
            const modal = document.getElementById('edit-user-modal');
            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-user-modal');
            modal.classList.add('hidden');
        }
    </script>
</x-layouts.dashboard>
