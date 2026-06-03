<x-layouts.app 
    :title="$title" 
    :description="$pastebin->description" 
    :ogImage="$pastebin->cover_path && $pastebin->cover_path !== 'defaultCover.png' ? asset('storage/' . $pastebin->cover_path) : ($pastebin->images->count() > 0 ? asset('storage/' . $pastebin->images->first()->image_path) : null)"
>
    <div class="min-h-screen text-gray-300 font-sans">
        <div class="max-w-[1400px] mx-auto px-2 py-4 md:py-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-950/20 border border-green-900/30 text-green-500 text-xs font-mono font-bold rounded-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('reputation_awarded'))
                <div class="mb-4 p-3 bg-yellow-950/20 border border-yellow-700/40 text-yellow-400 text-xs font-mono font-bold rounded-sm flex items-center gap-2">
                    <span class="text-yellow-500 text-base">⭐</span>
                    {{ session('reputation_awarded') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-4 p-4 bg-blue-950/20 border border-blue-900/30 text-blue-400 text-xs font-mono font-bold rounded-sm">
                    {{ session('info') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-950/20 border border-red-900/30 text-red-500 text-xs font-mono font-bold rounded-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="bg-[#0a0a0a] border border-red-900/40 px-5 py-4 mb-2 flex items-center justify-between rounded-sm">
                <div class="flex items-center gap-3">
                    {!! $pastebin->visibility->badge() !!}
                    <h1 class="text-white font-black text-lg md:text-xl tracking-tight  ">
                        {{ $pastebin->title }}
                    </h1>
                </div>
                <div class="text-[10px] md:text-xs text-gray-500 font-bold tracking-widest">
                    by 
                    <span style="color: {{ $pastebin->user ? $pastebin->user->identification->role->color() : '#888' }}">
                        {{ $pastebin->author_name }}
                    </span> 
                    <span class="mx-2 text-red-900/40">|</span> 
                    {{ $pastebin->created_at->format('d-m-Y, H:i A') }}
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-2 ">
                
                <aside class="w-full md:w-64 flex-shrink-0 bg-[#0a0a0a] border border-red-900/30 rounded-sm">
                    <div class="sticky top-4 md:top-20 p-5 text-center">
                        <div class="mb-4">
                            <a href="{{ $pastebin->user ? route('profile.show', $pastebin->user->username) : '#' }}" class="text-white font-black text-lg hover:text-red-500 tracking-tighter block">
                                @if($pastebin->user)
                                    {!! $pastebin->user->identification->role->userStyleWithBanner($pastebin->author_name, $pastebin->user->identification->color_username ?? '#ffffff') !!}
                                @else
                                    {{ $pastebin->author_name }}
                                @endif
                            </a>
                        </div>

                    <div class="mb-5 flex justify-center">
                        <div class="w-32 h-32 overflow-hidden ">
                            @if($pastebin->user && $pastebin->user->identification->avatar_path)
                                <img src="{{ asset('storage/' . $pastebin->user->identification->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                            @else
                                <img src="{{ asset('storage/avatars/default.png') }}" class="w-full h-full object-cover" alt="avatar">
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 space-y-2">
                        @if($pastebin->user)
                            <div class="border border-red-900/20 bg-[#050505] py-1 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">
                                DoxMe Members
                            </div>
                            <div class="border border-red-600 bg-red-600/10 py-1.5 px-4 rounded-sm">
                                <span class="text-red-500 font-black text-[10px] uppercase tracking-[0.2em]">
                                    {{ $pastebin->user->identification->role->label() }}
                                </span>
                            </div>
                        @else
                            <div class="border border-gray-800 bg-gray-900/50 py-1.5 px-4 rounded-sm">
                                <span class="text-gray-500 font-black text-[10px] uppercase tracking-[0.2em]">GUEST</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-[10px] space-y-2 text-left px-1 border-t border-red-900/10 pt-5">
                        @if($pastebin->user)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-bold uppercase tracking-tighter">Posts:</span>
                                <span class="text-white font-black">{{ $pastebin->user->pastebins()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-bold uppercase tracking-tighter">Followers:</span>
                                <span class="text-white font-black">{{ $pastebin->user->followers()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-bold uppercase tracking-tighter">Joined:</span>
                                <span class="text-white font-mono">{{ $pastebin->user->created_at->format('M Y') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-bold uppercase tracking-tighter">Views:</span>
                            <span class="text-red-500 font-black">{{ number_format($pastebin->views_count) }}</span>
                        </div>
                    </div>

                    <div class="mt-5 border-t border-neutral-800 pt-5">
                        <div class="text-xs text-neutral-500 mb-2">
                            On this paste (<span id="visitor-count" class="text-neutral-300 tabular-nums">{{ $visitorCount ?? count($visitors) }}</span>)
                        </div>
                        <div id="visitor-list" class="text-[10px] text-gray-400 font-mono leading-relaxed break-words">
                            @if(count($visitors) > 0)
                                @php
                                    $visitorLabels = collect($visitors)->map(function($visitor) {
                                        if ($visitor['type'] === 'member') {
                                            $role = \App\Enum\Role::from($visitor['role']);
                                            return $role->userStyle('@' . $visitor['name']);
                                        }
                                        return '<span class="text-gray-500">' . e($visitor['name']) . '</span>';
                                    });
                                @endphp
                                {!! $visitorLabels->implode(', ') !!}
                            @else
                                <span class="text-gray-700 italic">No active visitors</span>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>

                <main class="flex-1 flex flex-col gap-2">
                    <div class="bg-[#050505] border border-red-900/30 rounded-sm flex flex-col min-h-[600px]">
                        <div class="bg-[#111] px-5 py-2.5 border-b border-red-900/30 flex justify-between items-center text-[10px] text-gray-500 font-mono">
                            {{ $pastebin->created_at->format('d-m-Y, H:i A') }}
                        </div>

                        <div class="p-8 flex-1">
                            
                            @if(isset($isBurned) && $isBurned)
                                <div class="mb-8 p-4 bg-red-950/20 border-2 border-dashed border-red-600 rounded-sm text-red-500 font-mono text-xs flex items-start gap-3">
                                    <div class="flex-shrink-0 text-lg">⚠️</div>
                                    <div class="space-y-1">
                                        <h4 class="font-black tracking-widest uppercase text-red-600">MAIN CLEARANCE EXPIRED: BURN AFTER READING</h4>
                                        <p class="text-gray-400 text-[10px] leading-relaxed">
                                            This pastebin was flagged for zero-trace self-destruction. The transaction records, cover pictures, gallery attachments, and thread comments have been completely wiped from the terminal databases. **This page is your only window to copy its contents.** Refreshing or exiting will terminate your connection to this data.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($pastebin->cover_path && $pastebin->cover_path !== 'defaultCover.png')
                                <div class="mb-8 border border-red-900/20 bg-[#050505] p-1 rounded-sm overflow-hidden">
                                    <img src="{{ asset('storage/' . $pastebin->cover_path) }}" class="w-full max-h-[400px] object-cover" alt="cover">
                                </div>
                            @endif

                            <div class="mb-8" id="content-section-container">
                                <button id="minimize-fixed-btn" onclick="toggleMaximizeContent()" class="hidden fixed top-4 right-4 z-[1001] bg-[#111] border border-red-900/30 p-2.5 rounded-sm hover:border-red-600 text-gray-400 hover:text-white transition-colors shadow-2xl" title="Minimize">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4v4m0 0H4m4 0l-5-5m11 1V4m0 0h4m-4 0l5-5M8 20v-4m0 0H4m4 0l5 5m11-1v4m0-4h4m-4 0l5 5"/></svg>
                                </button>

                                <style>
                                    .markdown-body h1 { font-size: 1.5rem; font-weight: 900; color: #fff; margin-bottom: 1rem; margin-top: 1.5rem; text-transform: uppercase; }
                                    .markdown-body h2 { font-size: 1.25rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem; margin-top: 1.5rem; text-transform: uppercase; }
                                    .markdown-body h3 { font-size: 1.125rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem; margin-top: 1.25rem; }
                                    .markdown-body p { margin-bottom: 1rem; }
                                    .markdown-body a { color: #ef4444; text-decoration: underline; }
                                    .markdown-body a:hover { color: #dc2626; }
                                    .markdown-body ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
                                    .markdown-body ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
                                    .markdown-body li { margin-bottom: 0.25rem; }
                                    .markdown-body blockquote { border-left: 2px solid rgba(153, 27, 27, 0.5); padding-left: 1rem; color: #9ca3af; font-style: italic; background-color: rgba(0,0,0,0.2); padding-top: 0.5rem; padding-bottom: 0.5rem; margin-bottom: 1rem; }
                                    .markdown-body code { font-family: monospace; background-color: rgba(255,255,255,0.1); padding: 0.1rem 0.3rem; border-radius: 0.125rem; font-size: 0.9em; }
                                    .markdown-body pre { background-color: #000; padding: 1rem; overflow-x: auto; border: 1px solid rgba(153, 27, 27, 0.2); border-radius: 0.25rem; margin-bottom: 1rem; }
                                    .markdown-body pre code { background-color: transparent; padding: 0; }
                                    .markdown-body hr { border-color: rgba(153, 27, 27, 0.2); margin-top: 2rem; margin-bottom: 2rem; }
                                    .markdown-body table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
                                    .markdown-body th, .markdown-body td { border: 1px solid rgba(153, 27, 27, 0.3); padding: 0.5rem; text-align: left; }
                                    .markdown-body th { background-color: rgba(153, 27, 27, 0.1); font-weight: bold; }

                                    
                                    #pastebin-content-wrapper {
                                        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                                        overflow: hidden;
                                    }
                                    #pastebin-content-wrapper.collapsed {
                                        max-height: 800px;
                                    }
                                    #pastebin-content-wrapper.expanded {
                                        max-height: none;
                                    }

                                    
                                    #content-section-container.maximized {
                                        position: fixed;
                                        top: 0;
                                        left: 0;
                                        width: 100vw;
                                        height: 100vh;
                                        z-index: 1000;
                                        background-color: #050505;
                                        padding: 3rem;
                                        overflow-y: auto;
                                    }
                                    #content-section-container.maximized #pastebin-content-wrapper {
                                        max-height: none !important;
                                        padding: 0;
                                        max-width: 1200px;
                                        margin: 0 auto;
                                    }
                                    #content-section-container.maximized #view-full-btn-container {
                                        position: sticky;
                                        bottom: 0;
                                        background: #050505;
                                        padding-top: 1.5rem;
                                        padding-bottom: 1.5rem;
                                        margin-top: 2rem;
                                        border-top: 1px solid rgba(153, 27, 27, 0.2);
                                        z-index: 10;
                                    }
                                </style>
                                <div id="pastebin-content-wrapper" class="collapsed markdown-body text-gray-300 p-6 font-mono text-xs overflow-x-auto leading-relaxed scrollbar-thin scrollbar-thumb-red-900 scrollbar-track-transparent">
                                    {!! $contentMarkdown !!}
                                </div>
                                <div id="view-full-btn-container" class="border-t border-red-900/10 bg-gradient-to-t from-[#050505] to-transparent -mt-16 pt-12 pb-3 flex justify-center gap-3 relative">
                                    <button id="view-full-btn" onclick="toggleViewFull()" class="flex items-center gap-2 bg-[#0a0a0a] border border-red-900/30 hover:border-red-600 text-[9px] font-black uppercase tracking-[0.2em] text-red-500 hover:text-white px-5 py-2 rounded-sm transition-all duration-200 active:scale-95">
                                        <svg id="view-full-icon" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                        <span id="view-full-text">View Full Content</span>
                                    </button>
                                    <button id="maximize-btn" onclick="toggleMaximizeContent()" class="flex items-center gap-2 bg-[#0a0a0a] border border-red-900/30 hover:border-red-600 text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-white px-5 py-2 rounded-sm transition-all duration-200 active:scale-95">
                                        <svg id="maximize-icon" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                        <span id="maximize-text">Maximize</span>
                                    </button>
                                </div>
                            </div>

                            @if($pastebin->images && $pastebin->images->count() > 0)
                            <div class="mt-12 pt-8 border-t border-red-900/10">
                                <div class="text-[10px] font-black text-red-500 uppercase mb-5 tracking-[0.2em] flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    Attached Evidence Gallery ({{ $pastebin->images->count() }})
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($pastebin->images as $image)
                                        <div class="aspect-square border border-red-900/20 p-1.5 group">
                                            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover " alt="evidence">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <footer class="bg-[#111] border-t border-red-900/30 px-6 py-3 flex items-center justify-between rounded-b-sm mt-auto">
                            
                            <div class="flex gap-3">
                                 @auth
                                     @can('delete', $pastebin)
                                         <form id="delete-paste-form" action="{{ route('pastebin.destroy', $pastebin) }}" method="POST" class="inline">
                                             @csrf
                                             @method('DELETE')
                                             <button type="button" onclick="confirmDelete()" class="bg-red-950/20 border border-red-900/40 text-[9px] font-black px-4 py-1.5 text-red-500 hover:bg-red-600 hover:text-white uppercase tracking-widest transition-colors duration-150">
                                                 Delete
                                             </button>
                                         </form>
                                     @endcan
                                     @can('update', $pastebin)
                                         <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" class="bg-red-600/10 border border-red-600/30 text-[9px] font-black px-5 py-1.5 text-red-500 hover:bg-red-600 hover:text-white uppercase tracking-widest">Edit Thread</button>
                                     @else
                                         <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" class="bg-red-600/10 border border-red-600/30 text-[9px] font-black px-5 py-1.5 text-red-500 hover:bg-red-600 hover:text-white uppercase tracking-widest">Suggest Edit</button>
                                     @endcan
                                 @endauth
                                <button onclick="document.getElementById('report-modal').classList.remove('hidden')" class="bg-[#0a0a0a] border border-red-900/20 text-[9px] font-black px-4 py-1.5 text-gray-500 hover:text-white hover:border-white/20 uppercase tracking-widest">Report</button>
                                @guest
                                <a href="{{ route('login') }}" class="bg-[#0a0a0a] border border-red-900/20 text-[9px] font-black px-4 py-1.5 text-gray-500 hover:text-white hover:border-white/20 uppercase tracking-widest">Edit/Suggest</a>
                                @endguest
                            </div>

                            <div class="flex gap-3">
                                <button onclick="openShareModal()" class="bg-[#0a0a0a] border border-red-900/20 text-[9px] font-black px-4 py-1.5 text-gray-500 hover:text-white hover:border-white/20 uppercase tracking-widest transition-colors duration-150">
                                    Share
                                </button>
                                <a href="{{ route('pastebin.raw', $pastebin->slug) }}" target="_blank" class="bg-[#0a0a0a] border border-red-900/20 text-[9px] font-black px-4 py-1.5 text-gray-500 hover:text-white hover:border-white/20 uppercase tracking-widest transition-colors duration-150">
                                    Raw View
                                </a>
                                <a href="{{ route('pastebin.download', $pastebin->slug) }}" class="bg-red-950/20 border border-red-900/40 text-[9px] font-black px-4 py-1.5 text-red-500 hover:bg-red-600 hover:text-white uppercase tracking-widest transition-colors duration-150">
                                    Download
                                </a>
                            </div>
                        </footer>
                    </div>

                    <x-internal-ads />

                    <div class="bg-[#0a0a0a] border border-red-900/30 p-6 rounded-sm flex flex-col gap-6">
                        <h3 class="text-xs font-black text-red-500 uppercase tracking-[0.2em] flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            Discussion (Top 5 Recent)
                        </h3>

                        <style>
                            .comment-content blockquote {
                                border-left: 2px solid rgba(153, 27, 27, 0.5);
                                padding-left: 0.75rem;
                                margin-top: 0.5rem;
                                margin-bottom: 0.5rem;
                                font-style: italic;
                                color: #6b7280;
                                background-color: rgba(0,0,0,0.2);
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                            .comment-content p {
                                margin-bottom: 0.5rem;
                            }
                            .comment-content p:last-child {
                                margin-bottom: 0;
                            }
                        </style>

                        @if(isset($comments) && count($comments) > 0)
                            <div class="space-y-4">
                                @foreach($comments as $comment)
                                    <div class="border border-red-900/20 p-4 rounded-sm flex gap-4">
                                        <div class="w-8 h-8 overflow-hidden flex-shrink-0">
                                            @if($comment->user->identification->avatar_path)
                                                <img src="{{ asset('storage/' . $comment->user->identification->avatar_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs font-bold text-gray-600">
                                                    {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-2">
                                                <a href="{{ route('profile.show', $comment->user->username) }}" class="text-[11px] font-black tracking-tighter">
                                                    {!! $comment->user->identification->role->userStyleWithBanner($comment->user->username, $comment->user->identification->custom_color ?? '#ffffff') !!}
                                                </a>
                                                <div class="flex items-center gap-3">
                                                    <div class="text-[9px] text-gray-600 font-mono">{{ $comment->created_at->diffForHumans() }}</div>
                                                    @auth
                                                        <button type="button" onclick="replyToComment('{{ $comment->user->username }}', {{ json_encode($comment->content) }})" class="text-[9px] text-gray-500 hover:text-red-500 font-black uppercase tracking-widest transition-colors">Reply</button>
                                                    @endauth
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-400 font-mono leading-relaxed comment-content">
                                                {!! \Illuminate\Support\Str::markdown($comment->content) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-gray-600 font-mono italic p-4 text-center border border-red-900/10 bg-[#050505]">
                                No comments yet. Be the first to start the discussion.
                            </div>
                        @endif

                        @auth
                            <form action="{{ route('pastebin.comments.store', $pastebin) }}" method="POST" class="mt-4">
                                @csrf
                                <div class="flex flex-col gap-3">
                                    <textarea id="comment-textarea" name="content" rows="3" placeholder="Add a comment..." required class="w-full bg-[#050505] border border-red-900/20 rounded-sm px-4 py-3 text-xs font-mono text-gray-300 focus:outline-none focus:border-red-600 resize-none"></textarea>
                                    @error('content') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-red-600/10 border border-red-600/30 hover:bg-red-600 hover:text-white text-red-500 px-6 py-2 rounded-sm font-black text-[10px] uppercase tracking-[0.2em] transition-colors">Post Comment</button>
                                    </div>
                                </div>
                            </form>
                            <script>
                                function replyToComment(username, text) {
                                    const textarea = document.getElementById('comment-textarea');
                                    const quotedText = text.split('\n').map(line => '> ' + line).join('\n');
                                    const replyFormat = `> **@${username}** said:\n${quotedText}\n\n`;

                                    textarea.value = textarea.value ? textarea.value + '\n' + replyFormat : replyFormat;
                                    textarea.focus();
                                    textarea.scrollIntoView({behavior: 'smooth', block: 'center'});
                                }
</script>
                        @else
                            <div class="mt-4 text-xs text-gray-500 font-mono text-center p-3 border border-red-900/20 bg-[#050505]">
                                <a href="{{ route('login') }}" class="text-red-500 hover:underline">Log in</a> to post a comment.
                            </div>
                        @endauth
                    </div>

                    @if(auth()->check() && (auth()->id() === $pastebin->user_id || auth()->user()->canUsePremiumFeatures()))
                        @if(isset($pendingEdits) && count($pendingEdits) > 0)
                            <div class="bg-[#0a0a0a] border border-red-600/30 p-6 rounded-sm">
                                <h3 class="text-xs font-black text-red-500 uppercase mb-5 tracking-[0.2em] flex items-center gap-3">
                                    <span class="w-2 h-2 bg-red-600 rounded-full"></span>
                                    Pending Improvements ({{ count($pendingEdits) }})
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($pendingEdits as $edit)
                                        <div class="bg-[#050505] border border-red-900/20 p-4 flex flex-col gap-4 rounded-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-red-600/10 border border-red-900/20 flex items-center justify-center text-red-500 font-black text-[10px]">
                                                    {{ substr($edit->user->username, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-xs font-black text-white uppercase tracking-tighter">{{ $edit->user->username }}</div>
                                                    <div class="text-[9px] text-gray-600 font-mono">{{ $edit->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                            <div class="text-[11px] text-gray-400 italic bg-black/50 p-3 border-l border-red-600">
                                                "{{ $edit->title }}"
                                            </div>
                                            <div class="flex gap-2">
                                                <form action="{{ route('pastebin.edit.approve', $edit) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button class="w-full bg-green-600/10 border border-green-600/30 text-green-500 py-2 text-[9px] font-black uppercase tracking-widest hover:bg-green-600 hover:text-white">Approve</button>
                                                </form>
                                                <form action="{{ route('pastebin.edit.reject', $edit) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button class="w-full bg-red-600/10 border border-red-600/30 text-red-500 py-2 text-[9px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </main>
            </div>
        </div>
    </div>

    <div id="edit-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/95"></div>
        <div class="relative bg-[#0a0a0a] border border-red-900/40 w-full max-w-4xl rounded-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-red-900/40 bg-[#111]">
                <h2 class="text-sm font-black text-red-500 uppercase tracking-[0.2em]">
                    @can('update', $pastebin) Edit Thread @else Suggest Improvement @endcan
                </h2>
                <button onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-gray-500 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="@can('update', $pastebin) {{ route('pastebin.update', $pastebin) }} @else {{ route('pastebin.edit.store', $pastebin) }} @endcan" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                @can('update', $pastebin) @method('PUT') @endcan
                
                @can('update', $pastebin)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Title</label>
                        <input type="text" name="title" value="{{ $pastebin->title }}" class="w-full bg-[#050505] border border-red-900/20 px-4 py-3 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Description <span class="text-[8px] font-normal text-gray-500 normal-case">(Optional - Helps in finding it on search engines)</span></label>
                        <input type="text" name="description" value="{{ $pastebin->description }}" class="w-full bg-[#050505] border border-red-900/20 px-4 py-3 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                    <div class="space-y-2">
                        <label for="cover_path" class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Cover Image <span class="text-[8px] font-normal text-red-500 normal-case">(Optional - New cover replaces old)</span></label>
                        <input type="file" name="cover_path" id="cover_path" class="w-full bg-[#050505] border border-red-900/20 px-4 py-2 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">
                    </div>
                    <div class="space-y-2">
                        <label for="edit_image" class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Gallery Images (Max 5) <span class="text-[8px] font-normal text-red-500 normal-case">(Optional - Appends to gallery)</span></label>
                        <input type="file" name="image[]" id="edit_image" multiple class="w-full bg-[#050505] border border-red-900/20 px-4 py-2 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-gray-700 cursor-pointer">
                    </div>
                </div>

                @if($pastebin->images && $pastebin->images->count() > 0)
                <div class="space-y-2 mt-6">
                    <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Manage Existing Gallery Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-4 border border-red-900/20 bg-[#050505] rounded-sm">
                        @foreach($pastebin->images as $image)
                            <div class="relative aspect-square border border-red-900/20 p-1 group transition-all duration-150 image-to-delete-container">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover rounded-sm image-preview" alt="existing evidence">
                                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/70 flex items-center justify-center transition-all duration-150 overlay-delete opacity-0 group-hover:opacity-100">
                                    <label class="flex flex-col items-center justify-center cursor-pointer text-gray-400 hover:text-red-500 text-[9px] font-black uppercase tracking-wider gap-2 select-none w-full h-full">
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" onchange="toggleImageDeleteState(this)" class="form-checkbox text-red-600 focus:ring-red-600 h-4 w-4 bg-black border-red-900/40 rounded-sm cursor-pointer">
                                        <span class="delete-label">Delete</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[9px] text-gray-500 font-mono italic">Check "Delete" on any existing images you wish to remove upon saving changes.</p>
                </div>
                @endif
                @else
                <input type="hidden" name="title" value="{{ $pastebin->title }}">
                <input type="hidden" name="description" value="{{ $pastebin->description }}">
                @endcan
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Content</label>
                    <textarea name="content" rows="12" class="w-full bg-[#050505] border border-red-900/20 px-4 py-3 text-xs font-mono text-gray-300 focus:outline-none focus:border-red-600 resize-none rounded-sm">{{ $pastebin->content }}</textarea>
                </div>
                <div class="flex justify-end gap-6 pt-6 border-t border-red-900/10">
                    <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-[10px] font-black uppercase tracking-widest text-gray-600 hover:text-white">Dismiss</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-10 py-3.5 font-black uppercase text-[10px] tracking-widest rounded-sm">
                        @can('update', $pastebin) Save Changes @else Transmit Suggestion @endcan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @auth
        @can('delete', $pastebin)
            <script>
                function confirmDelete() {
                    window.doxmeModal({
                        title: 'CONFIRM DESTRUCTION',
                        content: 'WARNING: You are about to initiate database erasure of this record. This operation will permanently purge all comments, evidence galleries, and index data associated with this thread.<br><br><span class="text-red-500 font-bold uppercase">This cannot be undone.</span>',
                        confirmText: 'Execute Purge',
                        cancelText: 'Abort',
                        type: 'danger',
                        onConfirm: () => {
                            document.getElementById('delete-paste-form').submit();
                        }
                    });
                }
</script>
        @endcan
    @endauth

    <div id="report-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm" onclick="document.getElementById('report-modal').classList.add('hidden')"></div>
        <div class="relative bg-[#0a0a0a] border border-red-900/40 w-full max-w-md rounded-sm overflow-hidden shadow-2xl">
            <div class="bg-[#111] px-5 py-3 border-b border-red-900/40 flex justify-between items-center">
                <span class="text-xs font-black text-red-500 uppercase tracking-widest">Report Thread</span>
                <button onclick="document.getElementById('report-modal').classList.add('hidden')" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('pastebin.report', $pastebin->slug) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="reason" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Reason for Report</label>
                    <textarea id="reason" name="reason" required placeholder="Describe why this thread " rows="4" 
                        class="w-full bg-[#111] border border-white/10 rounded-sm px-4 py-3 text-xs text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 font-mono bg-black"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('report-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-700 bg-gray-900 hover:bg-gray-800 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-sm">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-sm">Submit Report</button>
                </div>
            </form>
        </div>
    </div>

    <div id="share-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/95 backdrop-blur-sm" onclick="closeShareModal()"></div>
        <div class="relative bg-[#0a0a0a] border border-red-900/40 w-full max-w-md rounded-sm overflow-hidden shadow-2xl">
            <div class="bg-[#111] px-5 py-3.5 border-b border-red-900/40 flex justify-between items-center">
                <span class="text-xs font-black text-red-500 uppercase tracking-widest">Share Pastebin</span>
                <button onclick="closeShareModal()" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($pastebin->title) }}" target="_blank" 
                       class="flex items-center justify-center gap-2 p-3 bg-[#0a0a0a] border border-red-900/20 hover:border-red-600 hover:bg-red-950/10 rounded-sm text-xs font-black uppercase tracking-wider text-gray-300 transition-all active:scale-95">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.11.02-1.93 1.23-5.46 3.62-.51.35-.98.53-1.39.51-.46-.01-1.35-.26-2.01-.48-.8-.27-1.44-.42-1.39-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.64-2.88 7.98-3.45 3.8-1.61 4.59-1.9 5.1-.19.06.12.08.26.06.4z"/></svg>
                        <span>Telegram</span>
                    </a>
                    
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($pastebin->title) }}" target="_blank" 
                       class="flex items-center justify-center gap-2 p-3 bg-[#0a0a0a] border border-red-900/20 hover:border-red-600 hover:bg-red-950/10 rounded-sm text-xs font-black uppercase tracking-wider text-gray-300 transition-all active:scale-95">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        <span>Twitter / X</span>
                    </a>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-bold text-gray-500 uppercase">Share Link</label>
                    <div class="flex items-center gap-2 bg-black border border-red-900/20 p-2.5 rounded-sm">
                        <input type="text" readonly id="share-link-input" value="{{ request()->url() }}" 
                               class="bg-transparent text-xs text-gray-300 font-mono focus:outline-none flex-1 select-all cursor-text" />
                        <button onclick="copyShareLink()" class="px-4 py-1.5 border border-red-600 bg-red-600/10 hover:bg-red-600/20 text-red-500 text-[10px] font-black uppercase tracking-widest transition-all rounded-sm flex items-center gap-1 active:scale-95">
                            <span id="copy-share-btn-text">Copy</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editImageInput = document.getElementById('edit_image');
            if (editImageInput) {
                editImageInput.addEventListener('change', function () {
                    if (this.files.length > 5) {
                        alert("You can only upload a maximum of 5 images");
                        this.value = '';
                    }
                });
            }
        });

        function toggleImageDeleteState(checkbox) {
            const container = checkbox.closest('.image-to-delete-container');
            const img = container.querySelector('.image-preview');
            const label = container.querySelector('.delete-label');
            const overlay = container.querySelector('.overlay-delete');
            if (checkbox.checked) {
                container.classList.add('border-red-600');
                container.classList.remove('border-red-900/20');
                img.style.filter = 'grayscale(100%) brightness(40%)';
                label.innerText = 'To Delete';
                label.classList.add('text-red-500');
                label.classList.remove('text-gray-400');
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            } else {
                container.classList.remove('border-red-600');
                container.classList.add('border-red-900/20');
                img.style.filter = '';
                label.innerText = 'Delete';
                label.classList.remove('text-red-500');
                label.classList.add('text-gray-400');
                overlay.style.opacity = '';
                overlay.style.pointerEvents = '';
            }
        }

        function openShareModal() {
            const modal = document.getElementById('share-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeShareModal() {
            const modal = document.getElementById('share-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function copyShareLink() {
            const copyText = document.getElementById("share-link-input");
            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value).then(() => {
                const btnText = document.getElementById("copy-share-btn-text");
                btnText.innerText = "Copied!";
                btnText.style.color = '#22c55e';

                setTimeout(() => {
                    btnText.innerText = "Copy";
                    btnText.style.color = '';
                }, 2000);
            }).catch((err) => {
                console.error('Failed to copy text: ', err);
            });
        }

        let isExpanded = false;
        function toggleViewFull() {
            const wrapper = document.getElementById('pastebin-content-wrapper');
            const btnContainer = document.getElementById('view-full-btn-container');
            const btnText = document.getElementById('view-full-text');
            const icon = document.getElementById('view-full-icon');

            if (!isExpanded) {
                wrapper.classList.remove('collapsed');
                wrapper.classList.add('expanded');
                wrapper.style.maxHeight = wrapper.scrollHeight + 'px';
                btnContainer.classList.remove('bg-gradient-to-t', 'from-[#050505]', '-mt-16', 'pt-12');
                btnContainer.classList.add('mt-4', 'pt-0');
                btnText.innerText = 'Collapse Content';
                icon.style.transform = 'rotate(180deg)';
                isExpanded = true;
            } else {
                wrapper.style.maxHeight = '800px';
                wrapper.classList.remove('expanded');
                wrapper.classList.add('collapsed');
                btnContainer.classList.add('bg-gradient-to-t', 'from-[#050505]', '-mt-16', 'pt-12');
                btnContainer.classList.remove('mt-4', 'pt-0');
                btnText.innerText = 'View Full Content';
                icon.style.transform = 'rotate(0deg)';
                isExpanded = false;
                document.getElementById('pastebin-content-wrapper').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        let isMaximized = false;
        function toggleMaximizeContent() {
            const container = document.getElementById('content-section-container');
            const maximizeText = document.getElementById('maximize-text');
            const maximizeIcon = document.getElementById('maximize-icon');
            const fixedCloseBtn = document.getElementById('minimize-fixed-btn');

            if (!isMaximized) {
                container.classList.add('maximized');
                document.body.style.overflow = 'hidden';
                maximizeText.innerText = 'Minimize';
                maximizeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4v4m0 0H4m4 0l-5-5m11 1V4m0 0h4m-4 0l5-5M8 20v-4m0 0H4m4 0l5 5m11-1v4m0-4h4m-4 0l5 5"/>';
                fixedCloseBtn.classList.remove('hidden');

                if (!isExpanded) {
                    const btnContainer = document.getElementById('view-full-btn-container');
                    btnContainer.classList.remove('bg-gradient-to-t', 'from-[#050505]', '-mt-16', 'pt-12');
                    btnContainer.classList.add('mt-4', 'pt-0');
                }

                isMaximized = true;
            } else {
                container.classList.remove('maximized');
                document.body.style.overflow = 'auto';
                maximizeText.innerText = 'Maximize';
                maximizeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>';
                fixedCloseBtn.classList.add('hidden');

                if (!isExpanded) {
                    const btnContainer = document.getElementById('view-full-btn-container');
                    btnContainer.classList.add('bg-gradient-to-t', 'from-[#050505]', '-mt-16', 'pt-12');
                    btnContainer.classList.remove('mt-4', 'pt-0');
                }

                isMaximized = false;
                document.getElementById('pastebin-content-wrapper').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        const PASTEBIN_SLUG = @json($pastebin->slug);
        const VISIT_URL     = '{{ route("pastebin.visit", ":slug") }}'.replace(':slug', PASTEBIN_SLUG);
        const ROOT_TRACK_URL = @json(route('visitors.root.track'));
        const CSRF_TOKEN    = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

        function buildVisitorItem(visitor) {
            const isMemb = visitor.type === 'member';
            const name   = (isMemb ? '@' : '') + visitor.name;
            if (isMemb && visitor.user_style) {
                return visitor.user_style;
            }
            const color = visitor.role_color || '#6b7280';
            return `<span style="color:${color}">${name}</span>`;
        }

        function updateVisitorList(data) {
            const countEl = document.getElementById('visitor-count');
            const listEl  = document.getElementById('visitor-list');
            if (!countEl || !listEl) return;

            countEl.textContent = data.count;

            if (data.visitors.length === 0) {
                listEl.innerHTML = '<span class="text-gray-700 italic">No active visitors</span>';
                return;
            }
            listEl.innerHTML = data.visitors.map(buildVisitorItem).join(', ');
        }

        async function heartbeat() {
            const headers = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            };

            try {
                const res = await fetch(VISIT_URL, { method: 'POST', headers, credentials: 'same-origin' });
                if (res.ok) {
                    updateVisitorList(await res.json());
                }
            } catch (e) {}

            if (!ROOT_TRACK_URL) return;
            try {
                await fetch(ROOT_TRACK_URL, { method: 'POST', headers, credentials: 'same-origin' });
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', () => {
            heartbeat();
            setInterval(heartbeat, 45000);
        });
</script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-layouts.app>
