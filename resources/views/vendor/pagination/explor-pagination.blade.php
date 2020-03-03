<ul class="pagination pull-left">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
        <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">قبلی</a></li>
    @else
        <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">قبلی</a></li>
    @endif

<!-- Pagination Elements -->
    @foreach ($elements as $element)
    <!-- "Three Dots" Separator -->
        @if (is_string($element))
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{{ $element }}</a></li>
        @endif

    <!-- Array Of Links -->
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->resolveCurrentPage($paginator->getPageName()))
                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">{{ $page }}</a></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

<!-- Next Page Link -->
    @if ($paginator->hasMorePages())
        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">بعدی</a></li>
    @else
        <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">بعدی</a></li>
    @endif
</ul>