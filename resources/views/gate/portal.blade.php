<x-layouts.app :title="$title">
    <div class="min-h-screen flex flex-col items-center justify-center bg-black px-4 relative overflow-hidden">
        
        <div class="absolute inset-0 opacity-[0.02] pointer-events-none select-none font-mono text-[10px] text-red-500 overflow-hidden leading-none">
            @for ($i = 0; $i < 30; $i++)
                <div class="whitespace-nowrap" style="animation-delay: {{ $i * 0.2 }}s; animation-duration: {{ 2 + ($i % 3) }}s">
                    {{ str_repeat('01101001 10010110 11100100 00101101 01011011 ', 8) }}
                </div>
            @endfor
        </div>

        <div class="w-full max-w-4xl mx-auto z-10 space-y-12 py-12">
            <div class="text-center space-y-4">
                <div class="inline-block border border-red-600 bg-red-600/10 px-4 py-1.5 rounded-sm">
                    <span class="text-red-500 font-mono font-black text-xs uppercase tracking-[0.25em]">
                        ACCESS PORTAL SECURE NODE
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter">
                    Select Connection Gate
                </h1>
                <p class="text-xs text-gray-500 font-mono max-w-lg mx-auto uppercase tracking-widest leading-relaxed">
                    Authorize your entry mechanism to the DoxMe terminal database. Tor connection is recommended for maximum trace-anonymization.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="bg-[#0a0a0a] border-2 border-red-600/50 hover:border-red-500 p-8 rounded-sm flex flex-col justify-between min-h-[380px] shadow-2xl shadow-red-950/20 hover:shadow-red-900/10 transition-all duration-300 transform hover:-translate-y-1 group relative">
                    <div class="absolute top-4 right-4 flex items-center gap-1.5 bg-red-950/30 border border-red-900/50 px-2 py-0.5 rounded-sm">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-ping"></span>
                        <span class="text-[8px] text-red-500 font-mono font-black uppercase tracking-widest">Recommended</span>
                    </div>

                    <div class="space-y-6">
                        <div class="w-12 h-12 bg-red-600/10 border border-red-600/30 rounded-sm flex items-center justify-center text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>

                        <div class="space-y-2">
                            <h2 class="text-xl font-black text-white uppercase tracking-tight">Onion Security Gate</h2>
                            <p class="text-xs text-gray-400 font-mono leading-relaxed">
                                Connect via the Tor network hidden onion service. Offers layered encryption, absolute IP trace protection, and metadata scrubbing.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5 pt-8">
                        <div class="bg-black border border-red-950 p-3 rounded-sm font-mono text-center select-all cursor-text overflow-x-auto scrollbar-none">
                            <span class="text-[10px] text-gray-500">{{ $torLink }}</span>
                        </div>
                        
                        <a href="{{ route('gate.tor') }}" 
                           class="w-full bg-red-600 hover:bg-red-700 text-white py-3.5 text-center text-xs font-black uppercase tracking-widest rounded-sm transition-all duration-150 block active:scale-95 shadow-lg shadow-red-900/10">
                            Secure Onion Entrance →
                        </a>
                    </div>
                </div>

                <div class="bg-[#0a0a0a] border border-gray-900 hover:border-red-900/40 p-8 rounded-sm flex flex-col justify-between min-h-[380px] shadow-2xl transition-all duration-300 transform hover:-translate-y-1 group relative">
                    <div class="space-y-6">
                        <div class="w-12 h-12 bg-gray-950 border border-gray-800 rounded-sm flex items-center justify-center text-gray-400 group-hover:text-red-500 group-hover:bg-red-600/5 group-hover:border-red-900/20 transition-all duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>

                        <div class="space-y-2">
                            <h2 class="text-xl font-black text-white uppercase tracking-tight">Standard Clearnet Gate</h2>
                            <p class="text-xs text-gray-400 font-mono leading-relaxed">
                                Access using standard public DNS. Faster response times but subjects your network IP address to traditional ISP routing logs.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5 pt-8">
                        <div class="bg-black border border-gray-950 p-3 rounded-sm font-mono text-center select-all cursor-text overflow-x-auto scrollbar-none">
                            <span class="text-[10px] text-gray-500">{{ $clearnetLink }}</span>
                        </div>
                        
                        <a href="{{ route('gate.clearnet') }}" 
                           class="w-full bg-[#111] hover:bg-red-650 hover:bg-red-950/20 border border-red-900/20 hover:border-red-600 text-red-500 hover:text-white py-3.5 text-center text-xs font-black uppercase tracking-widest rounded-sm transition-all duration-150 block active:scale-95">
                            Clearnet Entrance →
                        </a>
                    </div>
                </div>

            </div>

            <div class="text-center font-mono text-[9px] text-gray-600 uppercase tracking-widest pt-4">
                DoxMe Connection Gateway Protocol • All actions encrypted server-side
            </div>
        </div>
    </div>
</x-layouts.app>
