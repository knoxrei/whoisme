<x-layouts.app :title="$title">
    <div class="min-h-screen bg-[#050505] text-gray-200 font-mono pb-16">
        
        <!-- Top Search Bar Header -->
        <header class="border-b border-red-950/30 py-4 px-4 sticky top-0 z-30 shadow-md">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-4">
                <!-- Small Logo Link -->
                <a href="{{ route('search.index') }}" class="flex items-center gap-2 select-none shrink-0">
                    <x-layouts.icon class="w-6 h-6 text-red-600" />
                </a>

                <!-- Search Input Form -->
                <form action="{{ route('search.index') }}" method="GET" class="w-full max-w-xl flex gap-2">
                    <div class="relative flex-grow">
                        <input type="text" name="q" value="{{ $dto->query }}" required autocomplete="off"
                            class="w-full bg-[#050505] border border-red-950/40 text-gray-200 rounded px-3 py-2 pl-8 text-xs focus:outline-none focus:border-red-600 transition-colors">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-3 w-3 text-red-600/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Preserve Active Filters in Search Form -->
                    @if($dto->sortBy !== 'relevance') <input type="hidden" name="sort" value="{{ $dto->sortBy }}"> @endif
                    @if($dto->dateRange) <input type="hidden" name="date" value="{{ $dto->dateRange }}"> @endif
                    @if($dto->author) <input type="hidden" name="author" value="{{ $dto->author }}"> @endif
                    @if($dto->minLength) <input type="hidden" name="min_length" value="{{ $dto->minLength }}"> @endif
                    @if($dto->maxLength) <input type="hidden" name="max_length" value="{{ $dto->maxLength }}"> @endif

                    <button type="submit" class="bg-red-950/20 border border-red-900/30 text-red-500 hover:bg-red-600 hover:text-white px-4 py-2 rounded text-[10px] uppercase font-bold tracking-wider transition-colors duration-150">
                        Search
                    </button>
                </form>

                <!-- Navigation Quick Links -->
                <div class="flex items-center gap-3 ml-auto text-[10px] uppercase font-bold tracking-widest text-gray-500">
                    <a href="{{ route('search.trending') }}" class="hover:text-red-500 transition-colors">Trending</a>
                    <span>•</span>
                    <a href="{{ route('search.recent') }}" class="hover:text-red-500 transition-colors">Recent</a>
                    <span>•</span>
                    <a href="{{ route('search.advanced') }}" class="hover:text-red-500 transition-colors">Filters</a>
                </div>
            </div>
        </header>

        <!-- Main Body Grid Layout -->
        <div class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Left Column: Search Stats & Results -->
            <main class="lg:col-span-3">
                <!-- Search Diagnostics Stats -->
                <div class="mb-6 text-[10px] text-gray-500 uppercase tracking-widest flex items-center gap-4">
                    <span>Found {{ $count }} result{{ $count > 1 ? 's' : '' }}</span>
                    <span>•</span>
                    <span>Analyzed in {{ $executionTime }} ms</span>
                </div>

                <!-- Results Listing -->
                <div class="space-y-6">
                    @forelse($results as $result)
                        <article class="bg-[#090909] border border-red-950/15 p-4 rounded-sm hover:border-red-900/30 transition-all duration-150">
                            <!-- Header Title Link -->
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <h3 class="text-xs md:text-sm font-bold text-red-500 hover:underline leading-snug">
                                    <a href="{{ route('pastebin.show', $result->slug) }}">
                                        {{ $result->title }}
                                    </a>
                                </h3>
                                <!-- Rank Score Badge -->
                                <span class="text-[8px] bg-red-950/20 border border-red-900/30 text-red-500 px-1 py-0.5 rounded-sm select-none">
                                    Rank {{ $result->rank_score ?? '0.00' }}
                                </span>
                            </div>

                            <!-- Highlighted Context Snippet -->
                            <p class="text-xs text-gray-400 leading-relaxed font-mono mb-4 break-words">
                                {!! $result->snippet !!}
                            </p>

                            <!-- Meta Information Row -->
                            <footer class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-500  tracking-wider border-t border-red-950/10 pt-3">
                                <div class="flex items-center">
                                    
                                    <span class="text-gray-600">Author:</span> 
                                    <span class="text-gray-300 font-bold">
                                       <a href="{{ $result->user ? route('profile.show', $result->user->username) : '#' }}" class="text-white font-black text-sm tracking-tighter block">
                                        @if($result->user)
                                            {!! $result->user->identification->role->userStyle($result->author_name, $result->user->identification->color_username ?? '#ffffff') !!}
                                        @else
                                            {{ $result->author_name }}
                                        @endif
                                    </a>
                                    </span>
                                </div>
                                <div class="select-none text-red-950/40">|</div>
                                <div>
                                    <span class="text-gray-600">Views:</span> 
                                    <span class="text-gray-300 font-bold">{{ number_format($result->views_count) }}</span>
                                </div>
                                <div class="select-none text-red-950/40">|</div>
                                <div>
                                    <span class="text-gray-600">DLs:</span> 
                                    <span class="text-gray-300 font-bold">{{ number_format($result->download_count) }}</span>
                                </div>
                                <div class="select-none text-red-950/40">|</div>
                                <div>
                                    <span class="text-gray-600">Date:</span> 
                                    <span class="text-gray-300 font-bold">{{ $result->created_at->diffForHumans() }}</span>
                                </div>
                            </footer>
                        </article>
                    @empty
                        <div class="bg-[#0a0a0a] border border-red-950/30 p-8 text-center rounded-sm">
                            <svg class="w-8 h-8 text-red-500/40 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="text-xs uppercase font-black tracking-widest text-red-500 mb-1">No Results Match Query</h4>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest leading-relaxed">
                                Refine terms, remove exact quotes, or adjust filter ranges in the advanced options.
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Cursor-based Page Control -->
                @if($nextCursor)
                    <div class="mt-8 flex justify-center">
                        <a href="{{ route('search.index', array_merge(request()->query(), ['cursor' => $nextCursor])) }}"
                            class="bg-[#0f0f0f] border border-red-950/50 hover:border-red-600 text-gray-300 hover:text-white px-6 py-2.5 rounded text-[10px] font-bold uppercase tracking-widest transition-all">
                            Load Next Results &gt;&gt;
                        </a>
                    </div>
                @endif
            </main>

            <!-- Right Column: Sidebar Quick Filters -->
            <aside class="space-y-6">
                <!-- Sorting & Filters Box -->
                <div class="bg-[#0a0a0a] border border-red-950/20 p-5 rounded-sm shadow-md">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-red-500 border-b border-red-950/30 pb-2 mb-4">
                        Search Filters
                    </h4>
                    
                    <form action="{{ route('search.index') }}" method="GET" class="space-y-4">
                        <!-- Query Input (Hidden, inherited) -->
                        <input type="hidden" name="q" value="{{ $dto->query }}">

                        <!-- Sort By Option -->
                        <div>
                            <label class="block text-[9px] uppercase tracking-wider text-gray-400 mb-1.5 font-bold">Sort Matches By</label>
                            <select name="sort" class="w-full bg-[#050505] border border-red-950/40 text-gray-300 text-[10px] rounded p-2 focus:outline-none focus:border-red-600">
                                <option value="relevance" {{ $dto->sortBy === 'relevance' ? 'selected' : '' }}>Relevance Score</option>
                                <option value="views" {{ $dto->sortBy === 'views' ? 'selected' : '' }}>Most Viewed</option>
                                <option value="downloads" {{ $dto->sortBy === 'downloads' ? 'selected' : '' }}>Most Downloaded</option>
                                <option value="date_desc" {{ $dto->sortBy === 'date_desc' ? 'selected' : '' }}>Date: Newest</option>
                                <option value="date_asc" {{ $dto->sortBy === 'date_asc' ? 'selected' : '' }}>Date: Oldest</option>
                                <option value="content_length" {{ $dto->sortBy === 'content_length' ? 'selected' : '' }}>Content Length</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-[9px] uppercase tracking-wider text-gray-400 mb-1.5 font-bold">Publication Age</label>
                            <select name="date" class="w-full bg-[#050505] border border-red-950/40 text-gray-300 text-[10px] rounded p-2 focus:outline-none focus:border-red-600">
                                <option value="" {{ !$dto->dateRange ? 'selected' : '' }}>All Time</option>
                                <option value="24h" {{ $dto->dateRange === '24h' ? 'selected' : '' }}>Last 24 Hours</option>
                                <option value="7d" {{ $dto->dateRange === '7d' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="30d" {{ $dto->dateRange === '30d' ? 'selected' : '' }}>Last 30 Days</option>
                            </select>
                        </div>

                        <!-- Author Name Filter -->
                        <div>
                            <label class="block text-[9px] uppercase tracking-wider text-gray-400 mb-1.5 font-bold">Author/Alias</label>
                            <input type="text" name="author" value="{{ $dto->author }}" placeholder="e.g. Anon" autocomplete="off"
                                class="w-full bg-[#050505] border border-red-950/40 text-gray-300 text-[10px] rounded p-2 focus:outline-none focus:border-red-600">
                        </div>

                        <!-- Apply Filters Button -->
                        <button type="submit" class="w-full bg-red-950/20 border border-red-900/30 text-red-500 hover:bg-red-600 hover:text-white py-2 rounded text-[10px] uppercase font-bold tracking-widest transition-colors duration-150">
                            Apply Parameters
                        </button>
                    </form>
                </div>

                <!-- Tor Navigation Tips Box -->
                <div class="bg-[#0a0a0a] border border-red-950/20 p-5 rounded-sm shadow-md">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 border-b border-red-950/30 pb-2 mb-3">
                        Search Syntax Help
                    </h4>
                    <ul class="space-y-2.5 text-[9px] text-gray-500 leading-relaxed uppercase">
                        <li>
                            <strong class="text-red-500">Phrase search:</strong> Wrap phrases in quotes like <code class="text-gray-300">"sensitive hash"</code>.
                        </li>
                        <li>
                            <strong class="text-red-500">Boolean AND:</strong> Use <code class="text-gray-300">AND</code> to mandate matches, e.g. <code class="text-gray-300">leak AND database</code>.
                        </li>
                        <li>
                            <strong class="text-red-500">Boolean NOT:</strong> Exclude terms by adding <code class="text-gray-300">NOT</code>, e.g. <code class="text-gray-300">dox NOT paste</code>.
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.app>
