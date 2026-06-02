<x-layouts.dashboard :title="$title" :role="$role">
    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="p-4 border border-green-900/40 bg-green-950/20 text-green-400 text-xs font-mono rounded-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 border border-red-900/40 bg-red-950/20 text-red-400 text-xs font-mono rounded-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="border border-red-900/40 bg-[#0a0a0a] p-6 rounded-sm">
            <h1 class="text-lg font-black text-white uppercase tracking-tight mb-2">Bulk Email Broadcast</h1>
            <p class="text-xs text-gray-500 font-mono leading-relaxed">
                Delivery runs through the <strong class="text-red-500">queue worker</strong>, not the browser.
                Each email uses your configured timeout — invalid addresses or slow SMTP responses are skipped automatically.
            </p>
            <div class="mt-4 p-3 border border-yellow-900/30 bg-yellow-950/10 rounded-sm">
                <p class="text-[10px] text-yellow-500/90 font-mono">
                    Start the worker on the server (set <code class="text-yellow-300">--timeout</code> at least 5 seconds above your per-email timeout):
                </p>
                <code class="block mt-2 text-[10px] text-yellow-300 font-mono">php artisan queue:work --timeout={{ $defaultTimeoutSeconds + 5 }}</code>
            </div>
            <div class="mt-4 flex flex-wrap gap-4 text-[10px] font-mono">
                <span class="text-gray-400">With email: <strong class="text-white">{{ number_format($totalWithEmail) }}</strong></span>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400">Verified only: <strong class="text-white">{{ number_format($totalVerified) }}</strong></span>
            </div>
        </div>

        @if($activeCampaign)
            <div class="border border-red-600/40 bg-red-950/10 p-6 rounded-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xs font-black text-red-500 uppercase tracking-widest">Campaign In Progress</h2>
                    <span class="text-[10px] font-mono text-gray-500">#{{ $activeCampaign->id }}</span>
                </div>
                <p class="text-[10px] text-gray-400 font-mono mb-1 truncate">{{ $activeCampaign->subject }}</p>
                <p class="text-[9px] text-gray-600 font-mono mb-3">Timeout: {{ $activeCampaign->timeout_seconds ?? $defaultTimeoutSeconds }}s per email</p>
                <div class="h-2 bg-black border border-red-900/20 rounded-sm overflow-hidden mb-3">
                    <div class="h-full bg-red-600 transition-all" style="width: {{ $activeCampaign->progressPercent() }}%"></div>
                </div>
                <div class="grid grid-cols-4 gap-2 text-center text-[10px] font-mono">
                    <div><span class="text-white font-black">{{ number_format($activeCampaign->processed_count) }}</span><span class="text-gray-600 block">Processed</span></div>
                    <div><span class="text-green-500 font-black">{{ number_format($activeCampaign->sent_count) }}</span><span class="text-gray-600 block">Sent</span></div>
                    <div><span class="text-yellow-500 font-black">{{ number_format($activeCampaign->skipped_count) }}</span><span class="text-gray-600 block">Skipped</span></div>
                    <div><span class="text-red-500 font-black">{{ number_format(max(0, $activeCampaign->total_recipients - $activeCampaign->processed_count)) }}</span><span class="text-gray-600 block">Remaining</span></div>
                </div>
                <p class="text-[9px] text-gray-600 font-mono mt-3 text-center">
                    This page auto-refreshes every 5 seconds until the campaign finishes.
                </p>
            </div>
            <meta http-equiv="refresh" content="5">
        @endif

        <form action="{{ route('dashboard.bulk-mail.dispatch', [], false) }}" method="POST" class="border border-red-900/30 bg-[#050505] p-6 rounded-sm space-y-5">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Audience</label>
                <select name="verified_only" class="w-full bg-black border border-red-900/30 rounded-sm px-4 py-2.5 text-xs text-gray-300">
                    <option value="0" @selected(old('verified_only', '0') === '0')>All users with email (verified or not)</option>
                    <option value="1" @selected(old('verified_only') === '1')>Verified email only</option>
                </select>
            </div>
            <div class="space-y-2">
                <label for="timeout_seconds" class="text-[10px] font-black text-gray-500 uppercase tracking-widest">
                    Per-Email Timeout (seconds)
                </label>
                <input type="number" id="timeout_seconds" name="timeout_seconds" required
                    min="{{ $minTimeoutSeconds }}" max="{{ $maxTimeoutSeconds }}" step="1"
                    value="{{ old('timeout_seconds', $defaultTimeoutSeconds) }}"
                    class="w-full bg-black border border-red-900/30 rounded-sm px-4 py-2.5 text-xs text-white">
                <p class="text-[9px] text-gray-600 font-mono">
                    Allowed range: {{ $minTimeoutSeconds }}–{{ $maxTimeoutSeconds }}s. Emails that exceed this limit are skipped.
                </p>
                @error('timeout_seconds')
                    <p class="text-[9px] text-red-500 font-mono">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <label for="subject" class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Subject</label>
                <input type="text" id="subject" name="subject" required maxlength="255" value="{{ old('subject') }}"
                    class="w-full bg-black border border-red-900/30 rounded-sm px-4 py-2.5 text-xs text-white"
                    placeholder="Platform announcement">
            </div>
            <div class="space-y-2">
                <label for="message" class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Message</label>
                <textarea id="message" name="message" required rows="8" maxlength="10000"
                    class="w-full bg-black border border-red-900/30 rounded-sm px-4 py-3 text-xs text-gray-300 font-mono resize-y"
                    placeholder="Your message to all recipients...">{{ old('message') }}</textarea>
            </div>
            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-widest py-3 rounded-sm transition-colors">
                Queue Broadcast
            </button>
        </form>

        @if($campaigns->isNotEmpty())
            <div class="border border-red-900/30 bg-[#0a0a0a] p-6 rounded-sm">
                <h2 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4">Recent Campaigns</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[10px] font-mono">
                        <thead>
                            <tr class="text-gray-600 uppercase tracking-widest border-b border-red-900/20">
                                <th class="pb-2 pr-4">ID</th>
                                <th class="pb-2 pr-4">Subject</th>
                                <th class="pb-2 pr-4">Timeout</th>
                                <th class="pb-2 pr-4">Status</th>
                                <th class="pb-2 pr-4">Sent</th>
                                <th class="pb-2 pr-4">Skipped</th>
                                <th class="pb-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-900/10 text-gray-400">
                            @foreach($campaigns as $campaign)
                                <tr>
                                    <td class="py-3 pr-4 text-gray-500">#{{ $campaign->id }}</td>
                                    <td class="py-3 pr-4 text-gray-300 max-w-[180px] truncate">{{ $campaign->subject }}</td>
                                    <td class="py-3 pr-4 text-gray-500">{{ $campaign->timeout_seconds ?? $defaultTimeoutSeconds }}s</td>
                                    <td class="py-3 pr-4">
                                        <span class="px-1.5 py-0.5 rounded-sm text-[8px] font-black uppercase
                                            @if($campaign->status === 'completed') bg-green-950/30 text-green-500 border border-green-900/30
                                            @elseif($campaign->status === 'processing') bg-yellow-950/30 text-yellow-500 border border-yellow-900/30
                                            @else bg-red-950/30 text-red-500 border border-red-900/30 @endif">
                                            {{ $campaign->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 text-green-500">{{ number_format($campaign->sent_count) }}</td>
                                    <td class="py-3 pr-4 text-yellow-500">{{ number_format($campaign->skipped_count) }}</td>
                                    <td class="py-3 text-gray-600">{{ $campaign->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-layouts.dashboard>
