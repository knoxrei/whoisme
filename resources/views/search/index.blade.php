<x-layouts.app :title="$title">
    <div class="text-gray-100 flex min-h-screen flex-col items-center justify-center relative font-mono">

        <main class="w-full max-w-2xl px-4 flex flex-col items-center z-10">
            <div class="mb-2 relative">
                <x-layouts.icon class="w-24 h-24 text-red-600" />
            </div>

            <div class="mb-8 flex flex-col items-center select-none text-center">
                <div class="flex items-baseline">
                    <span class="text-4xl md:text-5xl font-black text-white tracking-tighter" style="font-family:'Outfit',sans-serif;">Dox</span>
                    <span class="text-4xl md:text-5xl font-black text-red-600 tracking-tighter" style="font-family:'Outfit',sans-serif;">Me</span>
                </div>
                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-[0.15em] mt-3 max-w-lg leading-relaxed">
                    Find anyone's identity here anonymously without worrying about any limitations. <span class="text-red-500 font-black">We do not track you, all data is fully encrypted.</span>
                </p>
                <div class="flex items-center gap-3 mt-4">
                    <a href="{{ route('gate.tor') }}" class="flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-red-500 bg-red-950/20 border border-red-900/40 hover:bg-red-650 hover:text-white px-4 py-1.5 rounded transition-all duration-150 active:scale-95">
                        <span>Secure Tor Node</span>
                    </a>
                    <a href="{{ route('gate.clearnet') }}" class="flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-gray-400 bg-gray-950 border border-gray-800 hover:border-red-950 hover:bg-[#111] px-4 py-1.5 rounded transition-all duration-150 active:scale-95">
                        <span>Clearnet Node</span>
                    </a>
                </div>
                @if(!empty($legacyPlatformName))
                    <div class="mt-5 w-full max-w-lg p-3 border border-red-900/30 bg-red-950/10 rounded-sm text-left">
                        <p class="text-[10px] text-gray-400 font-mono leading-relaxed">
                            Already registered on <strong class="text-red-500">{{ $legacyPlatformName }}</strong>?
                            You can <a href="{{ route('login') }}" class="text-red-500 hover:text-red-400 underline underline-offset-2">sign in here</a>
                            with the same username and password.
                        </p>
                    </div>
                @endif
            </div>

            <form action="{{ route('search.index') }}" method="GET" class="w-full mb-8" autocomplete="off" id="main-search-form">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none z-10">
                        <svg class="h-4 w-4 text-red-600/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input
                        id="search-input"
                        type="text" name="q"
                        placeholder="Type keywords, phrases, or queries..."
                        autocomplete="off" spellcheck="false"
                        class="w-full bg-[#0a0a0a] border border-red-950/40 text-gray-200 rounded-lg py-3.5 pl-12 pr-4 text-xs font-mono focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 "
                    >

                    <div id="autocomplete-box"
                         class="absolute left-0 right-0 top-full mt-1 bg-[#0d0d0d] border border-red-900/30 rounded-lg shadow-2xl z-50 hidden overflow-hidden">
                        <ul id="autocomplete-list" class="divide-y divide-red-950/10 max-h-64 overflow-y-auto"></ul>
                        <div id="autocomplete-loading" class="hidden px-4 py-3 text-[10px] text-gray-600 uppercase tracking-widest font-mono">
                            Searching index...
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                    <button type="submit" class="text-[10px] font-bold uppercase tracking-wider text-gray-300 bg-[#0f0f0f] border border-red-950/50 hover:border-red-600 hover:text-white px-5 py-2 rounded transition-colors duration-150">
                        Search Paste
                    </button>
                    <a href="{{ route('search.advanced') }}" class="text-[10px] font-bold uppercase tracking-wider text-red-500/80 hover:text-red-500 bg-red-950/10 border border-red-950/30 hover:border-red-600 px-5 py-2 rounded transition-colors duration-150">
                        Advanced Search
                    </a>
                </div>
            </form>

            <div class="w-full flex justify-center gap-4 text-[10px] font-bold uppercase tracking-widest border-t border-red-950/20 pt-6 mb-8">
                <a href="{{ route('search.trending') }}" class="text-gray-500 hover:text-red-500 transition-colors">Trending Indexes</a>
                <span class="text-red-950/50 select-none">|</span>
                <a href="{{ route('search.recent') }}" class="text-gray-500 hover:text-red-500 transition-colors">Recent Feeds</a>
                <span class="text-red-950/50 select-none">|</span>
                <a href="{{ route('pastebin.create') }}" class="text-gray-500 hover:text-red-500 transition-colors">Publish Paste</a>
            </div>
            <x-internal-ads class="mb-4 mt-5" />
            <div class="w-full grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
                <div class="bg-[#0a0a0a] border border-red-950/20 rounded-sm p-3 text-center">
                    <div class="text-red-500 font-black text-base tabular-nums" id="stat-users">
                        {{ number_format($stats['total_users'] ?? 0) }}
                    </div>
                    <div class="text-[9px] text-gray-600 uppercase tracking-widest mt-1">Total Users</div>
                </div>
                <div class="bg-[#0a0a0a] border border-red-950/20 rounded-sm p-3 text-center">
                    <div class="text-red-500 font-black text-base tabular-nums" id="stat-total">
                        {{ number_format($stats['total']) }}
                    </div>
                    <div class="text-[9px] text-gray-600 uppercase tracking-widest mt-1">Indexed Pastes</div>
                </div>
                <div class="bg-[#0a0a0a] border border-red-950/20 rounded-sm p-3 text-center">
                    <div class="text-red-500 font-black text-base tabular-nums" id="stat-views">
                        {{ number_format($stats['total_views']) }}
                    </div>
                    <div class="text-[9px] text-gray-600 uppercase tracking-widest mt-1">Total Views</div>
                </div>
                <div class="bg-[#0a0a0a] border border-red-950/20 rounded-sm p-3 text-center">
                    <div class="text-red-500 font-black text-base tabular-nums" id="stat-dl">
                        {{ number_format($stats['total_downloads']) }}
                    </div>
                    <div class="text-[9px] text-gray-600 uppercase tracking-widest mt-1">Downloads</div>
                </div>
            </div>

            @if($trending->isNotEmpty())
            <div class="w-full mb-8">
                <p class="text-[9px] text-gray-600 uppercase tracking-widest mb-3 text-center"> Trending Now</p>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($trending as $paste)
                        <a href="{{ route('pastebin.show', $paste->slug) }}"
                           class="text-[9px] font-bold uppercase tracking-wider px-3 py-1.5 bg-red-950/10 border border-red-950/30 hover:border-red-600 hover:text-white text-gray-500 rounded-sm transition-colors truncate max-w-[150px]"
                           title="{{ $paste->title }}">
                            {{ \Illuminate\Support\Str::limit($paste->title, 20) }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if(isset($latestUser))
            <div class="w-full mb-6 flex flex-col items-center">
                <p class="text-[9px] text-gray-600 uppercase tracking-widest mb-2.5 text-center">Latest Registered Operative</p>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#0a0a0a] border border-red-950/40 rounded-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                    <span class="text-[10px] font-bold text-gray-200 font-mono">@ <a href="{{ route('profile.show', $latestUser->username) }}">{{ $latestUser->username }}</a></span>
                    <span class="text-[8px] text-gray-500 font-mono">joined {{ $latestUser->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @endif

            <div class="w-full mb-8">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                    <p class="text-[9px] text-gray-600 uppercase tracking-widest">Online Now &mdash; <span id="root-visitor-count" class="text-red-500 font-black">{{ $rootVisitorCount ?? count($rootVisitors) }}</span> active</p>
                </div>
                <div id="root-visitor-list" class="text-[10px] font-mono text-gray-400 text-center leading-relaxed break-words">
                    @if(count($rootVisitors) > 0)
                        @php
                            $rootLabels = collect($rootVisitors)->map(function($visitor) {
                                if ($visitor['type'] === 'member') {
                                    $role = \App\Enum\Role::from($visitor['role']);
                                    return $role->userStyle('@' . $visitor['name']);
                                }
                                return '<span class="text-gray-500">' . e($visitor['name']) . '</span>';
                            });
                        @endphp
                        {!! $rootLabels->implode(', ') !!}
                    @else
                        <span class="text-gray-700 italic">No visitors tracked yet...</span>
                    @endif
                </div>
            </div>

       
        </main>
    </div>

    @include('search.partials.autocomplete-script', ['inputId' => 'search-input', 'boxId' => 'autocomplete-box', 'listId' => 'autocomplete-list'])

    <script>
    const ROOT_TRACK_URL = '{{ route("visitors.root.track") }}';
    const ROOT_CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    function buildRootVisitorItem(visitor) {
        const isMemb = visitor.type === 'member';
        const name   = (isMemb ? '@' : '') + visitor.name;
        if (isMemb && visitor.user_style) {
            return visitor.user_style;
        }
        const color = visitor.role_color || '#6b7280';
        return `<span style="color:${color}">${name}</span>`;
    }

    function updateRootVisitors(data) {
        const countEl = document.getElementById('root-visitor-count');
        const listEl  = document.getElementById('root-visitor-list');
        if (!countEl || !listEl) return;

        countEl.textContent = data.count;

        if (data.visitors.length === 0) {
            listEl.innerHTML = '<span class="text-gray-700 italic">No visitors tracked yet...</span>';
            return;
        }
        listEl.innerHTML = data.visitors.map(buildRootVisitorItem).join(', ');
    }

    async function rootHeartbeat() {
        try {
            const res = await fetch(ROOT_TRACK_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': ROOT_CSRF,
                },
            });
            if (res.ok) {
                const data = await res.json();
                updateRootVisitors(data);
            }
        } catch (e) {}
    }

    document.addEventListener('DOMContentLoaded', () => {
        rootHeartbeat();
        setInterval(rootHeartbeat, 30000);
    });
</script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-layouts.app>

