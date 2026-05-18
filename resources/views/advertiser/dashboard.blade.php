<x-layouts.dashboard :title="'Advertiser Dashboard'">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- Header Banner (Ultra Minimalist Red & Black) -->
        <div class="border border-red-900/30 bg-[#0a0a0a] p-8 rounded-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-red-950/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2 py-0.5 border border-red-900/40 bg-red-950/20 text-red-500 text-[9px] font-black uppercase tracking-widest rounded-sm">
                            Advertiser
                        </span>
                        <span class="text-gray-700 text-xs font-mono">•</span>
                        <span class="text-gray-500 text-[10px] font-mono tracking-wider uppercase">Balance: <span class="text-green-500 font-bold">${{ number_format($advertiser->balance, 2) }}</span></span>
                    </div>
                    <h1 class="text-2xl font-black text-white tracking-tight uppercase mb-2">
                        Ads Operations Terminal
                    </h1>
                    <p class="text-gray-500 text-xs font-mono max-w-2xl leading-relaxed">
                        Manage your active campaigns, track impressions, and request new ads via the Tor-optimized high-efficiency interface.
                    </p>
                </div>
                <div class="mt-6 md:mt-0 flex flex-wrap gap-4">
                    <a href="{{ route('advertiser.ads.create') }}" class="px-5 py-2.5 bg-red-700 hover:bg-red-800 text-white font-black text-[10px] tracking-widest uppercase rounded-sm transition-colors duration-150 shadow-lg shadow-red-900/20 active:scale-95">
                        Request New Ad
                    </a>
                </div>
            </div>
        </div>

        <!-- System Stats Grid (Pure Text & Border) -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Stat 1: Total Spend -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Total Spent</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">${{ number_format($totalSpent, 2) }}</p>
            </div>

            <!-- Stat 2: Total Impressions -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Total Impressions</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                </div>
                <p class="text-xl font-mono font-bold text-white">{{ number_format($totalImpressions) }}</p>
            </div>

            <!-- Stat 3: Total Clicks -->
            <div class="p-5 bg-[#0a0a0a] border border-red-900/20 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Total Clicks</span>
                    <div class="text-red-500/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                    </div>
                </div>
                <div class="flex justify-between items-end">
                    <p class="text-xl font-mono font-bold text-white">{{ number_format($totalClicks) }}</p>
                    <p class="text-[10px] text-gray-600 font-mono">CTR: {{ $totalImpressions > 0 ? number_format(($totalClicks / $totalImpressions) * 100, 2) : 0 }}%</p>
                </div>
            </div>
        </div>

        <!-- Dynamic Content: Active Campaigns & Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2-Columns: Campaigns Table -->
            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-xs font-black text-red-500 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Active Campaigns
                        </h2>
                    </div>

                    <div class="space-y-3">
                        @forelse($campaigns as $campaign)
                            <div class="flex items-center justify-between p-3.5 bg-[#0a0a0a] border border-red-900/10 hover:border-red-900/30 transition-colors duration-150 rounded-sm group">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 flex items-center justify-center bg-red-950/20 text-red-500 border border-red-900/10 rounded-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-gray-300">
                                            {{ $campaign->name }}
                                        </span>
                                        <p class="text-[8px] text-gray-600 font-mono uppercase mt-0.5">
                                            Budget: ${{ number_format($campaign->total_budget, 2) }} • {{ $campaign->ads->count() }} Ads
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right font-mono">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm text-[8px] font-black uppercase tracking-widest bg-{{ $campaign->status === 'active' ? 'green' : ($campaign->status === 'paused' ? 'yellow' : 'gray') }}-950/40 text-{{ $campaign->status === 'active' ? 'green' : ($campaign->status === 'paused' ? 'yellow' : 'gray') }}-500 border border-{{ $campaign->status === 'active' ? 'green' : ($campaign->status === 'paused' ? 'yellow' : 'gray') }}-900/50">
                                        {{ $campaign->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-[10px] text-gray-500 font-mono p-4 bg-[#050505] border border-red-900/10 text-center rounded-sm">
                                No active campaigns found. Start by requesting a new ad.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Chart -->
            <div class="space-y-6">
                <div class="p-6 border border-red-900/20 bg-[#050505] rounded-sm">
                    <h2 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Performance Intel
                    </h2>
                    <div id="performance-chart" class="w-full h-48"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load ApexCharts -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{
                    name: 'Impressions',
                    data: [31, 40, 28, 51, 42, 109, 100]
                }],
                chart: {
                    height: 200,
                    type: 'area',
                    fontFamily: 'JetBrains Mono, monospace',
                    toolbar: { show: false },
                    background: 'transparent'
                },
                theme: {
                    mode: 'dark'
                },
                colors: ['#ef4444'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: {
                    categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#4b5563', fontSize: '10px' } }
                },
                yaxis: { show: false },
                grid: {
                    borderColor: '#1f2937',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                }
            };

            var chart = new ApexCharts(document.querySelector("#performance-chart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-layouts.dashboard>
