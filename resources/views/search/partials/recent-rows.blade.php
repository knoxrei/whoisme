@foreach($pastes as $paste)
    <tr class="hover:bg-red-950/5 transition-colors duration-100">
        <td class="py-3.5 px-4 font-bold text-gray-200 max-w-sm truncate">
            <a href="{{ route('pastebin.show', $paste->slug) }}" class="hover:text-red-500 hover:underline">
                {{ $paste->title }}
            </a>
        </td>
        <td class="py-3.5 px-4 text-gray-400 font-bold">
            @if($paste->user_id)
                <a href="{{ route('profile.show', $paste->author_name) }}" class="hover:text-red-500 hover:underline">
                    {!! $paste->user->identification->role->userStyle($paste->author_name) !!}
                </a>
            @else
                {{ $paste->author_name }}
            @endif
        </td>
        <td class="py-3.5 px-4 text-center font-bold text-gray-300">{{ number_format($paste->views_count) }}</td>
        <td class="py-3.5 px-4 text-center font-bold text-gray-300">{{ number_format($paste->download_count) }}</td>
        <td class="py-3.5 px-4 text-right text-gray-500 font-mono">
            {{ $paste->created_at->format('Y-m-d H:i:s') }} ({{ $paste->created_at->diffForHumans() }})
        </td>
    </tr>
@endforeach
