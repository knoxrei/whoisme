@foreach($comments as $comment)
    <div class="bg-[#0a0a0a] border border-red-900/30 rounded-sm overflow-hidden hover:border-red-500/20 transition-all duration-150">
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
            <div class="text-gray-500 font-mono">{{ $comment->created_at->diffForHumans() }}</div>
        </div>
        <div class="p-4 text-xs text-gray-300 leading-relaxed font-mono whitespace-pre-wrap">{{ $comment->content }}</div>
    </div>
@endforeach
