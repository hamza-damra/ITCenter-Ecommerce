{{-- Gift Ideas Section --}}
@if(isset($promotionalAds['right']))
    <section class="home-section gift-ideas-section" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="gift-ideas-grid">
                {{-- RTL: Banner first (on the right), then products --}}
                @if(is_rtl())
                    {{-- Dynamic Promotional Ad - Right Position (RTL) --}}
                    <div class="gift-ideas-item gift-banner-item">
                        @if($promotionalAds['right']->link)
                            <a href="{{ $promotionalAds['right']->link }}"
                                class="product-item-section gift-idea-banner promotional-ad-link promotional-ad-overlay"
                                style="background-image: url('{{ $promotionalAds['right']->image_url }}'); cursor: pointer; display: block; text-decoration: none;">
                                @if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton())
                                    <div class="promotional-ad-content">
                                        @if($promotionalAds['right']->hasTitle())
                                            <h3 class="promotional-ad-title" style="color: {{ $promotionalAds['right']->title_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->title_font_size ?? '32px' }};">{{ $promotionalAds['right']->title }}</h3>
                                        @endif
                                        @if($promotionalAds['right']->hasSubtitle())
                                            <p class="promotional-ad-subtitle" style="color: {{ $promotionalAds['right']->subtitle_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->subtitle_font_size ?? '16px' }};">{{ $promotionalAds['right']->subtitle }}</p>
                                        @endif
                                        @if($promotionalAds['right']->hasButton())
                                            <span class="promotional-ad-button" style="background-color: {{ $promotionalAds['right']->button_bg_color ?? '#2563eb' }}; color: {{ $promotionalAds['right']->button_text_color ?? '#FFFFFF' }};">{{ $promotionalAds['right']->button_text }}</span>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        @else
                            <div class="product-item-section gift-idea-banner promotional-ad-overlay"
                                style="background-image: url('{{ $promotionalAds['right']->image_url }}');">
                                @if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton())
                                    <div class="promotional-ad-content">
                                        @if($promotionalAds['right']->hasTitle())
                                            <h3 class="promotional-ad-title" style="color: {{ $promotionalAds['right']->title_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->title_font_size ?? '32px' }};">{{ $promotionalAds['right']->title }}</h3>
                                        @endif
                                        @if($promotionalAds['right']->hasSubtitle())
                                            <p class="promotional-ad-subtitle" style="color: {{ $promotionalAds['right']->subtitle_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->subtitle_font_size ?? '16px' }};">{{ $promotionalAds['right']->subtitle }}</p>
                                        @endif
                                        @if($promotionalAds['right']->hasButton())
                                            <span class="promotional-ad-button" style="background-color: {{ $promotionalAds['right']->button_bg_color ?? '#2563eb' }}; color: {{ $promotionalAds['right']->button_text_color ?? '#FFFFFF' }};">{{ $promotionalAds['right']->button_text }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Product 1 --}}
                @if(isset($giftIdeas[0]))
                    <div class="gift-ideas-item gift-product-item">
                        <a href="{{ route('product.detail', $giftIdeas[0]) }}" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    @if($giftIdeas[0]->is_new)
                                        <div class="product-badge">{{ __t('messages.new') }}</div>
                                    @elseif($giftIdeas[0]->is_featured)
                                        <div class="product-badge">{{ __t('messages.hot') }}</div>
                                    @endif
                                    <div class="wishlist-btn" data-product-id="{{ $giftIdeas[0]->id }}"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="{{ $giftIdeas[0]->main_image }}" alt="{{ $giftIdeas[0]->name }}" loading="lazy">
                                </div>
                                <div class="product-info">
                                    <div class="product-title">{{ $giftIdeas[0]->name }}</div>
                                    <div class="product-description">{{ Str::limit($giftIdeas[0]->short_description, 60) }}</div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            @if($giftIdeas[0]->sale_price && $giftIdeas[0]->sale_price < $giftIdeas[0]->price)
                                                <span class="original-price">₪ {{ number_format($giftIdeas[0]->price, 0) }}</span>
                                                <span class="current-price">₪ {{ number_format($giftIdeas[0]->sale_price, 0) }}</span>
                                            @else
                                                <span class="current-price">₪ {{ number_format($giftIdeas[0]->price, 0) }}</span>
                                            @endif
                                        </div>
                                        @if($giftIdeas[0]->stock_status === 'out_of_stock')
                                            <button class="add-to-cart-icon out-of-stock" data-product-id="{{ $giftIdeas[0]->id }}"
                                                data-product-name="{{ $giftIdeas[0]->name }}"
                                                title="{{ __t('messages.request_product') }}"
                                                aria-label="{{ __t('messages.request_product') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $giftIdeas[0]->id }}, '{{ $giftIdeas[0]->name }}');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        @else
                                            <button
                                                class="add-to-cart-icon {{ in_array($giftIdeas[0]->id, $cartProductIds) ? 'in-cart' : '' }}"
                                                data-product-id="{{ $giftIdeas[0]->id }}"
                                                title="{{ in_array($giftIdeas[0]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                aria-label="{{ in_array($giftIdeas[0]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $giftIdeas[0]->id }}, this);">
                                                <i
                                                class="fas {{ in_array($giftIdeas[0]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Product 2 --}}
                @if(isset($giftIdeas[1]))
                    <div class="gift-ideas-item gift-product-item">
                        <a href="{{ route('product.detail', $giftIdeas[1]) }}" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    @if($giftIdeas[1]->is_new)
                                        <div class="product-badge">{{ __t('messages.new') }}</div>
                                    @elseif($giftIdeas[1]->is_featured)
                                        <div class="product-badge">{{ __t('messages.hot') }}</div>
                                    @endif
                                    <div class="wishlist-btn" data-product-id="{{ $giftIdeas[1]->id }}"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="{{ $giftIdeas[1]->main_image }}" alt="{{ $giftIdeas[1]->name }}" loading="lazy">
                                </div>
                                <div class="product-info">
                                    <div class="product-title">{{ $giftIdeas[1]->name }}</div>
                                    <div class="product-description">{{ Str::limit($giftIdeas[1]->short_description, 60) }}</div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            @if($giftIdeas[1]->sale_price && $giftIdeas[1]->sale_price < $giftIdeas[1]->price)
                                                <span class="original-price">₪ {{ number_format($giftIdeas[1]->price, 0) }}</span>
                                                <span class="current-price">₪ {{ number_format($giftIdeas[1]->sale_price, 0) }}</span>
                                            @else
                                                <span class="current-price">₪ {{ number_format($giftIdeas[1]->price, 0) }}</span>
                                            @endif
                                        </div>
                                        @if($giftIdeas[1]->stock_status === 'out_of_stock')
                                            <button class="add-to-cart-icon out-of-stock" data-product-id="{{ $giftIdeas[1]->id }}"
                                                data-product-name="{{ $giftIdeas[1]->name }}"
                                                title="{{ __t('messages.request_product') }}"
                                                aria-label="{{ __t('messages.request_product') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $giftIdeas[1]->id }}, '{{ $giftIdeas[1]->name }}');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        @else
                                            <button
                                                class="add-to-cart-icon {{ in_array($giftIdeas[1]->id, $cartProductIds) ? 'in-cart' : '' }}"
                                                data-product-id="{{ $giftIdeas[1]->id }}"
                                                title="{{ in_array($giftIdeas[1]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                aria-label="{{ in_array($giftIdeas[1]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $giftIdeas[1]->id }}, this);">
                                                <i
                                                    class="fas {{ in_array($giftIdeas[1]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- LTR: Banner last (on the left) --}}
                @if(!is_rtl())
                    {{-- Dynamic Promotional Ad - Right Position (LTR) --}}
                    <div class="gift-ideas-item gift-banner-item">
                        @if($promotionalAds['right']->link)
                            <a href="{{ $promotionalAds['right']->link }}"
                                class="product-item-section gift-idea-banner promotional-ad-link promotional-ad-overlay"
                                style="background-image: url('{{ $promotionalAds['right']->image_url }}'); cursor: pointer; display: block; text-decoration: none;">
                                @if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton())
                                    <div class="promotional-ad-content">
                                        @if($promotionalAds['right']->hasTitle())
                                            <h3 class="promotional-ad-title" style="color: {{ $promotionalAds['right']->title_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->title_font_size ?? '32px' }};">{{ $promotionalAds['right']->title }}</h3>
                                        @endif
                                        @if($promotionalAds['right']->hasSubtitle())
                                            <p class="promotional-ad-subtitle" style="color: {{ $promotionalAds['right']->subtitle_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->subtitle_font_size ?? '16px' }};">{{ $promotionalAds['right']->subtitle }}</p>
                                        @endif
                                        @if($promotionalAds['right']->hasButton())
                                            <span class="promotional-ad-button" style="background-color: {{ $promotionalAds['right']->button_bg_color ?? '#2563eb' }}; color: {{ $promotionalAds['right']->button_text_color ?? '#FFFFFF' }};">{{ $promotionalAds['right']->button_text }}</span>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        @else
                            <div class="product-item-section gift-idea-banner promotional-ad-overlay"
                                style="background-image: url('{{ $promotionalAds['right']->image_url }}');">
                                @if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton())
                                    <div class="promotional-ad-content">
                                        @if($promotionalAds['right']->hasTitle())
                                            <h3 class="promotional-ad-title" style="color: {{ $promotionalAds['right']->title_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->title_font_size ?? '32px' }};">{{ $promotionalAds['right']->title }}</h3>
                                        @endif
                                        @if($promotionalAds['right']->hasSubtitle())
                                            <p class="promotional-ad-subtitle" style="color: {{ $promotionalAds['right']->subtitle_color ?? '#FFFFFF' }}; font-size: {{ $promotionalAds['right']->subtitle_font_size ?? '16px' }};">{{ $promotionalAds['right']->subtitle }}</p>
                                        @endif
                                        @if($promotionalAds['right']->hasButton())
                                            <span class="promotional-ad-button" style="background-color: {{ $promotionalAds['right']->button_bg_color ?? '#2563eb' }}; color: {{ $promotionalAds['right']->button_text_color ?? '#FFFFFF' }};">{{ $promotionalAds['right']->button_text }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
