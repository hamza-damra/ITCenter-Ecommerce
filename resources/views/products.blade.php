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
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: #f5f5f5;
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

    .product-badge {
        position: absolute !important;
        top: 10px !important;
        bottom: auto !important;
        @if(is_rtl())
        left: 10px !important;
        right: auto !important;
        @else
        right: 10px !important;
        left: auto !important;
        @endif
        background: #ff4757;
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 5;
    }

    .wishlist-btn {
        position: absolute !important;
        top: 10px !important;
        bottom: auto !important;
        @if(is_rtl())
        right: 10px !important;
        left: auto !important;
        @else
        left: 10px !important;
        right: auto !important;
        @endif
        background: #fff;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .wishlist-btn:hover {
        background: #fff;
        transform: scale(1.1);
    }

    .wishlist-btn:hover i {
        color: #ff0000 !important;
    }

    .wishlist-btn.active {
        background: #fff !important;
    }

    .wishlist-btn.active i {
        color: #ff0000 !important;
    }

    .wishlist-btn i {
        font-size: 1rem;
        color: #666;
        transition: all 0.3s;
    }

    /* Solid heart icon should be red */
    .wishlist-btn i.fas.fa-heart {
        color: #ff0000 !important;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .product-description {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
    }

    .add-to-cart {
        background: #000;
        color: #fff;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-width: 140px;
        white-space: nowrap;
        font-size: 0.9rem;
    }

    .add-to-cart:hover {
        background: #333;
        transform: translateY(-2px);
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

    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .no-results i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 1.5rem;
    }

    .no-results h3 {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 1rem;
    }

    .no-results p {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 2rem;
    }
</style>

<div class="products-section">
    <div class="container">
        <div class="section-header">
            <h2>All Products</h2>
        </div>

        @if(request('search'))
        <div class="search-results-info">
            <h3>
                <i class="fas fa-search"></i>
                Search Results
            </h3>
            <p>
                Found <strong>{{ $products->total() }}</strong> {{ Str::plural('product', $products->total()) }} for
                <span class="search-query-highlight">"{{ request('search') }}"</span>
            </p>
            <a href="{{ route('products') }}" class="clear-search-btn">
                <i class="fas fa-times"></i>
                Clear Search
            </a>
        </div>
        @endif

        @if($products->count() > 0)
        <div class="product-grid">
            @forelse($products as $product)
            <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
                <div class="product-image">
                    <div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                        <i class="far fa-heart"></i>
                    </div>
                    @if($product->is_new)
                    <div class="product-badge">NEW</div>
                    @elseif($product->sale_price && $product->sale_price < $product->price)
                    <div class="product-badge">SALE</div>
                    @elseif($product->is_featured)
                    <div class="product-badge">HOT</div>
                    @endif
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                </div>
                <div class="product-info">
                    <div class="product-title">{{ $product->name }}</div>
                    <div class="product-description">{{ Str::limit($product->short_description, 60) }}</div>
                    <div class="product-footer">
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span style="text-decoration: line-through; color: #999; font-size: 0.9rem;">₪ {{ number_format($product->price, 0) }}</span>
                                ₪ {{ number_format($product->sale_price, 0) }}
                            @else
                                ₪ {{ number_format($product->price, 0) }}
                            @endif
                        </div>
                        <button class="add-to-cart" data-product-id="{{ $product->id }}" onclick="event.stopPropagation(); addToCart({{ $product->id }}, this);">
                            <i class="fas fa-shopping-cart"></i> Add to cart
                        </button>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
        @else
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3>No Products Found</h3>
            <p>
                @if(request('search'))
                    We couldn't find any products matching "<strong>{{ request('search') }}</strong>"
                @else
                    No products available at the moment
                @endif
            </p>
            <a href="{{ route('products') }}" class="clear-search-btn">
                <i class="fas fa-arrow-left"></i>
                View All Products
            </a>
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
