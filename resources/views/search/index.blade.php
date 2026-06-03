<x-layouts.app :title="$title">
    <div class="text-neutral-200 flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <main class="w-full max-w-xl flex flex-col items-center">
            <p class="text-sm text-neutral-400 text-center leading-relaxed mb-6 max-w-md">
                Search public pastes and profiles. Minimal logging, encrypted transport.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-2 mb-8 text-sm">
                <a href="{{ route('gate.tor') }}" class="px-3 py-1.5 rounded border border-neutral-700 text-neutral-300 hover:border-neutral-500 hover:text-white transition-colors">
                    Tor
                </a>
                <a href="{{ route('gate.clearnet') }}" class="px-3 py-1.5 rounded border border-neutral-800 text-neutral-500 hover:border-neutral-600 hover:text-neutral-300 transition-colors">
                    Clearnet
                </a>
            </div>

            @if(!empty($legacyPlatformName))
                <p class="w-full max-w-md mb-6 text-sm text-neutral-500 text-center">
                    Registered on {{ $legacyPlatformName }}?
                    <a href="{{ route('login') }}" class="text-neutral-300 hover:text-white underline underline-offset-2">Sign in</a>
                    with the same credentials.
                </p>
            @endif

            <form action="{{ route('search.index') }}" method="GET" class="w-full mb-8" autocomplete="off" id="main-search-form">
                <label for="search-input" class="sr-only">Search</label>
                <div class="relative">
                    <input
                        id="search-input"
                        type="search"
                        name="q"
                        placeholder="Keywords, username, or title…"
                        autocomplete="off"
                        spellcheck="false"
                        class="w-full bg-neutral-950 border border-neutral-800 text-neutral-100 rounded-md py-3 px-4 text-sm focus:outline-none focus:border-neutral-500"
                    >

                    <div id="autocomplete-box"
                         class="absolute left-0 right-0 top-full mt-1 bg-neutral-950 border border-neutral-800 rounded-md z-50 hidden overflow-hidden">
                        <ul id="autocomplete-list" class="divide-y divide-neutral-900 max-h-64 overflow-y-auto"></ul>
                        <div id="autocomplete-loading" class="hidden px-4 py-3 text-sm text-neutral-600">
                            Searching…
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                    <button type="submit" class="text-sm px-4 py-2 rounded-md bg-neutral-100 text-neutral-950 hover:bg-white transition-colors">
                        Search
                    </button>
                    <a href="{{ route('search.advanced') }}" class="text-sm px-4 py-2 rounded-md border border-neutral-800 text-neutral-400 hover:text-neutral-200 hover:border-neutral-600 transition-colors">
                        Advanced
                    </a>
                </div>
            </form>

            <nav class="w-full flex flex-wrap justify-center gap-x-4 gap-y-2 text-sm text-neutral-500 mb-8 pb-8 border-b border-neutral-900">
                <a href="{{ route('search.trending') }}" class="hover:text-neutral-200 transition-colors">Trending</a>
                <a href="{{ route('search.recent') }}" class="hover:text-neutral-200 transition-colors">Recent</a>
                <a href="{{ route('pastebin.create') }}" class="hover:text-neutral-200 transition-colors">New paste</a>
            </nav>

            <x-internal-ads class="mb-6 w-full" />

            <div class="w-full grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8 text-center">
                <div class="rounded-md border border-neutral-900 bg-neutral-950/80 p-3">
                    <div class="text-lg font-medium text-neutral-100 tabular-nums" id="stat-users">{{ number_format($stats['total_users'] ?? 0) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Users</div>
                </div>
                <div class="rounded-md border border-neutral-900 bg-neutral-950/80 p-3">
                    <div class="text-lg font-medium text-neutral-100 tabular-nums" id="stat-total">{{ number_format($stats['total']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Pastes</div>
                </div>
                <div class="rounded-md border border-neutral-900 bg-neutral-950/80 p-3">
                    <div class="text-lg font-medium text-neutral-100 tabular-nums" id="stat-views">{{ number_format($stats['total_views']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Views</div>
                </div>
                <div class="rounded-md border border-neutral-900 bg-neutral-950/80 p-3">
                    <div class="text-lg font-medium text-neutral-100 tabular-nums" id="stat-dl">{{ number_format($stats['total_downloads']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Downloads</div>
                </div>
            </div>

            @if($trending->isNotEmpty())
                <div class="w-full mb-8">
                    <p class="text-xs text-neutral-500 mb-2 text-center">Trending</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($trending as $paste)
                            <a href="{{ route('pastebin.show', $paste->slug) }}"
                               class="text-xs px-2.5 py-1 rounded border border-neutral-800 text-neutral-400 hover:text-neutral-200 hover:border-neutral-600 transition-colors truncate max-w-[10rem]"
                               title="{{ $paste->title }}">
                                {{ \Illuminate\Support\Str::limit($paste->title, 24) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(isset($latestUser))
                <p class="w-full mb-6 text-sm text-neutral-500 text-center">
                    Latest member:
                    <a href="{{ route('profile.show', $latestUser->username) }}" class="text-neutral-300 hover:text-white">{{ $latestUser->username }}</a>
                    <span class="text-neutral-600">· {{ $latestUser->created_at->diffForHumans() }}</span>
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
            if (isMemb && visitor.user_style) {
                return visitor.user_style;
            }
            const color = visitor.role_color || '#9ca3af';
            return '<span style="color:' + color + '">' + name + '</span>';
        }

        function updateRootVisitors(data) {
            const countEl = document.getElementById('root-visitor-count');
            const listEl = document.getElementById('root-visitor-list');
            if (!countEl || !listEl) return;

            countEl.textContent = data.count;

            if (!data.visitors.length) {
                listEl.innerHTML = '<span class="text-neutral-600">No one online right now.</span>';
                return;
            }

            listEl.innerHTML = data.visitors.map(buildRootVisitorItem).join('<span class="text-neutral-700">, </span>');
        }

        async function rootHeartbeat() {
            if (!ROOT_TRACK_URL || !ROOT_CSRF) return;
            try {
                const res = await fetch(ROOT_TRACK_URL, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': ROOT_CSRF,
                    },
                    credentials: 'same-origin',
                });
                if (res.ok) {
                    updateRootVisitors(await res.json());
                }
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', function () {
            rootHeartbeat();
            setInterval(rootHeartbeat, 45000);
        });
    })();
    </script>
</x-layouts.app>
