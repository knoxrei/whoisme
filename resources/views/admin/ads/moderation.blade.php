<x-layouts.dashboard :title="'Ad Management Dashboard'">
    <div class="space-y-8 max-w-7xl mx-auto">
        <div class="border border-red-900/30 bg-[#0a0a0a] p-8 rounded-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-red-950/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-2 py-0.5 border border-red-900/40 bg-red-950/20 text-red-500 text-[9px] font-black uppercase tracking-widest rounded-sm select-none">
                        Owner Control Room
                    </span>
                    <span class="text-gray-700 text-xs font-mono">•</span>
                    <span class="text-gray-500 text-[10px] font-mono tracking-wider uppercase">Advertisement Console</span>
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight uppercase mb-2">
                    Advertisement Management Center
                </h1>
                <p class="text-gray-500 text-xs font-mono max-w-2xl leading-relaxed">
                    Broadcast direct internal sponsors, inspect campaign telemetry, and moderate external advertising requests.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 border border-green-900/30 bg-green-950/20 text-green-500 text-xs font-mono rounded-sm">
                > {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-6 border border-red-900/20 bg-[#0a0a0a]/60 rounded-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 text-red-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block font-mono select-none">Active Banners</span>
                <span class="text-3xl font-black text-white block mt-2 font-mono">{{ $stats['active_count'] }}</span>
            </div>

            <div class="p-6 border border-yellow-900/20 bg-[#0a0a0a]/60 rounded-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 text-yellow-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block font-mono select-none">Awaiting Review</span>
                <span class="text-3xl font-black text-yellow-500 block mt-2 font-mono">{{ $stats['pending_count'] }}</span>
            </div>

            <div class="p-6 border border-indigo-900/20 bg-[#0a0a0a]/60 rounded-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 text-indigo-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block font-mono select-none">Total Impressions</span>
                <span class="text-3xl font-black text-indigo-400 block mt-2 font-mono">{{ number_format($stats['total_impressions']) }}</span>
            </div>

            <div class="p-6 border border-green-900/20 bg-[#0a0a0a]/60 rounded-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 text-green-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block font-mono select-none">Total Click-Throughs</span>
                <span class="text-3xl font-black text-green-400 block mt-2 font-mono">{{ number_format($stats['total_clicks']) }}</span>
            </div>
        </div>

        <details class="p-6 border border-red-900/30 bg-[#0a0a0a] rounded-sm group overflow-hidden" open>
            <summary class="text-xs font-black text-red-500 uppercase tracking-[0.2em] flex items-center justify-between cursor-pointer select-none font-mono list-none">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Direct Manual Ad Creation Terminal
                </span>
                <span class="text-[9px] text-gray-500 group-open:rotate-180 transition-transform">▼</span>
            </summary>
            
            <form action="{{ route('admin.ads.store_manual') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1.5 font-mono select-none">Ad Title / Campaign Name</label>
                        <input type="text" name="title" required class="w-full bg-black border border-red-900/20 focus:border-red-500 focus:ring-0 text-white rounded-sm px-3 py-2 text-xs font-mono transition-colors" placeholder="e.g. Premium VPN Service">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1.5 font-mono select-none">Target Redirect URL</label>
                        <input type="url" name="target_url" required class="w-full bg-black border border-red-900/20 focus:border-red-500 focus:ring-0 text-white rounded-sm px-3 py-2 text-xs font-mono transition-colors" placeholder="http://example.onion or https://example.com">
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1.5 font-mono select-none">Contact / Telegram / Jabber (Optional)</label>
                        <input type="text" name="contact" class="w-full bg-black border border-red-900/20 focus:border-red-500 focus:ring-0 text-white rounded-sm px-3 py-2 text-xs font-mono transition-colors" placeholder="e.g. @admin_contact (Defaults to Admin)">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1.5 font-mono select-none">Banner Image File (GIF, PNG, JPG - max 5MB)</label>
                        <div class="relative flex items-center">
                            <input type="file" name="image" required class="w-full text-xs text-gray-400 font-mono file:mr-4 file:py-1.5 file:px-3 file:rounded-sm file:border file:border-red-900/30 file:text-[9px] file:font-black file:uppercase file:tracking-widest file:bg-red-950/20 file:text-red-500 hover:file:bg-red-900 hover:file:text-white file:transition-colors file:duration-150">
                        </div>
                    </div>
                </div>
                
                <div class="md:col-span-2 pt-2 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-red-700 hover:bg-red-800 text-white font-black text-[9px] tracking-widest uppercase rounded-sm transition-colors duration-150 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Broadcast Ad Instantly
                    </button>
                </div>
            </form>
        </details>

        @if(count($pendingAds) > 0)
        <div class="space-y-4">
            <h2 class="text-xs font-black text-yellow-500 uppercase tracking-[0.2em] font-mono select-none">
                Pending Ad Requests Awaiting Authorization
            </h2>
            <div class="space-y-3">
                @foreach($pendingAds as $request)
                    <div class="p-6 border border-yellow-900/30 bg-[#050505] rounded-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6 hover:border-yellow-900/50 transition-colors">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block font-mono">Advertiser</span>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="w-6 h-6 flex items-center justify-center bg-yellow-950/20 border border-yellow-900/30 rounded-sm text-yellow-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-300">{{ $request->ad->campaign->advertiser->company_name ?? 'Unknown' }}</h4>
                                        <p class="text-[8px] text-gray-600 font-mono uppercase mt-0.5">Contact: <span class="text-yellow-500">{{ $request->ad->contact ?? 'N/A' }}</span></p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block font-mono">Ad Details</span>
                                <div class="mt-2">
                                    <h4 class="text-xs font-bold text-gray-300">{{ $request->ad->title }}</h4>
                                    <a href="{{ $request->ad->target_url }}" target="_blank" class="text-[9px] text-red-500 hover:text-red-400 font-mono mt-1 inline-block">Verify Target URL &nearr;</a>
                                </div>
                            </div>

                            <div>
                                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block font-mono">Media</span>
                                <div class="mt-2 border border-gray-800 bg-black flex items-center justify-center overflow-hidden h-12 w-full rounded-sm">
                                    @if($request->ad->media_url)
                                        <img src="{{ $request->ad->media_url }}" alt="Preview" class="w-full h-full object-contain">
                                    @else
                                        <span class="text-[8px] text-gray-600 uppercase">No Media</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 lg:border-l lg:border-yellow-900/20 lg:pl-6">
                            <form action="{{ route('admin.ads.approve', $request->ad->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-green-900/40 bg-green-950/20 text-green-500 hover:bg-green-900 hover:text-white text-[9px] font-black uppercase tracking-widest rounded-sm transition-colors font-mono">
                                    Approve
                                </button>
                            </form>
                            
                            <button type="button" onclick="openModal('revise-modal-{{ $request->ad->id }}')" class="px-4 py-2 border border-yellow-900/40 bg-yellow-950/20 text-yellow-500 hover:bg-yellow-900 hover:text-white text-[9px] font-black uppercase tracking-widest rounded-sm transition-colors font-mono">
                                Revise
                            </button>
                            
                            <button type="button" onclick="openModal('reject-modal-{{ $request->ad->id }}')" class="px-4 py-2 border border-red-900/40 bg-red-950/20 text-red-500 hover:bg-red-900 hover:text-white text-[9px] font-black uppercase tracking-widest rounded-sm transition-colors font-mono">
                                Reject
                            </button>
                        </div>
                    </div>

                    <div id="revise-modal-{{ $request->ad->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-200" style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.85);" data-modal-container>
                        <div class="relative w-full max-w-md bg-[#0a0a0a] border rounded-sm overflow-hidden transform scale-95 transition-transform duration-200 ease-out shadow-2xl shadow-black/90" data-modal-box style="border-color: rgba(234, 179, 8, 0.4);">
                            <form action="{{ route('admin.ads.request_revision', $request->ad->id) }}" method="POST">
                                @csrf
                                <div class="flex items-center justify-between px-6 py-4 border-b bg-[#111]" style="border-color: rgba(234, 179, 8, 0.2);">
                                    <h3 class="text-xs font-black uppercase tracking-[0.2em] font-mono text-yellow-500">
                                        Request Revision
                                    </h3>
                                </div>
                                <div class="p-6 text-xs text-gray-300 font-mono">
                                    <p class="mb-4 text-gray-500">Provide instructions for the advertiser on what needs to be changed.</p>
                                    <textarea name="notes" rows="4" class="w-full bg-black border border-yellow-900/30 rounded-sm p-3 text-white focus:border-yellow-500 focus:ring-0 transition-colors" placeholder="What needs to be changed?..." required></textarea>
                                </div>
                                <div class="px-6 py-4 border-t bg-[#050505] flex justify-end gap-3" style="border-color: rgba(234, 179, 8, 0.1);">
                                    <button type="button" onclick="closeModal('revise-modal-{{ $request->ad->id }}')" class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-white px-4 py-2 transition-colors duration-150">Cancel</button>
                                    <button type="submit" class="text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-yellow-950/20 text-yellow-500 border-yellow-900/30 hover:bg-yellow-600 hover:text-white transition-colors duration-150">Send Request</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="reject-modal-{{ $request->ad->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-200" style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.85);" data-modal-container>
                        <div class="relative w-full max-w-md bg-[#0a0a0a] border rounded-sm overflow-hidden transform scale-95 transition-transform duration-200 ease-out shadow-2xl shadow-black/90" data-modal-box style="border-color: rgba(153, 27, 27, 0.4);">
                            <form action="{{ route('admin.ads.reject', $request->ad->id) }}" method="POST">
                                @csrf
                                <div class="flex items-center justify-between px-6 py-4 border-b bg-[#111]" style="border-color: rgba(153, 27, 27, 0.2);">
                                    <h3 class="text-xs font-black uppercase tracking-[0.2em] font-mono text-red-500">
                                        Reject Ad
                                    </h3>
                                </div>
                                <div class="p-6 text-xs text-gray-300 font-mono">
                                    <p class="mb-4 text-gray-500">Provide the reason for rejecting this ad outright.</p>
                                    <textarea name="notes" rows="4" class="w-full bg-black border border-red-900/30 rounded-sm p-3 text-white focus:border-red-500 focus:ring-0 transition-colors" placeholder="Reason for rejection..." required></textarea>
                                </div>
                                <div class="px-6 py-4 border-t bg-[#050505] flex justify-end gap-3" style="border-color: rgba(153, 27, 27, 0.1);">
                                    <button type="button" onclick="closeModal('reject-modal-{{ $request->ad->id }}')" class="text-[9px] font-black uppercase tracking-widest text-gray-500 hover:text-white px-4 py-2 transition-colors duration-150">Cancel</button>
                                    <button type="submit" class="text-[9px] font-black uppercase tracking-widest px-4 py-2 border rounded-sm bg-red-950/20 text-red-500 border-red-900/30 hover:bg-red-600 hover:text-white transition-colors duration-150">Confirm Reject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="space-y-4">
            <h2 class="text-xs font-black text-red-500 uppercase tracking-[0.2em] font-mono select-none">
                All Banner Inventories Database
            </h2>
            <div class="border border-red-900/10 bg-[#0a0a0a] rounded-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs font-mono">
                        <thead>
                            <tr class="border-b border-red-900/20 bg-red-950/10 text-gray-400 select-none">
                                <th class="p-4 font-black tracking-wider uppercase text-[9px] w-24">Media</th>
                                <th class="p-4 font-black tracking-wider uppercase text-[9px]">Details</th>
                                <th class="p-4 font-black tracking-wider uppercase text-[9px]">Advertiser / Contact</th>
                                <th class="p-4 font-black tracking-wider uppercase text-[9px] w-32">Telemetry Stats</th>
                                <th class="p-4 font-black tracking-wider uppercase text-[9px] w-28">Status</th>
                                <th class="p-4 font-black tracking-wider uppercase text-[9px] w-48 text-right">Administrative Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-900/10">
                            @forelse($allAds as $ad)
                                <tr class="hover:bg-red-950/5 transition-colors">
                                    <td class="p-4">
                                        <div class="border border-gray-800 bg-black flex items-center justify-center overflow-hidden h-10 w-24 rounded-sm">
                                            @if($ad->media_url)
                                                <img src="{{ $ad->media_url }}" alt="Media Preview" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-[7px] text-gray-600 uppercase font-mono">No Media</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <div class="font-bold text-gray-200">{{ $ad->title }}</div>
                                        <div class="text-[9px] text-gray-500 mt-1 truncate max-w-[200px]" title="{{ $ad->target_url }}">
                                            Redirect: <a href="{{ $ad->target_url }}" target="_blank" class="text-red-500 hover:underline">{{ $ad->target_url }}</a>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <div class="text-gray-300">{{ $ad->campaign->advertiser->company_name ?? 'Direct Sponsor' }}</div>
                                        <div class="text-[9px] text-gray-500 mt-0.5">Contact: <span class="text-red-500/80">{{ $ad->contact ?? 'Admin' }}</span></div>
                                    </td>
                                    
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="text-[9px] text-indigo-400">Views: <span class="font-bold">{{ number_format($ad->statistics->sum('impressions')) }}</span></div>
                                        <div class="text-[9px] text-green-400 mt-0.5">Clicks: <span class="font-bold">{{ number_format($ad->statistics->sum('clicks')) }}</span></div>
                                    </td>
                                    
                                    <td class="p-4">
                                        @if($ad->status === 'active')
                                            <span class="px-2 py-0.5 border border-green-900/40 bg-green-950/20 text-green-500 text-[8px] font-black uppercase tracking-widest rounded-sm">ACTIVE</span>
                                        @elseif($ad->status === 'pending')
                                            <span class="px-2 py-0.5 border border-yellow-900/40 bg-yellow-950/20 text-yellow-500 text-[8px] font-black uppercase tracking-widest rounded-sm">PENDING</span>
                                        @elseif($ad->status === 'suspended')
                                            <span class="px-2 py-0.5 border border-orange-900/40 bg-orange-950/20 text-orange-500 text-[8px] font-black uppercase tracking-widest rounded-sm">SUSPENDED</span>
                                        @else
                                            <span class="px-2 py-0.5 border border-red-900/40 bg-red-950/20 text-red-500 text-[8px] font-black uppercase tracking-widest rounded-sm">{{ strtoupper($ad->status) }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($ad->status !== 'active')
                                                <form action="{{ route('admin.ads.activate', $ad->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 border border-green-900/40 bg-green-950/10 text-green-500 hover:bg-green-600 hover:text-white text-[8px] font-black uppercase tracking-widest rounded-sm transition-colors">
                                                        Activate
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.ads.suspend', $ad->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 border border-orange-900/40 bg-orange-950/10 text-orange-500 hover:bg-orange-650 hover:text-white text-[8px] font-black uppercase tracking-widest rounded-sm transition-colors">
                                                        Suspend
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.ads.delete', $ad->id) }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to permanently delete this banner and its telemetry? This cannot be undone.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 border border-red-900/40 bg-red-950/10 text-red-500 hover:bg-red-600 hover:text-white text-[8px] font-black uppercase tracking-widest rounded-sm transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-600 uppercase font-mono tracking-widest select-none">
                                        No advertisements registered in the database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
