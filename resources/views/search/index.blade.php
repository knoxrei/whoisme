<x-layouts.app :title="$title">
    <div class="text-gray-300 flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <main class="w-full max-w-xl flex flex-col items-center">
            <div class="mb-6">
                <x-layouts.icon class="w-20 h-20 text-red-600" />
            </div>

            <div class="mb-2 flex items-baseline">
                <span class="text-3xl md:text-4xl font-bold text-white tracking-tight">Dox</span>
                <span class="text-3xl md:text-4xl font-bold text-red-600 tracking-tight">Me</span>
            </div>

            <p class="text-sm text-gray-500 text-center leading-relaxed mb-6 max-w-md">
            Find anyone's identity here anonymously without worrying about any limitations. <span class="text-red-500 font-black">We do not track you, all data is fully encrypted.</span>
            </p>

            <div class="flex flex-wrap items-center justify-center gap-2 mb-8 text-sm">
                <a href="{{ route('gate.tor') }}" class="px-3 py-1.5 rounded border border-red-900/40 text-red-500 hover:border-red-600 hover:text-red-400 transition-colors">
                    Tor node
                </a>
                <a href="{{ route('gate.clearnet') }}" class="px-3 py-1.5 rounded border border-red-950/40 text-gray-500 hover:border-red-900 hover:text-gray-300 transition-colors">
                    Clearnet
                </a>
            </div>

            @if(!empty($legacyPlatformName))
                <p class="w-full max-w-md mb-6 text-sm text-gray-500 text-center border border-red-950/30 bg-black px-4 py-3 rounded-sm">
                    Registered on {{ $legacyPlatformName }}?
                    <a href="{{ route('login') }}" class="text-red-500 hover:text-red-400 underline underline-offset-2">Sign in</a>
                    with the same credentials.
                </p>
            @endif

            <form action="{{ route('search.index') }}" method="GET" class="w-full mb-8" autocomplete="off" id="main-search-form">
                <label for="search-input" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-red-600/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        id="search-input"
                        type="search"
                        name="q"
                        placeholder="Keywords, username, or title…"
                        autocomplete="off"
                        spellcheck="false"
                        class="w-full bg-black border border-red-950/50 text-gray-200 rounded-md py-3 pl-11 pr-4 text-sm focus:outline-none focus:border-red-600"
                    >
                    <div id="autocomplete-box"
                         class="absolute left-0 right-0 top-full mt-1 bg-black border border-red-950/50 rounded-md z-50 hidden overflow-hidden">
                        <ul id="autocomplete-list" class="divide-y divide-red-950/30 max-h-64 overflow-y-auto"></ul>
                        <div id="autocomplete-loading" class="hidden px-4 py-3 text-sm text-gray-600">Searching…</div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                    <button type="submit" class="text-sm px-5 py-2 rounded-md bg-red-700 text-white hover:bg-red-600 transition-colors">
                        Search
                    </button>
                    <a href="{{ route('search.advanced') }}" class="text-sm px-5 py-2 rounded-md border border-red-950/50 text-gray-400 hover:text-white hover:border-red-800 transition-colors">
                        Advanced
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

            <x-internal-ads class="mb-6 w-full" />

            <div class="w-full grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8 text-center">
                @foreach([
                    ['id' => 'stat-users', 'value' => number_format($stats['total_users'] ?? 0), 'label' => 'Users'],
                    ['id' => 'stat-total', 'value' => number_format($stats['total']), 'label' => 'Pastes'],
                    ['id' => 'stat-views', 'value' => number_format($stats['total_views']), 'label' => 'Views'],
                    ['id' => 'stat-dl', 'value' => number_format($stats['total_downloads']), 'label' => 'Downloads'],
                ] as $stat)
                    <div class="rounded-sm border border-red-950/30 bg-black p-3">
                        <div class="text-lg font-semibold text-red-500 tabular-nums" id="{{ $stat['id'] }}">{{ $stat['value'] }}</div>
                        <div class="text-xs text-gray-600 mt-1">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>

            @if($trending->isNotEmpty())
                <div class="w-full mb-8">
                    <p class="text-xs text-gray-600 mb-2 text-center">Trending</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($trending as $paste)
                            <a href="{{ route('pastebin.show', $paste->slug) }}"
                               class="text-xs px-2.5 py-1 rounded border border-red-950/40 text-gray-500 hover:text-red-500 hover:border-red-800 transition-colors truncate max-w-[10rem]"
                               title="{{ $paste->title }}">
                                {{ \Illuminate\Support\Str::limit($paste->title, 24) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(isset($latestUser))
                <p class="w-full mb-6 text-sm text-gray-500 text-center">
                    Latest member:
                    <a href="{{ route('profile.show', $latestUser->username) }}" class="text-red-500 hover:text-red-400">{{ $latestUser->username }}</a>
                    <span class="text-gray-600">· {{ $latestUser->created_at->diffForHumans() }}</span>
                </p>
            @endif

            <x-visitor-list
                :visitors="$rootVisitors"
                :count="$rootVisitorCount"
                class="mb-4"
            />
        </main>
    </div>

    @include('search.partials.autocomplete-script', ['inputId' => 'search-input', 'boxId' => 'autocomplete-box', 'listId' => 'autocomplete-list'])

    <script>
    (function () {
        const ROOT_TRACK_URL = @json(route('visitors.root.track'));
        const ROOT_CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

        function buildRootVisitorItem(visitor) {
            const isMemb = visitor.type === 'member';
            const name = (isMemb ? '@' : '') + visitor.name;
            if (isMemb && visitor.user_style) return visitor.user_style;
            const color = visitor.role_color || '#9ca3af';
            return '<span style="color:' + color + '">' + name + '</span>';
        }

        function updateRootVisitors(data) {
            const countEl = document.getElementById('root-visitor-count');
            const listEl = document.getElementById('root-visitor-list');
            if (!countEl || !listEl) return;
            countEl.textContent = data.count;
            if (!data.visitors.length) {
                listEl.innerHTML = '<span class="text-gray-600">No one online right now.</span>';
                return;
            }
            listEl.innerHTML = data.visitors.map(buildRootVisitorItem).join('<span class="text-gray-700">, </span>');
        }

        async function rootHeartbeat() {
            if (!ROOT_TRACK_URL || !ROOT_CSRF) return;
            try {
                const res = await fetch(ROOT_TRACK_URL, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': ROOT_CSRF },
                    credentials: 'same-origin',
                });
                if (res.ok) updateRootVisitors(await res.json());
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', function () {
            rootHeartbeat();
            setInterval(rootHeartbeat, 45000);
        });
    })();
    </script>
</x-layouts.app>
