@foreach($pastes as $paste)
    <tr class="hover:bg-red-950/5 transition-colors duration-150">
        <td class="p-4">
            <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-white hover:text-red-500 font-bold block truncate max-w-xs md:max-w-md transition-colors duration-150">
                {{ $paste->title }}
            </a>
            @if($paste->description)
                <span class="text-[10px] text-gray-500 block truncate max-w-xs md:max-w-md mt-0.5">{{ $paste->description }}</span>
            @endif
        </td>
        <td class="p-4 text-center">
            @if($paste->password)
                <span class="inline-block px-2 py-0.5 bg-red-950/20 border border-red-900/40 text-[9px] uppercase font-black text-red-500 tracking-wider">🔒 Passworded</span>
            @elseif($paste->visibility->value === 'private')
                <span class="inline-block px-2 py-0.5 bg-red-950/20 border border-red-900/20 text-[9px] uppercase font-black text-red-500 tracking-wider">Private</span>
            @elseif($paste->visibility->value === 'unlisted')
                <span class="inline-block px-2 py-0.5 bg-yellow-950/20 border border-yellow-900/20 text-[9px] uppercase font-black text-yellow-500 tracking-wider">Unlisted</span>
            @else
                <span class="inline-block px-2 py-0.5 bg-green-950/20 border border-green-900/20 text-[9px] uppercase font-black text-green-500 tracking-wider">Public</span>
            @endif
        </td>
        <td class="p-4 text-center text-gray-300 font-bold">{{ number_format($paste->views_count) }}</td>
        <td class="p-4 text-center text-gray-300 font-bold">{{ number_format($paste->download_count ?? 0) }}</td>
        <td class="p-4 text-right text-gray-400 font-mono">{{ $paste->created_at->diffForHumans() }}</td>
    </tr>
@endforeach
