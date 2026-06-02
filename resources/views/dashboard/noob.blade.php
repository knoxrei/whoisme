<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-8 max-w-7xl mx-auto">
        <div class="border border-red-900/30 bg-[#0a0a0a] p-8 rounded-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-red-950/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-2 py-0.5 border border-red-900/40 bg-red-950/20 text-red-500 text-[9px] font-black uppercase tracking-widest rounded-sm">
                        {{ $role->label() }}
                    </span>
                    <span class="text-gray-700 text-xs font-mono">•</span>
                    <span class="text-gray-500 text-[10px] font-mono tracking-wider uppercase">System Connected</span>
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight uppercase mb-2">
                    Welcome back, {!! $role->userStyle($user->username) !!}
                </h1>
                <p class="text-gray-500 text-xs font-mono max-w-2xl leading-relaxed">
                    Accessing operational terminal. You are currently browsing via a optimized high-efficiency Tor interface.
                </p>

                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="{{ route('pastebin.create') }}" class="px-5 py-2.5 bg-red-700 hover:bg-red-800 text-white font-black text-[10px] tracking-widest uppercase rounded-sm transition-colors duration-150">
                        Create New Paste
                    </a>
                    <a href="{{ route('profile.show', $user->username) }}" class="px-5 py-2.5 bg-[#0a0a0a] border border-red-900/20 text-gray-400 hover:text-white font-black text-[10px] tracking-widest uppercase rounded-sm transition-colors duration-150">
                        Public Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Total Pastes</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($totalPastes) }}</p>
            </div>

            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Total Views</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($totalViews) }}</p>
            </div>

            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Downloads</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($totalDownloads) }}</p>
            </div>

            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Approved Edits</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($approvedEdits) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                
                <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-xs font-black text-red-500 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Your Operational Logs
                        </h2>
                        <a href="{{ route('profile.show', $user->username) }}" class="text-[9px] text-gray-500 hover:text-white uppercase font-black tracking-widest font-mono">View All</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentPastes as $paste)
                            <div class="flex items-center justify-between p-3.5 bg-[#0a0a0a] border border-red-900/10 hover:border-red-900/30 transition-colors duration-150 rounded-sm group">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 flex items-center justify-center bg-red-950/20 text-red-500 border border-red-900/10 rounded-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-xs font-bold text-gray-300 group-hover:text-red-500 transition-colors duration-150">
                                            {{ Str::limit($paste->title, 40) }}
                                        </a>
                                        <p class="text-[8px] text-gray-600 font-mono uppercase mt-0.5">
                                            {{ $paste->visibility }} • {{ $paste->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right font-mono">
                                    <p class="text-xs font-bold text-gray-300">{{ number_format($paste->views_count) }}</p>
                                    <p class="text-[7px] text-gray-600 uppercase tracking-widest">Views</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-xs text-gray-600 italic font-mono p-4 bg-[#0a0a0a] border border-red-900/10 text-center rounded-sm">
                                No pastes cataloged under this terminal.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-xs font-black text-red-500 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Operational Tier Privileges
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-sm bg-[#0a0a0a] border border-red-900/10 flex flex-col justify-between">
                            <div>
                                <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest">GIF Avatars</span>
                                <h4 class="text-xs font-bold text-gray-300 mt-2">
                                    {{ $role->canHaveGifAvatar() ? 'Full Access' : 'Restricted' }}
                                </h4>
                            </div>
                            <p class="text-[8px] text-gray-600 font-mono mt-3 leading-relaxed">
                                {{ $role->canHaveGifAvatar() ? 'System supports dynamic GIF profile renders.' : 'Standard static profile renders only.' }}
                            </p>
                        </div>

                        <div class="p-4 rounded-sm bg-[#0a0a0a] border border-red-900/10 flex flex-col justify-between">
                            <div>
                                <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest">Encrypted Pastes</span>
                                <h4 class="text-xs font-bold text-gray-300 mt-2">
                                    {{ $role->canPasswordProtect() ? 'Active' : 'Locked' }}
                                </h4>
                            </div>
                            <p class="text-[8px] text-gray-600 font-mono mt-3 leading-relaxed">
                                {{ $role->canPasswordProtect() ? 'Key encryption modules online.' : 'Upgrade required for password keys.' }}
                            </p>
                        </div>

                        <div class="p-4 rounded-sm bg-[#0a0a0a] border border-red-900/10 flex flex-col justify-between">
                            <div>
                                <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest">Ident Name Shift</span>
                                <h4 class="text-xs font-bold text-gray-300 mt-2">
                                    {{ $usernameChangesRemaining }} Shifts
                                </h4>
                            </div>
                            <p class="text-[8px] text-gray-600 font-mono mt-3 leading-relaxed">
                                System tokens remaining to modify core database username.
                            </p>
                        </div>
                    </div>
            </div>

            <div class="space-y-6">
                @if($role->value !== 'rich')
                    <div class="p-6 border border-red-900/40 bg-gradient-to-b from-red-950/20 to-black rounded-sm relative overflow-hidden">
                        <div class="relative z-10">
                            <span class="text-[8px] font-black text-red-500 uppercase tracking-[0.2em]">Operational Level-Up</span>
                            <h3 class="text-lg font-black text-white uppercase tracking-tight mt-2 mb-2">Elevate Clearance</h3>
                            <p class="text-gray-500 text-xs font-mono leading-relaxed mb-6">
                                Unlock full decryption features, GIF renders, and access the signature premium <span class="text-yellow-500 font-bold">Gold Rich</span> interface.
                            </p>
                            <a href="{{ route('upgrade.index') }}" class="inline-block w-full text-center py-2.5 bg-red-700 hover:bg-red-800 text-white font-black text-[10px] tracking-widest uppercase rounded-sm transition-colors duration-150">
                                Request Clearance
                            </a>
                        </div>
                    </div>
                @endif

                <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                    <h2 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Global Terminal Rankings
                    </h2>
                    <div class="space-y-4">
                        @foreach($topContributors as $contributor)
                            <div class="flex items-center justify-between p-2.5 bg-[#0a0a0a] border border-red-900/10 rounded-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 flex-shrink-0 bg-red-950/30 border border-red-900/10 flex items-center justify-center text-[10px] font-black text-red-500 rounded-sm">
                                        @if($contributor->identification && $contributor->identification->avatar_path)
                                            <img src="{{ asset('storage/' . $contributor->identification->avatar_path) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($contributor->username, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('profile.show', $contributor->username) }}" class="text-xs font-bold text-gray-300 hover:text-red-500 transition-colors duration-150">
                                            {!! $contributor->identification->role->userStyleWithBanner($contributor->username, $contributor->identification->color_username ?? '#ffffff') !!}
                                        </a>
                                        <p class="text-[8px] text-gray-600 font-mono mt-0.5">
                                            @if($contributor->identification && $contributor->identification->role)
                                                {{ $contributor->identification->role->label() }}
                                            @else
                                                MEMBER
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right font-mono">
                                    <span class="text-[10px] font-bold text-gray-400">
                                        {{ $contributor->pastebins_count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>