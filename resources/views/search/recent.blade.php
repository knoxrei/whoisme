<x-layouts.app :title="$title">
    <div class="min-h-screen  text-gray-200 font-mono py-12 px-4">
        
        <!-- Main Box Container -->
        <div class="max-w-7xl mx-auto border border-red-950/20  p-6 relative">
            

            <!-- Page Title -->
            <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between border-b border-red-950/30 pb-4 gap-4">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-sm font-black uppercase tracking-[0.2em] text-white">Realtime Public Feed</h2>
                        <p class="text-[8px] text-gray-500 uppercase tracking-widest mt-0.5">Live index stream of public information releases</p>
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

            <!-- Table Feed -->
            <div class="overflow-x-auto border border-red-950/25 rounded-sm mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0f0f0f] border-b border-red-950/30 text-[9px] text-red-500 uppercase font-black tracking-widest">
                            <th class="py-3 px-4">Paste Title</th>
                            <th class="py-3 px-4">Author Signature</th>
                            <th class="py-3 px-4 text-center">Views</th>
                            <th class="py-3 px-4 text-center">Downloads</th>
                            <th class="py-3 px-4 text-right">Published At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-950/10 text-[10px]">
                        @forelse($pastes as $paste)
                            <tr class="hover:bg-red-950/5 transition-colors duration-100">
                                <!-- Title -->
                                <td class="py-3.5 px-4 font-bold text-gray-200 max-w-sm truncate">
                                    <a href="{{ route('pastebin.show', $paste->slug) }}" class="hover:text-red-500 hover:underline">
                                        {{ $paste->title }}
                                    </a>
                                </td>
                                <!-- Author -->
                                <td class="py-3.5 px-4 text-gray-400 font-bold">
                                    @if($paste->user_id)
                                    <a href="{{ route('profile.show', $paste->author_name) }}" class="hover:text-red-500 hover:underline">
                                        {!!  $paste->user->identification->role->userStyle($paste->author_name) !!}
                                    </a>
                                    @else
                                    {{ $paste->author_name }}
                                    @endif
                                </td>
                                <!-- Views -->
                                <td class="py-3.5 px-4 text-center font-bold text-gray-300">
                                    {{ number_format($paste->views_count) }}
                                </td>
                                <!-- Downloads -->
                                <td class="py-3.5 px-4 text-center font-bold text-gray-300">
                                    {{ number_format($paste->download_count) }}
                                </td>
                                <!-- Created Date -->
                                <td class="py-3.5 px-4 text-right text-gray-500 font-mono">
                                    {{ $paste->created_at->format('Y-m-d H:i:s') }} ({{ $paste->created_at->diffForHumans() }})
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center text-gray-600 uppercase tracking-widest select-none">
                                    No live feeds found in active index stream.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Page Load More Navigation -->
            @if($nextCursor)
                <div class="flex justify-center">
                    <a href="{{ route('search.recent', ['cursor' => $nextCursor]) }}"
                        class="bg-[#0f0f0f] border border-red-950/50 hover:border-red-600 text-gray-300 hover:text-white px-6 py-2.5 rounded text-[10px] font-bold uppercase tracking-widest transition-all">
                        Stream Next Records &gt;&gt;
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-layouts.app>
