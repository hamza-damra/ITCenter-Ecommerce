{{-- 
    Horizontal Product Scroller Component
    
    Usage:
    <x-horizontal-product-scroller 
        :products="$products" 
        title="عنوان القسم"
        :autoScroll="true"
        :autoScrollInterval="3000"
    />
--}}

@props([
    'products',
    'title' => '',
    'viewMoreUrl' => null,
    'autoScroll' => false,
    'autoScrollInterval' => 3000,
    'cardsToScroll' => 1,
    'containerId' => 'scroller-' . uniqid(),
    'cartProductIds' => [],
    'showDiscountPercentage' => false,
    'hideSaleBadge' => false
])

<div class="horizontal-scroller-section">
    @if($title || $viewMoreUrl)
    <div class="scroller-header">
        @if($title)
            <h2 class="scroller-title">{{ $title }}</h2>
        @endif
        @if($viewMoreUrl)
            <a href="{{ $viewMoreUrl }}" class="scroller-view-more">
                @if(is_rtl())
                    <i class="fas fa-arrow-left"></i> {{ __t('messages.view_more') }}
                @else
                    {{ __t('messages.view_more') }} <i class="fas fa-arrow-right"></i>
                @endif
            </a>
        @endif
    </div>
    @endif

    <div class="horizontal-scroller-wrapper" id="{{ $containerId }}">
        <!-- Left Arrow (shows on right in RTL) -->
        <button class="scroller-arrow scroller-arrow-left" aria-label="{{ __t('messages.previous') }}">
            <i class="fas fa-chevron-{{ is_rtl() ? 'right' : 'left' }}"></i>
        </button>

        <!-- Scrollable Container -->
        <div class="scroller-container" 
             data-auto-scroll="{{ $autoScroll ? 'true' : 'false' }}"
             data-auto-scroll-interval="{{ $autoScrollInterval }}"
             data-cards-to-scroll="{{ $cardsToScroll }}">
            <div class="scroller-track">
                @foreach($products as $product)
                <div class="scroller-card-wrapper">
                    <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
                        <div class="product-image">
                            @if($showDiscountPercentage && $product->sale_price && $product->sale_price < $product->price)
                            @php
                                $discountPercentage = round((($product->price - $product->sale_price) / $product->price) * 100);
                            @endphp
                            <div class="product-badge discount-badge">-{{ $discountPercentage }}%</div>
                            @elseif(!$hideSaleBadge && $product->is_new)
                            <div class="product-badge">{{ __t('messages.new') }}</div>
                            @elseif(!$hideSaleBadge && $product->sale_price && $product->sale_price < $product->price)
                            <div class="product-badge">{{ __t('messages.sale') }}</div>
                            @elseif(!$hideSaleBadge && $product->is_featured)
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
                                    @if($showDiscountPercentage && $product->sale_price && $product->sale_price < $product->price)
                                        <span class="original-price">₪ {{ number_format($product->price, 0) }}</span>
                                        <span class="current-price">₪ {{ number_format($product->sale_price, 0) }}</span>
                                    @else
                                        <span class="current-price">₪ {{ number_format($product->sale_price ?? $product->price, 0) }}</span>
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
                                <button class="add-to-cart-icon {{ in_array($product->id, $cartProductIds ?? []) ? 'in-cart' : '' }}"
                                        data-product-id="{{ $product->id }}"
                                        title="{{ in_array($product->id, $cartProductIds ?? []) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                        aria-label="{{ in_array($product->id, $cartProductIds ?? []) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                        onclick="event.stopPropagation(); addToCart({{ $product->id }}, this);">
                                    <i class="fas {{ in_array($product->id, $cartProductIds ?? []) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Arrow (shows on left in RTL) -->
        <button class="scroller-arrow scroller-arrow-right" aria-label="{{ __t('messages.next') }}">
            <i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i>
        </button>
    </div>

    <!-- Progress Dots (optional) -->
    <div class="scroller-dots"></div>
</div>
