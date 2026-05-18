<x-layouts.app :title="$title">
    <div class="min-h-screen  text-gray-200 font-mono py-12 px-4">
        
        <!-- Center Box Container -->
        <div class="max-w-4xl mx-auto border-2 border-red-950/30 p-6 md:p-8 relative">
          
            <!-- Page Title -->
            <div class="mb-6 flex items-center justify-between border-b border-red-950/20 pb-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-black uppercase tracking-[0.2em] text-white">Advanced Search </h2>
                </div>
                <a href="{{ route('search.index') }}" class="text-[9px] font-bold text-gray-500 hover:text-red-500 uppercase tracking-widest transition-colors">&lt;&lt; Search Home</a>
            </div>

            <!-- Form -->
            <form action="{{ route('search.index') }}" method="GET" class="space-y-6">
                
                <!-- Main Query Input -->
                <div>
                    <label class="block text-[10px] uppercase font-bold tracking-wider text-red-500 mb-2">Search Terms / Keywords</label>
                    <input type="text" name="q" placeholder="Type key terms, double-quoted phrases, or boolean terms..." autocomplete="off" required
                        class="w-full bg-[#050505] border border-red-950/40 text-gray-200 rounded px-3 py-2.5 text-xs focus:outline-none focus:border-red-600 transition-colors">
                    <p class="text-[9px] text-gray-600 mt-1.5 uppercase leading-relaxed tracking-wider">
                        supports exact phrase syntax <code class="text-gray-400">"exact search string"</code> and boolean logic <code class="text-gray-400">AND</code> / <code class="text-gray-400">OR</code> / <code class="text-gray-400">NOT</code>.
                    </p>
                </div>

                <!-- Parameters Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Author Signatures -->
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-2">Author Name / Signatures</label>
                        <input type="text" name="author" placeholder="e.g. TreixNox" autocomplete="off"
                            class="w-full bg-[#050505] border border-red-950/40 text-gray-200 rounded px-3 py-2 text-xs focus:outline-none focus:border-red-600 transition-colors">
                    </div>

                    <!-- Date range selector -->
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-2">Index Timeframe (Age)</label>
                        <select name="date" class="w-full bg-[#050505] border border-red-950/40 text-gray-300 text-xs rounded p-2 focus:outline-none focus:border-red-600">
                            <option value="">All Indexed Content</option>
                            <option value="24h">Past 24 Hours</option>
                            <option value="7d">Past 7 Days</option>
                            <option value="30d">Past 30 Days</option>
                        </select>
                    </div>

                    <!-- Sort strategy selection -->
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-2">Ranking Algorithm Mode</label>
                        <select name="sort" class="w-full bg-[#050505] border border-red-950/40 text-gray-300 text-xs rounded p-2 focus:outline-none focus:border-red-600">
                            <option value="relevance">Relevency Rank Scoring</option>
                            <option value="views">Total Read Frequency (Views)</option>
                            <option value="downloads">Total Download Logs</option>
                            <option value="date_desc">Chronological: Newest First</option>
                            <option value="date_asc">Chronological: Oldest First</option>
                            <option value="content_length">Total Content Size (Bytes)</option>
                        </select>
                    </div>

                    <!-- Content length ranges -->
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-2">Byte Size Parameters</label>
                        <div class="flex gap-2 items-center">
                            <input type="number" name="min_length" placeholder="Min Bytes" min="0"
                                class="w-1/2 bg-[#050505] border border-red-950/40 text-gray-200 rounded p-2 text-xs focus:outline-none focus:border-red-600 transition-colors">
                            <span class="text-gray-600 text-xs font-black uppercase">-</span>
                            <input type="number" name="max_length" placeholder="Max Bytes" min="0"
                                class="w-1/2 bg-[#050505] border border-red-950/40 text-gray-200 rounded p-2 text-xs focus:outline-none focus:border-red-600 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Submit / Control Section -->
                <div class="border-t border-red-950/20 pt-6 flex justify-end gap-3">
                    <button type="reset" class="text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-white px-4 py-2 transition-colors duration-150">
                        Reset Defaults
                    </button>
                    <button type="submit" class="bg-red-950/20 border border-red-900/30 text-red-500 hover:bg-red-600 hover:text-white px-6 py-2 rounded text-[10px] font-bold uppercase tracking-widest transition-colors duration-150 shadow-md">
                        Execute Advanced Query
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
