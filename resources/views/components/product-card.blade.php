{{-- Product Card Component --}}
{{-- Usage: <x-product-card :product="$product" /> --}}

@props(['product', 'showQuickActions' => true, 'showWishlist' => true])

<div class="product-card" data-product-id="{{ $product->id }}">
    {{-- Product Image --}}
    <div class="product-card-image">
        @if($product->images && $product->images->count() > 0)
            <img src="{{ asset('images/products/' . $product->images->first()->image_path) }}" 
                 alt="{{ $product->name }}" 
                 loading="lazy">
        @else
            <img src="{{ asset('images/placeholder-product.png') }}" 
                 alt="{{ $product->name }}" 
                 loading="lazy">
        @endif

        {{-- Product Badge --}}
        @if($product->discount_percentage > 0)
            <div class="product-badge discount-badge">
                -{{ $product->discount_percentage }}%
            </div>
        @elseif($product->is_featured)
            <div class="product-badge">
                {{ __('messages.featured') }}
            </div>
        @elseif($product->is_new)
            <div class="product-badge">
                {{ __('messages.new') }}
            </div>
        @endif

        {{-- Wishlist Button --}}
        @if($showWishlist)
            <button class="wishlist-btn {{ in_array($product->id, session('wishlist', [])) ? 'active' : '' }}" 
                    onclick="toggleWishlist({{ $product->id }})">
                <i class="{{ in_array($product->id, session('wishlist', [])) ? 'fas' : 'far' }} fa-heart"></i>
            </button>
        @endif

        {{-- Quick Actions --}}
        @if($showQuickActions)
            <div class="product-actions">
                <button class="quick-view-btn" onclick="quickView({{ $product->id }})" title="{{ __('messages.quick_view') }}">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="compare-btn" onclick="addToCompare({{ $product->id }})" title="{{ __('messages.compare') }}">
                    <i class="fas fa-balance-scale"></i>
                </button>
            </div>
        @endif

        {{-- Stock Indicator --}}
        @if($product->stock_quantity <= 0)
            <div class="stock-indicator">
                <i class="fas fa-times-circle"></i>
                {{ __('messages.out_of_stock') }}
            </div>
        @elseif($product->stock_quantity <= 5)
            <div class="stock-indicator low-stock">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $product->stock_quantity }} {{ __('messages.left') }}
            </div>
        @endif
    </div>

    {{-- Product Content --}}
    <div class="product-card-content">
        {{-- Product Title --}}
        <h3 class="product-card-title">
            <a href="{{ route('product.show', $product->slug) }}" class="text-primary">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Product Description --}}
        @if($product->short_description)
            <p class="product-card-description">
                {{ Str::limit($product->short_description, 100) }}
            </p>
        @endif

        {{-- Product Rating --}}
        @if($product->reviews_count > 0)
            <div class="product-rating">
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                <span class="rating-count">({{ $product->reviews_count }})</span>
            </div>
        @endif

        {{-- Product Footer --}}
        <div class="product-card-footer">
            {{-- Price --}}
            <div class="product-price">
                @if($product->discount_price && $product->discount_price < $product->price)
                    <span class="product-price-original">${{ number_format($product->price, 2) }}</span>
                    <span class="current-price">${{ number_format($product->discount_price, 2) }}</span>
                    @if($product->discount_percentage > 0)
                        <span class="discount-percentage">{{ $product->discount_percentage }}% {{ __('messages.off') }}</span>
                    @endif
                @else
                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            {{-- Add to Cart Button --}}
            @if($product->stock_quantity > 0)
                <button class="btn btn-primary btn-sm add-to-cart" 
                        onclick="addToCart({{ $product->id }})"
                        data-product-id="{{ $product->id }}">
                    <i class="fas fa-shopping-cart"></i>
                    {{ __('messages.add_to_cart') }}
                </button>
            @else
                <button class="btn btn-secondary btn-sm add-to-cart out-of-stock" disabled>
                    <i class="fas fa-bell"></i>
                    {{ __('messages.notify_me') }}
                </button>
            @endif
        </div>
    </div>
</div>

{{-- Styles specific to this component --}}
<style>
    .product-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-bounce);
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid transparent;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-blue);
    }

    .product-card-image {
        width: 100%;
        height: 200px;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: var(--space-4);
    }

    .product-card-image img {
        max-width: 85%;
        max-height: 85%;
        object-fit: contain;
        transition: transform var(--transition-normal);
    }

    .product-card:hover .product-card-image img {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: var(--space-3);
        {{ is_rtl() ? 'right' : 'left' }}: var(--space-3);
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-gray) 100%);
        color: var(--text-white);
        padding: var(--space-1) var(--space-3);
        border-radius: var(--radius-md);
        font-size: var(--text-xs);
        font-weight: 700;
        z-index: 5;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-badge.discount-badge {
        background: linear-gradient(135deg, var(--secondary-red) 0%, #dc2626 100%);
    }

    .wishlist-btn {
        position: absolute;
        top: var(--space-3);
        {{ is_rtl() ? 'left' : 'right' }}: var(--space-3);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-bounce);
        z-index: 10;
    }

    .wishlist-btn:hover {
        background: var(--text-white);
        transform: scale(1.1);
        box-shadow: var(--shadow-sm);
    }

    .wishlist-btn.active i {
        color: var(--secondary-red);
    }

    .product-actions {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        gap: var(--space-2);
        opacity: 0;
        transition: opacity var(--transition-normal);
        z-index: 15;
    }

    .product-card:hover .product-actions {
        opacity: 1;
    }

    .quick-view-btn, .compare-btn {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .quick-view-btn:hover, .compare-btn:hover {
        background: var(--primary-dark);
        color: var(--text-white);
        transform: scale(1.1);
    }

    .stock-indicator {
        position: absolute;
        bottom: var(--space-3);
        left: var(--space-3);
        background: linear-gradient(135deg, var(--secondary-red) 0%, #dc2626 100%);
        color: var(--text-white);
        padding: var(--space-1) var(--space-3);
        border-radius: var(--radius-md);
        font-size: var(--text-xs);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: var(--space-1);
    }

    .stock-indicator.low-stock {
        background: linear-gradient(135deg, var(--secondary-yellow) 0%, #d97706 100%);
    }

    .product-card-content {
        padding: var(--space-4);
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-card-title {
        font-size: var(--text-sm);
        font-weight: 600;
        margin-bottom: var(--space-2);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card-title a {
        color: var(--text-primary);
        text-decoration: none;
        transition: color var(--transition-normal);
    }

    .product-card:hover .product-card-title a {
        color: var(--primary-blue);
    }

    .product-card-description {
        font-size: var(--text-xs);
        color: var(--text-secondary);
        margin-bottom: var(--space-3);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        margin-bottom: var(--space-3);
        font-size: var(--text-xs);
    }

    .product-rating .stars {
        display: flex;
        gap: 1px;
    }

    .product-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: var(--space-3);
        border-top: 1px solid #f1f5f9;
        gap: var(--space-3);
    }

    .product-price {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-1);
        flex: 1;
    }

    .product-price-original {
        text-decoration: line-through;
        color: var(--text-muted);
        font-size: var(--text-xs);
        font-weight: 500;
    }

    .current-price {
        color: var(--text-primary);
        font-weight: 700;
        font-size: var(--text-lg);
    }

    .discount-percentage {
        font-size: var(--text-xs);
        color: var(--secondary-green);
        font-weight: 600;
        background: rgba(16, 185, 129, 0.1);
        padding: var(--space-1) var(--space-2);
        border-radius: var(--radius-sm);
    }

    .add-to-cart {
        white-space: nowrap;
        min-width: auto;
    }

    .add-to-cart.out-of-stock {
        background: transparent;
        color: var(--secondary-yellow);
        border-color: var(--secondary-yellow);
    }

    .add-to-cart.out-of-stock:hover {
        background: var(--secondary-yellow);
        color: var(--text-white);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .product-card-footer {
            flex-direction: column;
            gap: var(--space-2);
        }

        .add-to-cart {
            width: 100%;
        }
    }
</style>
