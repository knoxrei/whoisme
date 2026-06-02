<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-8 max-w-7xl mx-auto">
        <div class="border border-red-900/40 bg-gradient-to-b from-red-950/10 to-[#0a0a0a] p-6 rounded-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-white tracking-tight uppercase">
                    {{ $isStaff ? 'Global Audit Panel' : 'Edit Suggestions Hub' }}
                </h1>
                <p class="text-gray-500 text-xs font-mono mt-1">
                    {{ $isStaff 
                        ? 'System-wide control room to audit, approve, or reject Wikipedia-style edit suggestions.' 
                        : 'Manage edit suggestions made on your pastes, or trace status of edits you suggested elsewhere.' }}
                </p>
            </div>
            
            <div class="flex flex-wrap gap-2 font-mono text-[10px]">
                @php
                    $baseParams = [];
                    if (!$isStaff) {
                        $baseParams['tab'] = $currentTab;
                    }
                @endphp

                <a href="{{ route('dashboard.suggestions', $baseParams) }}" class="px-3 py-1.5 border {{ is_null($currentStatus) ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    All
                </a>
                <a href="{{ route('dashboard.suggestions', array_merge($baseParams, ['status' => 'pending'])) }}" class="px-3 py-1.5 border {{ $currentStatus === 'pending' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Pending
                </a>
                <a href="{{ route('dashboard.suggestions', array_merge($baseParams, ['status' => 'approved'])) }}" class="px-3 py-1.5 border {{ $currentStatus === 'approved' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Approved
                </a>
                <a href="{{ route('dashboard.suggestions', array_merge($baseParams, ['status' => 'rejected'])) }}" class="px-3 py-1.5 border {{ $currentStatus === 'rejected' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Rejected
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-950/20 border border-green-900/30 text-green-500 text-xs font-mono font-bold rounded-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(!$isStaff)
            <div class="flex border-b border-red-900/20 font-mono text-xs">
                <a href="{{ route('dashboard.suggestions', ['tab' => 'incoming', 'status' => $currentStatus]) }}" 
                   class="px-5 py-3 border-b-2 {{ $currentTab === 'incoming' ? 'border-red-600 text-red-500 font-black' : 'border-transparent text-gray-500 hover:text-gray-300' }} uppercase tracking-widest">
                    Suggestions on My Pastes (Incoming)
                </a>
                <a href="{{ route('dashboard.suggestions', ['tab' => 'outgoing', 'status' => $currentStatus]) }}" 
                   class="px-5 py-3 border-b-2 {{ $currentTab === 'outgoing' ? 'border-transparent border-b-red-600 text-red-500 font-black' : 'border-transparent text-gray-500 hover:text-gray-300' }} uppercase tracking-widest">
                    My Submitted Suggestions (Outgoing)
                </a>
            </div>
        @endif

        <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left font-mono">
                    <thead>
                        <tr class="text-gray-500 text-[9px] uppercase tracking-widest border-b border-red-900/10">
                            @if($isStaff)
                                <th class="pb-3 font-black">Suggester</th>
                                <th class="pb-3 font-black">Original Paste</th>
                            @elseif($currentTab === 'incoming')
                                <th class="pb-3 font-black">Suggester</th>
                                <th class="pb-3 font-black">My Paste</th>
                            @else
                                <th class="pb-3 font-black">Target Paste</th>
                            @endif
                            <th class="pb-3 font-black">Status</th>
                            <th class="pb-3 font-black">Submitted</th>
                            <th class="pb-3 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900/10">
                        @forelse($suggestions as $edit)
                            <tr class="text-xs">
                                @if($isStaff || $currentTab === 'incoming')
                                    <td class="py-4 pr-4 align-top">
                                        <span class="text-gray-300 font-bold block">@ {{ $edit->user->username ?? 'Anonymous' }}</span>
                                        <span class="text-[8px] text-gray-600 block mt-0.5">UID: {{ $edit->user_id ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-4 pr-4 align-top">
                                        @if($edit->pastebin)
                                            <a href="{{ route('pastebin.show', $edit->pastebin->slug) }}" class="text-gray-400 font-bold hover:text-red-500 transition-colors duration-150 block truncate max-w-xs">
                                                {{ $edit->pastebin->title }}
                                            </a>
                                        @else
                                            <span class="text-gray-600 italic">Deleted Pastebin</span>
                                        @endif

                                        @if($edit->pastebin)
                                            <details class="mt-2 text-[10px] bg-[#0a0a0a] border border-red-900/10 p-3 rounded-sm max-w-lg">
                                                <summary class="cursor-pointer text-red-500/70 font-black uppercase tracking-widest hover:text-red-500 select-none">
                                                    Review Changes
                                                </summary>
                                                <div class="mt-4 space-y-4 text-gray-300">
                                                    <div>
                                                        <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest block">Original Content</span>
                                                        <pre class="bg-black/50 p-2 font-mono text-[9px] whitespace-pre-wrap max-h-32 overflow-y-auto border border-red-900/5 mt-1">{{ $edit->pastebin->content }}</pre>
                                                    </div>
                                                    <div class="border-t border-red-900/5 pt-3">
                                                        <span class="text-[8px] font-black text-red-500/60 uppercase tracking-widest block">Suggested Content</span>
                                                        <pre class="bg-black/50 p-2 font-mono text-[9px] whitespace-pre-wrap max-h-32 overflow-y-auto border border-red-900/10 mt-1 text-white">{{ $edit->content }}</pre>
                                                    </div>
                                                </div>
                                            </details>
                                        @endif
                                    </td>
                                @else
                                    <td class="py-4 pr-4 align-top">
                                        @if($edit->pastebin)
                                            <a href="{{ route('pastebin.show', $edit->pastebin->slug) }}" class="text-gray-300 font-bold hover:text-red-500 transition-colors duration-150 block truncate max-w-sm">
                                                {{ $edit->pastebin->title }}
                                            </a>
                                            <p class="text-[8px] text-gray-600 mt-0.5">Author ID: {{ $edit->pastebin->user_id }}</p>
                                        @else
                                            <span class="text-gray-600 italic">Deleted Pastebin</span>
                                        @endif

                                        <details class="mt-2 text-[10px] bg-[#0a0a0a] border border-red-900/10 p-3 rounded-sm max-w-lg">
                                            <summary class="cursor-pointer text-gray-500 font-black uppercase tracking-widest hover:text-gray-400 select-none">
                                                My Suggested Changes
                                            </summary>
                                            <div class="mt-4 space-y-2 text-gray-300">
                                                <span class="text-[8px] font-black text-red-500/60 uppercase tracking-widest block">Suggested Content</span>
                                                <pre class="bg-black/50 p-2 font-mono text-[9px] whitespace-pre-wrap max-h-32 overflow-y-auto border border-red-900/10 mt-1 text-white">{{ $edit->content }}</pre>
                                            </div>
                                        </details>
                                    </td>
                                @endif

                                <td class="py-4 align-top">
                                    <span class="px-1.5 py-0.5 rounded-sm text-[8px] font-black uppercase tracking-widest border 
                                        {{ $edit->status === 'approved' ? 'bg-green-950/30 text-green-500 border-green-900/30' : ($edit->status === 'rejected' ? 'bg-red-950/30 text-red-500 border-red-900/30' : 'bg-yellow-950/30 text-yellow-500 border-yellow-900/30') }}">
                                        {{ $edit->status }}
                                    </span>
                                </td>
                                <td class="py-4 text-[10px] text-gray-500 align-top">{{ $edit->created_at->diffForHumans() }}</td>
                                <td class="py-4 text-right align-top">
                                    <div class="flex justify-end gap-2">
                                        @if($edit->status === 'pending' && $edit->pastebin && ($isStaff || $edit->pastebin->user_id === auth()->id()))
                                            <form action="{{ route('pastebin.edit.approve', $edit) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[9px] font-black bg-green-950/20 hover:bg-green-900/20 text-green-500 px-3 py-1 border border-green-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('pastebin.edit.reject', $edit) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[9px] font-black bg-red-950/20 hover:bg-red-900/20 text-red-500 px-3 py-1 border border-red-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                                    Reject
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[9px] text-gray-600 uppercase tracking-widest font-black">Logged</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-xs text-gray-600 italic">
                                    No suggestions captured under this query state.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $suggestions->links() }}
        </div>
    </div>
</x-layouts.dashboard>
