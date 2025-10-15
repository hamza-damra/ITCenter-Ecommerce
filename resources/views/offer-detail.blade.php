@extends('layouts.app')

@section('title', $offer->name . ' - Special Offer - IT Center')

@section('content')
<style>
    .offer-detail-container {
        padding: 3rem 0;
        background: #f8f9fa;
    }

    .offer-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 4rem 2rem;
        border-radius: 16px;
        margin-bottom: 3rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .offer-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .offer-hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
    }

    .offer-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
    }

    .offer-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .offer-description {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .offer-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .offer-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .offer-meta-item i {
        font-size: 1.2rem;
    }

    .offer-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    .products-section {
        background: #fff;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
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

    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ff4757;
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #4CAF50;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-category {
        color: #999;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-brand {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .product-pricing {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 1rem;
    }

    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.9rem;
    }

    .sale-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #4CAF50;
    }

    .savings {
        background: #ffeb3b;
        color: #333;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .product-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-view {
        flex: 1;
        background: #667eea;
        color: #fff;
        padding: 0.8rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-view:hover {
        background: #5568d3;
    }

    .btn-cart {
        flex: 1;
        background: #333;
        color: #fff;
        padding: 0.8rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-cart:hover {
        background: #000;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .timer-container {
        background: rgba(255,255,255,0.2);
        padding: 1.5rem;
        border-radius: 12px;
        margin-top: 2rem;
        backdrop-filter: blur(10px);
    }

    .timer-title {
        font-size: 1rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .countdown {
        display: flex;
        gap: 1rem;
    }

    .countdown-item {
        background: rgba(255,255,255,0.3);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        min-width: 80px;
    }

    .countdown-value {
        font-size: 2rem;
        font-weight: 700;
        display: block;
    }

    .countdown-label {
        font-size: 0.8rem;
        opacity: 0.9;
        text-transform: uppercase;
    }

    @media (max-width: 768px) {
        .offer-title {
            font-size: 2rem;
        }
        
        .offer-meta {
            flex-direction: column;
            gap: 1rem;
        }

        .countdown {
            flex-wrap: wrap;
        }

        .countdown-item {
            min-width: 60px;
            padding: 0.8rem;
        }

        .countdown-value {
            font-size: 1.5rem;
        }
    }
</style>

<div class="offer-detail-container">
    <div class="container">
        <!-- Offer Hero Section -->
        <div class="offer-hero">
            <div class="offer-hero-content">
                <span class="offer-badge">
                    <i class="fas fa-tag"></i> SPECIAL OFFER
                </span>
                <h1 class="offer-title">{{ $offer->name }}</h1>
                <p class="offer-description">{{ $offer->description }}</p>
                
                <div class="offer-meta">
                    <div class="offer-meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Valid from {{ $offer->start_date->format('M d, Y') }}</span>
                    </div>
                    <div class="offer-meta-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Until {{ $offer->end_date->format('M d, Y') }}</span>
                    </div>
                    @if($offer->discount_type && $offer->discount_value)
                    <div class="offer-meta-item">
                        <i class="fas fa-percent"></i>
                        <span>
                            @if($offer->discount_type === 'percentage')
                                {{ $offer->discount_value }}% OFF
                            @else
                                ₪{{ number_format($offer->discount_value, 0) }} OFF
                            @endif
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Countdown Timer -->
                @if($offer->end_date->isFuture())
                <div class="timer-container">
                    <div class="timer-title">
                        <i class="fas fa-clock"></i> Offer ends in:
                    </div>
                    <div class="countdown" id="countdown">
                        <div class="countdown-item">
                            <span class="countdown-value" id="days">00</span>
                            <span class="countdown-label">Days</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="hours">00</span>
                            <span class="countdown-label">Hours</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="minutes">00</span>
                            <span class="countdown-label">Minutes</span>
                        </div>
                        <div class="countdown-item">
                            <span class="countdown-value" id="seconds">00</span>
                            <span class="countdown-label">Seconds</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Offer Stats -->
        <div class="offer-stats">
            <div class="stat-card">
                <div class="stat-value">{{ $offer->products->count() }}</div>
                <div class="stat-label">Products in Offer</div>
            </div>
            @if($offer->discount_type && $offer->discount_value)
            <div class="stat-card">
                <div class="stat-value">
                    @if($offer->discount_type === 'percentage')
                        {{ $offer->discount_value }}%
                    @else
                        ₪{{ number_format($offer->discount_value, 0) }}
                    @endif
                </div>
                <div class="stat-label">Discount Value</div>
            </div>
            @endif
            @if($offer->min_purchase_amount)
            <div class="stat-card">
                <div class="stat-value">₪{{ number_format($offer->min_purchase_amount, 0) }}</div>
                <div class="stat-label">Minimum Purchase</div>
            </div>
            @endif
            <div class="stat-card">
                <div class="stat-value">{{ $offer->end_date->diffInDays(now()) }}</div>
                <div class="stat-label">Days Remaining</div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="products-section">
            <h2 class="section-title">
                <i class="fas fa-shopping-bag"></i> Products in This Offer
            </h2>

            @if($offer->products->count() > 0)
            <div class="product-grid">
                @foreach($offer->products as $product)
                <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
                    <div class="product-image">
                        @if($offer->discount_type === 'percentage')
                        <div class="discount-badge">-{{ $offer->discount_value }}%</div>
                        @elseif($offer->discount_type === 'fixed')
                        <div class="discount-badge">-₪{{ number_format($offer->discount_value, 0) }}</div>
                        @endif
                        
                        @if($product->is_new)
                        <div class="product-badge">NEW</div>
                        @elseif($product->is_featured)
                        <div class="product-badge">HOT</div>
                        @endif
                        
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        @if($product->category)
                        <div class="product-category">{{ $product->category->name }}</div>
                        @endif
                        
                        <h3 class="product-title">{{ $product->name }}</h3>
                        
                        @if($product->brand)
                        <div class="product-brand">
                            <i class="fas fa-building"></i> {{ $product->brand->name }}
                        </div>
                        @endif

                        <div class="product-pricing">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="original-price">₪{{ number_format($product->price, 0) }}</span>
                                <span class="sale-price">₪{{ number_format($product->sale_price, 0) }}</span>
                                @php
                                    $savings = (($product->price - $product->sale_price) / $product->price) * 100;
                                @endphp
                                <span class="savings">Save {{ number_format($savings, 0) }}%</span>
                            @else
                                <span class="sale-price">₪{{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>

                        <div class="product-actions">
                            <button class="btn-view" onclick="event.stopPropagation(); window.location.href='{{ route('product.detail', $product->slug) }}'">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn-cart" onclick="event.stopPropagation();">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No Products Available</h3>
                <p>There are currently no products associated with this offer.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@if($offer->end_date->isFuture())
<script>
    // Countdown Timer
    const endDate = new Date("{{ $offer->end_date->toIso8601String() }}").getTime();
    
    const countdown = setInterval(function() {
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance < 0) {
            clearInterval(countdown);
            document.getElementById("countdown").innerHTML = "<div style='text-align: center; padding: 2rem;'>OFFER EXPIRED</div>";
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById("days").textContent = String(days).padStart(2, '0');
        document.getElementById("hours").textContent = String(hours).padStart(2, '0');
        document.getElementById("minutes").textContent = String(minutes).padStart(2, '0');
        document.getElementById("seconds").textContent = String(seconds).padStart(2, '0');
    }, 1000);
</script>
@endif

@endsection
