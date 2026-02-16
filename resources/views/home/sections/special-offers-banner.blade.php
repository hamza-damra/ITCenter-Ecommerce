{{-- Strong Offers Banner Section --}}
@if(isset($promotionalAds['left']))
    <section class="home-section gift-ideas-section strong-offers-section" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="gift-ideas-grid">
                {{-- Dynamic Promotional Ad - Left Position --}}
                <div class="gift-ideas-item gift-banner-item strong-offers-banner">
                    @if($promotionalAds['left']->link)
                        <a href="{{ $promotionalAds['left']->link }}"
                            class="product-item-section gift-idea-banner promotional-ad-link promotional-ad-overlay"
                            style="background-image: url('{{ $promotionalAds['left']->image_url }}'); cursor: pointer; display: block; text-decoration: none;">
                            @if($promotionalAds['left']->hasTitle() || $promotionalAds['left']->hasSubtitle() || $promotionalAds['left']->hasButton())
                                <div class="promotional-ad-content">
                                    @if($promotionalAds['left']->hasTitle())
                                        <h3 class="promotional-ad-title" style="color: {{ $promotionalAds['left']->title_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['left']->title_font_size ?? '32px' }};">{{ $promotionalAds['left']->title }}</h3>
                                    @endif
                                    @if($promotionalAds['left']->hasSubtitle())
                                        <p class="promotional-ad-subtitle" style="color: {{ $promotionalAds['left']->subtitle_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['left']->subtitle_font_size ?? '16px' }};">{{ $promotionalAds['left']->subtitle }}</p>
                                    @endif
                                    @if($promotionalAds['left']->hasButton())
                                        <span class="promotional-ad-button" style="background-color: {{ $promotionalAds['left']->button_bg_color ?? '#2563eb' }}; color: {{ $promotionalAds['left']->button_text_color ?? '#FFFFFF' }};">{{ $promotionalAds['left']->button_text }}</span>
                                    @endif
                                </div>
                            @endif
                        </a>
                    @else
                        <div class="product-item-section gift-idea-banner promotional-ad-overlay"
                            style="background-image: url('{{ $promotionalAds['left']->image_url }}');">
                            @if($promotionalAds['left']->hasTitle() || $promotionalAds['left']->hasSubtitle() || $promotionalAds['left']->hasButton())
                                <div class="promotional-ad-content">
                                    @if($promotionalAds['left']->hasTitle())
                                        <h3 class="promotional-ad-title" style="color: {{ $promotionalAds['left']->title_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['left']->title_font_size ?? '32px' }};">{{ $promotionalAds['left']->title }}</h3>
                                    @endif
                                    @if($promotionalAds['left']->hasSubtitle())
                                        <p class="promotional-ad-subtitle" style="color: {{ $promotionalAds['left']->subtitle_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['left']->subtitle_font_size ?? '16px' }};">{{ $promotionalAds['left']->subtitle }}</p>
                                    @endif
                                    @if($promotionalAds['left']->hasButton())
                                        <span class="promotional-ad-button" style="background-color: {{ $promotionalAds['left']->button_bg_color ?? '#2563eb' }}; color: {{ $promotionalAds['left']->button_text_color ?? '#FFFFFF' }};">{{ $promotionalAds['left']->button_text }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Product 1 --}}
                @if(isset($featuredProducts[6]))
                    <div class="gift-ideas-item gift-product-item strong-offers-product">
                        <a href="{{ route('product.detail', $featuredProducts[6]) }}" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    @if($featuredProducts[6]->is_new)
                                        <div class="product-badge">{{ __t('messages.new') }}</div>
                                    @elseif($featuredProducts[6]->is_featured)
                                        <div class="product-badge">{{ __t('messages.hot') }}</div>
                                    @endif
                                    <div class="wishlist-btn" data-product-id="{{ $featuredProducts[6]->id }}"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="{{ $featuredProducts[6]->main_image }}" alt="{{ $featuredProducts[6]->name }}"
                                       >
                                </div>
                                <div class="product-info">
                                    <div class="product-title">{{ $featuredProducts[6]->name }}</div>
                                    <div class="product-description">{{ Str::limit($featuredProducts[6]->short_description, 60) }}
                                    </div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            @if($featuredProducts[6]->sale_price && $featuredProducts[6]->sale_price < $featuredProducts[6]->price)
                                                <span class="original-price">₪
                                                    {{ number_format($featuredProducts[6]->price, 0) }}</span>
                                                <span class="current-price">₪
                                                    {{ number_format($featuredProducts[6]->sale_price, 0) }}</span>
                                            @else
                                                <span class="current-price">₪ {{ number_format($featuredProducts[6]->price, 0) }}</span>
                                            @endif
                                        </div>
                                        @if($featuredProducts[6]->stock_status === 'out_of_stock')
                                            <button class="add-to-cart-icon out-of-stock"
                                                data-product-id="{{ $featuredProducts[6]->id }}"
                                                data-product-name="{{ $featuredProducts[6]->name }}"
                                                title="{{ __t('messages.request_product') }}"
                                                aria-label="{{ __t('messages.request_product') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $featuredProducts[6]->id }}, '{{ $featuredProducts[6]->name }}');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        @else
                                            <button
                                                class="add-to-cart-icon {{ in_array($featuredProducts[6]->id, $cartProductIds) ? 'in-cart' : '' }}"
                                                data-product-id="{{ $featuredProducts[6]->id }}"
                                                title="{{ in_array($featuredProducts[6]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                aria-label="{{ in_array($featuredProducts[6]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $featuredProducts[6]->id }}, this);">
                                                <i
                                                    class="fas {{ in_array($featuredProducts[6]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Product 2 --}}
                @if(isset($featuredProducts[7]))
                    <div class="gift-ideas-item gift-product-item strong-offers-product">
                        <a href="{{ route('product.detail', $featuredProducts[7]) }}" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    @if($featuredProducts[7]->is_new)
                                        <div class="product-badge">{{ __t('messages.new') }}</div>
                                    @elseif($featuredProducts[7]->is_featured)
                                        <div class="product-badge">{{ __t('messages.hot') }}</div>
                                    @endif
                                    <div class="wishlist-btn" data-product-id="{{ $featuredProducts[7]->id }}"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="{{ $featuredProducts[7]->main_image }}" alt="{{ $featuredProducts[7]->name }}"
                                       >
                                </div>
                                <div class="product-info">
                                    <div class="product-title">{{ $featuredProducts[7]->name }}</div>
                                    <div class="product-description">{{ Str::limit($featuredProducts[7]->short_description, 60) }}
                                    </div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            @if($featuredProducts[7]->sale_price && $featuredProducts[7]->sale_price < $featuredProducts[7]->price)
                                                <span class="original-price">₪
                                                    {{ number_format($featuredProducts[7]->price, 0) }}</span>
                                                <span class="current-price">₪
                                                    {{ number_format($featuredProducts[7]->sale_price, 0) }}</span>
                                            @else
                                                <span class="current-price">₪ {{ number_format($featuredProducts[7]->price, 0) }}</span>
                                            @endif
                                        </div>
                                        @if($featuredProducts[7]->stock_status === 'out_of_stock')
                                            <button class="add-to-cart-icon out-of-stock"
                                                data-product-id="{{ $featuredProducts[7]->id }}"
                                                data-product-name="{{ $featuredProducts[7]->name }}"
                                                title="{{ __t('messages.request_product') }}"
                                                aria-label="{{ __t('messages.request_product') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $featuredProducts[7]->id }}, '{{ $featuredProducts[7]->name }}');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        @else
                                            <button
                                                class="add-to-cart-icon {{ in_array($featuredProducts[7]->id, $cartProductIds) ? 'in-cart' : '' }}"
                                                data-product-id="{{ $featuredProducts[7]->id }}"
                                                title="{{ in_array($featuredProducts[7]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                aria-label="{{ in_array($featuredProducts[7]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $featuredProducts[7]->id }}, this);">
                                                <i
                                                    class="fas {{ in_array($featuredProducts[7]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
