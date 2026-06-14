<x-layouts.app :title="$title">
    <div class="w-full max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-mono text-gray-300">
        <!-- Chat Container -->
        <div class="bg-[#0a0a0a] border border-red-900/40 rounded-sm overflow-hidden flex flex-col md:flex-row h-[70vh] shadow-2xl">
            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col h-full border-r border-red-900/10">
                <!-- Header -->
                <div class="bg-[#111] px-4 py-3 border-b border-red-900/40 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-600 animate-pulse"></span>
                        <h1 class="text-xs font-black uppercase tracking-wider text-red-500">SYSTEM_CHAT_ROOM_v1.0</h1>
                    </div>
                    <!-- Connection Status Badges -->
                    <div class="text-[9px] font-bold flex items-center gap-1.5">
                        <span class="text-gray-600">STATUS:</span>
                        <span id="conn-status" class="px-2 py-0.5 border border-yellow-700/40 bg-yellow-950/20 text-yellow-500 rounded-sm uppercase">CONNECTING...</span>
                    </div>
                </div>

                <!-- Messages Container -->
                <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 scrollbar-thin scrollbar-thumb-red-900">
                    <div class="text-center py-10 text-xs text-gray-600 italic">Initializing console logs...</div>
                </div>

                <!-- Typing Indicator -->
                <div id="typing-indicator" class="px-4 py-1.5 text-[10px] text-gray-500 italic h-6 bg-[#070707] border-t border-red-900/5"></div>

                <!-- Input area -->
                <div class="bg-[#0c0c0c] p-3 border-t border-red-900/20">
                    @auth
                        <form id="chat-form" class="flex gap-2" autocomplete="off">
                            <input type="text" id="chat-input" placeholder="Type a secure transmission message..." required
                                   class="flex-1 bg-[#050505] border border-red-900/20 rounded-sm px-3 py-2 text-xs text-gray-300 focus:outline-none focus:border-red-600 placeholder-gray-650 font-mono">
                            <button type="submit" class="px-4 py-2 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-xs font-black uppercase tracking-widest transition-all rounded-sm">
                                Transmit
                            </button>
                        </form>
                    @else
                        <div class="text-center py-2 text-xs text-gray-500 bg-[#050505] border border-red-900/10 rounded-sm">
                            <span>You are viewing in read-only mode. Please <a href="{{ route('login') }}" class="text-red-500 hover:underline font-bold">log in</a> to transmit messages.</span>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Sidebar / Active Users -->
            <div class="w-full md:w-56 bg-[#080808] flex flex-col h-1/3 md:h-full">
                <div class="bg-[#111] px-4 py-3 border-b border-red-900/40 flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-red-500">Active Nodes</span>
                    <span id="node-count" class="text-[10px] font-black text-gray-400 bg-red-950/20 border border-red-900/40 px-1.5 py-0.5 rounded-sm">0</span>
                </div>
                <div id="user-list" class="flex-1 p-3 overflow-y-auto space-y-2.5 text-xs">
                    <div class="text-center py-6 text-[10px] text-gray-600 italic">No nodes detected.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hybrid Realtime / Polling Javascript Engine -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const messagesContainer = document.getElementById("chat-messages");
            const userListContainer = document.getElementById("user-list");
            const nodeCountEl = document.getElementById("node-count");
            const connStatusEl = document.getElementById("conn-status");
            const typingIndicator = document.getElementById("typing-indicator");
            const chatForm = document.getElementById("chat-form");
            const chatInput = document.getElementById("chat-input");

            const currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
            const currentUsername = {!! auth()->check() ? json_encode(auth()->user()->username) : 'null' !!};
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

            let messagesSet = new Set();
            let isPolling = false;
            let pollingTimer = null;
            let wsConnectionTimeout = null;
            let typingTimeout = null;

            // Simple HTML sanitizer
            function sanitize(str) {
                return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
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
                if (messagesContainer.innerHTML.includes("Initializing console logs...")) {
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
                    const res = await fetch("{{ route('chat.messages') }}");
                    if (res.ok) {
                        const data = await res.json();
                        if (data.length === 0) {
                            messagesContainer.innerHTML = '<div class="text-center py-10 text-xs text-gray-600 italic">Transmission vault is empty. Be the first to broadcast.</div>';
                        } else {
                            data.forEach(msg => appendMessage(msg, false));
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    }
                } catch (e) {
                    console.error("Error fetching message logs:", e);
                }
            }

            // Fallback AJAX Polling implementation
            async function pollMessages() {
                try {
                    const res = await fetch("{{ route('chat.messages') }}");
                    if (res.ok) {
                        const data = await res.json();
                        data.forEach(msg => appendMessage(msg, true));
                    }
                } catch (e) {
                    console.error("Polling error:", e);
                }
            }

            function startPollingFallback() {
                if (isPolling) return;
                isPolling = true;
                if (!currentUserId) {
                    connStatusEl.textContent = "POLLING (GUEST)";
                    connStatusEl.className = "px-2 py-0.5 border border-zinc-800 bg-zinc-950/20 text-zinc-550 rounded-sm uppercase";
                } else {
                    connStatusEl.textContent = "POLLING FALLBACK (TOR SAFE)";
                    connStatusEl.className = "px-2 py-0.5 border border-zinc-700 bg-zinc-950/20 text-zinc-500 rounded-sm uppercase";
                }
                
                // Clear any Echo instances or sockets if they exist
                if (window.Echo) {
                    try {
                        window.Echo.disconnect();
                    } catch (e) {}
                }

                // Poll immediately, then every 3 seconds
                pollMessages();
                pollingTimer = setInterval(pollMessages, 3000);

                // Setup local placeholder for online users in polling mode (just show self)
                updatePollingNodeList();
            }

            function updatePollingNodeList() {
                if (currentUserId) {
                    nodeCountEl.textContent = "1";
                    userListContainer.innerHTML = `
                        <div class="flex items-center gap-2 p-1.5 rounded-sm bg-red-950/5 border border-red-900/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            <span class="font-bold text-white">${sanitize(currentUsername)}</span>
                            <span class="text-[8px] text-gray-500 uppercase tracking-tighter ml-auto">SELF</span>
                        </div>
                        <div class="text-center text-[8px] text-gray-600 italic pt-2">Tor mode: hidden nodes not visible.</div>
                    `;
                } else {
                    nodeCountEl.textContent = "0";
                    userListContainer.innerHTML = `
                        <div class="text-center text-[8px] text-gray-600 italic pt-2">Log in to view active nodes.</div>
                    `;
                }
            }

            // Broadcast websocket engine using Laravel Echo
            function tryWebSocketConnection() {
                // If Laravel Echo library is not loaded or user is a guest, fail fast to polling
                if (typeof window.Echo === 'undefined' || !currentUserId) {
                    startPollingFallback();
                    return;
                }

                // Setup connection timeout: if Echo doesn't connect in 5 seconds, drop to polling
                wsConnectionTimeout = setTimeout(() => {
                    if (connStatusEl.textContent === "CONNECTING...") {
                        console.warn("WebSocket connection timed out. Falling back to AJAX polling.");
                        startPollingFallback();
                    }
                }, 5000);

                try {
                    // Reverb presence channel join
                    window.Echo.join('chat')
                        .here((users) => {
                            clearTimeout(wsConnectionTimeout);
                            connStatusEl.textContent = "REALTIME (WS)";
                            connStatusEl.className = "px-2 py-0.5 border border-green-700/40 bg-green-950/20 text-green-500 rounded-sm uppercase";
                            isPolling = false;
                            if (pollingTimer) clearInterval(pollingTimer);

                            // Populate node list
                            nodeCountEl.textContent = users.length;
                            userListContainer.innerHTML = "";
                            users.forEach(user => addNodeToList(user));
                        })
                        .joining((user) => {
                            addNodeToList(user);
                            nodeCountEl.textContent = parseInt(nodeCountEl.textContent) + 1;
                        })
                        .leaving((user) => {
                            removeNodeFromList(user);
                            nodeCountEl.textContent = Math.max(0, parseInt(nodeCountEl.textContent) - 1);
                        })
                        .listen('MessageSent', (e) => {
                            appendMessage(e, true);
                        })
                        .listenForWhisper('typing', (e) => {
                            showTyping(e.username);
                        });
                } catch (e) {
                    console.error("Echo connection error. Falling back to polling:", e);
                    clearTimeout(wsConnectionTimeout);
                    startPollingFallback();
                }
            }

            // DOM active node list adjustments
            function addNodeToList(user) {
                const elementId = `node-user-${user.id}`;
                if (document.getElementById(elementId)) return;

                const tag = user.id === currentUserId ? '<span class="text-[7px] text-gray-500 uppercase tracking-tighter ml-auto">SELF</span>' : '';
                const html = `
                    <div id="${elementId}" class="flex items-center gap-2 p-1.5 rounded-sm hover:bg-zinc-900/40 border border-transparent transition-colors">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        <span style="color:${user.role_color || '#ffffff'}" class="font-bold">${sanitize(user.username)}</span>
                        ${tag}
                    </div>
                `;
                userListContainer.insertAdjacentHTML('beforeend', html);
            }

            function removeNodeFromList(user) {
                const element = document.getElementById(`node-user-${user.id}`);
                if (element) element.remove();
            }

            // Typing whispers handler
            function showTyping(username) {
                typingIndicator.textContent = `${sanitize(username)} is composing terminal log...`;
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(() => {
                    typingIndicator.textContent = "";
                }, 3000);
            }

            // Sending message via AJAX
            if (chatForm && chatInput) {
                chatForm.addEventListener("submit", async function (e) {
                    e.preventDefault();
                    const msgText = chatInput.value.trim();
                    if (!msgText) return;

                    chatInput.value = "";

                    try {
                        const res = await fetch("{{ route('chat.send') }}", {
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

                // Trigger typing whisper in WebSocket mode
                chatInput.addEventListener("input", function () {
                    if (window.Echo && !isPolling) {
                        window.Echo.join('chat').whisper('typing', {
                            username: currentUsername
                        });
                    }
                });
            }

            // Initialize chat
            fetchHistory().then(() => {
                tryWebSocketConnection();
            });
        });
    </script>
</x-layouts.app>
