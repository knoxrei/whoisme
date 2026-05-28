<x-layouts.app :title="$user->username . ' Profile'">
    <div class="w-full max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-sans text-gray-200">
        <!-- Header Banner - High Contrast for Tor -->
        <div class="bg-[#0a0a0a] border border-red-900/40 p-4 md:p-6 flex items-center gap-6 mb-8 rounded-sm">
            <div class="w-20 h-20 md:w-24 md:h-24 overflow-hidden flex-shrink-0 border border-red-900/30">
                @if($user->identification->avatar_path)
                    <img src="{{ Storage::url($user->identification->avatar_path) }}" alt="{{ $user->username }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-white bg-[#111]">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">
                  {!! $user->identification->role->userStyle($user->username) !!}
                </h1>
                <div class="text-xs md:text-sm text-red-500 font-bold uppercase tracking-widest mt-1">
                    {{ $user->identification->role->label() }} 
                </div>
                
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-3">
                    <!-- Status -->
                    <div class="text-[11px] md:text-xs text-gray-300 flex items-center gap-x-2">
                        <span class="font-bold text-gray-500 uppercase tracking-tighter">Status:</span> 
                        @if($user->last_active && $user->last_active->diffInMinutes(now()) < 5)
                            <span class="text-green-500 font-black">ONLINE</span> 
                        @else
                            <span class="text-gray-500 font-bold uppercase tracking-tighter">OFFLINE</span> 
                        @endif
                    </div>

                    <!-- Followers / Following (Clickable) -->
                    <div class="flex items-center gap-4 text-[11px] md:text-xs">
                        <button onclick="toggleModal('followersModal')" class="flex items-center gap-1.5 hover:text-red-500 transition-colors">
                            <span class="text-gray-500 font-bold uppercase tracking-tighter">Followers:</span>
                            <span class="text-white font-black">{{ $user->followers_count }}</span>
                        </button>
                        <div class="w-[1px] h-3 bg-red-900/40"></div>
                        <button onclick="toggleModal('followingModal')" class="flex items-center gap-1.5 hover:text-red-500 transition-colors">
                            <span class="text-gray-500 font-bold uppercase tracking-tighter">Following:</span>
                            <span class="text-white font-black">{{ $user->following_count }}</span>
                        </button>
                    </div>
                </div>

                @if(!($user->last_active && $user->last_active->diffInMinutes(now()) < 5))
                    <div class="text-[10px] text-gray-600 mt-1 uppercase font-bold tracking-tighter">
                        Last Visit: {{ $user->last_active ? $user->last_active->diffForHumans() : 'Never' }}
                    </div>
                @endif
            </div>
            
            <div class="hidden sm:flex items-center gap-3 ml-auto">
                @auth
                    @if(auth()->id() !== $user->id)
                        @if(auth()->user()->following()->where('following_id', $user->id)->exists())
                            <form action="{{ route('user.unfollow', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-5 py-2 border border-gray-700 bg-gray-900 hover:bg-gray-800 text-gray-400 text-xs font-black uppercase tracking-widest transition-all rounded-sm">
                                    Unfollow
                                </button>
                            </form>
                        @else
                            <form action="{{ route('user.follow', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-5 py-2 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-xs font-black uppercase tracking-widest transition-all rounded-sm">
                                    Follow
                                </button>
                            </form>
                        @endif

                        <!-- Administrative Moderation Operations -->
                        @if(in_array(auth()->user()->identification->role->value, ['owner', 'moderator']))
                            @if($user->identification->role->value === 'banned')
                                <form id="unban-user-form" action="{{ route('profile.unban', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmUnban()" class="px-5 py-2 border border-green-600 bg-green-600/10 hover:bg-green-600/20 text-green-500 text-xs font-black uppercase tracking-widest transition-all rounded-sm">
                                        Unban
                                    </button>
                                </form>
                                <script>
                                    function confirmUnban() {
                                        window.doxmeModal({
                                            title: 'RESTORE SIGNATURE',
                                            content: 'Confirm restoration of terminal access privileges for signature <strong>{{ $user->username }}</strong>. They will be permitted to post new data entries.',
                                            confirmText: 'Unban User',
                                            cancelText: 'Abort',
                                            type: 'success',
                                            onConfirm: () => {
                                                document.getElementById('unban-user-form').submit();
                                            }
                                        });
                                    }
                                </script>
                            @else
                                <form id="ban-user-form" action="{{ route('profile.ban', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="button" onclick="confirmBan()" class="px-5 py-2 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-xs font-black uppercase tracking-widest transition-all rounded-sm">
                                        Ban
                                    </button>
                                </form>
                                <script>
                                    function confirmBan() {
                                        window.doxmeModal({
                                            title: 'TERMINAL DEPRIVATION',
                                            content: 'WARNING: You are restricting write/post permissions for signature <strong>{{ $user->username }}</strong> in the terminal database.<br><br>They will retain read and authentication options.',
                                            confirmText: 'Execute Ban',
                                            cancelText: 'Abort',
                                            type: 'danger',
                                            onConfirm: () => {
                                                document.getElementById('ban-user-form').submit();
                                            }
                                        });
                                    }
                                </script>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('profile.edit') }}" class="px-4 py-2 border border-red-800 bg-[#0a0a0a] hover:bg-red-950/30 text-white text-xs font-bold uppercase tracking-widest transition-all rounded-sm">
                            Edit Profile
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/20 border border-green-900/40 text-green-500 text-sm font-bold rounded-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-gray-900/20 border border-gray-700 text-gray-500 text-sm font-bold rounded-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Sidebar: Forum Info -->
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                    <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider">
                        {{ $user->username }}'s Forum Info
                    </div>
                    <div class="p-5">
                        <div class="w-full mb-6 py-2 border border-red-600/50 bg-red-950/10 text-red-500 text-center font-black uppercase tracking-[0.2em] text-xs">
                            {{ $user->identification->role->label() }}
                        </div>

                        <div class="space-y-4">
                            <div class="border-b border-white/10 pb-2">
                                <div class="text-[10px] font-bold text-gray-500 uppercase mb-1">Joined</div>
                                <div class="text-sm text-white font-mono">{{ $user->created_at->format('y-m-d') }}</div>
                            </div>

                            <div class="border-b border-white/10 pb-2">
                                <div class="text-[10px] font-bold text-gray-500 uppercase mb-1">Time Spent Online</div>
                                <div class="text-sm text-white font-mono">
                                    {{ $user->created_at->diffForHumans(null, true) }}
                                </div>
                            </div>

                            <div class="pb-2">
                                <div class="text-[10px] font-bold text-gray-500 uppercase mb-1">User Identifier</div>
                                <div class="text-sm text-white font-mono">{{ $user->id }}</div>
                            </div>

                            <div class="pb-2">
                                <div class="text-[10px] font-bold text-gray-500 uppercase mb-1">Changes / Refs</div>
                                <div class="text-sm text-white font-mono">{{ $user->edits()->where('status', 'approved')->count() }} / {{ $user->referredUsers()->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts Block -->
                <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                    <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider">
                        Recent Posts
                    </div>
                    <div class="p-0">
                        @forelse($recentPosts as $post)
                            <div class="border-b border-red-900/10 p-3 hover:bg-red-950/5 transition-all flex flex-col gap-1">
                                <a href="{{ route('pastebin.show', $post->slug) }}" class="text-[11px] font-black text-white hover:text-red-500 transition-colors">
                                    {{ Str::limit($post->title, 40) }}
                                </a>
                                <div class="text-[9px] text-gray-600 font-mono">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="p-5 text-center text-xs text-gray-600 italic">No posts yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Comments Block -->
                <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                    <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider">
                        Recent Comments
                    </div>
                    <div class="p-0">
                        @forelse($recentComments as $comment)
                            <div class="border-b border-red-900/10 p-3 hover:bg-red-950/5 transition-all flex flex-col gap-1">
                                <a href="{{ route('pastebin.show', $comment->pastebin->slug ?? '#') }}" class="text-[11px] font-black text-gray-300 hover:text-red-500 transition-colors italic">
                                    "{{ Str::limit($comment->clean_content, 50) }}"
                                </a>
                                <div class="text-[9px] text-gray-500 mt-1">
                                    on <a href="{{ route('pastebin.show', $comment->pastebin->slug ?? '#') }}" class="text-white hover:underline">{{ Str::limit($comment->pastebin->title ?? 'Unknown', 30) }}</a>
                                    • <span class="font-mono text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center text-xs text-gray-600 italic">No comments yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Middle Column: Details & Stats -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Recent Contributions Block -->
                <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                    <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider flex items-center justify-between">
                        Recent Approved Contributions
                       
                    </div>
                    <div class="p-0">
                        @forelse($recentContributions as $contribution)
                            <div class="border-b border-red-900/10 p-4 hover:bg-red-950/5 transition-all flex items-center justify-between group">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('pastebin.show', $contribution->pastebin->slug) }}" class="text-xs font-black text-white hover:text-red-500 transition-colors">
                                        {{ $contribution->pastebin->title }}
                                    </a>
                                    <div class="text-[10px] text-gray-500 font-mono italic">
                                        "{{ Str::limit($contribution->title, 50) }}"
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[9px] font-black text-green-500 uppercase tracking-widest">Approved</div>
                                    <div class="text-[9px] text-gray-600 font-mono">{{ $contribution->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-xs text-gray-600 italic">No approved contributions yet.</div>
                        @endforelse
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- ADVERTISER SECTION — only shown for Advertiser role users   --}}
                {{-- ============================================================ --}}
                @if($advertiserData)
                    {{-- Active Banners Preview --}}
                    @if($advertiserData['activeBanners']->isNotEmpty())
                        <div class="bg-[#0a0a0a] border border-amber-900/30 overflow-hidden rounded-sm">
                            <div class="bg-[#111] px-4 py-2.5 border-b border-amber-900/40 text-[11px] font-black text-amber-500 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Active Banners
                                <span class="ml-auto text-[9px] font-mono text-amber-700">{{ $advertiserData['activeBanners']->count() }} LIVE</span>
                            </div>
                            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($advertiserData['activeBanners'] as $banner)
                                    <div class="group relative border border-amber-900/20 rounded-sm overflow-hidden bg-[#050505] hover:border-amber-700/40 transition-colors">
                                        <a href="{{ $banner->target_url }}" target="_blank" rel="noopener noreferrer">
                                            <img
                                                src="{{ $banner->media_url }}"
                                                alt="{{ $banner->title }}"
                                                class="w-full h-24 object-cover opacity-90 group-hover:opacity-100 transition-opacity"
                                                onerror="this.style.display='none'"
                                            >
                                        </a>
                                        <div class="px-3 py-2 flex items-center justify-between">
                                            <div>
                                                <div class="text-[10px] font-black text-white truncate max-w-[160px]">{{ $banner->title }}</div>
                                                <div class="text-[8px] text-amber-600 uppercase font-mono">{{ $banner->campaign_name }}</div>
                                            </div>
                                            <span class="text-[8px] font-black px-1.5 py-0.5 rounded-sm border border-green-700/40 bg-green-950/20 text-green-500 uppercase">LIVE</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Advertiser Stats Overview --}}
                    <div class="bg-[#0a0a0a] border border-amber-900/30 overflow-hidden rounded-sm">
                        <div class="bg-[#111] px-4 py-2.5 border-b border-amber-900/40 text-[11px] font-black text-amber-500 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Advertising Statistics
                            @if($advertiserData['advertiser']->company_name)
                                <span class="ml-auto text-[9px] font-mono text-amber-700">{{ $advertiserData['advertiser']->company_name }}</span>
                            @endif
                        </div>
                        <div class="p-5">
                            {{-- Summary Stat Cards --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                                <div class="bg-[#050505] border border-amber-900/15 rounded-sm p-3 text-center">
                                    <div class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Clicks</div>
                                    <div class="text-xl font-black text-amber-400">{{ number_format($advertiserData['totalClicks']) }}</div>
                                </div>
                                <div class="bg-[#050505] border border-amber-900/15 rounded-sm p-3 text-center">
                                    <div class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Views</div>
                                    <div class="text-xl font-black text-amber-400">{{ number_format($advertiserData['totalImpressions']) }}</div>
                                </div>
                                <div class="bg-[#050505] border border-amber-900/15 rounded-sm p-3 text-center">
                                    <div class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Campaigns</div>
                                    <div class="text-xl font-black text-white">{{ $advertiserData['totalCampaigns'] }}</div>
                                </div>
                                <div class="bg-[#050505] border border-amber-900/15 rounded-sm p-3 text-center">
                                    <div class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total Ads</div>
                                    <div class="text-xl font-black text-white">{{ $advertiserData['totalAds'] }}</div>
                                </div>
                            </div>

                            {{-- CTR rate if both exist --}}
                            @if($advertiserData['totalImpressions'] > 0)
                                <div class="mb-5">
                                    <div class="flex justify-between text-[9px] font-mono text-gray-500 mb-1">
                                        <span class="uppercase tracking-widest">Click-Through Rate (CTR)</span>
                                        <span class="text-amber-500 font-black">{{ number_format(($advertiserData['totalClicks'] / $advertiserData['totalImpressions']) * 100, 2) }}%</span>
                                    </div>
                                    @php
                                        $ctrPercent = min(100, ($advertiserData['totalClicks'] / max(1, $advertiserData['totalImpressions'])) * 100 * 10);
                                    @endphp
                                    <div class="w-full h-1 bg-amber-900/20 rounded-full overflow-hidden">
                                        <div class="h-full bg-amber-500 rounded-full transition-all" style="width: {{ $ctrPercent }}%"></div>
                                    </div>
                                </div>
                            @endif

                            {{-- All Ads Table --}}
                            @if($advertiserData['allAds']->isNotEmpty())
                                <div class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 border-t border-amber-900/10 pt-4">All Ads (incl. expired)</div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left font-mono">
                                        <thead>
                                            <tr class="text-[8px] text-gray-600 uppercase tracking-widest border-b border-amber-900/10">
                                                <th class="pb-2 font-black">Ad Title</th>
                                                <th class="pb-2 font-black">Campaign</th>
                                                <th class="pb-2 font-black">Type</th>
                                                <th class="pb-2 font-black text-center">Clicks</th>
                                                <th class="pb-2 font-black text-center">Views</th>
                                                <th class="pb-2 font-black text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-amber-900/10">
                                            @foreach($advertiserData['allAds'] as $ad)
                                                @php
                                                    $adClicks = $ad->statistics->sum('clicks');
                                                    $adViews  = $ad->statistics->sum('impressions');
                                                @endphp
                                                <tr class="text-[10px] hover:bg-amber-950/5 transition-colors">
                                                    <td class="py-2.5 pr-3">
                                                        <a href="{{ $ad->target_url }}" target="_blank" rel="noopener noreferrer" class="text-gray-200 font-bold hover:text-amber-400 transition-colors truncate max-w-[140px] block">
                                                            {{ Str::limit($ad->title, 28) }}
                                                        </a>
                                                    </td>
                                                    <td class="py-2.5 pr-3 text-gray-500">{{ Str::limit($ad->campaign_name, 20) }}</td>
                                                    <td class="py-2.5 pr-3">
                                                        <span class="text-[8px] uppercase tracking-wider text-gray-400 border border-gray-800 px-1.5 py-0.5 rounded-sm">{{ $ad->type }}</span>
                                                    </td>
                                                    <td class="py-2.5 text-center font-black text-amber-400">{{ number_format($adClicks) }}</td>
                                                    <td class="py-2.5 text-center font-black text-gray-300">{{ number_format($adViews) }}</td>
                                                    <td class="py-2.5 text-center">
                                                        <span class="text-[8px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded-sm border
                                                            @if($ad->status === 'active') border-green-800/50 bg-green-950/20 text-green-500
                                                            @elseif($ad->status === 'paused') border-yellow-800/50 bg-yellow-950/20 text-yellow-500
                                                            @elseif($ad->status === 'rejected') border-red-800/50 bg-red-950/20 text-red-500
                                                            @else border-gray-800/50 bg-gray-950/20 text-gray-500
                                                            @endif">
                                                            {{ $ad->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="py-6 text-center text-[10px] text-gray-600 italic">No ads created yet.</div>
                            @endif
                        </div>
                    </div>
                @endif
                {{-- END ADVERTISER SECTION --}}

                        <!-- Referral System Card -->
                        <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                            <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider flex items-center justify-between">
                                <span>{{ auth()->check() && auth()->id() === $user->id ? 'Your Referral Program' : $user->username . "'s Referral Program" }}</span>
                            </div>
                            <div class="p-5 space-y-4">
                                <p class="text-xs text-gray-400 leading-relaxed">
                                    Invite other users to join to DoxMe and get benefits
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label class="text-[10px] font-bold text-gray-500 uppercase">{{ auth()->check() && auth()->id() === $user->id ? 'Your Referral Link' : $user->username . "'s Referral Link" }}</label>
                                        <div class="flex items-center gap-2 bg-[#050505] border border-red-900/20 p-2 rounded-sm">
                                            <input type="text" readonly id="referral-link" value="{{ route('register.index', ['ref' => $user->username]) }}" 
                                                   class="bg-transparent text-xs text-gray-300 font-mono focus:outline-none flex-1 select-all cursor-text" />
                                            <button onclick="copyReferralLink()" class="px-3 py-1 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-[10px] font-black uppercase tracking-widest transition-all rounded-sm flex items-center gap-1 active:scale-95">
                                                <span id="copy-btn-text">Copy</span>
                                            </button>
                                        </div>
                                    </div>

                                 
                                </div>

                                <div class="flex items-center justify-between border-t border-red-900/10 pt-4 mt-2">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">{{ auth()->check() && auth()->id() === $user->id ? 'Total Referred Users' : 'Total Referred by ' . $user->username }}</span>
                                        <span class="text-[9px] text-gray-600">Calculated in real-time</span>
                                    </div>
                                    <span class="text-xs text-red-500 font-black font-mono bg-red-950/20 border border-red-900/40 px-3 py-1 rounded-sm">
                                        {{ $user->referredUsers()->count() }} USERS
                                    </span>
                                </div>
                            </div>
                        </div>

                        <script>
                            function fallbackCopyText(text, successCallback) {
                                const textArea = document.createElement("textarea");
                                textArea.value = text;
                                textArea.style.position = "fixed";
                                textArea.style.top = "0";
                                textArea.style.left = "0";
                                textArea.style.width = "2em";
                                textArea.style.height = "2em";
                                textArea.style.padding = "0";
                                textArea.style.border = "none";
                                textArea.style.outline = "none";
                                textArea.style.boxShadow = "none";
                                textArea.style.background = "transparent";
                                document.body.appendChild(textArea);
                                textArea.focus();
                                textArea.select();
                                try {
                                    const successful = document.execCommand('copy');
                                    if (successful && successCallback) {
                                        successCallback();
                                    }
                                } catch (err) {
                                    console.error('Fallback copy failed', err);
                                }
                                document.body.removeChild(textArea);
                            }

                            function copyTextToClipboard(text, successCallback) {
                                if (!navigator.clipboard) {
                                    fallbackCopyText(text, successCallback);
                                    return;
                                }
                                navigator.clipboard.writeText(text).then(successCallback, function(err) {
                                    fallbackCopyText(text, successCallback);
                                });
                            }

                            function copyReferralLink() {
                                const copyText = document.getElementById("referral-link");
                                copyText.select();
                                copyText.setSelectionRange(0, 99999); // For mobile devices
                                
                                copyTextToClipboard(copyText.value, function() {
                                    const btnText = document.getElementById("copy-btn-text");
                                    btnText.innerText = "Copied!";
                                    btnText.style.color = '#22c55e'; // Tailwind green-500
                                    
                                    setTimeout(() => {
                                        btnText.innerText = "Copy";
                                        btnText.style.color = '';
                                    }, 2000);
                                });
                            }

                            function copyReferralCode() {
                                const copyText = document.getElementById("referral-code");
                                copyText.select();
                                copyText.setSelectionRange(0, 99999); // For mobile devices
                                
                                copyTextToClipboard(copyText.value, function() {
                                    const btnText = document.getElementById("copy-code-btn-text");
                                    btnText.innerText = "Copied!";
                                    btnText.style.color = '#22c55e'; // Tailwind green-500
                                    
                                    setTimeout(() => {
                                        btnText.innerText = "Copy";
                                        btnText.style.color = '';
                                    }, 2000);
                                });
                            }
                        </script>

                <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                    <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider">
                        Contact Details
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div class="text-gray-400 font-bold">HomePage</div>
                            <div class="col-span-2 text-red-400 font-medium">
                                @if($user->identification->website && $user->identification->website !== 'N/A')
                                    <a href="{{ $user->identification->website }}" target="_blank" class="hover:underline transition-all">{{ $user->identification->website }}</a>
                                @else
                                    <span class="text-gray-700">N/A</span>
                                @endif
                            </div>

                            @if(!empty($user->email))
                            <div class="text-gray-400 font-bold">Email</div>
                            <div class="col-span-2 flex items-center gap-2">
                                <span class="text-gray-300 font-mono text-xs">{{ $user->email }}</span>
                                @if($user->email_verified_at)
                                    <span class="flex items-center gap-1 text-[9px] font-black text-blue-400 border border-blue-500/30 bg-blue-500/10 px-1.5 py-0.5 rounded-sm">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Verified
                                    </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
 <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                    <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider">
                        Additional Info
                    </div>
                    <div class="p-5 space-y-4">
                       
                        <div>
                            <div class="text-[10px] font-bold text-gray-500 uppercase mb-1">Bio</div>
                            <div class="text-xs text-gray-300 italic leading-relaxed">
                                {{ $user->identification->bio ?? 'No bio provided.' }}
                            </div>
                        </div>
                    
                    </div>
                </div>
                <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm">
                    <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 text-[11px] font-black text-red-500 uppercase tracking-wider">
                        Forum Statistics
                    </div>
                    <div class="p-6">
                        <div class="border-b border-red-900/30 pb-3 mb-6">
                            <h3 class="text-sm font-black text-red-500 uppercase tracking-widest">Main Stats</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <div class="text-[10px] font-bold text-gray-500 uppercase mb-2">Total Pastebin</div>
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-2xl font-black text-white">{{ $user->pastebins_count }}</span>
                                        <span class="text-[10px] text-gray-500">({{ number_format($user->pastebins_count / max(1, $user->created_at->diffInDays(now())), 2) }} / day)</span>
                                    </div>
                                    <a href="{{ route('profile.pastebins', $user->username) }}" class="text-[9px] text-red-500 hover:text-red-400 font-bold uppercase mt-1 block">Find All Pastebin</a>
                                </div>

                                <div>
                                    <div class="text-[10px] font-bold text-gray-500 uppercase mb-2">Total Posts</div>
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-2xl font-black text-white">{{ $user->comments_count }}</span>
                                        <span class="text-[10px] text-gray-500">({{ number_format($user->comments_count / max(1, $user->created_at->diffInDays(now())), 2) }} / day)</span>
                                    </div>
                                    <a href="{{ route('profile.posts', $user->username) }}" class="text-[9px] text-red-500 hover:text-red-400 font-bold uppercase mt-1 block">Find All Posts</a>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <div class="text-[10px] font-bold text-gray-500 uppercase mb-2">Reputation</div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-black text-red-600">{{ $user->identification->reputation ?? 0 }}</span>
                                    </div>
                                </div>
                       
                                @auth
                                    @if(auth()->id() !== $user->id)
                                        <div class="mt-4">
                                            @if(auth()->user()->following()->where('following_id', $user->id)->exists())
                                                <form action="{{ route('user.unfollow', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full py-2 border border-gray-700 bg-gray-900 hover:bg-gray-800 text-gray-400 text-[10px] font-black uppercase tracking-widest transition-all rounded-sm">
                                                        Unfollow User
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('user.follow', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full py-2 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-[10px] font-black uppercase tracking-widest transition-all rounded-sm">
                                                        Follow User
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Section -->
    <div id="followersModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#0a0a0a] border border-red-900/40 w-full max-w-md rounded-sm overflow-hidden shadow-2xl">
            <div class="bg-[#111] px-5 py-3 border-b border-red-900/40 flex justify-between items-center">
                <span class="text-xs font-black text-red-500 uppercase tracking-widest">Followers</span>
                <button onclick="toggleModal('followersModal')" class="text-gray-500 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="p-4 max-h-[60vh] overflow-y-auto space-y-3">
                @forelse($user->followers as $follower)
                    <a href="{{ route('profile.show', $follower->username) }}" class="flex items-center gap-3 p-2 hover:bg-red-900/5 transition-all">
                        <div class="w-10 h-10 overflow-hidden flex-shrink-0">
                            @if($follower->identification->avatar_path)
                                <img src="{{ Storage::url($follower->identification->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-700 font-bold bg-[#050505]">{{ substr($follower->username, 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $follower->username }}</div>
                            <div class="text-[10px] text-gray-500 uppercase">{{ $follower->identification->role->label() }}</div>
                        </div>
                    </a>
                @empty
                    <div class="py-10 text-center text-xs text-gray-600 italic">No followers yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="followingModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#0a0a0a] border border-red-900/40 w-full max-w-md rounded-sm overflow-hidden shadow-2xl">
            <div class="bg-[#111] px-5 py-3 border-b border-red-900/40 flex justify-between items-center">
                <span class="text-xs font-black text-red-500 uppercase tracking-widest">Following</span>
                <button onclick="toggleModal('followingModal')" class="text-gray-500 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="p-4 max-h-[60vh] overflow-y-auto space-y-3">
                @forelse($user->following as $following)
                    <a href="{{ route('profile.show', $following->username) }}" class="flex items-center gap-3 p-2 border border-red-900/10 hover:bg-red-900/5 transition-all">
                        <div class="w-10 h-10 border border-red-900/20 overflow-hidden flex-shrink-0">
                            @if($following->identification->avatar_path)
                                <img src="{{ Storage::url($following->identification->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-700 font-bold bg-[#050505]">{{ substr($following->username, 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $following->username }}</div>
                            <div class="text-[10px] text-gray-500 uppercase">{{ $following->identification->role->label() }}</div>
                        </div>
                    </a>
                @empty
                    <div class="py-10 text-center text-xs text-gray-600 italic">Not following anyone yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        // Close on clicking outside
        window.onclick = function(event) {
            if (event.target.id === 'followersModal') toggleModal('followersModal');
            if (event.target.id === 'followingModal') toggleModal('followingModal');
        }
    </script>
</x-layouts.app>
