<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- Title & Filter Panel -->
        <div class="border border-red-900/40 bg-gradient-to-b from-red-950/10 to-[#0a0a0a] p-6 rounded-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-white tracking-tight uppercase">
                    Manage Reported Threads
                </h1>
                <p class="text-gray-500 text-xs font-mono mt-1">
                    System control room for Owner and Moderators to inspect and moderate reported threads.
                </p>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-2 font-mono text-[10px]">
                <a href="{{ route('dashboard.reports') }}" class="px-3 py-1.5 border {{ is_null($currentStatus) ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    All
                </a>
                <a href="{{ route('dashboard.reports', ['status' => 'pending']) }}" class="px-3 py-1.5 border {{ $currentStatus === 'pending' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Pending
                </a>
                <a href="{{ route('dashboard.reports', ['status' => 'dismissed']) }}" class="px-3 py-1.5 border {{ $currentStatus === 'dismissed' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Dismissed
                </a>
                <a href="{{ route('dashboard.reports', ['status' => 'resolved']) }}" class="px-3 py-1.5 border {{ $currentStatus === 'resolved' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Resolved
                </a>
            </div>
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

        <!-- Reports Table -->
        <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left font-mono">
                    <thead>
                        <tr class="text-gray-500 text-[9px] uppercase tracking-widest border-b border-red-900/10">
                            <th class="pb-3 font-black">Target Thread</th>
                            <th class="pb-3 font-black">Reporter</th>
                            <th class="pb-3 font-black">Reason</th>
                            <th class="pb-3 font-black">Status</th>
                            <th class="pb-3 font-black">Submitted</th>
                            <th class="pb-3 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900/10">
                        @forelse($reports as $report)
                            <tr class="text-xs">
                                <td class="py-4 pr-4 align-top">
                                    @if($report->pastebin)
                                        <a href="{{ route('pastebin.show', $report->pastebin->slug) }}" target="_blank" class="text-gray-300 font-bold hover:text-red-500 transition-colors">
                                            {{ Str::limit($report->pastebin->title, 35) }}
                                        </a>
                                        <span class="text-[8px] text-gray-600 block mt-0.5">SLUG: {{ $report->pastebin->slug }}</span>
                                    @else
                                        <span class="text-red-500/60 italic font-bold">Deleted Pastebin</span>
                                    @endif
                                </td>
                                <td class="py-4 pr-4 align-top">
                                    @if($report->user)
                                        <span class="text-gray-300 font-bold block">@ {{ $report->user->username }}</span>
                                        <span class="text-[8px] text-gray-600 block mt-0.5">UID: {{ $report->user_id }}</span>
                                    @else
                                        <span class="text-gray-500 italic block">Anonymous</span>
                                    @endif
                                </td>
                                <td class="py-4 pr-4 align-top max-w-xs break-words">
                                    <span class="text-gray-400 text-xs">{{ $report->reason }}</span>
                                </td>
                                <td class="py-4 align-top">
                                    <span class="px-1.5 py-0.5 rounded-sm text-[8px] font-black uppercase tracking-widest border 
                                        {{ $report->status === 'resolved' ? 'bg-green-950/30 text-green-500 border-green-900/30' : ($report->status === 'dismissed' ? 'bg-gray-950/30 text-gray-500 border-gray-900/30' : 'bg-red-950/30 text-red-500 border-red-900/30') }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td class="py-4 text-[10px] text-gray-500 align-top">{{ $report->created_at->diffForHumans() }}</td>
                                <td class="py-4 text-right align-top">
                                    <div class="flex justify-end gap-2 flex-wrap">
                                        @if($report->status === 'pending')
                                            {{-- Dismiss Button --}}
                                            <form action="{{ route('dashboard.reports.dismiss', $report->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[9px] font-black bg-gray-950/20 hover:bg-gray-700 hover:text-white text-gray-400 px-3 py-1 border border-gray-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                    Dismiss
                                                </button>
                                            </form>

                                            {{-- Edit Thread Button (opens modal) --}}
                                            @if($report->pastebin)
                                                <button
                                                    type="button"
                                                    onclick="openEditModal({{ $report->id }}, '{{ addslashes($report->pastebin->title) }}', '{{ addslashes($report->pastebin->description ?? '') }}', {{ json_encode($report->pastebin->content) }})"
                                                    class="text-[9px] font-black bg-yellow-950/20 hover:bg-yellow-700 hover:text-white text-yellow-500 px-3 py-1 border border-yellow-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                    Edit Thread
                                                </button>
                                            @endif

                                            {{-- Delete Thread Button --}}
                                            @if($report->pastebin)
                                                <form action="{{ route('pastebin.destroy', $report->pastebin) }}" method="POST" onsubmit="return confirm('WARNING: Are you absolutely sure you want to permanently erase this thread from the database? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[9px] font-black bg-red-950/20 hover:bg-red-600 hover:text-white text-red-500 px-3 py-1 border border-red-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                        Delete Thread
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-[9px] text-gray-600 uppercase tracking-widest font-black">Logged</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-xs text-gray-600 italic">
                                    No reports captured under this query state.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $reports->links() }}
        </div>
    </div>

    <!-- Instant Edit Thread Modal -->
    <div id="edit-thread-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#0a0a0a] border border-yellow-900/40 rounded-sm w-full max-w-2xl shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-yellow-900/20">
                <div>
                    <h2 class="text-sm font-black text-yellow-400 uppercase tracking-widest">Instant Edit Thread</h2>
                    <p class="text-[10px] text-gray-500 font-mono mt-0.5">Direct override — changes apply immediately and report is marked as resolved.</p>
                </div>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-white transition-colors text-lg font-bold leading-none">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="edit-thread-form" method="POST" action="">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Thread Title</label>
                        <input
                            type="text"
                            name="title"
                            id="edit-modal-title"
                            required
                            maxlength="255"
                            class="w-full bg-[#050505] border border-red-900/20 text-gray-200 text-xs font-mono px-3 py-2.5 rounded-sm focus:outline-none focus:border-yellow-700/60 transition-colors"
                            placeholder="Thread title..."
                        />
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Description <span class="text-gray-600 normal-case tracking-normal">(optional)</span></label>
                        <input
                            type="text"
                            name="description"
                            id="edit-modal-description"
                            maxlength="500"
                            class="w-full bg-[#050505] border border-red-900/20 text-gray-200 text-xs font-mono px-3 py-2.5 rounded-sm focus:outline-none focus:border-yellow-700/60 transition-colors"
                            placeholder="Short description..."
                        />
                    </div>

                    <!-- Content -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Thread Content</label>
                        <textarea
                            name="content"
                            id="edit-modal-content"
                            required
                            rows="10"
                            class="w-full bg-[#050505] border border-red-900/20 text-gray-200 text-xs font-mono px-3 py-2.5 rounded-sm focus:outline-none focus:border-yellow-700/60 transition-colors resize-y"
                            placeholder="Thread content (Markdown supported)..."
                        ></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-yellow-900/20 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="text-[10px] font-black text-gray-500 hover:text-white uppercase tracking-widest px-4 py-2 border border-gray-900/30 rounded-sm transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="text-[10px] font-black bg-yellow-950/30 hover:bg-yellow-600 hover:text-black text-yellow-400 uppercase tracking-widest px-5 py-2 border border-yellow-900/40 rounded-sm transition-colors duration-150">
                        Apply Edit &amp; Resolve
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(reportId, title, description, content) {
            document.getElementById('edit-modal-title').value = title;
            document.getElementById('edit-modal-description').value = description;
            document.getElementById('edit-modal-content').value = content;
            document.getElementById('edit-thread-form').action = '/dashboard/reports/' + reportId + '/edit-thread';

            const modal = document.getElementById('edit-thread-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-thread-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal on backdrop click
        document.getElementById('edit-thread-modal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeEditModal();
        });
    </script>
</x-layouts.dashboard>
