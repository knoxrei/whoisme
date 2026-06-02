<x-layouts.app title="Support Our Infrastructure">
    <div class="min-h-[90vh] bg-[#050505] text-gray-300 py-16 px-6 font-sans">
        <main class="w-full max-w-4xl mx-auto">
            <div class="mb-12 border-b border-red-900/30 pb-6 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4" style="font-family: 'Outfit', sans-serif;">
                    Support <span class="text-red-600">DoxMe</span>
                </h1>
                <p class="text-gray-500 font-mono text-sm uppercase tracking-widest">Help us keep the servers running anonymously.</p>
            </div>

            <div class="mb-12 bg-red-950/10 border border-red-900/30 p-8 rounded-sm">
                <p class="text-gray-400 text-sm leading-relaxed mb-6 font-mono">
                    Maintaining absolute freedom of information and robust OPSEC comes at a cost. We rely on anonymous contributions from our community to pay for bulletproof hosting, DDoS protection, and ongoing development. If you value this platform, consider sending a contribution to one of the addresses below.
                </p>
                <div class="flex items-center gap-3 text-red-500 font-black uppercase tracking-widest text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    We do not track donors. All contributions are final.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm hover:border-orange-500/30 transition-colors">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.004 12.386c.94-.368 1.583-1.077 1.583-2.186 0-1.428-1.127-2.28-3.053-2.28h-3.03v8.57h3.336c2.093 0 3.395-.87 3.395-2.476 0-1.092-.72-1.782-1.802-2.034v-.02zm-3.04-3.043h1.492c.866 0 1.41.34 1.41 1.09 0 .748-.544 1.135-1.458 1.135h-1.444v-2.225zm1.59 5.37h-1.59v-2.39h1.61c.97 0 1.603.364 1.603 1.18 0 .806-.606 1.21-1.623 1.21z" /><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.82 11.23c-.22.842-.716 1.453-1.42 1.836.85.342 1.343 1.05 1.21 2.072-.186 1.428-1.537 2.37-3.957 2.37H9.288v-1.7h1.026c.306 0 .393-.11.393-.385V8.125c0-.274-.087-.384-.393-.384H9.288v-1.7h5.18c2.26 0 3.42 1.056 3.52 2.22.09 1.028-.485 1.77-1.48 2.052l.06-.01.127.015.424-.04c.338-.03.58-.236.69-.69l.01-.06h1.76l-.06.18z" /></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-widest uppercase">Bitcoin (BTC)</h3>
                    </div>
                    <div class="bg-black/50 p-3 rounded border border-white/5 font-mono text-xs text-gray-400 break-all select-all">
                        {{ config('support.btc_address') }}
                    </div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm hover:border-purple-500/30 transition-colors">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 17.97L4.58 13.62 11.943 24l7.37-10.38-7.372 4.35h.003zM12.056 0L4.69 12.223l7.365 4.354 7.365-4.35L12.056 0z"/></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-widest uppercase">Ethereum (ETH)</h3>
                    </div>
                    <div class="bg-black/50 p-3 rounded border border-white/5 font-mono text-xs text-gray-400 break-all select-all">
                        {{ config('support.eth_address') }}
                    </div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm hover:border-green-500/30 transition-colors">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center text-green-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0a12 12 0 100 24 12 12 0 000-24zm0 18.254c-3.14 0-5.733-1.008-5.733-2.26 0-1.25 2.593-2.26 5.733-2.26s5.733 1.01 5.733 2.26c0 1.252-2.593 2.26-5.733 2.26zm5.82-5.467c-.63.268-1.467.49-2.428.647v-3.05h1.94v-2.02h-1.94v-2.61h-6.78v2.61h-1.94v2.02h1.94v3.05c-.96-.157-1.797-.38-2.427-.648C4.54 10.082 3.5 8.652 3.5 7.027 3.5 4.256 7.306 2 12 2s8.5 2.256 8.5 5.027c0 1.625-1.04 3.055-2.68 3.76zm-2.428 1.344c-.95.148-2.09.23-3.392.23-1.3 0-2.44-.082-3.39-.23v-3.315h6.78v3.315z"/></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-widest uppercase">USDT (ERC-20)</h3>
                    </div>
                    <div class="bg-black/50 p-3 rounded border border-white/5 font-mono text-xs text-gray-400 break-all select-all">
                        {{ config('support.usdt_address') }}
                    </div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm hover:border-teal-500/30 transition-colors">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-full bg-teal-500/10 flex items-center justify-center text-teal-500">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.916 11.233a1.442 1.442 0 0 0-.965-.407H4.51c-.607 0-1.066.38-1.25.922a1.272 1.272 0 0 0 .17.925l2.426 4.094c.334.563.847.886 1.493.886h15.426c.61 0 1.07-.384 1.253-.93a1.277 1.277 0 0 0-.17-.93l-2.942-3.96h-.002zM4.51 6.347h15.442c.607 0 1.066-.38 1.25-.922a1.273 1.273 0 0 0-.17-.926L18.607.406c-.334-.564-.847-.887-1.493-.887H1.688c-.61 0-1.07.384-1.253.93a1.277 1.277 0 0 0 .17.93l2.942 3.96h-.002zm16.406 10.906c.335.565.848.888 1.494.888H6.985c-.608 0-1.066-.38-1.25-.923a1.273 1.273 0 0 0 .17-.926l2.425-4.094c.335-.565.848-.888 1.494-.888h15.425c.61 0 1.07.384 1.253.93a1.277 1.277 0 0 0-.17.93l-2.942 3.96h-.002l-2.474 4.123z"/></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-widest uppercase">Solana (SOL)</h3>
                    </div>
                    <div class="bg-black/50 p-3 rounded border border-white/5 font-mono text-xs text-gray-400 break-all select-all">
                        {{ config('support.sol_address') }}
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center border-t border-white/5 pt-8">
                <p class="text-xs text-gray-600 uppercase tracking-widest font-black mb-4">
                    For direct inquiries
                </p>
                <a href="mailto:{{ config('support.email') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors font-mono">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ config('support.email') }}
                </a>
            </div>

            <x-internal-ads class="mt-12" />
        </main>
    </div>
</x-layouts.app>
