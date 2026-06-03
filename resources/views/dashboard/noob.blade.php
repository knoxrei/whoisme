<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- System Status Banner -->
        <div class="border border-red-900/30 bg-[#050505] p-8 rounded-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 bottom-0 w-1/4 bg-gradient-to-l from-red-600/5 to-transparent"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-2 py-0.5 border border-red-900/40 bg-red-950/30 text-red-500 text-[8px] font-black uppercase tracking-widest rounded-sm">
                        {{ $role->label() }} CLEARANCE
                    </span>
                    <span class="w-1 h-1 rounded-full bg-red-600 animate-pulse"></span>
                    <span class="text-gray-500 text-[9px] font-mono tracking-widest uppercase font-black">Link Established</span>
                </div>
                
                <h1 class="text-3xl font-black text-white tracking-tighter uppercase mb-2 flex items-baseline gap-3">
                    {!! $role->userStyle($user->username) !!}
                    <span class="text-gray-700 text-xs font-mono lowercase tracking-normal font-medium">/ home / operator</span>
                </h1>
                
                <p class="text-gray-500 text-xs font-mono max-w-3xl leading-relaxed mt-4">
                    Terminal session active. Monitoring operational efficiency and data transmission. All actions are logged under signature <span class="text-red-900">#{{ substr(sha1($user->id), 0, 8) }}</span>.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('pastebin.create') }}" class="px-6 py-2.5 bg-red-600 hover:bg-red-500 text-white font-black text-[10px] tracking-widest uppercase rounded-sm transition-all shadow-lg shadow-red-900/20">
                        Initiate Transmission
                    </a>
                    <a href="{{ route('profile.show', $user->username) }}" class="px-6 py-2.5 bg-black border border-red-900/20 text-gray-400 hover:text-white font-black text-[10px] tracking-widest uppercase rounded-sm transition-all">
                        Public Identity
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-6 bg-[#050505] border border-red-900/10 rounded-sm group hover:border-red-900/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] font-mono">Cataloged Assets</span>
                    <div class="p-2 bg-red-950/10 rounded-sm">
                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-white font-mono">{{ number_format($totalPastes) }}</p>
                    <span class="text-[8px] text-gray-600 uppercase font-black">Entries</span>
                </div>
            </div>

            <div class="p-6 bg-[#050505] border border-red-900/10 rounded-sm group hover:border-red-900/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] font-mono">Engagement Index</span>
                    <div class="p-2 bg-red-950/10 rounded-sm">
                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-white font-mono">{{ number_format($totalViews) }}</p>
                    <span class="text-[8px] text-gray-600 uppercase font-black">Views</span>
                </div>
            </div>

            <div class="p-6 bg-[#050505] border border-red-900/10 rounded-sm group hover:border-red-900/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] font-mono">Data Extractions</span>
                    <div class="p-2 bg-red-950/10 rounded-sm">
                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-white font-mono">{{ number_format($totalDownloads) }}</p>
                    <span class="text-[8px] text-gray-600 uppercase font-black">DLs</span>
                </div>
            </div>

            <div class="p-6 bg-[#050505] border border-red-900/10 rounded-sm group hover:border-red-900/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.2em] font-mono">Contribution Cred</span>
                    <div class="p-2 bg-red-950/10 rounded-sm">
                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-white font-mono">{{ number_format($approvedEdits) }}</p>
                    <span class="text-[8px] text-gray-600 uppercase font-black">Edits</span>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Logs -->
                <div class="border border-red-900/10 bg-[#050505] rounded-sm overflow-hidden">
                    <div class="px-6 py-4 bg-black/40 border-b border-red-900/5 flex items-center justify-between">
                        <h2 class="text-[10px] font-black text-white uppercase tracking-[0.2em] flex items-center gap-2">
                            <span class="w-1.5 h-3 bg-red-600"></span>
                            Recent Asset Transmissions
                        </h2>
                        <a href="{{ route('dashboard.pastes') }}" class="text-[8px] text-gray-600 hover:text-red-500 uppercase font-black tracking-widest transition-colors font-mono">Access Full Archive</a>
                    </div>

                    <div class="divide-y divide-red-900/5">
                        @forelse($recentPastes as $paste)
                            <div class="flex items-center justify-between p-5 hover:bg-red-950/[0.02] transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 flex items-center justify-center bg-black border border-red-900/10 text-red-600 group-hover:border-red-600/30 transition-all rounded-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <div>
                                        <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-xs font-bold text-gray-300 group-hover:text-white transition-colors">
                                            {{ Str::limit($paste->title, 50) }}
                                        </a>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            <span class="text-[8px] text-gray-600 uppercase font-black tracking-widest border border-red-900/10 px-1.5 rounded-[1px]">
                                                {{ $paste->visibility }}
                                            </span>
                                            <span class="text-[9px] text-gray-700 font-mono">/ {{ $paste->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <span class="text-sm font-black text-gray-400 font-mono group-hover:text-red-500 transition-colors">{{ number_format($paste->views_count) }}</span>
                                    <span class="text-[7px] text-gray-700 uppercase font-black tracking-tighter">Hits</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center">
                                <p class="text-[9px] text-gray-600 uppercase tracking-widest font-black italic">Terminal archive is empty.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Capabilities -->
                <div class="p-8 border border-red-900/10 bg-[#050505] rounded-sm relative overflow-hidden">
                    <div class="absolute right-0 top-0 p-4 opacity-5">
                        <svg class="w-32 h-32 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"/></svg>
                    </div>
                    
                    <h2 class="text-[10px] font-black text-red-600 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                        Authorized Module Access
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-3">
                            <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest block">Rendering Engine</span>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $role->canHaveGifAvatar() ? 'bg-green-500' : 'bg-red-900' }}"></span>
                                <span class="text-xs font-bold text-gray-300">{{ $role->canHaveGifAvatar() ? 'GIF Renders Enabled' : 'Static Only' }}</span>
                            </div>
                            <p class="text-[8px] text-gray-600 leading-relaxed font-mono">Dynamic profile signature visualization based on clearance level.</p>
                        </div>

                        <div class="space-y-3">
                            <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest block">Security Layer</span>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $role->canPasswordProtect() ? 'bg-green-500' : 'bg-red-900' }}"></span>
                                <span class="text-xs font-bold text-gray-300">{{ $role->canPasswordProtect() ? 'AES-256 Modules' : 'Standard Access' }}</span>
                            </div>
                            <p class="text-[8px] text-gray-600 leading-relaxed font-mono">Authorized use of encryption keys for asset protection.</p>
                        </div>

                        <div class="space-y-3">
                            <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest block">Identity Shifts</span>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                <span class="text-xs font-bold text-gray-300">{{ $usernameChangesRemaining }} Shifts Available</span>
                            </div>
                            <p class="text-[8px] text-gray-600 leading-relaxed font-mono">Remaining structural database modifications for current signature.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                @if($role->value !== 'rich')
                    <div class="p-8 border border-red-900/40 bg-gradient-to-br from-red-950/20 via-black to-black rounded-sm group">
                        <span class="text-[8px] font-black text-red-500 uppercase tracking-[0.3em]">Operational Elevation</span>
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter mt-3 mb-4 group-hover:text-red-500 transition-colors">Request Higher Clearance</h3>
                        <p class="text-gray-500 text-xs font-mono leading-relaxed mb-8">
                            Upgrade to <span class="text-red-400">PRIME</span> or <span class="text-yellow-500 font-bold">GOLD RICH</span> to unlock full decryption modules, persistent pins, and advanced signature styles.
                        </p>
                        <a href="{{ route('upgrade.index') }}" class="block w-full text-center py-3 bg-red-600 hover:bg-red-700 text-white font-black text-[10px] tracking-[0.2em] uppercase rounded-sm transition-all">
                            Initiate Upgrade
                        </a>
                    </div>
                @endif

                <div class="border border-red-900/10 bg-[#050505] rounded-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-900/5 bg-black/40">
                        <h2 class="text-[10px] font-black text-red-600 uppercase tracking-[0.2em] flex items-center gap-2">
                            Global Tier Standings
                        </h2>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($topContributors as $contributor)
                            <div class="flex items-center justify-between p-3 bg-black border border-red-900/[0.05] hover:border-red-900/20 transition-all rounded-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 flex-shrink-0 bg-red-950/10 border border-red-900/10 flex items-center justify-center text-[10px] font-black text-red-600 overflow-hidden rounded-sm">
                                        @if($contributor->identification && $contributor->identification->avatar_path)
                                            <img src="{{ asset('storage/' . $contributor->identification->avatar_path) }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                        @else
                                            {{ strtoupper(substr($contributor->username, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <a href="{{ route('profile.show', $contributor->username) }}" class="text-[11px] font-bold text-gray-400 hover:text-white transition-colors">
                                            @ {{ $contributor->username }}
                                        </a>
                                        <span class="text-[7px] text-gray-700 uppercase font-black tracking-widest mt-0.5">
                                            {{ $contributor->identification->role->label() ?? 'MEMBER' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black text-gray-600 font-mono">{{ $contributor->pastebins_count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>