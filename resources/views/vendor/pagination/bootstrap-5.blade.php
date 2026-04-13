@if ($paginator->hasPages())
<div class="pw-pagination">
    <div class="pw-pagination__info">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
    </div>
    <ul class="pw-pagination__list">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li><span class="pw-page-link pw-page-link--disabled">&lsaquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" class="pw-page-link" rel="prev">&lsaquo;</a></li>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li><span class="pw-page-link pw-page-link--dots">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="pw-page-link pw-page-link--active">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}" class="pw-page-link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" class="pw-page-link" rel="next">&rsaquo;</a></li>
        @else
            <li><span class="pw-page-link pw-page-link--disabled">&rsaquo;</span></li>
        @endif
    </ul>
</div>
@endif
