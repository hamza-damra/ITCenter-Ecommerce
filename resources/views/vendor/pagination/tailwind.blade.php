@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="width: 100%;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 0.9rem; font-weight: 500;">
                    « Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 0.9rem;">
                    « Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #999; font-weight: 500;">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #e69270; border: 1px solid #e69270; border-radius: 8px; color: #fff; font-weight: 600;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; text-decoration: none; font-weight: 500; transition: all 0.3s; font-size: 0.9rem;">
                    Next »
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; color: #999; cursor: not-allowed; font-size: 0.9rem; font-weight: 500;">
                    Next »
                </span>
            @endif
        </div>
    </nav>

    <style>
        nav[aria-label="Pagination Navigation"] a:hover {
            background: #e69270 !important;
            color: #fff !important;
            border-color: #e69270 !important;
        }
        nav[aria-label="Pagination Navigation"] svg {
            display: none !important;
        }
    </style>
@endif
