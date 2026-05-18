<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- Title & Filter Panel -->
        <div class="border border-red-900/40 bg-gradient-to-b from-red-950/10 to-[#0a0a0a] p-6 rounded-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-white tracking-tight uppercase">My Cataloged Pastes</h1>
                <p class="text-gray-500 text-xs font-mono mt-1">Directory of all pastes transmitted under this terminal signature.</p>
            </div>
            
            <!-- Filters (Pure HTML Links for high Tor speed) -->
            <div class="flex gap-2 font-mono text-[10px]">
                <a href="{{ route('dashboard.pastes') }}" class="px-3 py-1.5 border {{ is_null($currentVisibility) ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    All
                </a>
                <a href="{{ route('dashboard.pastes', ['visibility' => 'public']) }}" class="px-3 py-1.5 border {{ $currentVisibility === 'public' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Public
                </a>
                <a href="{{ route('dashboard.pastes', ['visibility' => 'private']) }}" class="px-3 py-1.5 border {{ $currentVisibility === 'private' ? 'border-red-600 bg-red-950/20 text-red-500 font-black' : 'border-red-900/20 text-gray-500 hover:text-white' }} uppercase tracking-widest rounded-sm">
                    Private
                </a>
            </div>
        </div>

        <!-- Catalog List Table -->
        <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left font-mono">
                    <thead>
                        <tr class="text-gray-500 text-[9px] uppercase tracking-widest border-b border-red-900/10">
                            <th class="pb-3 font-black">Title</th>
                            <th class="pb-3 font-black">Visibility</th>
                            <th class="pb-3 font-black">Views</th>
                            <th class="pb-3 font-black">Downloads</th>
                            <th class="pb-3 font-black">Created</th>
                            <th class="pb-3 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-900/10">
                        @forelse($pastebins as $paste)
                            <tr class="text-xs group">
                                <td class="py-4 pr-4">
                                    <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-gray-300 font-bold hover:text-red-500 transition-colors duration-150 block truncate max-w-xs md:max-w-md">
                                        {{ $paste->title }}
                                    </a>
                                    @if($paste->description)
                                        <p class="text-[8px] text-gray-600 mt-0.5 truncate max-w-xs md:max-w-md leading-normal">{{ $paste->description }}</p>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="px-1.5 py-0.5 rounded-sm text-[8px] font-black uppercase tracking-widest border 
                                        {{ $paste->visibility === 'public' ? 'bg-green-950/30 text-green-500 border-green-900/30' : 'bg-red-950/30 text-red-500 border-red-900/30' }}">
                                        {{ $paste->visibility }}
                                    </span>
                                </td>
                                <td class="py-4 text-gray-400 font-mono">{{ number_format($paste->views_count) }}</td>
                                <td class="py-4 text-gray-400 font-mono">{{ number_format($paste->download_count) }}</td>
                                <td class="py-4 text-[10px] text-gray-500">{{ $paste->created_at->diffForHumans() }}</td>
                                <td class="py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-[8px] font-black bg-red-950/20 hover:bg-red-900/20 text-red-500 px-3 py-1 border border-red-900/30 uppercase tracking-widest rounded-sm transition-colors duration-150">
                                            Open
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-xs text-gray-600 italic">
                                    No pastes detected in this directory matching query parameters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Block -->
            @if($pastebins->hasPages())
                <div class="mt-6 pt-6 border-t border-red-900/10 flex justify-between items-center text-[10px] font-mono">
                    <div>
                        <span class="text-gray-600 uppercase tracking-widest">Showing</span> 
                        <span class="text-gray-300 font-bold">{{ $pastebins->firstItem() }}-{{ $pastebins->lastItem() }}</span> 
                        <span class="text-gray-600 uppercase tracking-widest">of</span> 
                        <span class="text-gray-300 font-bold">{{ $pastebins->total() }}</span>
                    </div>
                    
                    <div class="flex gap-2">
                        @if($pastebins->onFirstPage())
                            <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">Prev</span>
                        @else
                            <a href="{{ $pastebins->previousPageUrl() }}" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white uppercase tracking-widest rounded-sm transition-colors duration-150">Prev</a>
                        @endif

                        @if($pastebins->hasMorePages())
                            <a href="{{ $pastebins->nextPageUrl() }}" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white uppercase tracking-widest rounded-sm transition-colors duration-150">Next</a>
                        @else
                            <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
