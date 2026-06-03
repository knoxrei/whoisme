<x-layouts.dashboard :title="$title" :role="$role">
    <div class="space-y-6 max-w-7xl mx-auto">
        <div class="border border-red-900/30 bg-[#050505] p-8 rounded-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tighter uppercase flex items-center gap-3">
                        <span class="w-2 h-8 bg-red-600"></span>
                        Asset Catalog
                    </h1>
                    <p class="text-gray-500 text-xs font-mono mt-2 max-w-2xl leading-relaxed">
                        Directory of all data transmissions cataloged under this terminal signature. Monitor engagement metrics and visibility status.
                    </p>
                </div>
                
                <div class="flex gap-1 bg-black/40 p-1 border border-red-900/10 rounded-sm font-mono text-[9px]">
                    <a href="{{ route('dashboard.pastes') }}" class="px-4 py-2 {{ is_null($currentVisibility) ? 'bg-red-600 text-white font-black' : 'text-gray-500 hover:text-white' }} uppercase tracking-widest transition-all">
                        All
                    </a>
                    <a href="{{ route('dashboard.pastes', ['visibility' => 'public']) }}" class="px-4 py-2 {{ $currentVisibility === 'public' ? 'bg-red-600 text-white font-black' : 'text-gray-500 hover:text-white' }} uppercase tracking-widest transition-all">
                        Public
                    </a>
                    <a href="{{ route('dashboard.pastes', ['visibility' => 'private']) }}" class="px-4 py-2 {{ $currentVisibility === 'private' ? 'bg-red-600 text-white font-black' : 'text-gray-500 hover:text-white' }} uppercase tracking-widest transition-all">
                        Private
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 border border-red-900/10 bg-[#050505] rounded-sm shadow-inner shadow-red-950/5">
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
                                        {{ $paste->visibility->value === 'public' ? 'bg-green-950/30 text-green-500 border-green-900/30' : 'bg-red-950/30 text-red-500 border-red-900/30' }}">
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

            {{ $pastebins->links() }}
        </div>
    </div>
</x-layouts.dashboard>
