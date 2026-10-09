@if ($paginator->hasPages())

    <nav>
        <div class="pagination">

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <span class="disabled">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}">
                    Previous
                </a>
            @endif


            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="disabled">
                        {{ $element }}
                    </span>
                @endif


                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="active">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach


            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}">
                    Next
                </a>
            @else
                <span class="disabled">
                    Next
                </span>
            @endif

        </div>
    </nav>

@endif
