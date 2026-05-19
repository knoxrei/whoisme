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
            
            <!-- Breadcrumb/Header Bar -->
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

            <!-- Main Thread Container -->
            <div class="flex flex-col md:flex-row gap-2 ">
                
                <!-- Left Sidebar (User Card Column) -->
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

                    <!-- Avatar -->
                    <div class="mb-5 flex justify-center">
                        <div class="w-32 h-32 overflow-hidden ">
                            @if($pastebin->user && $pastebin->user->identification->avatar_path)
                                <img src="{{ asset('storage/' . $pastebin->user->identification->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                            @else
                                <img src="{{ asset('storage/avatars/default.png') }}" class="w-full h-full object-cover" alt="avatar">
                            @endif
                        </div>
                    </div>

                    <!-- Role Badge -->
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

                    <!-- User Stats -->
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
                </div>
            </aside>

                <!-- Right Content Area -->
                <main class="flex-1 flex flex-col gap-2">
                    <!-- Post Card -->
                    <div class="bg-[#050505] border border-red-900/30 rounded-sm flex flex-col min-h-[600px]">
                        <!-- Post Header -->
                        <div class="bg-[#111] px-5 py-2.5 border-b border-red-900/30 flex justify-between items-center text-[10px] text-gray-500 font-mono">
                            {{ $pastebin->created_at->format('d-m-Y, H:i A') }}
                        </div>

                        <!-- Post Content -->
                        <div class="p-8 flex-1">
                            
                            <!-- Burn-After-Reading Alert -->
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

                            <!-- Cover Image -->
                            @if($pastebin->cover_path && $pastebin->cover_path !== 'defaultCover.png')
                                <div class="mb-8 border border-red-900/20 bg-[#050505] p-1 rounded-sm overflow-hidden">
                                    <img src="{{ asset('storage/' . $pastebin->cover_path) }}" class="w-full max-h-[400px] object-cover" alt="cover">
                                </div>
                            @endif

                     

                            <!-- Main Content Block -->
                            <div class="mb-8">
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
                                </style>
                                <div class="markdown-body text-gray-300 p-6 font-mono text-xs overflow-x-auto max-h-[800px] leading-relaxed scrollbar-thin scrollbar-thumb-red-900 scrollbar-track-transparent">
                                    {!! $contentMarkdown !!}
                                </div>
                            </div>

                            <!-- Gallery Images -->
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

                        <!-- Footer Actions -->
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

                    <!-- Sponsor Banners Section -->
                    @php
                        $showBanners = \App\Helper\AdTracker::getBanners(4, 2);
                    @endphp

                    @if($showBanners->isNotEmpty())
                        <div class="bg-[#050505] border border-red-900/30 p-5 rounded-sm">
                            <p class="text-[9px] text-red-500 font-black uppercase tracking-[0.2em] text-center mb-3 flex items-center justify-center gap-1.5 font-mono select-none">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                OFFICIAL PLATFORM SPONSORS
                            </p>
                            <div class="flex flex-wrap justify-center gap-4">
                                @foreach($showBanners as $banner)
                                    <a href="{{ route('ads.click', $banner->id) }}" target="_blank" 
                                       class="block w-full max-w-[466px] h-[58px] border border-red-950/40 hover:border-red-600/70 overflow-hidden rounded-sm bg-black transition-colors duration-150 relative group">
                                        <img src="{{ asset($banner->media_url) }}" alt="{{ $banner->title }}" 
                                             class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-150">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Comments Section -->
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

                    <!-- Pending Suggestions Block -->
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

    <!-- Edit/Suggest Modal -->
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
                                <!-- Overlay with checkbox -->
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

    <!-- Report Modal -->
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

    <!-- Share Modal -->
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
                <!-- Social Share Links -->
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

                <!-- Copy Link Input -->
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
        // Gallery selection limit for edit modal
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
                btnText.style.color = '#22c55e'; // Tailwind green-500
                
                setTimeout(() => {
                    btnText.innerText = "Copy";
                    btnText.style.color = '';
                }, 2000);
            }).catch((err) => {
                console.error('Failed to copy text: ', err);
            });
        }
    </script>
</x-layouts.app>
