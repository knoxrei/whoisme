<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="border border-red-900/30 bg-[#050505] p-8 rounded-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tighter uppercase flex items-center gap-3">
                        <span class="w-2 h-8 bg-red-600"></span>
                        {{ $isStaff ? 'Global Audit Terminal' : 'Edit Suggestions Hub' }}
                    </h1>
                    <p class="text-gray-500 text-xs font-mono mt-2 max-w-2xl leading-relaxed">
                        {{ $isStaff 
                            ? 'As a system auditor, you are responsible for reviewing community-driven improvements. Ensure content integrity before approving modifications.' 
                            : 'Monitor the evolution of your content through community suggestions, or track the progress of edits you have submitted to other cataloged entries.' }}
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-1 bg-black/40 p-1 border border-red-900/10 rounded-sm font-mono text-[9px]">
                    @php
                        $baseParams = [];
                        if (!$isStaff) {
                            $baseParams['tab'] = $currentTab;
                        }
                    @endphp

                    <a href="{{ route('dashboard.suggestions', $baseParams) }}" class="px-4 py-2 {{ is_null($currentStatus) ? 'bg-red-600 text-white font-black' : 'text-gray-500 hover:text-gray-300' }} uppercase tracking-widest transition-all">
                        All
                    </a>
                    <a href="{{ route('dashboard.suggestions', array_merge($baseParams, ['status' => 'pending'])) }}" class="px-4 py-2 {{ $currentStatus === 'pending' ? 'bg-yellow-600/20 text-yellow-500 font-black border border-yellow-600/30' : 'text-gray-500 hover:text-gray-300' }} uppercase tracking-widest transition-all">
                        Pending
                    </a>
                    <a href="{{ route('dashboard.suggestions', array_merge($baseParams, ['status' => 'approved'])) }}" class="px-4 py-2 {{ $currentStatus === 'approved' ? 'bg-green-600/20 text-green-500 font-black border border-green-600/30' : 'text-gray-500 hover:text-gray-300' }} uppercase tracking-widest transition-all">
                        Approved
                    </a>
                    <a href="{{ route('dashboard.suggestions', array_merge($baseParams, ['status' => 'rejected'])) }}" class="px-4 py-2 {{ $currentStatus === 'rejected' ? 'bg-red-600/20 text-red-500 font-black border border-red-600/30' : 'text-gray-500 hover:text-gray-300' }} uppercase tracking-widest transition-all">
                        Rejected
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="px-6 py-4 bg-green-950/10 border-l-4 border-green-600 text-green-500 text-xs font-mono font-bold">
                [SYSTEM]: {{ session('success') }}
            </div>
        @endif

        @if(!$isStaff)
            <div class="flex border-b border-red-900/20 font-mono text-[10px] gap-8 px-2">
                <a href="{{ route('dashboard.suggestions', ['tab' => 'incoming', 'status' => $currentStatus]) }}" 
                   class="pb-3 border-b-2 {{ $currentTab === 'incoming' ? 'border-red-600 text-white font-black' : 'border-transparent text-gray-500 hover:text-gray-300' }} uppercase tracking-widest transition-all">
                    Incoming Proposals
                </a>
                <a href="{{ route('dashboard.suggestions', ['tab' => 'outgoing', 'status' => $currentStatus]) }}" 
                   class="pb-3 border-b-2 {{ $currentTab === 'outgoing' ? 'border-red-600 text-white font-black' : 'border-transparent text-gray-500 hover:text-gray-300' }} uppercase tracking-widest transition-all">
                    My Submissions
                </a>
            </div>
        @endif

        <div class="space-y-4">
            @forelse($suggestions as $edit)
                <div class="bg-[#050505] border border-red-900/10 rounded-sm overflow-hidden group hover:border-red-900/30 transition-all duration-300">
                    <!-- Suggestion Meta Header -->
                    <div class="px-6 py-4 bg-black/40 border-b border-red-900/5 flex flex-wrap justify-between items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-gray-600 uppercase font-black tracking-widest">Suggester</span>
                                <a href="{{ route('profile.show', $edit->user->username ?? 'Anonymous') }}" class="text-xs font-bold text-gray-300 hover:text-red-500 transition-colors">
                                    @ {{ $edit->user->username ?? 'Anonymous' }}
                                </a>
                            </div>
                            <div class="h-6 w-px bg-red-900/20"></div>
                            <div class="flex flex-col">
                                <span class="text-[9px] text-gray-600 uppercase font-black tracking-widest">Target Asset</span>
                                @if($edit->pastebin)
                                    <a href="{{ route('pastebin.show', $edit->pastebin->slug) }}" class="text-xs font-bold text-gray-400 hover:text-white transition-colors flex items-center gap-1">
                                        {{ Str::limit($edit->pastebin->title, 40) }}
                                        <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-600 italic">Redacted/Deleted</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="text-[10px] text-gray-500 font-mono">{{ $edit->created_at->format('Y-m-d H:i') }}</span>
                            <span class="px-2 py-1 rounded-sm text-[9px] font-black uppercase tracking-widest border 
                                {{ $edit->status === 'approved' ? 'bg-green-950/20 text-green-500 border-green-900/20' : ($edit->status === 'rejected' ? 'bg-red-950/20 text-red-500 border-red-900/20' : 'bg-yellow-950/20 text-yellow-500 border-yellow-900/20') }}">
                                {{ $edit->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Comparison Section -->
                    <div class="p-6">
                        @if($edit->pastebin)
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest">Original Reference</span>
                                        <span class="text-[8px] font-mono text-gray-700">SHA1: {{ substr(sha1($edit->pastebin->content), 0, 8) }}</span>
                                    </div>
                                    <div class="bg-black border border-red-900/5 p-4 rounded-sm">
                                        <pre class="font-mono text-[10px] text-gray-500 whitespace-pre-wrap max-h-[250px] overflow-y-auto custom-scrollbar">{{ $edit->pastebin->content }}</pre>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-red-500/60 uppercase tracking-widest">Proposed Revision</span>
                                        <span class="text-[8px] font-mono text-gray-700">SHA1: {{ substr(sha1($edit->content), 0, 8) }}</span>
                                    </div>
                                    <div class="bg-[#080808] border border-red-600/20 p-4 rounded-sm shadow-inner shadow-red-950/10">
                                        <pre class="font-mono text-[10px] text-gray-200 whitespace-pre-wrap max-h-[250px] overflow-y-auto custom-scrollbar">{{ $edit->content }}</pre>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-black/40 border border-red-900/10 p-8 text-center">
                                <span class="text-xs text-gray-600 uppercase tracking-widest font-black italic">Content Comparison Unavailable: Source asset has been purged from the terminal.</span>
                            </div>
                        @endif
                    </div>

                    <!-- Actions Footer -->
                    <div class="px-6 py-4 bg-black/20 border-t border-red-900/5 flex justify-between items-center">
                        <div class="text-[9px] text-gray-600 font-mono italic">
                            UUID: {{ $edit->id }}
                        </div>
                        <div class="flex gap-3">
                            @if($edit->status === 'pending' && $edit->pastebin && ($isStaff || $edit->pastebin->user_id === auth()->id()))
                                <form action="{{ route('pastebin.edit.reject', $edit) }}" method="POST" onsubmit="return confirm('Archive this proposal as rejected?')">
                                    @csrf
                                    <button type="submit" class="px-5 py-2 text-[9px] font-black border border-red-900/30 text-red-500 uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all rounded-sm">
                                        Reject
                                    </button>
                                </form>
                                <form action="{{ route('pastebin.edit.approve', $edit) }}" method="POST" onsubmit="return confirm('Commit these changes to the asset?')">
                                    @csrf
                                    <button type="submit" class="px-5 py-2 text-[9px] font-black bg-green-600 text-white uppercase tracking-widest hover:bg-green-500 transition-all rounded-sm border border-green-600">
                                        Approve & Merge
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center gap-2 text-[10px] text-gray-600 uppercase font-black tracking-widest">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    Review Finalized
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center bg-[#050505] border border-red-900/10 rounded-sm">
                    <div class="mb-4 opacity-20 flex justify-center">
                        <x-layouts.icon class="w-16 h-16 grayscale" />
                    </div>
                    <p class="text-xs text-gray-600 uppercase tracking-[0.2em] font-black">No proposals detected in the current filter stack.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-6">
            {{ $suggestions->links() }}
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(220, 38, 38, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(220, 38, 38, 0.3);
        }
    </style>
</x-layouts.dashboard>
