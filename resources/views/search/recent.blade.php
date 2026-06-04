<x-layouts.app :title="$title">
    <div class="min-h-screen text-gray-200 font-mono py-12 px-4">
        
        <div class="max-w-7xl mx-auto border border-red-950/20 p-6 relative">

            <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between border-b border-red-950/30 pb-4 gap-4">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-sm font-black  tracking-[0.2em] text-white">Recent Feeds</h2>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 text-[9px] uppercase font-bold tracking-widest text-gray-500">
                    <a href="{{ route('search.index') }}" class="hover:text-red-500 transition-colors">Home</a>
                    <span>•</span>
                    <a href="{{ route('search.trending') }}" class="hover:text-red-500 transition-colors">Trending Index</a>
                    <span>•</span>
                    <a href="{{ route('search.advanced') }}" class="hover:text-red-500 transition-colors">Advanced Filters</a>
                </div>
            </div>

            <div class="overflow-x-auto border border-red-950/25 rounded-sm mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0f0f0f] border-b border-red-950/30 text-[9px] text-red-500 uppercase font-black tracking-widest">
                            <th class="py-3 px-4">Paste Title</th>
                            <th class="py-3 px-4">Author Signature</th>
                            <th class="py-3 px-4 text-center">Views</th>
                            <th class="py-3 px-4 text-center">Downloads</th>
                            <th class="py-3 px-4 text-center">Comments</th>
                            <th class="py-3 px-4 text-right">Published At</th>
                        </tr>
                    </thead>
                    <tbody id="recent-feed" class="divide-y divide-red-950/10 text-[10px]">
                        @forelse($pastes as $paste)
                            @include('search.partials.recent-rows', ['pastes' => collect([$paste])])
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-gray-600 uppercase tracking-widest select-none">
                                    No live feeds found in active index stream.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="load-more-wrap" class="flex flex-col items-center gap-3 {{ $nextCursor ? '' : 'hidden' }}">
                <button
                    id="load-more-btn"
                    data-cursor="{{ $nextCursor }}"
                    data-url="{{ route('search.recent') }}"
                    data-target="recent-feed"
                    data-type="table"
                    class="load-more-btn bg-[#0f0f0f] border border-red-950/50 hover:border-red-600 text-gray-300 hover:text-white px-6 py-2.5 rounded text-[10px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                    <span class="btn-label">Stream Next Records &gt;&gt;</span>
                    <span class="btn-spinner hidden">
                        <svg class="animate-spin h-3 w-3 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>

        </div>
    </div>

    @include('search.partials.load-more-script')
</x-layouts.app>
