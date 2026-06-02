@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="mt-6 pt-6 border-t border-red-900/10">

        <div class="flex gap-2 items-center justify-between sm:hidden font-mono text-[10px]">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">
                    &laquo; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white hover:border-red-600 uppercase tracking-widest rounded-sm transition-colors duration-150">
                    &laquo; Prev
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white hover:border-red-600 uppercase tracking-widest rounded-sm transition-colors duration-150">
                    Next &raquo;
                </a>
            @else
                <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">
                    Next &raquo;
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:items-center sm:justify-between font-mono text-[10px]">

            <div>
                <span class="text-gray-600 uppercase tracking-widest">Showing</span>
                @if ($paginator->firstItem())
                    <span class="text-gray-300 font-bold">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
                    <span class="text-gray-600 uppercase tracking-widest">of</span>
                @else
                    <span class="text-gray-300 font-bold">{{ $paginator->count() }}</span>
                    <span class="text-gray-600 uppercase tracking-widest">of</span>
                @endif
                <span class="text-gray-300 font-bold">{{ $paginator->total() }}</span>
            </div>

            <div class="flex items-center gap-1">

                @if ($paginator->onFirstPage())
                    <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">
                        Prev
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white hover:border-red-600 uppercase tracking-widest rounded-sm transition-colors duration-150">
                        Prev
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 py-1 text-gray-600">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="px-3 py-1 bg-red-900/30 border border-red-700 text-red-400 font-black rounded-sm cursor-default">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                   class="px-3 py-1 border border-red-900/20 text-gray-500 hover:text-white hover:border-red-600 rounded-sm transition-colors duration-150">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1 border border-red-900/30 text-gray-400 hover:text-white hover:border-red-600 uppercase tracking-widest rounded-sm transition-colors duration-150">
                        Next
                    </a>
                @else
                    <span class="px-3 py-1 border border-red-900/10 text-gray-700 uppercase tracking-widest rounded-sm cursor-not-allowed">
                        Next
                    </span>
                @endif

            </div>
        </div>
    </nav>
@endif
