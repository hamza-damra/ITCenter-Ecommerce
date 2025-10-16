@extends('layouts.app')

@section('title', __t('messages.favorites') . ' - IT Center')

@section('content')
<style>
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
        border-bottom: 2px solid #e0e0e0;
    }

    .favorites-header h1 {
        font-size: 2rem;
        color: #333;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .favorites-header h1 i {
        color: #ff4757;
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
        background: #e69270ff;
        color: #fff;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
    }

    .empty-favorites .btn-primary:hover {
        background: #d07e5eff;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(230, 146, 112, 0.3);
    }

    /* Product Grid */
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
        box-shadow: 0 4px 16px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.2), 0 4px 16px rgba(0,0,0,0.12);
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
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
        background: #fff;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .wishlist-btn:hover {
        background: #ff4757;
        color: #fff;
        transform: scale(1.1);
    }

    .wishlist-btn.active {
        background: #ff4757;
        color: #fff;
    }

    .wishlist-btn i {
        font-size: 1rem;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        {{ is_rtl() ? 'left' : 'right' }}: 10px;
        background: #ff4757;
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
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
        gap: 1rem;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #e69270ff;
    }

    .add-to-cart {
        background: #e69270ff;
        color: #fff;
        padding: 0.6rem 1rem;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-width: 140px;
        white-space: nowrap;
    }

    .add-to-cart:hover {
        background: #d07e5eff;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 146, 112, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
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
        color: #e69270ff;
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
                            <button class="add-to-cart" onclick="event.stopPropagation();">{{ __t('messages.add_to_cart') }}</button>
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
</script>
@endsection
