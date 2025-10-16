{{-- Clean Brand Products View - No Inline CSS/JS --}}
@extends('layouts.app')

@section('title', $brand->name)

@section('content')
    <div class="brand-detail-page">
        {{-- Brand Header --}}
        <header class="brand-header" data-brand-id="{{ $brand->id }}">
            @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" 
                     alt="{{ $brand->name }}" 
                     class="brand-logo">
            @endif
            <div class="brand-info">
                <h1 class="brand-name">{{ $brand->name }}</h1>
                @if($brand->description)
                    <p class="brand-description">{{ $brand->description }}</p>
                @endif
                <div class="brand-meta">
                    <span class="products-count">{{ $brand->products_count }} {{ __('Products') }}</span>
                    @if($brand->website)
                        <a href="{{ $brand->website }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="brand-website">
                            {{ __('Visit Website') }}
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- Products Section --}}
        <section class="brand-products">
            <div class="products-header">
                <h2>{{ __('Products from') }} {{ $brand->name }}</h2>
                <div class="products-count-text">
                    {{ $products->total() }} {{ __('products found') }}
                </div>
            </div>

            <div class="products-grid" data-component="products-grid">
                @forelse($products as $product)
                    <article class="product-card" data-product-id="{{ $product->id }}">
                        <a href="{{ route('product.detail', $product->slug) }}" class="product-link">
                            {{-- Product Image --}}
                            <div class="product-image-wrapper">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image"
                                         loading="lazy">
                                @endif
                                
                                {{-- Product Badges --}}
                                <div class="product-badges">
                                    @if($product->is_new)
                                        <span class="badge badge-new">{{ __('New') }}</span>
                                    @endif
                                    @if($product->sale_price)
                                        <span class="badge badge-sale">{{ __('Sale') }}</span>
                                    @endif
                                    @if(!$product->is_in_stock)
                                        <span class="badge badge-out-of-stock">{{ __('Out of Stock') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Product Info --}}
                            <div class="product-info">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                
                                @if($product->category)
                                    <p class="product-category">{{ $product->category->name }}</p>
                                @endif

                                <div class="product-pricing">
                                    @if($product->sale_price)
                                        <span class="price-sale">{{ number_format($product->sale_price, 2) }} {{ __('currency') }}</span>
                                        <span class="price-regular">{{ number_format($product->price, 2) }} {{ __('currency') }}</span>
                                    @else
                                        <span class="price">{{ number_format($product->price, 2) }} {{ __('currency') }}</span>
                                    @endif
                                </div>

                                @if($product->avg_rating > 0)
                                    <div class="product-rating" data-rating="{{ $product->avg_rating }}">
                                        <span class="rating-stars" aria-label="{{ __('Rating') }}: {{ $product->avg_rating }}">
                                            {{ str_repeat('★', floor($product->avg_rating)) }}{{ str_repeat('☆', 5 - floor($product->avg_rating)) }}
                                        </span>
                                        <span class="rating-count">({{ $product->reviews_count }})</span>
                                    </div>
                                @endif
                            </div>
                        </a>

                        {{-- Product Actions --}}
                        <div class="product-actions">
                            <button type="button" 
                                    class="btn-add-to-cart" 
                                    data-product-id="{{ $product->id }}"
                                    {{ !$product->is_in_stock ? 'disabled' : '' }}>
                                {{ __('Add to Cart') }}
                            </button>
                            <button type="button" 
                                    class="btn-favorite" 
                                    data-product-id="{{ $product->id }}"
                                    aria-label="{{ __('Add to favorites') }}">
                                ♡
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <p>{{ __('No products found for this brand') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <nav class="pagination" aria-label="{{ __('Pagination') }}">
                    {{ $products->links() }}
                </nav>
            @endif
        </section>
    </div>
@endsection
