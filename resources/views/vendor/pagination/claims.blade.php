@if ($paginator->hasPages())
    <div class="custom-pagination">

        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <span class="disabled">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">Previous</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">Next</a>
        @else
            <span class="disabled">Next</span>
        @endif

    </div>

    <p>
        Showing {{ $paginator->firstItem() }} to
        {{ $paginator->lastItem() }} of
        {{ $paginator->total() }} results
    </p>
@endif
