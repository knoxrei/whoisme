<x-layouts.app :title="$title">
    <div class="w-full max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-mono text-gray-200">
        
        <!-- Back Link & Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between border-b border-red-900/30 pb-4 gap-4">
            <div>
                <a href="{{ route('profile.show', $user->username) }}" class="text-[10px] font-bold text-gray-500 hover:text-red-500 uppercase tracking-widest transition-colors duration-150">
                    &lt;&lt; Back to @ {{ $user->username }}'s Profile
                </a>
                <h1 class="text-2xl font-black text-white uppercase tracking-tight mt-2">
                    All Forum Posts by {!! $user->identification->role->userStyle($user->username) !!}
                </h1>
            </div>
            <div class="bg-[#0a0a0a] border border-red-900/30 px-4 py-2 rounded-sm text-xs">
                Total Found: <span class="text-red-500 font-black">{{ $comments->total() }}</span>
            </div>
        </div>

        <!-- Comments Feed Container -->
        <div class="space-y-4 mb-6">
            @if($comments->isEmpty())
                <div class="bg-[#0a0a0a] border border-red-900/30 rounded-sm p-8 text-center text-gray-500 text-xs">
                    No active posts found for this user.
                </div>
            @else
                @foreach($comments as $comment)
                    <div class="bg-[#0a0a0a] border border-red-900/30 rounded-sm overflow-hidden hover:border-red-500/20 transition-all duration-150">
                        <!-- Comment Header Info -->
                        <div class="bg-[#111] px-4 py-2.5 border-b border-red-900/40 flex items-center justify-between text-[10px]">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 uppercase">On Thread:</span>
                                @if($comment->pastebin)
                                    <a href="{{ route('pastebin.show', $comment->pastebin->slug) }}" class="text-red-500 hover:text-red-400 font-bold uppercase transition-colors">
                                        {{ $comment->pastebin->title }}
                                    </a>
                                @else
                                    <span class="text-gray-600 font-bold uppercase italic">[Deleted Thread]</span>
                                @endif
                            </div>
                            <div class="text-gray-500 font-mono">
                                {{ $comment->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Comment Body -->
                        <div class="p-4 text-xs text-gray-300 leading-relaxed font-mono whitespace-pre-wrap">
                            {{ $comment->content }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Custom Dark Pagination -->
        <div class="mt-6">
            {{ $comments->links() }}
        </div>

    </div>
</x-layouts.app>
