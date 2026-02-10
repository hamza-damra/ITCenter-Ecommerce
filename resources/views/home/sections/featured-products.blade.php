{{-- Featured Products Section --}}
@if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <div class="featured-section">
        <div class="container">
            <div class="product-grid" id="featuredProducts">
                @if(isset($specialOfferProducts) && $specialOfferProducts->count() > 0)
                    <div class="promo-featured-card special-offer-swapper" id="specialOfferSwapper">
                        <div class="special-offer-header">{{ is_rtl() ? 'عرض خاص' : 'Special Offer' }}</div>

                        {{-- Products Slides --}}
                        <div class="special-offer-slides">
                            @foreach($specialOfferProducts as $index => $offerProduct)
                                <div class="special-offer-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                    @if($offerProduct->sale_price && $offerProduct->sale_price < $offerProduct->price)
                                        <div class="badge-save">
                                            <span class="save-label">{{ is_rtl() ? 'وفر' : 'Save' }}</span>
                                            <span
                                                class="save-amount">₪{{ number_format($offerProduct->price - $offerProduct->sale_price, 0) }}</span>
                                        </div>
                                    @endif
                                    <div class="promo-media">
                                        <img src="{{ $offerProduct->main_image }}" 
                                             alt="{{ $offerProduct->name }}"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::assetUrl('images/products/default.png') }}';">
                                    </div>
                                    <div class="promo-body">
                                        <div class="promo-product-name">{{ $offerProduct->name }}</div>
                                        <div class="promo-prices">
                                            @if($offerProduct->sale_price && $offerProduct->sale_price < $offerProduct->price)
                                                <span class="orig">₪{{ number_format($offerProduct->price, 0) }}</span>
                                                <span class="sale">₪{{ number_format($offerProduct->sale_price, 0) }}</span>
                                            @else
                                                <span class="sale">₪{{ number_format($offerProduct->price, 0) }}</span>
                                            @endif
                                        </div>
                                        <div class="promo-cta">
                                            <a
                                                href="{{ route('product.detail', $offerProduct) }}">{{ is_rtl() ? 'تسوق الآن' : 'Shop Now' }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigation Dots --}}
                        @if($specialOfferProducts->count() > 1)
                            <div class="special-offer-dots">
                                @foreach($specialOfferProducts as $index => $offerProduct)
                                    <button class="special-offer-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"
                                        aria-label="Go to product {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @elseif(isset($promotionalOffers) && $promotionalOffers->count() > 0)
                    @php $promo = $promotionalOffers->first(); @endphp
                    <div class="promo-featured-card">
                        <div class="special-offer-header">{{ is_rtl() ? 'عرض خاص' : 'Special Offer' }}</div>
                        <div class="badge-save">
                            <span class="save-label">{{ is_rtl() ? 'وفر' : 'Save' }}</span>
                            <span
                                class="save-amount">₪{{ number_format($promo->original_price - $promo->sale_price, 0) }}</span>
                        </div>
                        <div class="promo-media">
                            @php
                                $img = null;
                                if ($promo->product && $promo->product->main_image) {
                                    $path = $promo->product->main_image;
                                    if (str_starts_with($path, 'http')) {
                                        $img = $path;
                                    } elseif (str_starts_with($path, 'storage/') || str_starts_with($path, 'images/')) {
                                        $img = asset($path);
                                    } else {
                                        $img = asset('storage/' . $path);
                                    }
                                }
                            @endphp
                            <img src="{{ $img ?? asset('images/placeholder.png') }}" alt="{{ $promo->title }}">
                        </div>
                        <div class="promo-body">
                            <div class="promo-title">{{ $promo->title }}</div>
                            @if($promo->product)
                                <div class="promo-product-name">{{ $promo->product->name }}</div>
                            @endif
                            <div class="promo-prices">
                                <span class="orig">₪{{ number_format($promo->original_price, 0) }}</span>
                                <span class="sale">₪{{ number_format($promo->sale_price, 0) }}</span>
                            </div>
                            @if($promo->end_date)
                                <div class="promo-countdown" data-end="{{ optional($promo->end_date)->format('c') }}">
                                    <div class="label">{{ is_rtl() ? 'العرض ينتهي خلال:' : 'Hurry up! Offer ends in:' }}</div>
                                    <div class="boxes">
                                        <div class="box"><span class="num cd-hours">00</span><span
                                                class="unit">{{ is_rtl() ? 'ساعات' : 'HRS' }}</span></div>
                                        <div class="box"><span class="num cd-mins">00</span><span
                                                class="unit">{{ is_rtl() ? 'دقائق' : 'MINS' }}</span></div>
                                        <div class="box"><span class="num cd-secs">00</span><span
                                                class="unit">{{ is_rtl() ? 'ثواني' : 'SECS' }}</span></div>
                                    </div>
                                </div>
                            @endif
                            @if($promo->product)
                                <div class="promo-cta">
                                    <a href="{{ route('product.detail', $promo->product) }}">
                                        @if(is_rtl())
                                            {{ 'اطلب الآن' }} <i class="fas fa-shopping-cart"></i>
                                        @else
                                            <i class="fas fa-shopping-cart"></i> {{ 'Order Now' }}
                                        @endif
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @foreach($featuredProducts->take(8) as $product)
                    <a href="{{ route('product.detail', $product) }}" class="product-card-link">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->is_new)
                                    <div class="product-badge">{{ __t('messages.new') }}</div>
                                @elseif($product->is_featured)
                                    <div class="product-badge">{{ __t('messages.hot') }}</div>
                                @endif
                                <div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </div>

                                <img src="{{ $product->main_image }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                            </div>
                            <div class="product-info">
                                {{-- Product Rating --}}
                                <div class="product-rating">
                                    <div class="stars">
                                        @php
                                            $rating = $product->average_rating ?? 4.5;
                                            $fullStars = floor($rating);
                                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $fullStars)
                                                <i class="fas fa-star"></i>
                                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $product->reviews_count ?? rand(10, 150) }})</span>
                                </div>

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
                                        <button class="add-to-cart-icon out-of-stock" data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}" title="{{ __t('messages.request_product') }}"
                                            aria-label="{{ __t('messages.request_product') }}"
                                            onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                                            <i class="fas fa-bell"></i>
                                        </button>
                                    @else
                                        <button class="add-to-cart-icon {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                            data-product-id="{{ $product->id }}"
                                            title="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                            aria-label="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                            onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, this);">
                                            <i
                                                class="fas {{ in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    </div>
@else
    <!-- Empty State for Featured Products -->
    <div class="featured-section">
        <div class="container">
            <div class="empty-state-mini">
                <div class="empty-state-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="empty-state-title">{{ __t('messages.no_products_title') }}</h3>
                <p class="empty-state-subtitle">{{ __t('messages.no_products_subtitle') }}</p>
                <div class="empty-state-actions">
                    <a href="{{ route('contact') }}" class="empty-state-btn secondary">
                        <i class="fas fa-envelope"></i>
                        {{ __t('messages.contact_support') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
