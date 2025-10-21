@extends('layouts.app')

@section('title', 'Our Products - IT Center')

@section('content')
<style>
    /* Import Google Font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Override font - exclude Font Awesome icons */
    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    .products-section {
        padding: 3rem 2rem;
        background: #f5f5f5;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #333;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1), 0 2px 6px rgba(0,0,0,0.08);
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: #fafafaff;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .product-image img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .product-image .icon-placeholder {
        font-size: 4rem;
    }

    .wishlist-btn {
        position: absolute !important;
        top: 12px !important;
        bottom: auto !important;
        @if(is_rtl())
        left: 12px !important;
        right: auto !important;
        @else
        right: 12px !important;
        left: auto !important;
        @endif
        background: rgba(255, 255, 255, 0.95);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }

    .wishlist-btn:hover {
        background: rgba(255, 255, 255, 1);
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .wishlist-btn:hover i {
        color: #ff0000 !important;
    }

    .wishlist-btn.active {
        background: rgba(255, 255, 255, 1) !important;
    }

    .wishlist-btn.active i {
        color: #ff0000 !important;
    }

    .wishlist-btn i {
        font-size: 1.1rem;
        color: #888;
        transition: all 0.3s;
    }

    /* Solid heart icon should be red */
    .wishlist-btn i.fas.fa-heart {
        color: #ff0000 !important;
    }

    .product-badge {
        position: absolute !important;
        top: 12px !important;
        bottom: auto !important;
        @if(is_rtl())
        right: 12px !important;
        left: auto !important;
        @else
        left: 12px !important;
        right: auto !important;
        @endif
        background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 2px 8px rgba(255, 71, 87, 0.3);
        letter-spacing: 0.3px;
    }

    .product-info {
        padding: 1.25rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-title {
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
        text-align: center;
        line-height: 1.4;
    }

    .product-description {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 1rem;
        line-height: 1.5;
        text-align: center;
        flex-grow: 1;
    }

    .product-footer {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
        margin-top: auto;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        text-align: start;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .product-price .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 1rem;
        font-weight: 500;
    }

    .add-to-cart {
        background: linear-gradient(135deg, #454546ff 0%, #000000ff 100%);
        color: #fff;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        white-space: nowrap;
        font-size: 0.95rem;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .add-to-cart:hover {
        background: linear-gradient(135deg, #7bace3ff 0%, #404249ff 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(78, 115, 223, 0.4);
    }

    .add-to-cart.in-cart {
        background: #4CAF50;
    }

    .add-to-cart.in-cart:hover {
        background: #45a049;
    }

    .add-to-cart.in-cart i {
        animation: cartBounce 0.5s ease;
    }

    .add-to-cart.out-of-stock {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .add-to-cart.out-of-stock:hover {
        background: linear-gradient(135deg, #f57c00 0%, #ef6c00 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 152, 0, 0.4);
    }

    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    .product-card a {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .wishlist-btn,
    .add-to-cart {
        position: relative;
        z-index: 10;
    }

    /* Pagination Styling */
    .pagination {
        display: flex !important;
        gap: 0.5rem !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 1rem 0 !important;
        list-style: none !important;
        margin: 0 !important;
    }

    .pagination li {
        list-style: none !important;
        margin: 0 !important;
    }

    .pagination .page-item {
        list-style: none !important;
    }

    .pagination .page-link,
    .pagination a,
    .pagination span {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 40px !important;
        height: 40px !important;
        padding: 0.5rem 1rem !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        color: #333 !important;
        text-decoration: none !important;
        font-weight: 500 !important;
        transition: all 0.3s !important;
        font-size: 1rem !important;
        line-height: 1 !important;
    }

    .pagination .page-link:hover,
    .pagination a:hover {
        background: #4169E1 !important;
        color: #fff !important;
        border-color: #4169E1 !important;
        transform: translateY(-2px) !important;
    }

    .pagination .page-item.active .page-link,
    .pagination .page-item.active span,
    .pagination .active span {
        background: #4169E1 !important;
        color: #fff !important;
        border-color: #4169E1 !important;
    }

    .pagination .page-item.disabled .page-link,
    .pagination .page-item.disabled span,
    .pagination .disabled span {
        background: #f5f5f5 !important;
        color: #999 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }

    .pagination .page-link svg,
    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }

    /* Hide default nav wrapper styles */
    .pagination nav {
        width: 100% !important;
    }

    .pagination-wrapper {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
    }

    .pagination-wrapper nav {
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
    }

    .pagination-wrapper ul {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0.5rem !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-footer {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .add-to-cart {
            width: 100%;
            min-width: unset;
        }
        
        .product-price {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
        
        .add-to-cart {
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
        }
    }

    /* Search Results Indicator */
    .search-results-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
    }

    .search-results-info h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .search-results-info p {
        font-size: 1rem;
        opacity: 0.95;
        margin: 0;
    }

    .search-query-highlight {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
        margin: 0 0.3rem;
    }

    .clear-search-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid white;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .clear-search-btn:hover {
        background: white;
        color: #667eea;
    }

    /* RTL Support for Search Results */
    @if(is_rtl())
    .search-results-info h3,
    .search-results-info p {
        direction: rtl;
    }
    @endif

    .no-results {
        text-align: center;
        padding: 5rem 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .no-results::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(78, 115, 223, 0.05) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .no-results-content {
        position: relative;
        z-index: 1;
    }

    .no-results-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3);
        animation: iconBounce 3s ease-in-out infinite;
    }

    @keyframes iconBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .no-results-icon i {
        font-size: 3.5rem;
        color: white;
        margin: 0;
    }

    .no-results h3 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 1rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .no-results p {
        font-size: 1.15rem;
        color: #6c757d;
        margin-bottom: 2.5rem;
        line-height: 1.6;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .no-results-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary-action {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        font-size: 1.05rem;
    }

    .btn-primary-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .btn-secondary-action {
        background: white;
        color: #667eea;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s;
        border: 2px solid #667eea;
        font-size: 1.05rem;
    }

    .btn-secondary-action:hover {
        background: #667eea;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
    }

    /* RTL Support for No Results */
    @if(is_rtl())
    .no-results h3,
    .no-results p {
        direction: rtl;
        text-align: center;
    }
    @endif

    @media (max-width: 768px) {
        .no-results {
            padding: 3rem 1.5rem;
        }

        .no-results-icon {
            width: 100px;
            height: 100px;
        }

        .no-results-icon i {
            font-size: 2.5rem;
        }

        .no-results h3 {
            font-size: 1.5rem;
        }

        .no-results p {
            font-size: 1rem;
        }

        .no-results-actions {
            flex-direction: column;
        }

        .btn-primary-action,
        .btn-secondary-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="products-section">
    <div class="container">
        <div class="section-header">
            <h2>{{ __t('messages.all_products') }}</h2>
        </div>

        @if(request('search'))
        <div class="search-results-info">
            <h3>
                <i class="fas fa-search"></i>
                {{ __t('messages.search_results') }}
            </h3>
            <p>
                {{ __t('messages.found') }} <strong>{{ $products->total() }}</strong> {{ __t('messages.products_for') }}
                <span class="search-query-highlight">"{{ request('search') }}"</span>
            </p>
            <a href="{{ route('products') }}" class="clear-search-btn">
                <i class="fas fa-times"></i>
                {{ __t('messages.clear_search') }}
            </a>
        </div>
        @endif

        @if($products->count() > 0)
        <div class="product-grid">
            @forelse($products as $product)
            <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
                <div class="product-image">
                    @if($product->is_new)
                    <div class="product-badge">{{ __t('messages.new') }}</div>
                    @elseif($product->sale_price && $product->sale_price < $product->price)
                    <div class="product-badge">{{ __t('messages.sale') }}</div>
                    @elseif($product->is_featured)
                    <div class="product-badge">{{ __t('messages.hot') }}</div>
                    @endif
                    <div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                        <i class="far fa-heart"></i>
                    </div>
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                </div>
                <div class="product-info">
                    <div class="product-title">{{ $product->name }}</div>
                    <div class="product-description">{{ Str::limit($product->short_description, 60) }}</div>
                    <div class="product-footer">
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="original-price">₪ {{ number_format($product->price, 0) }}</span>
                                <span>₪ {{ number_format($product->sale_price, 0) }}</span>
                            @else
                                <span class="original-price" style="visibility: hidden;">₪ 0</span>
                                <span>₪ {{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                        @if($product->stock_status === 'out_of_stock')
                        <button class="add-to-cart out-of-stock"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                onclick="event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                            @if(is_rtl())
                                {{ __t('messages.request_product') }} <i class="fas fa-bell"></i>
                            @else
                                <i class="fas fa-bell"></i> {{ __t('messages.request_product') }}
                            @endif
                        </button>
                        @else
                        <button class="add-to-cart {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                data-product-id="{{ $product->id }}"
                                data-original-text="{{ __t('messages.add_to_cart') }}"
                                data-added-text="{{ __t('messages.in_cart') }}"
                                onclick="event.stopPropagation(); addToCart({{ $product->id }}, this);">
                            @if(in_array($product->id, $cartProductIds))
                                @if(is_rtl())
                                    {{ __t('messages.in_cart') }} <i class="fas fa-check"></i>
                                @else
                                    <i class="fas fa-check"></i> {{ __t('messages.in_cart') }}
                                @endif
                            @else
                                @if(is_rtl())
                                    {{ __t('messages.add_to_cart') }} <i class="fas fa-shopping-cart"></i>
                                @else
                                    <i class="fas fa-shopping-cart"></i> {{ __t('messages.add_to_cart') }}
                                @endif
                            @endif
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
        @else
        <div class="no-results">
            <div class="no-results-content">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>{{ __t('messages.no_products_found_title') }}</h3>
                <p>
                    @if(request('search'))
                        {{ __t('messages.no_products_found_search', ['query' => request('search')]) }}
                    @else
                        {{ __t('messages.no_products_available') }}
                    @endif
                </p>
                <div class="no-results-actions">
                    <a href="{{ route('products') }}" class="btn-primary-action">
                        <i class="fas fa-th-large"></i>
                        {{ __t('messages.view_all_products') }}
                    </a>
                    @if(request('search'))
                    <a href="{{ route('products') }}" class="btn-secondary-action">
                        <i class="fas fa-times"></i>
                        {{ __t('messages.clear_filters') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if(isset($products) && method_exists($products, 'hasPages') && $products->hasPages())
        <div class="pagination-wrapper" style="display: flex; justify-content: center; margin: 3rem 0 2rem 0; padding: 0 1rem; width: 100%;">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    // Add event listener for wishlist buttons to force color change
    document.addEventListener('DOMContentLoaded', function() {
        // Observer to watch for class changes on wishlist buttons
        const observeWishlistButtons = () => {
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            wishlistButtons.forEach(button => {
                // Create a MutationObserver for each button
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === 'class') {
                            const icon = button.querySelector('i');
                            if (button.classList.contains('active')) {
                                // Force red color when active
                                if (icon) {
                                    icon.style.color = '#ff0000';
                                }
                            } else {
                                // Reset to gray when not active
                                if (icon) {
                                    icon.style.color = '#666';
                                }
                            }
                        }
                    });
                });
                
                // Start observing
                observer.observe(button, { attributes: true });
            });
        };
        
        // Initial observation
        setTimeout(observeWishlistButtons, 500);
    });
</script>

@endsection
