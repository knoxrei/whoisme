<x-layouts.app :title="$title">
    <div class="w-full max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 font-mono text-gray-200">
        
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
                Total Found: <span class="text-red-500 font-black">{{ $total }}</span>
            </div>
        </div>

        <div id="posts-feed" class="space-y-4 mb-6">
            @if($comments->isEmpty())
                <div class="bg-[#0a0a0a] border border-red-900/30 rounded-sm p-8 text-center text-gray-500 text-xs">
                    No active posts found for this user.
                </div>
            @else
                @foreach($comments as $comment)
                    @include('profile.partials.post-rows', ['comments' => collect([$comment])])
                @endforeach
            @endif
        </div>

        <div id="load-more-wrap" class="flex flex-col items-center gap-3 {{ $nextCursor ? '' : 'hidden' }}">
            <button
                id="load-more-btn"
                data-cursor="{{ $nextCursor }}"
                data-url="{{ route('profile.posts', $user->username) }}"
                data-target="posts-feed"
                data-type="div"
                class="load-more-btn bg-[#0f0f0f] border border-red-900/30 hover:border-red-600 text-gray-400 hover:text-white px-6 py-2.5 rounded-sm text-[10px] font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                <span class="btn-label">Load More Posts &gt;&gt;</span>
                <span class="btn-spinner hidden">
                    <svg class="animate-spin h-3 w-3 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Loading...
                </span>
            </button>
        </div>

    </div>

    @include('search.partials.load-more-script')
</x-layouts.app>
