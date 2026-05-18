<x-layouts.app :title="$title">
    <div class="min-h-screen text-gray-300 font-sans">
        <div class="max-w-[1400px] mx-auto px-2 py-4 md:py-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-950/20 border border-green-900/30 text-green-500 text-xs font-mono font-bold rounded-sm">
                    {{ session('success') }}
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
                        <div class="w-32 h-32 border border-red-900/20 overflow-hidden bg-[#050505]">
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
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-bold uppercase tracking-tighter">Posts:</span>
                            <span class="text-white font-black">{{ $pastebin->user ? $pastebin->user->pastebins()->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-bold uppercase tracking-tighter">Followers:</span>
                            <span class="text-white font-black">{{ $pastebin->user ? $pastebin->user->followers()->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 font-bold uppercase tracking-tighter">Joined:</span>
                            <span class="text-white font-mono">{{ $pastebin->user ? $pastebin->user->created_at->format('M Y') : 'N/A' }}</span>
                        </div>
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
                                    <div class="flex-shrink-0 animate-pulse text-lg">⚠️</div>
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
                                <a href="{{ route('pastebin.raw', $pastebin->slug) }}" target="_blank" class="bg-[#0a0a0a] border border-red-900/20 text-[9px] font-black px-4 py-1.5 text-gray-500 hover:text-white hover:border-white/20 uppercase tracking-widest transition-colors duration-150">
                                    Raw View
                                </a>
                                <a href="{{ route('pastebin.download', $pastebin->slug) }}" class="bg-red-950/20 border border-red-900/40 text-[9px] font-black px-4 py-1.5 text-red-500 hover:bg-red-600 hover:text-white uppercase tracking-widest transition-colors duration-150">
                                    Download
                                </a>
                            </div>
                        </footer>
                    </div>

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
                        <label for="image" class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Gallery Images (Max 5) <span class="text-[8px] font-normal text-red-500 normal-case">(Optional - Appends to gallery)</span></label>
                        <input type="file" name="image[]" id="image" multiple class="w-full bg-[#050505] border border-red-900/20 px-4 py-2 text-xs text-gray-300 focus:outline-none focus:border-red-600 rounded-sm file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-gray-700 cursor-pointer">
                    </div>
                </div>
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
</x-layouts.app>
