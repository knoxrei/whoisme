@foreach($results as $result)
    <article class="bg-[#090909] border border-red-950/15 p-4 rounded-sm hover:border-red-900/30 transition-all duration-150">
        <div class="flex items-start justify-between gap-3 mb-2">
            <h3 class="text-xs md:text-sm font-bold text-red-500 hover:underline leading-snug">
                <a href="{{ route('pastebin.show', $result->slug) }}">{{ $result->title }}</a>
            </h3>
            <span class="text-[8px] bg-red-950/20 border border-red-900/30 text-red-500 px-1 py-0.5 rounded-sm select-none">
                Rank {{ $result->rank_score ?? '0.00' }}
            </span>
        </div>
        <p class="text-xs text-gray-400 leading-relaxed font-mono mb-4 break-words">{!! $result->snippet !!}</p>
        <footer class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-500 tracking-wider border-t border-red-950/10 pt-3">
            <div class="flex items-center">
                <span class="text-gray-600">Author:</span>
                <span class="text-gray-300 font-bold">
                    <a href="{{ $result->user ? route('profile.show', $result->user->username) : '#' }}" class="text-white font-black text-sm tracking-tighter block">
                        @if($result->user)
                            {!! $result->user->identification->role->userStyle($result->author_name, $result->user->identification->color_username ?? '#ffffff') !!}
                        @else
                            {{ $result->author_name }}
                        @endif
                    </a>
                </span>
            </div>
            <div class="select-none text-red-950/40">|</div>
            <div><span class="text-gray-600">Views:</span> <span class="text-gray-300 font-bold">{{ number_format($result->views_count) }}</span></div>
            <div class="select-none text-red-950/40">|</div>
            <div><span class="text-gray-600">DLs:</span> <span class="text-gray-300 font-bold">{{ number_format($result->download_count) }}</span></div>
            <div class="select-none text-red-950/40">|</div>
            <div><span class="text-gray-600">Date:</span> <span class="text-gray-300 font-bold">{{ $result->created_at->diffForHumans() }}</span></div>
        </footer>
    </article>
@endforeach
