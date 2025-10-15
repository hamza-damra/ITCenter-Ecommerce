@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="width: 100%;">
        <ul class="pagination" style="display: flex; gap: 0.5rem; align-items: center; justify-content: center; list-style: none; padding: 0; margin: 0;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')" style="list-style: none;">
                    <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 1.2rem;">&lsaquo;</span>
                </li>
            @else
                <li class="page-item" style="list-style: none;">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 1.2rem;">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true" style="list-style: none;">
                        <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #999; font-weight: 500;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page" style="list-style: none;">
                                <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #e69270; border: 1px solid #e69270; border-radius: 8px; color: #fff; font-weight: 600;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item" style="list-style: none;">
                                <a class="page-link" href="{{ $url }}" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item" style="list-style: none;">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 1.2rem;">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')" style="list-style: none;">
                    <span class="page-link" style="display: flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 1.2rem;">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .pagination .page-link:hover {
            background: #e69270 !important;
            color: #fff !important;
            border-color: #e69270 !important;
        }
    </style>
@endif
