<x-layouts.app :title="$title">
    <div class="w-full max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-mono text-gray-200">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between border-b border-red-900/30 pb-4 gap-4">
            <div>
                <h1 class="text-2xl font-black text-white uppercase tracking-tight mt-2">
                    Global Pastebins Index
                </h1>
                <p class="text-xs text-gray-500 mt-1">Listing all public, non-self-destructing pastes.</p>
            </div>
            <div class="bg-[#0a0a0a] border border-red-900/30 px-4 py-2 rounded-sm text-xs">
                Total Available: <span class="text-red-500 font-black">{{ $pastebins->total() }}</span>
            </div>
        </div>

        <!-- Pastebins List Container -->
        <div class="bg-[#0a0a0a] border border-red-900/30 overflow-hidden rounded-sm mb-6">
            <div class="bg-[#111] px-4 py-3 border-b border-red-900/40 text-xs font-black text-red-500 uppercase tracking-wider">
                Recent Pastebins
            </div>

            @if($pastebins->isEmpty())
                <div class="p-8 text-center text-gray-500 text-xs">
                    No active pastebins found.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-red-900/20 text-gray-500 uppercase tracking-widest text-[10px] bg-[#050505]">
                                <th class="p-4">Title</th>
                                <th class="p-4">Author</th>
                                <th class="p-4 text-center">Views</th>
                                <th class="p-4 text-center">Downloads</th>
                                <th class="p-4 text-right">Published</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-950/20">
                            @foreach($pastebins as $paste)
                                <tr class="hover:bg-red-900/5 transition-colors">
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            @if($paste->password)
                                                <svg class="w-3 h-3 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            @endif
                                            <a href="{{ route('pastebin.show', $paste->slug) }}" class="text-sm font-bold text-white hover:text-red-500 transition-colors">
                                                {{ Str::limit($paste->title, 40) }}
                                            </a>
                                        </div>
                                        <div class="text-[10px] text-gray-600 mt-1 uppercase tracking-widest flex items-center gap-2">
                                            <span>Syntax: <span class="text-red-400 font-bold">{{ $paste->syntax_highlighting }}</span></span>
                                            <span>Size: <span class="text-gray-400">{{ number_format($paste->size_bytes / 1024, 2) }} KB</span></span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @if($paste->user)
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
                                            <span class="text-gray-500 italic">Anonymous</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center text-gray-300 font-bold">
                                        {{ number_format($paste->views) }}
                                    </td>
                                    <td class="p-4 text-center text-gray-300 font-bold">
                                        {{ number_format($paste->downloads_count) }}
                                    </td>
                                    <td class="p-4 text-right text-gray-500 text-[10px] tracking-wider">
                                        {{ $paste->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $pastebins->links() }}
        </div>

    </div>
</x-layouts.app>
