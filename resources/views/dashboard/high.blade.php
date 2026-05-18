<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- Control Center Header -->
        <div class="border border-red-900/40 bg-gradient-to-b from-red-950/10 to-[#0a0a0a] p-8 rounded-sm relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2 py-0.5 border border-red-900/40 bg-red-950/20 text-red-500 text-[9px] font-black uppercase tracking-widest rounded-sm">
                            System Control
                        </span>
                        <span class="text-gray-700 text-xs font-mono">•</span>
                        <span class="text-gray-500 text-[10px] font-mono tracking-wider uppercase">
                            {{ now()->format('l, d F Y') }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-white tracking-tight uppercase mb-2">
                        Control Center: <span class="text-red-500">{{ $role->label() }}</span>
                    </h4>
                    <p class="text-gray-500 text-xs font-mono max-w-2xl leading-relaxed">
                        Elevated administrative clearance active. Monitoring system configurations, checking log traces, and handling user operational requests.
                    </p>
                </div>
            </div>
        </div>

        <!-- Admin Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Stat 1: Total Users -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Total Users</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($systemTotalUsers) }}</p>
            </div>

            <!-- Stat 2: Total Pastes -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">System Pastes</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($systemTotalPastes) }}</p>
            </div>

            <!-- Stat 3: Pending Edits -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Pending Suggestions</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <a href="{{ route('dashboard.suggestions') }}" class="block hover:text-red-500 transition-colors">
                    <p class="text-xl font-mono font-bold text-white">{{ number_format($systemPendingEdits) }}</p>
                </a>
            </div>

            <!-- Stat 4: Pending Upgrades -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Pending Upgrades</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/></svg>
                    </div>
                </div>
                <a href="{{ route('dashboard.upgrades') }}" class="block hover:text-red-500 transition-colors">
                    <p class="text-xl font-mono font-bold text-white">{{ number_format($systemPendingUpgrades) }}</p>
                </a>
            </div>

            <!-- Stat 5: Pending Reports -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Pending Reports</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <a href="{{ route('dashboard.reports') }}" class="block hover:text-red-500 transition-colors">
                    <p class="text-xl font-mono font-bold text-white">{{ number_format($systemPendingReports) }}</p>
                </a>
            </div>

            <!-- Stat 6: Active Staff -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Operator Count</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($systemActiveStaff) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Suggestions Audit Log -->
            <div class="lg:col-span-2 p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-black text-red-500 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Recent Suggestion Feed
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left font-mono">
                        <thead>
                            <tr class="text-gray-500 text-[9px] uppercase tracking-widest border-b border-red-900/10">
                                <th class="pb-3 font-black">Suggester</th>
                                <th class="pb-3 font-black">Target Paste</th>
                                <th class="pb-3 font-black">Status</th>
                                <th class="pb-3 font-black">Date</th>
                                <th class="pb-3 font-black text-right">Route</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-900/10">
                            @forelse($recentSystemEdits as $edit)
                                <tr class="text-xs">
                                    <td class="py-4">
                                        <span class="text-gray-300 font-bold">@ {{ $edit->user->username ?? 'Anonymous' }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-gray-400 font-bold">{{ Str::limit($edit->pastebin->title ?? 'N/A', 25) }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="px-1.5 py-0.5 rounded-sm text-[8px] font-black uppercase tracking-widest 
                                            {{ $edit->status === 'approved' ? 'bg-green-950/30 text-green-500 border border-green-900/30' : ($edit->status === 'rejected' ? 'bg-red-950/30 text-red-500 border border-red-900/30' : 'bg-yellow-950/30 text-yellow-500 border border-yellow-900/30') }}">
                                            {{ $edit->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-[10px] text-gray-500">
                                        {{ $edit->created_at->diffForHumans() }}
                                    </td>
                                    <td class="py-4 text-right">
                                        @if($edit->pastebin)
                                            <a href="{{ route('pastebin.show', $edit->pastebin->slug) }}" class="text-[9px] font-black bg-red-950/20 hover:bg-red-900/20 text-red-500 px-3 py-1 border border-red-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                Review
                                            </a>
                                        @else
                                            <span class="text-gray-600">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-xs text-gray-600 italic">
                                        No edit suggestions logged in database history.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Controls -->
            <div class="space-y-6">
                <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                    <h2 class="text-xs font-black text-red-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Terminal Operations
                    </h2>
                    <div class="grid grid-cols-1 gap-3">
                        <button onclick="alert('Module Online - Announcement interface routed')" class="py-2.5 rounded-sm bg-red-950/20 hover:bg-red-900/20 text-red-500 border border-red-900/30 text-[10px] tracking-widest uppercase font-black text-center transition-colors duration-150">
                            Broadcast Alert
                        </button>
                        <button onclick="alert('Maintenance block configured')" class="py-2.5 rounded-sm bg-red-950/20 hover:bg-red-900/20 text-red-500 border border-red-900/30 text-[10px] tracking-widest uppercase font-black text-center transition-colors duration-150">
                            Trigger Maint Lock
                        </button>
                        <button onclick="alert('Platform cache flushed successfully')" class="py-2.5 rounded-sm bg-red-700 hover:bg-red-800 text-white text-[10px] tracking-widest uppercase font-black text-center transition-colors duration-150">
                            Flush Platform Cache
                        </button>
                    </div>
                </div>

                <!-- Alert Triggers -->
                <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                    <h2 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Platform System Health
                    </h2>
                    <div class="space-y-3 font-mono">
                        <div class="p-3.5 bg-green-950/10 border border-green-900/25 flex items-center justify-between rounded-sm">
                            <span class="text-green-500 text-[10px] font-black uppercase tracking-wider">Memory Blocks</span>
                            <span class="text-xs text-green-400 font-bold">Stable</span>
                        </div>
                        <div class="p-3.5 bg-green-950/10 border border-green-900/25 flex items-center justify-between rounded-sm">
                            <span class="text-green-500 text-[10px] font-black uppercase tracking-wider">Onion Routing</span>
                            <span class="text-xs text-green-400 font-bold">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
