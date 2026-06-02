@foreach($pastes as $paste)
    <tr class="hover:bg-red-950/5 transition-colors duration-100">
        <td class="py-3.5 px-4 font-bold text-gray-200 max-w-sm truncate">
            <a href="{{ route('pastebin.show', $paste->slug) }}" class="hover:text-red-500 hover:underline">
                {{ $paste->title }}
            </a>
        </td>
        <td class="py-3.5 px-4 text-gray-400 font-bold">
            @if($paste->user_id && $paste->user)
                <a href="{{ route('profile.show', $paste->user->username) }}" class="flex items-center gap-2 group">
                    <div class="w-6 h-6 border border-red-900/30 overflow-hidden shrink-0">
                        @if($paste->user->identification && $paste->user->identification->avatar_path)
                            <img src="{{ Storage::url($paste->user->identification->avatar_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-[#111] flex items-center justify-center text-[10px] font-bold text-gray-500">{{ substr($paste->user->username, 0, 1) }}</div>
                        @endif
                    </div>
                    <span class="text-xs group-hover:text-red-500 transition-colors">
                        {!! $paste->user->identification->role->userStyle($paste->user->username) !!}
                    </span>
                </a>
            @else
                {{ $paste->author_name ?: 'Anonymous' }}
            @endif
        </td>
        <td class="py-3.5 px-4 text-center font-bold text-gray-300">{{ number_format($paste->views_count) }}</td>
        <td class="py-3.5 px-4 text-center font-bold text-gray-300">{{ number_format($paste->download_count) }}</td>
        <td class="py-3.5 px-4 text-center font-bold text-gray-300">{{ number_format($paste->comments_count ?? 0) }}</td>
        <td class="py-3.5 px-4 text-right text-gray-500 font-mono">
            {{ $paste->created_at->format('Y-m-d H:i:s') }} ({{ $paste->created_at->diffForHumans() }})
        </td>
    </tr>
@endforeach
