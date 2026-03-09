@if ($paginator->hasPages())
    <div class="pagination-controls flex gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button
                class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-400 text-sm transition-all disabled:opacity-50"
                disabled>
                Previous
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-400 text-sm hover:bg-gray-700 hover:text-white transition-all">
                Previous
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 text-gray-500 flex items-center">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button
                            class="btn-page px-3 py-1.5 rounded-lg bg-blue-600 border border-blue-600 text-white text-sm font-bold shadow-lg shadow-blue-500/20">
                            {{ $page }}
                        </button>
                    @else
                        <a href="{{ $url }}"
                            class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-400 text-sm hover:bg-gray-700 hover:text-white transition-all flex items-center justify-center">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-400 text-sm hover:bg-gray-700 hover:text-white transition-all">
                Next
            </a>
        @else
            <button
                class="btn-page px-3 py-1.5 rounded-lg bg-gray-800 border border-gray-700 text-gray-400 text-sm transition-all disabled:opacity-50"
                disabled>
                Next
            </button>
        @endif
    </div>
@endif