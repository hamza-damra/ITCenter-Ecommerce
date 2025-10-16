@extends('layouts.app')

@section('title', __t('messages.home') . ' - IT Center')

@section('content')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(to right, rgba(0, 0, 0, 0.2), transparent), radial-gradient(circle, rgba(0, 0, 0, 0.5),transparent), url('{{ asset('images/assets/wallpaper2.png') }}');
        background-size: cover;
        background-repeat: no-repeat;
        padding: 8rem 2rem;
        margin-bottom: 3rem;
        position: relative;
        height: 700px;
    }

    .hero-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .hero-content {
        max-width: 600px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .hero-content h1 {
        font-size: 3.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        font-weight: 700;
    }

    .hero-content p {
        color: rgba(255,255,255,1);
        font-size: 1.2rem;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .hero-btn {
        background: #fff;
        color: #333;
        padding: 1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        background: #f0f0f0;
    }

    /* Categories Section */
    .categories-section {
        background: #fff;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #333;
        font-weight: 700;
    }

    .view-more {
        color: #666;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .view-more:hover {
        color: #e69270ff;
    }

    /* RTL Support for arrows */
    [dir="rtl"] .view-more {
        flex-direction: row-reverse;
    }

    .categories-wrapper {
        position: relative;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 60px;
    }

    .categories-grid {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        padding: 1rem 0;
    }

    .categories-grid::-webkit-scrollbar {
        display: none;
    }

    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s;
        min-width: 120px;
        flex-shrink: 0;
    }

    .category-item:hover {
        transform: translateY(-5px);
    }

    .category-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        border: 2px solid #e0e0e0;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .category-nav-btn:hover {
        background: #e69270ff;
        border-color: #e69270ff;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .category-nav-btn.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    .category-nav-btn.left {
        left: 0;
    }

    .category-nav-btn.right {
        right: 0;
    }

    .category-nav-btn i {
        font-size: 1.2rem;
        color: #333;
    }

    .category-nav-btn:hover i {
        color: #fff;
    }

    .category-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s;
    }

    .category-item:hover .category-icon {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .category-icon img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }

    .category-name {
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
    }

    /* Featured Brands Slider */
    .brands-section {
        background: #1a1a1a;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .brands-section .section-header h2 {
        color: #fff;
    }

    .brands-section .view-more {
        color: #aaa;
    }

    .brands-section .view-more:hover {
        color: #fff;
    }

    .brands-slider {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 1rem 0;
        scrollbar-width: none;
    }

    .brands-slider::-webkit-scrollbar {
        display: none;
    }

    .brand-card {
        min-width: 200px;
        height: 120px;
        background: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .brand-card:hover {
        transform: scale(1.05);
    }

    .brand-card img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Banner Sliders */
    .banners-section {
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .banners-slider {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .banner-large, .banner-small {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .banner-large {
        height: 350px;
    }

    .banner-small {
        height: 350px;
    }

    .banner-large:hover, .banner-small:hover {
        transform: scale(1.02);
    }

    .banner-large img, .banner-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Special Cards Grid */
    .special-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .special-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s;
    }

    .special-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .special-card-image {
        height: 250px;
        position: relative;
    }

    .special-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .special-card-content {
        padding: 1.5rem;
    }

    .special-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .special-card-subtitle {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .special-card-btn {
        color: #e69270ff;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Builder Cards */
    .builder-cards {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .builder-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem 1.5rem;
        color: #fff;
        cursor: pointer;
        transition: transform 0.3s;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .builder-card:nth-child(1) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .builder-card:nth-child(2) { background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%); }
    .builder-card:nth-child(3) { background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%); }
    .builder-card:nth-child(4) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .builder-card:nth-child(5) { background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%); }

    .builder-card:hover {
        transform: translateY(-5px);
    }

    .builder-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .builder-card-link {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.9);
    }

    .builder-card-image {
        margin-top: 1rem;
        height: 100px;
    }

    .builder-card-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Popular Tags */
    .popular-tags {
        background: #1a1a1a;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 3rem;
    }

    .popular-tags h3 {
        color: #fff;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        font-style: italic;
        font-weight: 700;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .tag {
        background: rgba(255,255,255,0.1);
        color: #fff;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .tag:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
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

    @media (max-width: 1200px) {
        .special-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        .builder-cards {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .banners-slider {
            grid-template-columns: 1fr;
        }
        .special-cards {
            grid-template-columns: 1fr;
        }
        .builder-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        .categories-grid {
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
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
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1>{{ __t('messages.hero_title') }}</h1>
            <p>{{ __t('messages.hero_subtitle') }}</p>
            <a href="{{ route('products') }}" class="hero-btn">{{ __t('messages.shop_now') }}</a>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>{{ __t('messages.shop_by_category') }}</h2>
            <a href="{{ route('categories') }}" class="view-more">
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
            </a>
        </div>

        <div class="categories-wrapper">
            <button class="category-nav-btn left" id="categoryScrollLeft">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="categories-grid" id="categoriesGrid">
                @foreach($categories as $category)
                <div class="category-item" onclick="window.location.href='{{ route('products', ['category' => $category->slug]) }}'">
                    <div class="category-icon">
                        @if($category->image)
                            @if(str_starts_with($category->image, 'http'))
                                <img src="{{ $category->image }}" alt="{{ $category->name }}">
                            @else
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                            @endif
                        @else
                            <img src="{{ asset('images/products/default.png') }}" alt="{{ $category->name }}">
                        @endif
                    </div>
                    <div class="category-name">{{ $category->name }}</div>
                </div>
                @endforeach
            </div>

            <button class="category-nav-btn right" id="categoryScrollRight">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- Special Offer Cards -->
<div class="container">
    @if($activeOffers->count() > 0 || $onSaleProducts->count() > 0)
    <div class="special-cards">
        @foreach($activeOffers->take(3) as $offer)
        <div class="special-card" onclick="window.location.href='{{ route('offer.show', $offer->slug) }}'">
            <div class="special-card-image">
                @if($offer->banner_image)
                    @if(str_starts_with($offer->banner_image, 'http'))
                        <img src="{{ $offer->banner_image }}" alt="{{ $offer->name }}">
                    @else
                        <img src="{{ asset($offer->banner_image) }}" alt="{{ $offer->name }}">
                    @endif
                @else
                    <img src="https://via.placeholder.com/400x250/ff6b6b/fff?text={{ urlencode($offer->name) }}" alt="{{ $offer->name }}">
                @endif
            </div>
            <div class="special-card-content">
                <div class="special-card-title">{{ $offer->name }}</div>
                <div class="special-card-subtitle">{{ Str::limit($offer->description, 50) }}</div>
                <a href="{{ route('offer.show', $offer->slug) }}" class="special-card-btn">
                    Shop Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
        
        @if($activeOffers->count() < 3 && $onSaleProducts->count() > 0)
        <div class="special-card" onclick="window.location.href='{{ route('products', ['filter' => 'sale']) }}'">
            <div class="special-card-image">
                @if($onSaleProducts->first()->main_image)
                    <img src="{{ $onSaleProducts->first()->main_image }}" alt="On Sale">
                @else
                    <img src="https://via.placeholder.com/400x250/48b1bf/fff?text=On+Sale" alt="On Sale">
                @endif
            </div>
            <div class="special-card-content">
                <div class="special-card-title">On Sale Now</div>
                <div class="special-card-subtitle">Don't miss our amazing deals!</div>
                <a href="{{ route('products', ['filter' => 'sale']) }}" class="special-card-btn">
                    Shop Sale <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endif

        @if($activeOffers->count() + ($onSaleProducts->count() > 0 ? 1 : 0) < 4 && $newProducts->count() > 0)
        <div class="special-card" onclick="window.location.href='{{ route('products', ['filter' => 'new']) }}'">
            <div class="special-card-image">
                @if($newProducts->first()->main_image)
                    <img src="{{ $newProducts->first()->main_image }}" alt="New Arrivals">
                @else
                    <img src="https://via.placeholder.com/400x250/4ecdc4/fff?text=New+Arrivals" alt="New Arrivals">
                @endif
            </div>
            <div class="special-card-content">
                <div class="special-card-title">New Arrivals</div>
                <div class="special-card-subtitle">Be the first to explore today's newest finds.</div>
                <a href="{{ route('products', ['filter' => 'new']) }}" class="special-card-btn">
                    See What's New <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Featured Products -->
    <div class="section-header">
        <h2>{{ __t('messages.featured_products') }}</h2>
        <a href="{{ route('products') }}" class="view-more">
            {{ __t('messages.view_more') }} <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
        </a>
    </div>

    <div class="product-grid">
        @foreach($featuredProducts as $product)
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
                        <i class="fas fa-shopping-cart"></i> {{ __t('messages.add_to_cart') }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Shop by Categories - Builder Cards -->
    @if($categories->count() >= 5)
    <div class="section-header">
        <h2>{{ __t('messages.shop_by_category') }}</h2>
    </div>

    <div class="builder-cards">
        @foreach($categories->take(5) as $index => $category)
        <div class="builder-card" onclick="window.location.href='{{ route('products', ['category' => $category->slug]) }}'">
            <div class="builder-card-title">{{ $category->name }}</div>
            <div class="builder-card-link">{{ __t('messages.explore_products', ['count' => $category->products_count]) }} ›</div>
            @if($category->image)
            <div class="builder-card-image">
                @if(str_starts_with($category->image, 'http'))
                    <img src="{{ $category->image }}" alt="{{ $category->name }}">
                @else
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- New Arrivals -->
    <div class="section-header">
        <h2>{{ __t('messages.new_arrivals') }}</h2>
        <a href="{{ route('products') }}" class="view-more">
            @if(app()->getLocale() === 'ar')
                <i class="fas fa-arrow-left"></i> {{ __t('messages.view_more') }}
            @else
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-right"></i>
            @endif
        </a>
    </div>

    <div class="product-grid">
        @foreach($newProducts as $product)
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
                        <i class="fas fa-shopping-cart"></i> {{ __t('messages.add_to_cart') }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Bestsellers -->
    @if($bestsellerProducts->count() > 0)
    <div class="section-header">
        <h2>{{ __t('messages.best_sellers') }}</h2>
        <a href="{{ route('products', ['filter' => 'bestseller']) }}" class="view-more">
            @if(app()->getLocale() === 'ar')
                <i class="fas fa-arrow-left"></i> {{ __t('messages.view_more') }}
            @else
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-right"></i>
            @endif
        </a>
    </div>

    <div class="product-grid">
        @foreach($bestsellerProducts as $product)
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
                        <i class="fas fa-shopping-cart"></i> {{ __t('messages.add_to_cart') }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- On Sale Products -->
    @if($onSaleProducts->count() > 0)
    <div class="section-header">
        <h2>{{ __t('messages.on_sale') }}</h2>
        <a href="{{ route('products', ['filter' => 'sale']) }}" class="view-more">
            @if(app()->getLocale() === 'ar')
                <i class="fas fa-arrow-left"></i> {{ __t('messages.view_more') }}
            @else
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-right"></i>
            @endif
        </a>
    </div>

    <div class="product-grid">
        @foreach($onSaleProducts as $product)
        <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
            <div class="product-image">
                <div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <div class="product-badge">SALE</div>
                <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
            </div>
            <div class="product-info">
                <div class="product-title">{{ $product->name }}</div>
                <div class="product-description">{{ Str::limit($product->short_description, 60) }}</div>
                <div class="product-footer">
                    <div class="product-price">
                        <span style="text-decoration: line-through; color: #999; font-size: 0.9rem;">₪ {{ number_format($product->price, 0) }}</span>
                        ₪ {{ number_format($product->sale_price, 0) }}
                    </div>
                    <button class="add-to-cart" data-product-id="{{ $product->id }}" onclick="event.stopPropagation(); addToCart({{ $product->id }}, this);">
                        <i class="fas fa-shopping-cart"></i> {{ __t('messages.add_to_cart') }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script>
    // Category Slider Navigation
    document.addEventListener('DOMContentLoaded', function() {
        const categoriesGrid = document.getElementById('categoriesGrid');
        const scrollLeftBtn = document.getElementById('categoryScrollLeft');
        const scrollRightBtn = document.getElementById('categoryScrollRight');
        
        if (categoriesGrid && scrollLeftBtn && scrollRightBtn) {
            const scrollAmount = 300;
            
            // Update button states
            function updateButtonStates() {
                const maxScroll = categoriesGrid.scrollWidth - categoriesGrid.clientWidth;
                
                if (categoriesGrid.scrollLeft <= 0) {
                    scrollLeftBtn.classList.add('disabled');
                } else {
                    scrollLeftBtn.classList.remove('disabled');
                }
                
                if (categoriesGrid.scrollLeft >= maxScroll - 5) {
                    scrollRightBtn.classList.add('disabled');
                } else {
                    scrollRightBtn.classList.remove('disabled');
                }
            }
            
            // Scroll left
            scrollLeftBtn.addEventListener('click', function() {
                categoriesGrid.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
            
            // Scroll right
            scrollRightBtn.addEventListener('click', function() {
                categoriesGrid.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
            
            // Update button states on scroll
            categoriesGrid.addEventListener('scroll', updateButtonStates);
            
            // Initial button state
            updateButtonStates();
            
            // Update on window resize
            window.addEventListener('resize', updateButtonStates);
        }

        // Add additional event listener for wishlist buttons to force color change
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
    });
</script>

@endsection
