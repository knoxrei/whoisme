<x-layouts.app :title="$title">
    <div class="text-gray-100 flex min-h-screen flex-col items-center justify-center relative font-mono ">
        

        <main class="w-full max-w-2xl px-4 flex flex-col items-center z-10">
            <!-- Sleek Cyberpunk Logo Container -->
            <div class="mb-2 relative">
                <x-layouts.icon class="w-24 h-24 text-red-600" />
            </div>

            <!-- Logo Title -->
            <div class="mb-8 flex items-baseline select-none">
                <span class="text-4xl md:text-5xl font-black text-white tracking-tighter" style="font-family: 'Outfit', sans-serif;">Dox</span>
                <span class="text-4xl md:text-5xl font-black text-red-600 tracking-tighter" style="font-family: 'Outfit', sans-serif;">Me</span>
            </div>

            <!-- Search Form -->
            <form action="{{ route('search.index') }}" method="GET" class="w-full mb-8">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-red-600/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    
                    <input type="text" name="q" placeholder="Type keywords, phrases, or queries..." required autocomplete="off"
                        class="w-full bg-[#0a0a0a] border border-red-950/40 text-gray-200 rounded-lg py-3.5 pl-12 pr-4 text-xs font-mono focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 transition-all shadow-[0_0_15px_rgba(0,0,0,0.85)]">
                </div>

                <!-- Fast Options Buttons -->
                <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
                    <button type="submit" class="text-[10px] font-bold uppercase tracking-wider text-gray-300 bg-[#0f0f0f] border border-red-950/50 hover:border-red-600 hover:text-white px-5 py-2 rounded transition-colors duration-150">
                        Search Paste
                    </button>
                    <a href="{{ route('search.advanced') }}" class="text-[10px] font-bold uppercase tracking-wider text-red-500/80 hover:text-red-500 bg-red-950/10 border border-red-950/30 hover:border-red-600 px-5 py-2 rounded transition-colors duration-150">
                        Advanced Search
                    </a>
                </div>
            </form>

            <!-- Quick Links Panel -->
            <div class="w-full flex justify-center gap-4 text-[10px] font-bold uppercase tracking-widest border-t border-red-950/20 pt-6">
                <a href="{{ route('search.trending') }}" class="text-gray-500 hover:text-red-500 transition-colors">
                    Trending Indexes
                </a>
                <span class="text-red-950/50 select-none">|</span>
                <a href="{{ route('search.recent') }}" class="text-gray-500 hover:text-red-500 transition-colors">
                    Recent Feeds
                </a>
                <span class="text-red-950/50 select-none">|</span>
                <a href="{{ route('pastebin.create') }}" class="text-gray-500 hover:text-red-500 transition-colors">
                    Publish Paste
                </a>
            </div>

            <!-- Quick Stats/Disclaimer -->
            <div class="mt-12 text-center max-w-md select-none">
                <p class="text-[9px] text-gray-600 leading-relaxed uppercase tracking-wider">
                    Indexed data is processed in real time. Private or password protected records are strictly blacklisted. Fully compatible with Tor network low-bandwidth protocols.
                </p>
            </div>
        </main>
    </div>
</x-layouts.app>
