<x-layouts.app title="Advertise with Us">
    <div class="min-h-screen bg-[#050505] text-gray-300 py-16 px-6 font-sans">
        <main class="w-full max-w-5xl mx-auto">

            <!-- Hero -->
            <div class="mb-12 border-b border-red-900/30 pb-6 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4"
                    style="font-family: 'Outfit', sans-serif;">
                    Advertise on <span class="text-red-600">DoxMe</span>
                </h1>
                <p class="text-gray-400 text-sm leading-relaxed font-mono">
                    Get your message in front of a highly targeted, privacy-conscious audience. Launch powerful
                    campaigns that appear directly on our search results and across all pastebins.
                </p>
            </div>

            <!-- Pricing -->
            <div class="mb-20">
                <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase mb-2">Advertising Plans</h2>
                    <p class="text-gray-500 font-mono text-xs text-center max-w-xl">Simple, transparent pricing for
                        maximum exposure.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-[#0a0a0a] border border-white/5 p-8 rounded-sm hover:border-red-500/30 flex flex-col items-center text-center transition-colors">
                        <div class="text-red-500 font-black text-4xl mb-2">$10</div>
                        <div class="text-white font-bold uppercase tracking-widest text-sm mb-4">1 Week</div>
                        <p class="text-xs text-gray-400 font-mono leading-relaxed">Perfect for short-term campaigns and
                            immediate visibility.</p>
                    </div>
                    <div
                        class="bg-[#111111] border border-red-900/50 p-8 rounded-sm hover:border-red-500 flex flex-col items-center text-center transition-colors">
                        <div class="text-red-500 font-black text-4xl mb-2">$25</div>
                        <div class="text-white font-bold uppercase tracking-widest text-sm mb-4">1 Month</div>
                        <p class="text-xs text-gray-400 font-mono leading-relaxed">Our most popular plan. Maintain a
                            strong presence all month.</p>
                    </div>
                    <div
                        class="bg-[#0a0a0a] border border-white/5 p-8 rounded-sm hover:border-red-500/30 flex flex-col items-center text-center transition-colors">
                        <div class="text-red-500 font-black text-4xl mb-2">$45</div>
                        <div class="text-white font-bold uppercase tracking-widest text-sm mb-4">2 Months</div>
                        <p class="text-xs text-gray-400 font-mono leading-relaxed">Long-term domination. Secure your
                            spot at the top.</p>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
                <div
                    class="bg-[#0a0a0a] border border-white/5 p-8 rounded-sm hover:border-red-500/30 transition-colors">
                    <div
                        class="w-12 h-12 bg-red-950/20 rounded flex items-center justify-center text-red-500 mb-6 border border-red-900/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-black uppercase tracking-widest text-sm mb-3">Search Engine Results</h3>
                    <p class="text-xs text-gray-400 font-mono leading-relaxed">Your ad will be injected seamlessly into
                        the DoxMe search results, capturing users at their highest intent.</p>
                </div>
                <div
                    class="bg-[#0a0a0a] border border-white/5 p-8 rounded-sm hover:border-red-500/30 transition-colors">
                    <div
                        class="w-12 h-12 bg-red-950/20 rounded flex items-center justify-center text-red-500 mb-6 border border-red-900/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-black uppercase tracking-widest text-sm mb-3">Pastebin Views</h3>
                    <p class="text-xs text-gray-400 font-mono leading-relaxed">Display your banner or text ad on every
                        active pastebin, guaranteeing thousands of daily impressions.</p>
                </div>
                <div
                    class="bg-[#0a0a0a] border border-white/5 p-8 rounded-sm hover:border-red-500/30 transition-colors">
                    <div
                        class="w-12 h-12 bg-red-950/20 rounded flex items-center justify-center text-red-500 mb-6 border border-red-900/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-white font-black uppercase tracking-widest text-sm mb-3">Advertiser Account</h3>
                    <p class="text-xs text-gray-400 font-mono leading-relaxed">Gain access to a dedicated dashboard to
                        manage campaigns, upload banners, and track real-time analytics.</p>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- DASHBOARD WINDOW — server-rendered stats                      -->
            <!-- ============================================================ -->
            <div class="mb-20">
                <div class="flex flex-col items-center mb-8">
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase mb-2">Exclusive Advertiser
                        Suite</h2>
                    <p class="text-gray-500 font-mono text-xs text-center max-w-xl">Take full control of your campaigns
                        with our custom-built, cyber-aesthetic Advertiser Dashboard.</p>
                </div>

                <div class="rounded-lg border border-white/10 bg-[#0a0a0a] overflow-hidden shadow-2xl">
                    <!-- Window Chrome -->
                    <div class="bg-[#111] border-b border-white/5 px-4 py-3 flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                        <div class="ml-4 text-[10px] font-mono text-gray-500 uppercase tracking-widest flex-1">
                            Advertiser_Dashboard.exe</div>
                        <div class="text-[9px] font-mono text-gray-600 uppercase tracking-widest">
                            Updated: {{ now()->format('H:i:s') }}
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row min-h-[440px]">
                        <!-- Sidebar -->
                        <div
                            class="w-full md:w-48 border-r border-white/5 bg-[#050505] p-4 flex flex-col gap-2 flex-shrink-0">
                            <div
                                class="px-3 py-2 bg-red-600/10 text-red-500 text-xs font-bold flex items-center gap-2 border border-red-900/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Overview
                            </div>
                            <div class="px-3 py-2 text-gray-500 text-xs font-bold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                                Campaigns
                            </div>
                            <div class="px-3 py-2 text-gray-500 text-xs font-bold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Analytics
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="flex-1 p-6 bg-[#080808]">
                            <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                                <h3 class="text-white font-bold uppercase tracking-widest text-sm">Campaign Overview
                                </h3>
                                <a href="#submit-ad-section"
                                    class="px-3 py-1 bg-red-600 text-white text-xs font-black uppercase hover:bg-red-700 transition-colors">
                                    New Campaign
                                </a>
                            </div>

                            <!-- Stats Cards -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                                <div class="bg-[#111] p-4 border border-white/5 rounded-sm">
                                    <div class="text-[9px] text-gray-500 uppercase tracking-widest font-mono mb-1">
                                        Active Ads</div>
                                    <div class="text-2xl text-white font-black">{{ $activeCount }}</div>
                                </div>
                                <div class="bg-[#111] p-4 border border-white/5 rounded-sm">
                                    <div class="text-[9px] text-gray-500 uppercase tracking-widest font-mono mb-1">Total
                                        Impressions</div>
                                    <div class="text-2xl text-red-500 font-black">
                                        @if($totalImpressions >= 1000000)
                                            {{ number_format($totalImpressions / 1000000, 1) }}M
                                        @elseif($totalImpressions >= 1000)
                                            {{ number_format($totalImpressions / 1000, 1) }}K
                                        @else
                                            {{ number_format($totalImpressions) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-[#111] p-4 border border-white/5 rounded-sm">
                                    <div class="text-[9px] text-gray-500 uppercase tracking-widest font-mono mb-1">Total
                                        Clicks</div>
                                    <div class="text-2xl text-amber-400 font-black">
                                        @if($totalClicks >= 1000000)
                                            {{ number_format($totalClicks / 1000000, 1) }}M
                                        @elseif($totalClicks >= 1000)
                                            {{ number_format($totalClicks / 1000, 1) }}K
                                        @else
                                            {{ number_format($totalClicks) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-[#111] p-4 border border-white/5 rounded-sm">
                                    <div class="text-[9px] text-gray-500 uppercase tracking-widest font-mono mb-1">Avg
                                        CTR</div>
                                    <div class="text-2xl text-green-400 font-black">{{ $avgCtr }}%</div>
                                </div>
                            </div>

                            <!-- CTR Progress Bar -->
                            @if($totalImpressions > 0)
                                <div class="mb-5">
                                    <div class="flex justify-between text-[9px] font-mono text-gray-600 mb-1">
                                        <span class="uppercase tracking-widest">Click-Through Rate</span>
                                        <span class="text-green-500 font-black">{{ $avgCtr }}%</span>
                                    </div>
                                    @php $ctrBarWidth = min(100, $avgCtr * 10) @endphp
                                    <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $ctrBarWidth }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Active Banners List inside window -->
                            <div class="bg-[#111] border border-white/5 rounded-sm overflow-hidden">
                                <div
                                    class="flex justify-between items-center text-[10px] text-gray-400 font-mono border-b border-white/5 px-4 py-2.5">
                                    <span class="font-black uppercase tracking-widest">Active Banner Ads</span>
                                    <span class="text-gray-600">{{ $activeBanners->count() }} running</span>
                                </div>
                                <div class="divide-y divide-white/5 max-h-52 overflow-y-auto">
                                    @forelse($activeBanners as $banner)
                                        <a href="{{ route('ads.click', $banner->id) }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors group">
                                            <div
                                                class="w-16 h-8 flex-shrink-0 overflow-hidden rounded-sm border border-white/5 bg-[#050505]">
                                                <img src="{{ asset($banner->media_url) }}" alt="{{ $banner->title }}"
                                                    class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div
                                                    class="text-[10px] font-bold text-gray-200 truncate group-hover:text-red-400 transition-colors">
                                                    {{ $banner->title }}
                                                </div>
                                                <div class="text-[8px] font-mono text-gray-600">
                                                    {{ number_format($banner->total_clicks) }} clicks
                                                    &middot;
                                                    {{ number_format($banner->total_impressions) }} views
                                                </div>
                                            </div>
                                            <span
                                                class="text-[8px] font-black px-1.5 py-0.5 border border-green-800/50 bg-green-950/20 text-green-500 uppercase flex-shrink-0">
                                                LIVE
                                            </span>
                                        </a>
                                    @empty
                                        <div class="px-4 py-4 text-[10px] text-gray-600 italic font-mono">
                                            No active banners at this time.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- ACTIVE BANNERS SHOWCASE                                       -->
            <!-- ============================================================ -->


            <!-- ============================================================ -->
            <!-- SUBMISSION FORM                                               -->
            <!-- ============================================================ -->
            <div id="submit-ad-section" class="border-t border-white/5 pt-16">
                <div class="flex flex-col items-center mb-10">
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase mb-2">Submit Your Ad Request
                    </h2>
                    <p class="text-gray-500 font-mono text-xs text-center max-w-xl">Fill out the form below. Once
                        approved, your ad campaign will be scheduled for launch.</p>
                </div>

                @if(session('success'))
                    <div
                        class="mb-6 p-4 bg-green-950/20 border border-green-900/50 rounded-sm max-w-3xl mx-auto flex items-start gap-3">
                        <span class="text-green-500 text-sm">✓</span>
                        <div class="text-xs text-green-400 font-mono">{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-950/20 border border-red-900/50 rounded-sm max-w-3xl mx-auto">
                        <ul class="list-disc list-inside text-xs text-red-500 font-mono space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-[#0a0a0a] border border-white/5 rounded-sm p-8 max-w-3xl mx-auto">
                    <form action="{{ route('advertise.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div>
                            <label for="title"
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Campaign
                                Title</label>
                            <input type="text" id="title" name="title" required value="{{ old('title') }}"
                                placeholder="e.g. Summer Privacy Sale"
                                class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 font-mono">
                        </div>

                        <div>
                            <label for="contact"
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Contact
                                Info (Telegram / Tox / Email)</label>
                            <input type="text" id="contact" name="contact" required value="{{ old('contact') }}"
                                placeholder="e.g. @username or email@proton.me"
                                class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 font-mono">
                            <p class="text-[10px] text-gray-600 mt-1 font-mono">So the admin can contact you if there's
                                an issue with your ad.</p>
                        </div>

                        <div>
                            <label for="target_url"
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Target
                                URL</label>
                            <input type="url" id="target_url" name="target_url" required value="{{ old('target_url') }}"
                                placeholder="https://your-secure-site.com"
                                class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Ad
                                Creative (Banner)</label>
                            <div class="border-2 border-dashed border-white/10 rounded-sm p-8 text-center bg-[#111] hover:border-red-500/50 cursor-pointer"
                                onclick="document.getElementById('image').click()">
                                <input type="file" id="image" name="image" accept="image/*" class="hidden" required
                                    onchange="previewAdImage(event)">
                                <div id="upload-ad-placeholder" class="flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="text-xs font-bold text-gray-400 font-mono">Click to upload banner (Max
                                        5MB)</span>
                                </div>
                                <div id="ad-image-preview-container" class="hidden w-full relative">
                                    <img id="ad-image-preview" src="#" alt="Preview"
                                        class="max-h-32 mx-auto rounded-sm border border-white/10">
                                    <button type="button"
                                        class="absolute top-0 right-0 bg-red-600 text-white p-1 rounded-sm"
                                        onclick="event.stopPropagation(); removeAdImage();">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-sm transition-colors">
                            Submit Ad Request
                        </button>
                    </form>
                </div>
            </div>

            {{-- Platform sponsors --}}
            @php $internalAds = \App\Helper\AdTracker::getBanners(0, 0); @endphp
            @if($internalAds->isNotEmpty())
                <div class="mt-12 w-full">
                    <div class="w-full flex items-center justify-center gap-3 mb-4">
                        <div class="h-[1px] bg-red-950/40 flex-grow"></div>
                        <span
                            class="text-[8px] font-black text-red-500 uppercase tracking-[0.2em] whitespace-nowrap select-none flex items-center gap-1.5 font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            OFFICIAL PLATFORM SPONSORS
                        </span>
                        <div class="h-[1px] bg-red-950/40 flex-grow"></div>
                    </div>
                    <div class="flex flex-col gap-3 items-center">
                        @foreach($internalAds as $ad)
                            <a href="{{ route('ads.click', $ad->id) }}" target="_blank"
                                class="block w-full h-[58px] border border-red-950/30 hover:border-red-500/60 overflow-hidden rounded bg-[#0a0a0a]/30 transition-all duration-150 relative group">
                                <img src="{{ asset($ad->media_url) }}" alt="{{ $ad->title }}"
                                    class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-150">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </main>
    </div>

    {{-- Image upload preview only (minimal JS, no fetch/polling) --}}
    <script>
        function previewAdImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                document.getElementById('ad-image-preview').src = reader.result;
                document.getElementById('upload-ad-placeholder').classList.add('hidden');
                document.getElementById('ad-image-preview-container').classList.remove('hidden');
            };
            if (event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
        }
        function removeAdImage() {
            document.getElementById('image').value = '';
            document.getElementById('upload-ad-placeholder').classList.remove('hidden');
            document.getElementById('ad-image-preview-container').classList.add('hidden');
            document.getElementById('ad-image-preview').src = '#';
        }
    </script>
</x-layouts.app>