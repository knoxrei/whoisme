<x-layouts.app title="Search anyone">
    <div class="min-h-screen bg-black text-white flex flex-col items-center justify-center relative font-sans">

        <main class="w-full max-w-3xl px-4 flex flex-col items-center mt-[-10vh]">

            <!-- Logo Icon (Goat Skull Graphic Placeholder) -->
            <div class="mb-4 relative">
                <!-- Outer Glow -->
                <x-layouts.icon class="w-50 h-50" />
            </div>

            <!-- DoxMe Logo Text -->
            <div class="mb-5 flex items-baseline">
                <span class="text-4xl md:text-[5.5rem] font-black text-white tracking-tighter"
                    style="font-family: 'Outfit', sans-serif;">Dox</span>
                <span class="text-4xl md:text-[5.5rem] font-black text-red-600 tracking-tighter"
                    style="font-family: 'Outfit', sans-serif;">Me</span>
            </div>

            <!-- Subtitle -->
        
            <!-- Search Bar -->
            <div class="w-full max-w-2xl mb-8">
                <form action="#" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="q" placeholder="Search for someone or keywords..."
                        class="w-full bg-[#050505]/80 border border-gray-800/80 text-gray-300 rounded-2xl py-4 pl-14 pr-4 text-sm focus:outline-none focus:border-gray-600 transition-colors shadow-inner">
                </form>
            </div>
    <p class="text-gray-400 text-sm md:text-sm mb-10 text-center font-medium">
                Secure, anonymous, and resilient information sharing. <span class="text-red-600 font-semibold">Search
                    with<br>total privacy. we dont track you.</span>
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-6 justify-center w-full">

                

                <div class="flex items-center gap-4 text-xs font-black uppercase tracking-widest">
                <a href="{{ route('pastebin.create') }}"
                    class="text-gray-500 hover:text-red-500 transition-colors duration-300">
                    Contribution now
                </a>    
                <span class="text-white/10">•</span>
                <a href="{{ route('about') }}" class="text-gray-500 hover:text-red-500 transition-colors duration-300">
                        About Us
                    </a>
                <span class="text-white/10">•</span>
                <a href="{{ route('support') }}" class="text-gray-500 hover:text-red-500 transition-colors duration-300">
                        Support Us
                    </a>
                    <span class="text-white/10">•</span>
                    <a href="{{ route('terms') }}" class="text-gray-500 hover:text-red-500 transition-colors duration-300">
                        Terms
                    </a>
                </div>
            </div>

        </main>
    </div>
</x-layouts.app>
