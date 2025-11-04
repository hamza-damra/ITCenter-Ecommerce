@extends('layouts.app')

@section('title', __t('messages.favorites') . ' - IT Center')

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

    /* Favorites Section */
    .favorites-container {
        background: #fff;
        padding: 3rem 0;
        min-height: 60vh;
    }

    .favorites-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #4169E1;
    }

    .favorites-header h1 {
        font-size: 2.5rem;
        color: #333;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .favorites-header h1 i {
        color: #4169E1;
        font-size: 2rem;
    }

    .favorites-count {
        color: #666;
        font-size: 1rem;
        font-weight: 500;
    }

    /* Empty State */
    .empty-favorites {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-favorites i {
        font-size: 5rem;
        color: #e0e0e0;
        margin-bottom: 1.5rem;
    }

    .empty-favorites h2 {
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .empty-favorites p {
        color: #999;
        margin-bottom: 2rem;
    }

    .empty-favorites .btn-primary {
        background: #4169E1;
        color: #fff;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
    }

    .empty-favorites .btn-primary:hover {
        background: #1E90FF;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(65, 105, 225, 0.4);
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        border: 1px solid rgba(230, 146, 112, 0.08);
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2762f3 0%, #1a4dbf 50%, #333333 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(39, 98, 243, 0.12), 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: rgba(39, 98, 243, 0.2);
    }

    .product-card:hover::before {
        opacity: 0;
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 1rem;
    }

    .product-image img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .wishlist-btn {
        position: absolute;
        top: 10px;
        {{ is_rtl() ? 'right' : 'left' }}: 10px;
        background: rgba(255, 255, 255, 0.95);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        border: 1px solid rgba(39, 98, 243, 0.1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }

    .wishlist-btn:hover {
        background: #2762f3;
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.3);
    }

    .wishlist-btn.active {
        background: #2762f3;
        color: #fff;
        border-color: #2762f3;
    }

    .wishlist-btn i {
        font-size: 1rem;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        {{ is_rtl() ? 'left' : 'right' }}: 10px;
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.4);
        text-transform: uppercase;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1e293b;
        transition: color 0.3s ease;
    }

    .product-card:hover .product-title {
        color: #2762f3;
    }

    .product-description {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2762f3;
    }

    /* Icon-Only Add to Cart Button */
    .add-to-cart-icon {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: #ffffff;
        border: none;
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.25);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .add-to-cart-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a4dbf 0%, #0f3a8f 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
        z-index: 0;
    }

    .add-to-cart-icon i {
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .add-to-cart-icon:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(39, 98, 243, 0.4), 0 2px 8px rgba(39, 98, 243, 0.2);
    }

    .add-to-cart-icon:hover::before {
        opacity: 1;
    }

    .add-to-cart-icon:hover i {
        transform: scale(1.1);
    }

    .add-to-cart-icon:active {
        transform: translateY(0) scale(1);
    }

    /* Success state - green with check icon */
    .add-to-cart-icon.in-cart {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
    }

    .add-to-cart-icon.in-cart::before {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .add-to-cart-icon.in-cart:hover {
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4), 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .add-to-cart-icon.in-cart i {
        animation: cartBounce 0.5s ease;
    }

    /* Out of stock state - orange with bell icon */
    .add-to-cart-icon.out-of-stock {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.25);
        cursor: pointer;
    }

    .add-to-cart-icon.out-of-stock::before {
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    }

    .add-to-cart-icon.out-of-stock:hover {
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4), 0 2px 8px rgba(249, 115, 22, 0.2);
    }

    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    /* Price styling */
    .product-price .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }

    .product-price .current-price {
        color: #2762f3;
        font-weight: 700;
        font-size: 1.2rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .product-footer {
            gap: 0.75rem;
        }

        .add-to-cart-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }

        .add-to-cart-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 1rem;
        }

        .favorites-header h1 {
            font-size: 1.5rem;
        }

        .favorites-count {
            font-size: 0.9rem;
        }
    }

    /* RTL Support */
    [dir="rtl"] .product-footer {
        flex-direction: row-reverse;
    }

    /* Loading State */
    .loading {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .loading i {
        font-size: 3rem;
        color: #4169E1;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Scroll Animation - Bottom to Top */
    .scroll-animate {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .scroll-animate.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="favorites-container">
    <div class="container">
        @if($favorites->count() > 0)
            <div class="favorites-header">
                <h1>
                    <i class="fas fa-heart"></i>
                    {{ __t('messages.my_favorites') }}
                </h1>
                <div class="favorites-count">
                    {{ $favorites->count() }} {{ $favorites->count() == 1 ? __t('messages.item') : __t('messages.items') }}
                </div>
            </div>

            <div class="product-grid">
                @foreach($favorites as $product)
                <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
                    <div class="product-image">
                        <button class="wishlist-btn active" 
                                data-product-id="{{ $product->id }}"
                                onclick="event.stopPropagation(); toggleFavorite({{ $product->id }}, this);">
                            <i class="fas fa-heart"></i>
                        </button>
                        @if($product->is_new)
                        <div class="product-badge">NEW</div>
                        @elseif($product->sale_price && $product->sale_price < $product->price)
                        <div class="product-badge">SALE</div>
                        @elseif($product->is_featured)
                        <div class="product-badge">HOT</div>
                        @endif
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                    </div>
                    <div class="product-info">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-description">{{ Str::limit($product->short_description, 60) }}</div>
                        <div class="product-footer">
                            <div class="product-price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="original-price">₪ {{ number_format($product->price, 0) }}</span>
                                    <span class="current-price">₪ {{ number_format($product->sale_price, 0) }}</span>
                                @else
                                    <span class="current-price">₪ {{ number_format($product->price, 0) }}</span>
                                @endif
                            </div>
                            @if($product->stock_status === 'out_of_stock')
                            <button class="add-to-cart-icon out-of-stock"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    title="{{ __t('messages.request_product') }}"
                                    aria-label="{{ __t('messages.request_product') }}"
                                    onclick="event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                                <i class="fas fa-bell"></i>
                            </button>
                            @else
                            <button class="add-to-cart-icon {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    title="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                    aria-label="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                    onclick="event.stopPropagation(); addToCart({{ $product->id }}, this);">
                                <i class="fas {{ in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-favorites">
                <i class="far fa-heart"></i>
                <h2>{{ __t('messages.no_favorites') }}</h2>
                <p>{{ __t('messages.no_favorites_description') }}</p>
                <a href="{{ route('products') }}" class="btn-primary">{{ __t('messages.start_shopping') }}</a>
            </div>
        @endif
    </div>
</div>

<script>
// Scroll Animation - Bottom to Top
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.classList.add('scroll-animate');
        observer.observe(card);
    });
});

// CSRF Token setup for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

/**
 * Toggle favorite status for a product
 */
function toggleFavorite(productId, button) {
    const icon = button.querySelector('i');
    const isActive = button.classList.contains('active');
    
    // Optimistic UI update
    button.classList.toggle('active');
    icon.classList.toggle('fas');
    icon.classList.toggle('far');
    
    // Send request to server
    fetch(`/favorites/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update favorites count in header
            updateFavoritesCount();
            
            // If removed, remove the card from the page after a short delay
            if (data.action === 'removed') {
                setTimeout(() => {
                    const card = button.closest('.product-card');
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    
                    setTimeout(() => {
                        card.remove();
                        
                        // Check if there are no more favorites and reload the page
                        const remainingCards = document.querySelectorAll('.product-card');
                        if (remainingCards.length === 0) {
                            location.reload();
                        } else {
                            // Update the count display
                            const countElement = document.querySelector('.favorites-count');
                            if (countElement) {
                                const newCount = remainingCards.length;
                                const itemText = newCount === 1 ? '{{ __t("messages.item") }}' : '{{ __t("messages.items") }}';
                                countElement.textContent = `${newCount} ${itemText}`;
                            }
                        }
                    }, 300);
                }, 200);
            }
        } else {
            // Revert UI if request failed
            button.classList.toggle('active');
            icon.classList.toggle('fas');
            icon.classList.toggle('far');
        }
    })
    .catch(error => {
        console.error('Error toggling favorite:', error);
        // Revert UI on error
        button.classList.toggle('active');
        icon.classList.toggle('fas');
        icon.classList.toggle('far');
    });
}

/**
 * Update the favorites count in the header
 */
function updateFavoritesCount() {
    fetch('/favorites/ids')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.header-icon .fa-heart').parentElement.querySelector('.badge');
            if (badge) {
                badge.textContent = data.favoriteIds.length;
            }
        })
        .catch(error => console.error('Error updating favorites count:', error));
}

// Update favorites count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFavoritesCount();
});

/**
 * Add product to cart
 */
function addToCart(productId, button) {
    // Prevent multiple rapid clicks
    if (button.disabled) return;
    button.disabled = true;

    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button state
            button.classList.add('in-cart');
            button.title = '{{ __t("messages.in_cart") }}';
            button.setAttribute('aria-label', '{{ __t("messages.in_cart") }}');

            // Update icon
            const icon = button.querySelector('i');
            icon.classList.remove('fa-shopping-cart');
            icon.classList.add('fa-check');

            // Update cart count in header
            updateCartCount();

            // Show success message (optional)
            console.log('Product added to cart successfully');
        } else {
            console.error('Failed to add product to cart:', data.message);
            alert(data.message || '{{ __t("messages.error_adding_to_cart") }}');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        alert('{{ __t("messages.error_adding_to_cart") }}');
    })
    .finally(() => {
        button.disabled = false;
    });
}

/**
 * Request out of stock product
 */
function requestProduct(productId, productName) {
    // Show a confirmation or modal for product request
    const message = `{{ __t("messages.request_product_message") }}`.replace(':product', productName);

    if (confirm(message || `Would you like to be notified when ${productName} is back in stock?`)) {
        fetch(`/products/request/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || '{{ __t("messages.request_product_success") }}');
            } else {
                alert(data.message || '{{ __t("messages.request_product_error") }}');
            }
        })
        .catch(error => {
            console.error('Error requesting product:', error);
            alert('{{ __t("messages.request_product_error") }}');
        });
    }
}

/**
 * Update cart count in header
 */
function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.header-icon .fa-shopping-cart').parentElement.querySelector('.badge');
            if (badge) {
                badge.textContent = data.count;
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}
</script>
@endsection
