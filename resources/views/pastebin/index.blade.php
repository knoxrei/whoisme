<x-layouts.app :title="$title">
<div class="min-h-screen text-gray-200 font-mono py-12 px-4">

    <div class="max-w-7xl mx-auto border border-red-950/20 p-6 relative">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-950/20 border border-green-900/30 text-green-500 text-xs font-bold rounded-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-950/20 border border-red-900/30 text-red-500 text-xs font-bold rounded-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between border-b border-red-900/30 pb-4 gap-4">
            <div>
                <h2 class="text-2xl font-black text-white uppercase tracking-tight mt-2">
                    Pastebins Index
                </h2>
                <p class="text-xs text-gray-500 mt-1">Listing all public, non-self-destructing pastes.</p>
            </div>
            <div class="bg-[#0a0a0a] border border-red-900/30 px-4 py-2 rounded-sm text-xs">
                Total Available: <span class="text-red-500 font-black" id="total-count">{{ $pastebins->total() }}</span>
            </div>
        </div>

        {{-- ── PINNED PASTEBINS (always rendered, never hidden by AJAX) ── --}}
        @if($pinnedPastebins->isNotEmpty())
            <div class="border border-red-600/40 overflow-hidden rounded-sm mb-6" id="pinned-section">
                <div class=" px-4 py-3 border-b border-red-600/40 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs font-black text-red-500 uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 2l-1.41 1.41L16 5H8.83L7 3.17 5.59 4.58 7 6v2.17l-5 5V16h5v5l1 1 1-1v-5h5v-2.83l5-5V6l1.41-1.41L16 2zm-1 12H9v-1.17L13 8.83V6h-2V8H9V6H7v2.83l-4 4V14h12v-1.17l-4-4V8H9V6h6v2.83l4 4V14h-4z"/>
                        </svg>
                        Pinned Pastebins
                        <span class="text-gray-600 font-normal text-[10px]">({{ $pinnedPastebins->count() }})</span>
                    </div>
                    @auth
                        @if(auth()->user()->identification?->role?->canManagePinned())
                            <span class="text-[9px] text-gray-600 font-mono">Drag rows to reorder</span>
                        @endif
                    @endauth
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-900/20 text-gray-500 uppercase tracking-widest text-[10px] bg-[#050505]">
                                @auth
                                    @if(auth()->user()->identification?->role?->canManagePinned())
                                        <th class="p-4 w-6"></th>
                                    @endif
                                @endauth
                                <th class="p-4">Title</th>
                                <th class="p-4">Author</th>
                                <th class="p-4 text-center">Views</th>
                                <th class="p-4 text-center">Downloads</th>
                                <th class="p-4 text-center">Comments</th>
                                <th class="p-4 text-right">Created</th>
                                @auth
                                    @if(auth()->user()->identification?->role?->canManagePinned())
                                        <th class="p-4 text-right">Action</th>
                                    @endif
                                @endauth
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/20" id="pinned-tbody">
                            @foreach($pinnedPastebins as $pin)
                                @php $paste = $pin->pastebin; @endphp
                                <tr class="hover:bg-red-900/5 transition-colors pinned-row" data-pin-id="{{ $pin->id }}">
                                    @auth
                                        @if(auth()->user()->identification?->role?->canManagePinned())
                                            <td class="p-4 cursor-grab text-gray-600 drag-handle select-none" title="Drag to reorder">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"/></svg>
                                            </td>
                                        @endif
                                    @endauth
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            @if($paste->password)
                                                <svg class="w-3 h-3 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            @endif
                                            <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-sm font-bold text-white hover:text-red-500 transition-colors">
                                                {{ Str::limit($paste->title, 40) }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @if($paste->user)
                                            <a href="{{ route('profile.show', $paste->user->username) }}" class="flex items-center gap-2 group">
                                                <div class="w-6 h-6 border border-red-900/30 overflow-hidden shrink-0">
                                                    @if($paste->user->identification && $paste->user->identification->avatar_path)
                                                        <img src="{{ Storage::url($paste->user->identification->avatar_path) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-[#111] flex items-center justify-center text-[10px] font-bold text-gray-500">{{ substr($paste->user->username, 0, 1) }}</div>
                                                    @endif
                                                </div>
                                                <span class="text-xs group-hover:text-red-500 transition-colors">
                                                    {!! $paste->user->identification->role->userStyle($paste->user->username) !!}
                                                </span>
                                            </a>
                                        @else
                                            <span class="text-gray-500 italic">Anonymous</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center text-gray-300 font-bold">
                                        {{ number_format($paste->views_count ?? $paste->views ?? 0) }}
                                    </td>
                                    <td class="p-4 text-center text-gray-300 font-bold">
                                        {{ number_format($paste->download_count ?? $paste->downloads_count ?? 0) }}
                                    </td>
                                    <td class="p-4 text-center text-gray-300 font-bold">
                                        {{ number_format($paste->comments_count ?? 0) }}
                                    </td>
                                    <td class="p-4 text-right text-gray-500 text-[10px] tracking-wider">
                                        {{ $paste->created_at->diffForHumans() ?? '0 seconds ago'}}
                                    </td>
                                    @auth
                                        @if(auth()->user()->identification?->role?->canManagePinned())
                                            <td class="p-4 text-right">
                                                <form action="{{ route('pastebin.unpin', $pin) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-red-500 border border-red-900/20 hover:border-red-600 px-3 py-1 transition-colors"
                                                        onclick="return confirm('Unpin this pastebin?')">
                                                        Unpin
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- ── SEARCH + SORT CONTROLS ── --}}
        <div class="mb-6 border border-red-900/30 p-4 bg-[#0a0a0a] rounded-sm">
            <div class="flex flex-col md:flex-row gap-4">
                {{-- Search input --}}
                <div class="flex-grow relative">
                    <input
                        type="text"
                        id="search-input"
                        value="{{ $query }}"
                        placeholder="Search by title..."
                        autocomplete="off"
                        class="w-full bg-[#050505] border border-red-900/30 text-gray-200 text-xs px-4 py-2 pr-10 focus:outline-none focus:border-red-600 transition-colors rounded-sm"
                    >
                    {{-- Spinner --}}
                    <div id="search-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                    {{-- Clear button --}}
                    <button id="search-clear" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 hover:text-red-500 transition-colors" title="Clear search">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="text-[10px] text-gray-600 mt-2">Realtime search berdasarkan judul. Gunakan tombol ▲▼ di header kolom untuk mengurutkan.</p>
        </div>

        {{-- ── MAIN PASTEBIN TABLE ── --}}
        <div class="border border-red-900/30 overflow-hidden rounded-sm mb-6" id="main-table-wrapper">
            <div class="px-4 py-3 border-b border-red-900/40 flex items-center justify-between">
                <span class="text-xs font-black text-red-500 uppercase tracking-wider" id="table-header-label">
                    @if($query)
                        Search Results for: "{{ $query }}"
                    @else
                        Pastebin Index
                    @endif
                </span>
                <span class="text-[10px] text-gray-600" id="sort-indicator">
                    Sorted by: <span id="sort-label">{{ $allowedOrderBy[$orderBy] }}</span>
                    <span id="sort-dir-icon">{{ $orderDirection === 'asc' ? '▲' : '▼' }}</span>
                </span>
            </div>

            {{-- Rate Limit Banner (hidden by default, shown in table area) --}}
            <div id="rate-limit-banner" class="hidden px-4 py-3 bg-yellow-950/30 border-b border-yellow-800/40 flex items-center gap-3">
                <svg class="w-4 h-4 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <div>
                    <p class="text-yellow-400 text-xs font-bold">Rate Limit Aktif</p>
                    <p class="text-yellow-600 text-[10px]" id="rate-limit-msg">Terlalu banyak request. Tunggu sebentar sebelum mencari lagi.</p>
                </div>
                <div class="ml-auto text-right">
                    <span class="text-yellow-500 font-black text-sm" id="rate-limit-countdown">5</span>
                    <span class="text-yellow-700 text-[10px] block">detik</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-red-900/20 text-gray-500 uppercase tracking-widest text-[10px] bg-[#050505]">
                            <th class="p-4">Title</th>
                            <th class="p-4">Author</th>

                            {{-- Views --}}
                            <th class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <span>Views</span>
                                    <div class="flex flex-col gap-0.5">
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="views_count" data-dir="asc" title="Sort ascending" id="sort-views_count-asc">▲</button>
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="views_count" data-dir="desc" title="Sort descending" id="sort-views_count-desc">▼</button>
                                    </div>
                                </div>
                            </th>

                            {{-- Downloads --}}
                            <th class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <span>Downloads</span>
                                    <div class="flex flex-col gap-0.5">
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="download_count" data-dir="asc" title="Sort ascending" id="sort-download_count-asc">▲</button>
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="download_count" data-dir="desc" title="Sort descending" id="sort-download_count-desc">▼</button>
                                    </div>
                                </div>
                            </th>

                            {{-- Comments --}}
                            <th class="p-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <span>Comments</span>
                                    <div class="flex flex-col gap-0.5">
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="comments_count" data-dir="asc" title="Sort ascending" id="sort-comments_count-asc">▲</button>
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="comments_count" data-dir="desc" title="Sort descending" id="sort-comments_count-desc">▼</button>
                                    </div>
                                </div>
                            </th>

                            {{-- Published --}}
                            <th class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <span>Published</span>
                                    <div class="flex flex-col gap-0.5">
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="created_at" data-dir="asc" title="Sort ascending" id="sort-created_at-asc">▲</button>
                                        <button class="sort-btn leading-none text-[8px] hover:text-red-400 transition-colors" data-col="created_at" data-dir="desc" title="Sort descending" id="sort-created_at-desc">▼</button>
                                    </div>
                                </div>
                            </th>

                            @auth
                                @if(auth()->user()->identification?->role?->canManagePinned())
                                    <th class="p-4 text-right">Pin</th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-950/20" id="main-tbody">
                        @forelse($pastebins as $paste)
                            <tr class="hover:bg-red-900/5 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        @if($paste->password)
                                            <svg class="w-3 h-3 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        @endif
                                        <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-sm font-bold text-white hover:text-red-500 transition-colors">
                                            {{ Str::limit($paste->title, 40) }}
                                        </a>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($paste->user)
                                        <a href="{{ route('profile.show', $paste->user->username) }}" class="flex items-center gap-2 group">
                                            <div class="w-6 h-6 border border-red-900/30 overflow-hidden shrink-0">
                                                @if($paste->user->identification && $paste->user->identification->avatar_path)
                                                    <img src="{{ Storage::url($paste->user->identification->avatar_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-[#111] flex items-center justify-center text-[10px] font-bold text-gray-500">{{ substr($paste->user->username, 0, 1) }}</div>
                                                @endif
                                            </div>
                                            <span class="text-xs group-hover:text-red-500 transition-colors">
                                                {!! $paste->user->identification->role->userStyle($paste->user->username) !!}
                                            </span>
                                        </a>
                                    @else
                                        <span class="text-gray-500 italic">{{ $paste->author_name ?: 'Anonymous' }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center text-gray-300 font-bold">{{ number_format($paste->views_count ?? $paste->views ?? 0) }}</td>
                                <td class="p-4 text-center text-gray-300 font-bold">{{ number_format($paste->download_count ?? $paste->downloads_count ?? 0) }}</td>
                                <td class="p-4 text-center text-gray-300 font-bold">{{ number_format($paste->comments_count ?? 0) }}</td>
                                <td class="p-4 text-right text-gray-500 text-[10px] tracking-wider">{{ $paste->created_at->diffForHumans() }}</td>
                                @auth
                                    @if(auth()->user()->identification?->role?->canManagePinned())
                                        <td class="p-4 text-right">
                                            @if($paste->pinnedRecord)
                                                <span class="text-[9px] text-red-500 font-black uppercase tracking-widest opacity-60">Pinned</span>
                                            @else
                                                <form action="{{ route('pastebin.pin', $paste) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-red-400 border border-red-900/20 hover:border-red-600 px-3 py-1 transition-colors">
                                                        Pin
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                @endauth
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-500 text-xs">No active pastebins found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4" id="pagination-wrapper">
            {{ $pastebins->links() }}
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- JAVASCRIPT                                                             --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <script>
    (function () {
        // ── Config ────────────────────────────────────────────────────────────
        const SEARCH_ENDPOINT = '{{ route("pastebin.list.search") }}';
        const CAN_MANAGE_PIN  = @json(auth()->check() && auth()->user()->identification?->role?->canManagePinned());
        const CSRF_TOKEN      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        // ── State ─────────────────────────────────────────────────────────────
        let currentQuery     = '{{ $query }}';
        let currentOrderBy   = '{{ $orderBy }}';
        let currentDir       = '{{ $orderDirection }}';
        let currentPage      = 1;
        let debounceTimer    = null;
        let rateLimitTimer   = null;
        let isRateLimited    = false;

        // ── DOM refs ──────────────────────────────────────────────────────────
        const searchInput      = document.getElementById('search-input');
        const searchSpinner    = document.getElementById('search-spinner');
        const searchClear      = document.getElementById('search-clear');
        const mainTbody        = document.getElementById('main-tbody');
        const paginationWrapper = document.getElementById('pagination-wrapper');
        const totalCount       = document.getElementById('total-count');
        const tableHeaderLabel = document.getElementById('table-header-label');
        const sortLabel        = document.getElementById('sort-label');
        const sortDirIcon      = document.getElementById('sort-dir-icon');
        const rateLimitBanner  = document.getElementById('rate-limit-banner');
        const rateLimitMsg     = document.getElementById('rate-limit-msg');
        const rateLimitCountdown = document.getElementById('rate-limit-countdown');

        const sortLabelMap = {
            created_at:     'Published',
            views_count:    'Views',
            download_count: 'Downloads',
            comments_count: 'Comments',
        };

        // ── Highlight active sort button ───────────────────────────────────────
        function highlightSortButtons() {
            document.querySelectorAll('.sort-btn').forEach(btn => {
                const isActive = btn.dataset.col === currentOrderBy && btn.dataset.dir === currentDir;
                btn.classList.toggle('text-red-500', isActive);
                btn.classList.toggle('font-black',  isActive);
                btn.classList.toggle('text-gray-600', !isActive);
            });
            if (sortLabel)    sortLabel.textContent  = sortLabelMap[currentOrderBy] ?? currentOrderBy;
            if (sortDirIcon)  sortDirIcon.textContent = currentDir === 'asc' ? '▲' : '▼';
        }
        highlightSortButtons();

        // ── Show/hide clear button ─────────────────────────────────────────────
        function updateClearBtn() {
            const hasVal = searchInput.value.trim().length > 0;
            searchClear.classList.toggle('hidden', !hasVal);
            searchSpinner.classList.add('hidden');
        }
        updateClearBtn();

        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            currentQuery = '';
            currentPage  = 1;
            updateClearBtn();
            doSearch();
        });

        // ── Search input event ────────────────────────────────────────────────
        searchInput.addEventListener('input', () => {
            updateClearBtn();
            searchSpinner.classList.remove('hidden');
            searchClear.classList.add('hidden');

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                currentQuery = searchInput.value.trim();
                currentPage  = 1;
                doSearch();
            }, 400);
        });

        // ── Sort button events ────────────────────────────────────────────────
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const col = btn.dataset.col;
                const dir = btn.dataset.dir;

                // If clicking same col+dir, toggle opposite
                if (currentOrderBy === col && currentDir === dir) {
                    currentDir = dir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentOrderBy = col;
                    currentDir     = dir;
                }
                currentPage = 1;
                highlightSortButtons();
                doSearch();
            });
        });

        // ── Show rate limit banner ────────────────────────────────────────────
        function showRateLimit(seconds, message) {
            isRateLimited = true;
            rateLimitBanner.classList.remove('hidden');
            rateLimitBanner.classList.add('flex');
            if (rateLimitMsg) rateLimitMsg.textContent = message || 'Terlalu banyak request. Tunggu sebentar.';

            let remaining = seconds;
            rateLimitCountdown.textContent = remaining;

            clearInterval(rateLimitTimer);
            rateLimitTimer = setInterval(() => {
                remaining--;
                rateLimitCountdown.textContent = remaining;
                if (remaining <= 0) {
                    clearInterval(rateLimitTimer);
                    hideRateLimit();
                }
            }, 1000);
        }

        function hideRateLimit() {
            isRateLimited = false;
            rateLimitBanner.classList.add('hidden');
            rateLimitBanner.classList.remove('flex');
        }

        // ── Build a table row from JSON ───────────────────────────────────────
        function buildRow(paste) {
            const profileBase = '/user-';
            const showBase    = '/pastebin/';

            let authorHtml = '';
            if (paste.author) {
                const avatarHtml = paste.author.avatar_url
                    ? `<img src="${paste.author.avatar_url}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full bg-[#111] flex items-center justify-center text-[10px] font-bold text-gray-500">${escHtml(paste.author.initial)}</div>`;

                authorHtml = `
                    <a href="${profileBase}${escHtml(paste.author.username)}" class="flex items-center gap-2 group">
                        <div class="w-6 h-6 border border-red-900/30 overflow-hidden shrink-0">${avatarHtml}</div>
                        <span class="text-xs group-hover:text-red-500 transition-colors">${paste.author.style_html ?? escHtml(paste.author.username)}</span>
                    </a>`;
            } else {
                authorHtml = `<span class="text-gray-500 italic">${escHtml(paste.author_name || 'Anonymous')}</span>`;
            }

            const lockIcon = paste.has_password
                ? `<svg class="w-3 h-3 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>`
                : '';

            let pinCell = '';
            if (CAN_MANAGE_PIN) {
                if (paste.is_pinned) {
                    pinCell = `<td class="p-4 text-right"><span class="text-[9px] text-red-500 font-black uppercase tracking-widest opacity-60">Pinned</span></td>`;
                } else {
                    pinCell = `<td class="p-4 text-right">
                        <form action="${escHtml(paste.pin_route)}" method="POST" class="inline">
                            <input type="hidden" name="_token" value="${escHtml(CSRF_TOKEN)}">
                            <button type="submit" class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-red-400 border border-red-900/20 hover:border-red-600 px-3 py-1 transition-colors">Pin</button>
                        </form>
                    </td>`;
                }
            }

            return `
                <tr class="hover:bg-red-900/5 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            ${lockIcon}
                            <a href="${showBase}${escHtml(paste.slug)}" class="text-sm font-bold text-white hover:text-red-500 transition-colors">${escHtml(paste.title)}</a>
                        </div>
                    </td>
                    <td class="p-4">${authorHtml}</td>
                    <td class="p-4 text-center text-gray-300 font-bold">${paste.views_count}</td>
                    <td class="p-4 text-center text-gray-300 font-bold">${paste.download_count}</td>
                    <td class="p-4 text-center text-gray-300 font-bold">${paste.comments_count}</td>
                    <td class="p-4 text-right text-gray-500 text-[10px] tracking-wider">${escHtml(paste.created_at)}</td>
                    ${pinCell}
                </tr>`;
        }

        // ── Build pagination links ────────────────────────────────────────────
        function buildPagination(data) {
            if (data.last_page <= 1) {
                paginationWrapper.innerHTML = '';
                return;
            }
            let links = `<div class="flex flex-wrap gap-1 text-xs font-mono">`;
            for (let p = 1; p <= data.last_page; p++) {
                const active = p === data.current_page;
                links += `<button
                    class="pagination-btn px-3 py-1.5 border transition-colors ${active
                        ? 'border-red-600 bg-red-900/30 text-red-400 font-black'
                        : 'border-red-900/30 text-gray-500 hover:border-red-600 hover:text-red-400'}"
                    data-page="${p}">${p}</button>`;
            }
            links += `</div>`;
            paginationWrapper.innerHTML = links;

            paginationWrapper.querySelectorAll('.pagination-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentPage = parseInt(btn.dataset.page);
                    doSearch();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
        }

        // ── Main fetch ────────────────────────────────────────────────────────
        function doSearch() {
            searchSpinner.classList.remove('hidden');
            searchClear.classList.add('hidden');

            const params = new URLSearchParams({
                q:               currentQuery,
                order_by:        currentOrderBy,
                order_direction: currentDir,
                page:            currentPage,
            });

            fetch(`${SEARCH_ENDPOINT}?${params.toString()}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(async res => {
                const json = await res.json();

                if (res.status === 429 || json.rate_limited) {
                    showRateLimit(json.retry_after ?? 5, json.message);
                    searchSpinner.classList.add('hidden');
                    updateClearBtn();
                    return;
                }

                hideRateLimit();

                // Update header label
                if (tableHeaderLabel) {
                    tableHeaderLabel.textContent = currentQuery
                        ? `Search Results for: "${currentQuery}"`
                        : 'Pastebin Index';
                }

                // Update total count
                if (totalCount) totalCount.textContent = json.total.toLocaleString();

                // Render rows
                if (json.rows.length === 0) {
                    mainTbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-gray-500 text-xs">No pastebins found matching your search.</td></tr>`;
                } else {
                    mainTbody.innerHTML = json.rows.map(buildRow).join('');
                }

                buildPagination(json);
                searchSpinner.classList.add('hidden');
                updateClearBtn();
            })
            .catch(() => {
                searchSpinner.classList.add('hidden');
                updateClearBtn();
            });
        }

        // ── Utility: HTML escape ──────────────────────────────────────────────
        function escHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

    })();
    </script>

    {{-- ── PINNED DRAG & DROP (admin only) ── --}}
    @auth
        @if(auth()->user()->identification?->role?->canManagePinned())
        <script>
            (function () {
                const tbody = document.getElementById('pinned-tbody');
                if (!tbody) return;

                let dragSrc = null;

                function getDragAfterElement(container, y) {
                    const rows = [...container.querySelectorAll('.pinned-row:not(.dragging)')];
                    return rows.reduce((closest, child) => {
                        const box = child.getBoundingClientRect();
                        const offset = y - box.top - box.height / 2;
                        if (offset < 0 && offset > closest.offset) {
                            return { offset, element: child };
                        }
                        return closest;
                    }, { offset: Number.NEGATIVE_INFINITY }).element;
                }

                tbody.addEventListener('dragstart', function (e) {
                    const row = e.target.closest('.pinned-row');
                    if (!row) return;
                    dragSrc = row;
                    row.classList.add('dragging', 'opacity-40');
                    e.dataTransfer.effectAllowed = 'move';
                });

                tbody.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    const afterElement = getDragAfterElement(tbody, e.clientY);
                    if (afterElement == null) {
                        tbody.appendChild(dragSrc);
                    } else {
                        tbody.insertBefore(dragSrc, afterElement);
                    }
                });

                tbody.addEventListener('dragend', function (e) {
                    const row = e.target.closest('.pinned-row');
                    if (row) row.classList.remove('dragging', 'opacity-40');
                    const order = [...tbody.querySelectorAll('.pinned-row')].map(r => parseInt(r.dataset.pinId));

                    fetch('{{ route("pastebin.pinned.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ order }),
                    }).catch(() => {});
                });

                tbody.querySelectorAll('.pinned-row').forEach(row => {
                    row.setAttribute('draggable', 'true');
                });
            })();
        </script>
        @endif
    @endauth

</div>
</x-layouts.app>
