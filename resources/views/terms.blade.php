<x-layouts.app title="Terms of Service">
    <div class="min-h-[90vh] bg-[#050505] text-gray-300 py-16 px-6 font-sans">
        <main class="w-full max-w-4xl mx-auto">
            <div class="mb-12 border-b border-red-900/30 pb-6">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4" style="font-family: 'Outfit', sans-serif;">
                    Terms of <span class="text-red-600" style="text-shadow: 0 0 25px rgba(220, 38, 38, 0.4);">Service</span>
                </h1>
                <p class="text-gray-500 font-mono text-sm uppercase tracking-widest">Read Carefully. No Exceptions.</p>
            </div>

            <div class="space-y-10 font-mono text-sm">
                
                <div class="bg-red-950/10 border border-red-900/30 p-6 rounded-sm">
                    <h3 class="text-red-500 font-black uppercase tracking-widest mb-3 text-lg">1. Absolute Freedom of Information</h3>
                    <p class="text-gray-400 leading-relaxed">
                        By accessing this service, you acknowledge that <strong>we have absolutely no rules</strong> regarding the submission of pastebins, sharing of information, or the practice of "doxxing". We turn a blind eye to all content uploaded to our servers. We do not moderate, censor, or remove information based on moral, ethical, or legal requests.
                    </p>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm">
                    <h3 class="text-white font-black uppercase tracking-widest mb-3 text-lg">2. Zero Accountability & Blind Eye Policy</h3>
                    <p class="text-gray-400 leading-relaxed">
                        We provide the infrastructure; you provide the data. We close our eyes to what is happening on the platform. If you find your personal information here, do not expect us to take it down. We are a neutral conduit of data and take zero responsibility for the consequences of the information shared.
                    </p>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm">
                    <h3 class="text-white font-black uppercase tracking-widest mb-3 text-lg">3. Transparent Reporting</h3>
                    <p class="text-gray-400 leading-relaxed">
                        While we do not take action on reports to remove content, any reports or complaints submitted to our administration will be treated with full transparency. <strong class="text-red-400">All reports will be made public and transparent.</strong> By sending us a report, you forfeit any expectation of privacy regarding your communication with us.
                    </p>
                </div>

                <div class="bg-[#0a0a0a] border border-white/5 p-6 rounded-sm">
                    <h3 class="text-white font-black uppercase tracking-widest mb-3 text-lg">4. User Autonomy</h3>
                    <p class="text-gray-400 leading-relaxed">
                        You are entirely responsible for your own OPSEC (Operational Security). If you choose to use our platform, you do so at your own risk. We do not protect you, and we do not protect your targets.
                    </p>
                </div>

                <div class="mt-12 text-center">
                    <p class="text-xs text-gray-600 uppercase tracking-widest font-black">
                        Last Updated: {{ date('Y-m-d') }} // END OF TERMS
                    </p>
                </div>

            </div>

            @php
                $internalAds = \App\Helper\AdTracker::getBanners(2, 0);
            @endphp
            @if($internalAds->isNotEmpty())
                <div class="mt-12 w-full">
                    <div class="w-full flex items-center justify-center gap-3 mb-4">
                        <div class="h-[1px] bg-red-950/40 flex-grow"></div>
                        <span class="text-[8px] font-black text-red-500 uppercase tracking-[0.2em] whitespace-nowrap select-none flex items-center gap-1.5 font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            OFFICIAL PLATFORM SPONSORS
                        </span>
                        <div class="h-[1px] bg-red-950/40 flex-grow"></div>
                    </div>
                    <div class="flex flex-col gap-3 items-center">
                        @foreach($internalAds as $ad)
                            <a href="{{ route('ads.click', $ad->id) }}" target="_blank" class="block w-full h-[58px] border border-red-950/30 hover:border-red-500/60 overflow-hidden rounded bg-[#0a0a0a]/30 transition-all duration-150 relative group">
                                <img src="{{ asset($ad->media_url) }}" alt="{{ $ad->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-150">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>
    </div>
</x-layouts.app>
