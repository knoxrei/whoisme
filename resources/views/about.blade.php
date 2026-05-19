<x-layouts.app title="About Us">
    <div class="min-h-[90vh] bg-[#050505] text-gray-300 py-16 px-6 font-sans">
        <main class="w-full max-w-4xl mx-auto">
            <div class="mb-12 border-b border-red-900/30 pb-6">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4" style="font-family: 'Outfit', sans-serif;">
                    About <span class="text-red-600" style="text-shadow: 0 0 25px rgba(220, 38, 38, 0.4);">DoxMe</span>
                </h1>
                <p class="text-gray-500 font-mono text-sm uppercase tracking-widest">Uncensorable Information Infrastructure.</p>
            </div>

            <div class="space-y-12">
                <!-- Our Mission -->
                <section class="border border-red-500 p-8 rounded-sm relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3 uppercase tracking-widest font-mono">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Our Mission
                    </h2>
                    <p class="text-sm leading-relaxed text-gray-400 font-mono">
                        DoxMe was built on a single, uncompromising principle: absolute freedom of information. In an era where data is heavily monitored, censored, and controlled by centralized entities, we provide a decentralized-minded, bulletproof conduit for data transmission. We do not judge the nature of the data; we simply ensure its survival.
                    </p>
                </section>

                <!-- Architecture & Security -->
                <section class="border border-red-500 p-8 rounded-sm relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3 uppercase tracking-widest font-mono">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Architecture & Security
                    </h2>
                    <p class="text-sm leading-relaxed text-gray-400 font-mono">
                        Our infrastructure is designed to resist takedown requests and targeted attacks. We operate a strict no-logs policy regarding user identity and origin IPs. You remain a ghost in the system. The platform strips metadata, enforces minimal javascript execution paths, and is optimized for the Tor network to guarantee untraceable OPSEC for our users.
                    </p>
                </section>

                <!-- The Team -->
                <section class="border border-red-500 p-8 rounded-sm relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3 uppercase tracking-widest font-mono">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        The Team
                    </h2>
                    <p class="text-sm leading-relaxed text-gray-400 font-mono">
                        We are a decentralized collective of developers, security researchers, and privacy advocates. We do not operate under real names, nor do we associate our infrastructure with any single jurisdiction. We remain entirely anonymous to protect the platform and ourselves from state and corporate intervention.
                    </p>
                </section>
            </div>

            @php
                $internalAds = \App\Helper\AdTracker::getBanners(2, 0);
            @endphp
            @if($internalAds->isNotEmpty())
                <div class="mt-12 w-full">
                    <div class="w-full flex items-center justify-center gap-3 mb-4">
                        <div class="h-[1px] bg-red-950/40 flex-grow"></div>
                        <span class="text-[8px] font-black text-red-500 uppercase tracking-[0.2em] whitespace-nowrap select-none flex items-center gap-1.5 font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
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
