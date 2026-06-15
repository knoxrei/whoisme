<x-layouts.app :title="$title">
    <style>
        .chat-fullscreen-active {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            max-width: none !important;
            z-index: 9999 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            border: none !important;
        }
    </style>

    <div class="w-full max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-mono text-gray-300">
        <!-- Non-JavaScript Legacy Fallback Notice -->
        <noscript>
            <meta http-equiv="refresh" content="15">
            <div class="bg-red-950/20 border border-red-900/30 text-red-400 p-3 mb-4 rounded-sm text-xs font-mono flex items-center justify-between">
                <div>
                    <strong>System Message:</strong> JavaScript is disabled. Running in legacy HTML mode. Page auto-refreshes every 15 seconds. Maximize/Fullscreen is disabled.
                </div>
                <a href="{{ route('chat.index') }}" class="px-2 py-1 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-[10px] uppercase font-black tracking-wider rounded-sm">Manual Refresh</a>
            </div>
        </noscript>

        <!-- Chat Container -->
        <div id="chat-container" class="bg-[#0a0a0a] border border-red-900/40 rounded-sm overflow-hidden flex flex-col md:flex-row h-[70vh] shadow-2xl transition-all duration-200">
            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col h-full border-r border-red-900/10">
                <!-- Header -->
                <div class="bg-[#111] px-4 py-3 border-b border-red-900/40 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xs font-black uppercase tracking-wider text-red-500">Global Chat Room</h1>
                    </div>
                    <!-- Connection Status & Fullscreen Actions -->
                    <div class="flex items-center gap-4">
                        <button id="fullscreen-btn" type="button" class="text-gray-500 hover:text-white transition-colors flex items-center gap-1.5 p-1 hover:bg-red-950/10 rounded-sm" title="Maximize Chat / Enlarge Window (Requires JavaScript)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                            <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:inline">Maximize Window</span>
                        </button>
                        <div class="text-[9px] font-bold flex items-center gap-1.5">
                            <span class="text-gray-600">STATUS:</span>
                            <span class="px-2 py-0.5 border border-green-700/40 bg-green-950/20 text-green-500 rounded-sm uppercase font-bold">ACTIVE</span>
                        </div>
                    </div>
                </div>

                <!-- Messages Container -->
                <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 scrollbar-thin scrollbar-thumb-red-900">
                    @if(isset($initialMessages) && count($initialMessages) > 0)
                        @foreach($initialMessages as $msg)
                            @php
                                $isMe = auth()->check() && $msg['user']['id'] === auth()->id();
                                $messageBg = $isMe ? 'bg-red-950/5 border border-red-900/10' : 'bg-[#0b0b0b]/60 border border-zinc-900';
                                $avatarSrc = str_starts_with($msg['user']['avatar_path'], 'http') ? $msg['user']['avatar_path'] : '/storage/'.$msg['user']['avatar_path'];
                                $timeStr = \Carbon\Carbon::parse($msg['created_at'])->timezone(config('app.timezone', 'UTC'))->format('H:i:s');
                            @endphp
                            <div class="p-2.5 rounded-sm flex items-start gap-2.5 {{ $messageBg }} transition-colors" data-id="{{ $msg['id'] }}">
                                <div class="w-6 h-6 rounded-sm overflow-hidden flex-shrink-0 border border-red-900/20 bg-[#111] flex items-center justify-center">
                                    <img src="{{ $avatarSrc }}" alt="" class="w-full h-full object-cover" onerror="this.src='/storage/avatars/default.png'; this.onerror=null;">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <div class="flex items-center gap-1.5 text-[10px]">
                                            <span style="color:{{ $msg['user']['role_color'] }}" class="font-bold">{{ $msg['user']['username'] }}</span>
                                            <span class="text-[7px] text-red-500 uppercase tracking-widest border border-red-900/30 px-1 py-0.2 rounded-sm bg-red-950/10">{{ $msg['user']['role_label'] }}</span>
                                        </div>
                                        <span class="text-[8px] text-gray-600 font-mono">{{ $timeStr }}</span>
                                    </div>
                                    <div class="text-xs text-gray-300 mt-1 font-mono break-all leading-relaxed">{{ $msg['message'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-10 text-xs text-gray-600 italic font-mono">Connecting to chat...</div>
                    @endif
                </div>

                <!-- Input area -->
                <div class="bg-[#0c0c0c] p-3 border-t border-red-900/20">
                    @auth
                        <form id="chat-form" action="{{ route('chat.send') }}" method="POST" class="flex gap-2" autocomplete="off">
                            @csrf
                            <input type="text" id="chat-input" name="message" placeholder="Type a message..." required
                                   class="flex-1 bg-[#050505] border border-red-900/20 rounded-sm px-3 py-2 text-xs text-gray-300 focus:outline-none focus:border-red-600 placeholder-gray-650 font-mono">
                            <button type="submit" class="px-4 py-2 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-xs font-black uppercase tracking-widest transition-all rounded-sm">
                                Send
                            </button>
                        </form>
                    @else
                        <div class="text-center py-2 text-xs text-gray-500 bg-[#050505] border border-red-900/10 rounded-sm">
                            <span>You are viewing in read-only mode. Please <a href="{{ route('login') }}" class="text-red-500 hover:underline font-bold">log in</a> to send messages.</span>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Sidebar / Active Members -->
            <div class="w-full md:w-56 bg-[#080808] flex flex-col h-1/3 md:h-full">
                <div class="bg-[#111] px-4 py-3 border-b border-red-900/40 flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-red-500">Active Members</span>
                    <span id="member-count" class="text-[10px] font-black text-gray-400 bg-red-950/20 border border-red-900/40 px-1.5 py-0.5 rounded-sm">{{ count($initialActiveUsers ?? []) }}</span>
                </div>
                <div id="user-list" class="flex-1 p-3 overflow-y-auto space-y-2.5 text-xs">
                    @if(isset($initialActiveUsers) && count($initialActiveUsers) > 0)
                        @foreach($initialActiveUsers as $u)
                            @php
                                $isMe = auth()->check() && $u['id'] === auth()->id();
                            @endphp
                            <div id="member-user-{{ $u['id'] }}" class="flex items-center gap-2 p-1.5 rounded-sm hover:bg-zinc-900/40 border border-transparent transition-colors font-mono">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                <span style="color:{{ $u['role_color'] }}" class="font-bold">{{ $u['username'] }}</span>
                                @if($isMe)
                                    <span class="text-[7px] text-gray-500 uppercase tracking-tighter ml-auto font-mono">SELF</span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6 text-[10px] text-gray-600 italic font-mono">No members detected.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Polling Javascript Engine -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const messagesContainer = document.getElementById("chat-messages");
            const userListContainer = document.getElementById("user-list");
            const memberCountEl = document.getElementById("member-count");
            const chatForm = document.getElementById("chat-form");
            const chatInput = document.getElementById("chat-input");
            const fullscreenBtn = document.getElementById("fullscreen-btn");
            const chatContainer = document.getElementById("chat-container");

            const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

            let messagesSet = new Set();

            // Populate messagesSet with existing elements to avoid duplicate fetches
            document.querySelectorAll('#chat-messages [data-id]').forEach(el => {
                const id = parseInt(el.getAttribute('data-id'));
                if (id) messagesSet.add(id);
            });

            // Scroll to bottom initially if messages exist
            if (messagesSet.size > 0) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Simple HTML sanitizer
            function sanitize(str) {
                return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
            }

            // Fullscreen Toggler
            if (fullscreenBtn && chatContainer) {
                fullscreenBtn.addEventListener("click", function () {
                    chatContainer.classList.toggle("chat-fullscreen-active");
                    const isActive = chatContainer.classList.contains("chat-fullscreen-active");
                    if (isActive) {
                        fullscreenBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14h6v6m10-6h-6v6M4 10h6V4m10 6h-6V4"></path></svg>
                            <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:inline">Maximize Window</span>
                        `;
                        document.body.style.overflow = "hidden";
                    } else {
                        fullscreenBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                            <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:inline">Maximize Window</span>
                        `;
                        document.body.style.overflow = "";
                    }
                });
            }

            // Append a message to the UI
            function appendMessage(msg, scroll = true) {
                if (messagesSet.has(msg.id)) return;
                messagesSet.add(msg.id);

                const date = new Date(msg.created_at);
                const timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                
                const styleUser = `<span style="color:${msg.user.role_color || '#ffffff'}" class="font-bold">${sanitize(msg.user.username)}</span>`;
                const tagRole = `<span class="text-[7px] text-red-500 uppercase tracking-widest border border-red-900/30 px-1 py-0.2 rounded-sm bg-red-950/10">${sanitize(msg.user.role_label)}</span>`;

                const isMe = msg.user.id === currentUserId;
                const messageBg = isMe ? 'bg-red-950/5 border border-red-900/10' : 'bg-[#0b0b0b]/60 border border-zinc-900';

                const avatarSrc = msg.user.avatar_path.startsWith('http') ? msg.user.avatar_path : `/storage/${msg.user.avatar_path}`;

                const html = `
                    <div class="p-2.5 rounded-sm flex items-start gap-2.5 ${messageBg} transition-colors" data-id="${msg.id}">
                        <div class="w-6 h-6 rounded-sm overflow-hidden flex-shrink-0 border border-red-900/20 bg-[#111] flex items-center justify-center">
                            <img src="${avatarSrc}" alt="" class="w-full h-full object-cover" onerror="this.src='/storage/avatars/default.png'; this.onerror=null;">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-1.5 text-[10px]">
                                    ${styleUser}
                                    ${tagRole}
                                </div>
                                <span class="text-[8px] text-gray-600 font-mono">${timeStr}</span>
                            </div>
                            <div class="text-xs text-gray-300 mt-1 font-mono break-all leading-relaxed">${sanitize(msg.message)}</div>
                        </div>
                    </div>
                `;

                // If no messages placeholder exists, clear it
                if (messagesContainer.innerHTML.includes("Connecting to chat...") || messagesContainer.innerHTML.includes("Initializing console logs...")) {
                    messagesContainer.innerHTML = "";
                }

                messagesContainer.insertAdjacentHTML('beforeend', html);
                if (scroll) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            // Fetch history from database initially
            async function fetchHistory() {
                try {
                    const res = await fetch("{{ route('chat.messages', [], false) }}");
                    if (res.ok) {
                        const data = await res.json();
                        if (data.length === 0 && messagesSet.size === 0) {
                            messagesContainer.innerHTML = '<div class="text-center py-10 text-xs text-gray-600 italic">No messages yet. Say hello!</div>';
                        } else {
                            data.forEach(msg => appendMessage(msg, false));
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    }
                } catch (e) {
                    console.error("Error fetching message logs:", e);
                }
            }

            // Fallback AJAX Polling implementation for messages
            async function pollMessages() {
                try {
                    const res = await fetch("{{ route('chat.messages', [], false) }}");
                    if (res.ok) {
                        const data = await res.json();
                        data.forEach(msg => appendMessage(msg, true));
                    }
                } catch (e) {
                    console.error("Polling error:", e);
                }
            }

            // Poll active users list
            async function pollActiveUsers() {
                try {
                    const res = await fetch("{{ route('chat.users', [], false) }}");
                    if (res.ok) {
                        const data = await res.json();
                        memberCountEl.textContent = data.length;
                        if (data.length === 0) {
                            userListContainer.innerHTML = '<div class="text-center py-6 text-[10px] text-gray-600 italic">No active members.</div>';
                            return;
                        }

                        userListContainer.innerHTML = "";
                        data.forEach(user => {
                            const elementId = `member-user-${user.id}`;
                            const tag = user.id === currentUserId ? '<span class="text-[7px] text-gray-500 uppercase tracking-tighter ml-auto font-mono">SELF</span>' : '';
                            const html = `
                                <div id="${elementId}" class="flex items-center gap-2 p-1.5 rounded-sm hover:bg-zinc-900/40 border border-transparent transition-colors font-mono">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    <span style="color:${user.role_color || '#ffffff'}" class="font-bold">${sanitize(user.username)}</span>
                                    ${tag}
                                </div>
                            `;
                            userListContainer.insertAdjacentHTML('beforeend', html);
                        });
                    }
                } catch (e) {
                    console.error("Error fetching active users:", e);
                }
            }

            // Sending message via AJAX
            if (chatForm && chatInput) {
                chatForm.addEventListener("submit", async function (e) {
                    e.preventDefault();
                    const msgText = chatInput.value.trim();
                    if (!msgText) return;

                    chatInput.value = "";

                    try {
                        const res = await fetch("{{ route('chat.send', [], false) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: msgText })
                        });

                        if (res.ok) {
                            const data = await res.json();
                            if (data.status === 'success') {
                                appendMessage(data.message, true);
                            }
                        }
                    } catch (err) {
                        console.error("Message send failure:", err);
                    }
                });
            }

            // Initialize chat history and begin polling loops
            fetchHistory().then(() => {
                // Poll new messages every 3 seconds
                setInterval(pollMessages, 3000);

                // Poll online users every 15 seconds
                pollActiveUsers();
                setInterval(pollActiveUsers, 15000);
            });
        });
    </script>
</x-layouts.app>
