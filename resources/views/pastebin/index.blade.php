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

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between border-b border-red-900/30 pb-4 gap-4">
            <div>
                <h2 class="text-2xl font-black text-white uppercase tracking-tight mt-2">
                    Global Pastebins Index
                </h2>
                <p class="text-xs text-gray-500 mt-1">Listing all public, non-self-destructing pastes.</p>
            </div>
            <div class="bg-[#0a0a0a] border border-red-900/30 px-4 py-2 rounded-sm text-xs">
                Total Available: <span class="text-red-500 font-black">{{ $pastebins->total() }}</span>
            </div>
        </div>

        @if($pinnedPastebins->isNotEmpty())
            <div class="border border-red-600/40 overflow-hidden rounded-sm mb-6" id="pinned-section">
                <div class="bg-[#111] px-4 py-3 border-b border-red-600/40 flex items-center justify-between">
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
                                            <svg class="w-3 h-3 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M16 2l-1.41 1.41L16 5H8.83L7 3.17 5.59 4.58 7 6v2.17l-5 5V16h5v5l1 1 1-1v-5h5v-2.83l5-5V6l1.41-1.41L16 2z"/>
                                            </svg>
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
                                        {{ $paste->created_at->format('d-m-Y') }}
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

        <div class="border border-red-900/30 overflow-hidden rounded-sm mb-6">
            <div class="px-4 py-3 border-b border-red-900/40 text-xs font-black text-red-500 uppercase tracking-wider">
                Recent Pastebins
            </div>

            @if($pastebins->isEmpty())
                <div class="p-8 text-center text-gray-500 text-xs">
                    No active pastebins found.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-900/20 text-gray-500 uppercase tracking-widest text-[10px] bg-[#050505]">
                                <th class="p-4">Title</th>
                                <th class="p-4">Author</th>
                                <th class="p-4 text-center">Views</th>
                                <th class="p-4 text-center">Downloads</th>
                                <th class="p-4 text-center">Comments</th>
                                <th class="p-4 text-right">Published</th>
                                @auth
                                    @if(auth()->user()->identification?->role?->canManagePinned())
                                        <th class="p-4 text-right">Pin</th>
                                    @endif
                                @endauth
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/20">
                            @foreach($pastebins as $paste)
                                <tr >
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
                                        {{ $paste->created_at->diffForHumans() }}
                                    </td>
                                    @auth
                                        @if(auth()->user()->identification?->role?->canManagePinned())
                                            <td class="p-4 text-right">
                                                @if($paste->pinnedRecord)
                                                    <span class="text-[9px] text-red-500 font-black uppercase tracking-widest opacity-60">Pinned</span>
                                                @else
                                                    <form action="{{ route('pastebin.pin', $paste) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-red-400 border border-red-900/20 hover:border-red-600 px-3 py-1 transition-colors">
                                                            Pin
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        @endif
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-4">
            {{ $pastebins->links() }}
        </div>

    </div>

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
                    if (row) {
                        row.classList.remove('dragging', 'opacity-40');
                    }
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
