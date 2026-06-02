<x-layouts.dashboard :title="$title" :role="$role">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="border border-red-900/40 bg-[#0a0a0a] p-6 rounded-sm">
            <h1 class="text-lg font-black text-white uppercase tracking-tight mb-2">Bulk Email Broadcast</h1>
            <p class="text-xs text-gray-500 font-mono leading-relaxed">
                Sends to users with an email address (verified or not). Each recipient has a
                <strong class="text-red-500">{{ $timeoutSeconds }}s</strong> timeout — invalid addresses or slow SMTP responses are skipped automatically.
            </p>
            <div class="mt-4 flex gap-4 text-[10px] font-mono">
                <span class="text-gray-400">With email: <strong class="text-white">{{ number_format($totalWithEmail) }}</strong></span>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400">Verified only: <strong class="text-white">{{ number_format($totalVerified) }}</strong></span>
            </div>
        </div>

        <form id="bulk-mail-form" class="border border-red-900/30 bg-[#050505] p-6 rounded-sm space-y-5">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Audience</label>
                <select name="verified_only" id="verified_only" class="w-full bg-black border border-red-900/30 rounded-sm px-4 py-2.5 text-xs text-gray-300">
                    <option value="0">All users with email (verified or not)</option>
                    <option value="1">Verified email only</option>
                </select>
            </div>
            <div class="space-y-2">
                <label for="subject" class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Subject</label>
                <input type="text" id="subject" name="subject" required maxlength="255"
                    class="w-full bg-black border border-red-900/30 rounded-sm px-4 py-2.5 text-xs text-white"
                    placeholder="Platform announcement">
            </div>
            <div class="space-y-2">
                <label for="message" class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Message</label>
                <textarea id="message" name="message" required rows="8" maxlength="10000"
                    class="w-full bg-black border border-red-900/30 rounded-sm px-4 py-3 text-xs text-gray-300 font-mono resize-y"
                    placeholder="Your message to all recipients..."></textarea>
            </div>
            <button type="submit" id="bulk-send-btn"
                class="w-full bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-widest py-3 rounded-sm transition-colors">
                Start Broadcast
            </button>
        </form>

        <div id="bulk-progress" class="hidden border border-red-900/30 bg-[#0a0a0a] p-6 rounded-sm">
            <div class="flex justify-between text-[10px] font-mono mb-3">
                <span class="text-gray-500">Progress</span>
                <span id="bulk-progress-label" class="text-red-500 font-black">0 / 0</span>
            </div>
            <div class="h-2 bg-black border border-red-900/20 rounded-sm overflow-hidden mb-4">
                <div id="bulk-progress-bar" class="h-full bg-red-600 transition-all duration-300" style="width: 0%"></div>
            </div>
            <div class="grid grid-cols-3 gap-3 text-center text-[10px] font-mono mb-4">
                <div class="p-3 border border-green-900/30 bg-green-950/10 rounded-sm">
                    <div class="text-green-500 font-black text-lg" id="stat-sent">0</div>
                    <div class="text-gray-600 uppercase tracking-widest mt-1">Sent</div>
                </div>
                <div class="p-3 border border-yellow-900/30 bg-yellow-950/10 rounded-sm">
                    <div class="text-yellow-500 font-black text-lg" id="stat-skipped">0</div>
                    <div class="text-gray-600 uppercase tracking-widest mt-1">Skipped</div>
                </div>
                <div class="p-3 border border-red-900/30 bg-red-950/10 rounded-sm">
                    <div class="text-red-500 font-black text-lg" id="stat-remaining">0</div>
                    <div class="text-gray-600 uppercase tracking-widest mt-1">Remaining</div>
                </div>
            </div>
            <div id="bulk-log" class="max-h-48 overflow-y-auto text-[10px] font-mono text-gray-500 space-y-1"></div>
        </div>
    </div>

    <script>
    (function () {
        const form = document.getElementById('bulk-mail-form');
        const progressBox = document.getElementById('bulk-progress');
        const sendBtn = document.getElementById('bulk-send-btn');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const BATCH_SIZE = 5;

        const recipientsUrl = @json(route('dashboard.bulk-mail.recipients'));
        const sendUrl = @json(route('dashboard.bulk-mail.send'));

        let sent = 0, skipped = 0;

        function logLine(text, ok) {
            const el = document.createElement('div');
            el.className = ok ? 'text-green-500/80' : 'text-yellow-500/80';
            el.textContent = text;
            document.getElementById('bulk-log').prepend(el);
        }

        function updateUI(processed, total) {
            const pct = total > 0 ? Math.round((processed / total) * 100) : 0;
            document.getElementById('bulk-progress-bar').style.width = pct + '%';
            document.getElementById('bulk-progress-label').textContent = processed + ' / ' + total;
            document.getElementById('stat-sent').textContent = sent;
            document.getElementById('stat-skipped').textContent = skipped;
            document.getElementById('stat-remaining').textContent = Math.max(0, total - processed);
        }

        async function fetchRecipients(verifiedOnly) {
            const res = await fetch(recipientsUrl + '?verified_only=' + (verifiedOnly ? '1' : '0'), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!res.ok) throw new Error('Failed to load recipients');
            return res.json();
        }

        async function sendBatch(userIds, subject, message) {
            const res = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ user_ids: userIds, subject, message }),
            });
            if (!res.ok) throw new Error('Batch send failed');
            return res.json();
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();
            const verifiedOnly = document.getElementById('verified_only').value === '1';

            if (!subject || !message) return;

            sendBtn.disabled = true;
            sendBtn.textContent = 'Broadcasting...';
            progressBox.classList.remove('hidden');
            document.getElementById('bulk-log').innerHTML = '';
            sent = 0;
            skipped = 0;

            try {
                const { user_ids: allIds, total } = await fetchRecipients(verifiedOnly);
                if (total === 0) {
                    logLine('No recipients found.', false);
                    return;
                }

                updateUI(0, total);

                for (let i = 0; i < allIds.length; i += BATCH_SIZE) {
                    const chunk = allIds.slice(i, i + BATCH_SIZE);
                    const result = await sendBatch(chunk, subject, message);
                    sent += result.sent;
                    skipped += result.skipped;

                    (result.results || []).forEach(function (row) {
                        const label = row.username + ' <' + row.email + '>';
                        if (row.status === 'sent') {
                            logLine('✓ ' + label, true);
                        } else {
                            logLine('⊘ ' + label + ' (' + (row.reason || 'skipped') + ')', false);
                        }
                    });

                    updateUI(Math.min(i + BATCH_SIZE, total), total);
                }

                logLine('Broadcast complete.', true);
            } catch (err) {
                logLine('Error: ' + err.message, false);
            } finally {
                sendBtn.disabled = false;
                sendBtn.textContent = 'Start Broadcast';
            }
        });
    })();
    </script>
</x-layouts.dashboard>
