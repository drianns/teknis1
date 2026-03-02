@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-800 text-gray-600 cursor-not-allowed border border-gray-700/50">
                <i class='bx bx-chevron-left text-xl'></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-800 text-gray-400 hover:bg-blue-600 hover:text-white transition-all border border-gray-700/50">
                <i class='bx bx-chevron-left text-xl'></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-8 h-8 text-gray-600">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white font-bold shadow-lg shadow-blue-600/20">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white transition-all border border-gray-700/50">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-800 text-gray-400 hover:bg-blue-600 hover:text-white transition-all border border-gray-700/50">
                <i class='bx bx-chevron-right text-xl'></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-800 text-gray-600 cursor-not-allowed border border-gray-700/50">
                <i class='bx bx-chevron-right text-xl'></i>
            </span>
        @endif
    </nav>
@endif
