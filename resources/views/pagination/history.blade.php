@if ($paginator->total() > 0)
    <nav class="history-pagination" aria-label="{{ __('labels.pagination_nav') }}">
        <div class="d-grid gap-2 d-sm-none">
            @if ($paginator->onFirstPage())
                <span class="btn btn-outline-secondary disabled">{{ __('pagination.previous') }}</span>
            @else
                <a class="btn btn-outline-primary" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('pagination.previous') }}</a>
            @endif
            <div class="text-center small text-muted py-1">
                {{ __('labels.pagination_page_of', ['current' => $paginator->currentPage(), 'last' => $paginator->lastPage()]) }}
            </div>
            @if ($paginator->hasMorePages())
                <a class="btn btn-outline-primary" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('pagination.next') }}</a>
            @else
                <span class="btn btn-outline-secondary disabled">{{ __('pagination.next') }}</span>
            @endif
        </div>

        <ul class="pagination mb-0 d-none d-sm-flex justify-content-center flex-wrap">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">{{ __('pagination.previous') }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('pagination.previous') }}</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('pagination.next') }}</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">{{ __('pagination.next') }}</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
