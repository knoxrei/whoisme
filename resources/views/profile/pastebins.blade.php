<x-layouts.app :title="$title">
    <div class="w-full max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-mono text-gray-200">
        
        <!-- Back Link & Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between border-b border-red-900/30 pb-4 gap-4">
            <div>
                <a href="{{ route('profile.show', $user->username) }}" class="text-[10px] font-bold text-gray-500 hover:text-red-500 uppercase tracking-widest transition-colors duration-150">
                    &lt;&lt; Back to @ {{ $user->username }}'s Profile
                </a>
                <h1 class="text-2xl font-black text-white uppercase tracking-tight mt-2">
                    All Pastebins by {!! $user->identification->role->userStyle($user->username) !!}
                </h1>
            </div>
            <div class="bg-[#0a0a0a] border border-red-900/30 px-4 py-2 rounded-sm text-xs">
                Total Found: <span class="text-red-500 font-black">{{ $pastebins->total() }}</span>
            </div>
        </div>

        <!-- Pastebins List Container -->
        <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm mb-6">
            <div class="bg-[#111] px-4 py-3 border-b border-red-900/40 text-xs font-black text-red-500 uppercase tracking-wider">
                Index of Pastebins
            </div>

            @if($pastebins->isEmpty())
                <div class="p-8 text-center text-gray-500 text-xs">
                    No active pastebins found for this user.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-900/20 text-gray-500 uppercase tracking-widest text-[10px] bg-[#050505]">
                                <th class="p-4">Title</th>
                                <th class="p-4 text-center">Visibility</th>
                                <th class="p-4 text-center">Views</th>
                                <th class="p-4 text-center">Downloads</th>
                                <th class="p-4 text-right">Published</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/20">
                            @foreach($pastebins as $paste)
                                <tr class="hover:bg-red-950/5 transition-colors duration-150">
                                    <td class="p-4">
                                        <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-white hover:text-red-500 font-bold block truncate max-w-xs md:max-w-md transition-colors duration-150">
                                            {{ $paste->title }}
                                        </a>
                                        @if($paste->description)
                                            <span class="text-[10px] text-gray-500 block truncate max-w-xs md:max-w-md mt-0.5">
                                                {{ $paste->description }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($paste->password)
                                            <span class="inline-block px-2 py-0.5 bg-red-950/20 border border-red-900/40 text-[9px] uppercase font-black text-red-500 tracking-wider">
                                                🔒 Passworded
                                            </span>
                                        @elseif($paste->visibility->value === 'private')
                                            <span class="inline-block px-2 py-0.5 bg-red-950/20 border border-red-900/20 text-[9px] uppercase font-black text-red-500 tracking-wider">
                                                Private
                                            </span>
                                        @elseif($paste->visibility->value === 'unlisted')
                                            <span class="inline-block px-2 py-0.5 bg-yellow-950/20 border border-yellow-900/20 text-[9px] uppercase font-black text-yellow-500 tracking-wider">
                                                Unlisted
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-green-950/20 border border-green-900/20 text-[9px] uppercase font-black text-green-500 tracking-wider">
                                                Public
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center text-gray-300 font-bold">
                                        {{ number_format($paste->views_count) }}
                                    </td>
                                    <td class="p-4 text-center text-gray-300 font-bold">
                                        {{ number_format($paste->download_count ?? 0) }}
                                    </td>
                                    <td class="p-4 text-right text-gray-400 font-mono">
                                        {{ $paste->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Custom Dark Pagination -->
        <div class="mt-6">
            {{ $pastebins->links() }}
        </div>

    </div>
</x-layouts.app>
