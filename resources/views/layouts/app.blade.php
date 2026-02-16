<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ locale_direction() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Center')</title>
    <meta name="description" content="@yield('meta_description', 'IT Center - Your trusted source for the latest technology, electronics, laptops, desktops, and accessories at competitive prices.')">
    @yield('meta')

    {{-- Preconnect to external origins for faster resource loading --}}
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Font Awesome CSS (self-hosted to avoid CDN tracking prevention warnings in Edge) --}}
    <link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
    </noscript>
    {{-- Force font-display:swap on Font Awesome to prevent render blocking --}}
    <style>
        @font-face {
            font-family: "Font Awesome 6 Free";
            font-display: swap;
        }

        @font-face {
            font-family: "Font Awesome 6 Brands";
            font-display: swap;
        }
    </style>

    {{-- Critical CSS loaded normally --}}
    <link rel="stylesheet" href="{{ asset('css/layout.min.css') }}">

    {{-- Non-critical CSS deferred via media trick --}}
    <link rel="stylesheet" href="{{ asset('css/horizontal-scroller.css') }}" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/horizontal-scroller.css') }}">
    </noscript>
    <link rel="stylesheet" href="{{ asset('css/search-autocomplete.css') }}" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/search-autocomplete.css') }}">
    </noscript>

    @if (is_rtl())
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet"
            media="print" onload="this.media='all'">
        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
        </noscript>
    @endif

    @stack('head')
</head>

<body data-t-request-product="{{ __t('messages.request_product') }}"
    data-t-contact-us="{{ __t('messages.contact_us') }}">
    @sectionMissing('hideHeader')
        <!-- Mobile Menu Toggle - Outside header for proper fixed positioning -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu" type="button">
            <div class="hamburger-icon" style="display: flex; flex-direction: column; gap: 5px; width: 20px;">
                <span
                    style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
                <span
                    style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
                <span
                    style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
            </div>
        </button>

        <!-- Mobile Search Icon Button -->
        <button class="mobile-search-btn" id="mobileSearchBtn" aria-label="{{ __t('messages.search') }}"
            type="button">
            <i class="fas fa-search"></i>
        </button>

        <!-- Mobile Search Overlay -->
        <div class="mobile-search-overlay" id="mobileSearchOverlay">
            <div class="mobile-search-overlay-header">
                <form action="{{ route('products') }}" method="GET" class="mobile-search-form" autocomplete="off">
                    <button type="submit" class="mobile-search-submit-btn" aria-label="{{ __t('messages.search') }}">
                        <i class="fas fa-search"></i>
                    </button>
                    <input type="search" name="search" class="mobile-search-input" id="mobileSearchInput"
                        placeholder="{{ __t('messages.search') }}" autocomplete="off" autofocus>
                </form>
                <button class="mobile-search-close" id="mobileSearchClose" type="button" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mobile-search-results" id="mobileSearchResults">
                {{-- Results injected by JS --}}
            </div>
        </div>

        <!-- Mobile Menu Overlay - positioned to NOT cover the nav-menu -->
        <div class="mobile-menu-overlay" id="mobileMenuOverlay"
            style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1040; opacity: 0; visibility: hidden; pointer-events: none;">
        </div>

        <!-- Mobile Navigation Menu - OUTSIDE header for proper z-index -->
        <nav class="nav-menu" id="navMenu" style="display: none;">
            <div class="nav-menu-header">
                <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo">
            </div>
            <ul class="nav-menu-list">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i
                            class="fas fa-home"></i> {{ __t('messages.home') }}</a></li>
                <li><a href="{{ route('categories') }}"
                        class="{{ request()->routeIs('categories') ? 'active' : '' }}"><i class="fas fa-th-large"></i>
                        {{ __t('messages.categories') }}</a></li>
                <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}"><i
                            class="fas fa-box"></i>
                        {{ __t('messages.products') }}</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}"><i
                            class="fas fa-info-circle"></i> {{ __t('messages.about') }}</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i
                            class="fas fa-envelope"></i> {{ __t('messages.contact') }}</a></li>
            </ul>

            {{-- Mobile Nav Icons Section (Cart, Wishlist, Account) --}}
            <div class="nav-menu-icons-section">
                @php
                    // Get cart count for mobile nav
                    if (Auth::check()) {
                        $mobileCartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                    } else {
                        $sessionId = Session::getId();
                        $mobileCartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum('quantity');
                    }

                    // Get favorites count for mobile nav
                    $mobileFavCount = 0;
                    try {
                        if (\App\Services\DatabaseStateService::isDatabaseAvailable()) {
                            if (Auth::check()) {
                                $mobileFavCount = \App\Models\Favorite::where('user_id', Auth::id())->count();
                            } else {
                                $sessionId = Session::getId();
                                $mobileFavCount = \App\Models\Favorite::where('session_id', $sessionId)->count();
                            }
                        }
                    } catch (\Exception $e) {
                        $mobileFavCount = 0;
                    }
                @endphp

                <a href="{{ route('cart.index') }}" class="nav-icon-item">
                    <span class="nav-icon-content">
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ __t('messages.cart') }}</span>
                    </span>
                    <span class="nav-badge {{ $mobileCartCount > 0 ? '' : 'hidden' }}"
                        id="mobile-cart-count">{{ $mobileCartCount }}</span>
                </a>

                <a href="{{ route('favorites') }}" class="nav-icon-item">
                    <span class="nav-icon-content">
                        <i class="fas fa-heart"></i>
                        <span>{{ __t('messages.favorites') }}</span>
                    </span>
                    <span class="nav-badge {{ $mobileFavCount > 0 ? '' : 'hidden' }}"
                        id="mobile-favorites-count">{{ $mobileFavCount }}</span>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="nav-icon-item">
                        <span class="nav-icon-content">
                            <i class="fas fa-user"></i>
                            <span>{{ __t('messages.login') }}</span>
                        </span>
                    </a>
                    <a href="{{ route('register') }}" class="nav-icon-item">
                        <span class="nav-icon-content">
                            <i class="fas fa-user-plus"></i>
                            <span>{{ __t('messages.register') }}</span>
                        </span>
                    </a>
                @else
                    <a href="{{ route('profile.index') }}" class="nav-icon-item">
                        <span class="nav-icon-content">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ __t('messages.my_profile') }}</span>
                        </span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="nav-icon-item">
                        <span class="nav-icon-content">
                            <i class="fas fa-box"></i>
                            <span>{{ __t('messages.my_orders') }}</span>
                        </span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-icon-item"
                            style="width: 100%; border: none; cursor: pointer; font-family: inherit;">
                            <span class="nav-icon-content">
                                <i class="fas fa-sign-out-alt" style="color: #dc3545 !important;"></i>
                                <span style="color: #dc3545;">{{ __t('messages.logout') }}</span>
                            </span>
                        </button>
                    </form>
                @endguest
            </div>

            {{-- Language Selector in Mobile Nav --}}
            <div class="nav-menu-language-section">
                <div class="language-title">{{ __t('messages.language') }}</div>
                @foreach (available_locales() as $locale)
                    <a href="{{ switch_locale_url($locale) }}"
                        class="nav-lang-item {{ $locale === current_locale() ? 'active' : '' }}">
                        <span class="lang-flag">
                            @if ($locale === 'en')
                                🇬🇧
                            @elseif($locale === 'ar')
                                🇵🇸
                            @elseif($locale === 'he')
                                🇮🇱
                            @else
                                🌐
                            @endif
                        </span>
                        <span>{{ locale_name($locale) }}</span>
                        @if ($locale === current_locale())
                            <i class="fas fa-check"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </nav>


        <header>
            <div class="header-container">
                {{-- Mobile Header Icons (visible only on mobile ≤768px) --}}
                <div class="mobile-header-icons">
                    <button type="button" class="mhi-btn" id="mobileHeaderMenuBtn" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="{{ route('cart.index') }}" class="mhi-btn mhi-cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="mhi-badge {{ $mobileCartCount > 0 ? '' : 'hidden' }}"
                            id="mhi-cart-count">{{ $mobileCartCount }}</span>
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="mhi-btn" aria-label="{{ __t('messages.login') }}">
                            <i class="fas fa-user"></i>
                        </a>
                    @else
                        <a href="{{ route('profile.index') }}" class="mhi-btn"
                            aria-label="{{ __t('messages.my_profile') }}">
                            <i class="fas fa-user"></i>
                        </a>
                    @endguest
                    <button type="button" class="mhi-btn" id="mobileHeaderSearchBtn"
                        aria-label="{{ __t('messages.search') }}">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo" width="125"
                            height="35">
                    </a>
                </div>

                <form action="{{ route('products') }}" method="GET" class="search-bar" role="search"
                    autocomplete="off">
                    <input type="search" name="search" placeholder="{{ __t('messages.search') }}"
                        autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false"
                        aria-haspopup="listbox" aria-controls="search-autocomplete-listbox"
                        aria-label="{{ __t('messages.search') }}" value="{{ request('search') }}">
                    <!--<button class="search-btn" type="submit" aria-label="{{ __t('messages.search') }}">
                    <i class="fas fa-search"></i>
                </button>-->
                </form>

                <div class="header-icons">
                    @guest
                        <div class="header-icon" style="position: relative;">
                            <a href="{{ route('login') }}"
                                style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;"
                                aria-label="{{ __t('messages.login') }}">
                                <i class="fas fa-user"></i>
                            </a>
                        </div>
                    @else
                        <div class="header-icon user-dropdown" style="position: relative;">
                            <div class="user-toggle"
                                style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-user-circle"></i>
                                <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.3s;"></i>
                            </div>
                            <div class="user-dropdown-menu"
                                style="display: none; position: absolute; top: calc(100% + 10px); inset-inline-end: 0; background: #ffffff; backdrop-filter: blur(10px); border: 2px solid #e8eef7; border-radius: 12px; min-width: max-content; box-shadow: 0 8px 24px rgba(39, 98, 243, 0.12); overflow: hidden; z-index: 1001; opacity: 0; transform: translateY(-10px); transition: opacity 0.3s ease, transform 0.3s ease;">
                                <a href="{{ route('profile.index') }}" class="user-menu-item"
                                    style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: start; font-family: inherit; font-size: inherit; text-decoration: none; color: inherit; white-space: nowrap;">
                                    <i class="fas fa-user"></i>
                                    <span>{{ __t('messages.my_profile') }}</span>
                                </a>
                                <a href="{{ route('orders.index') }}" class="user-menu-item"
                                    style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: start; font-family: inherit; font-size: inherit; text-decoration: none; color: inherit; white-space: nowrap;">
                                    <i class="fas fa-box"></i>
                                    <span>{{ __t('messages.my_orders') }}</span>
                                </a>
                                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="user-menu-item"
                                        style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: start; font-family: inherit; font-size: inherit; text-decoration: none; color: #dc3545; white-space: nowrap;">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>{{ __t('messages.logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    <div class="header-icon language-dropdown" style="position: relative;">
                        <div class="language-toggle"
                            style="cursor: pointer; display: flex; align-items: center; gap: 0.4rem;">
                            <i class="fas fa-globe"></i>
                            <span class="current-lang"
                                style="font-size: 0.85rem; font-weight: 600;">{{ strtoupper(current_locale()) }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.3s;"></i>
                        </div>
                        <div class="language-dropdown-menu">
                            @foreach (available_locales() as $locale)
                                <a href="{{ switch_locale_url($locale) }}"
                                    class="language-option {{ $locale === current_locale() ? 'active' : '' }}"
                                    data-locale="{{ $locale }}">
                                    <span class="lang-icon">
                                        @if ($locale === 'en')
                                            🇬🇧
                                        @else
                                            🇵🇸
                                        @endif
                                    </span>
                                    <span class="lang-name">{{ locale_name($locale) }}</span>
                                    @if ($locale === current_locale())
                                        <i class="fas fa-check lang-check"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="header-icon">
                        <a href="{{ route('favorites') }}" style="color: inherit; text-decoration: none;"
                            aria-label="{{ __t('messages.favorites') }}">
                            <i class="fas fa-heart"></i>
                            @php
                                // Get initial favorites count from server to prevent flash
                                $initialFavCount = 0;
                                try {
                                    // Check if database is available before querying
                                    if (\App\Services\DatabaseStateService::isDatabaseAvailable()) {
                                        if (Auth::check()) {
                                            $initialFavCount = \App\Models\Favorite::where(
                                                'user_id',
                                                Auth::id(),
                                            )->count();
                                        } else {
                                            $sessionId = Session::getId();
                                            $initialFavCount = \App\Models\Favorite::where(
                                                'session_id',
                                                $sessionId,
                                            )->count();
                                        }
                                    }
                                } catch (\Exception $e) {
                                    // Database not available or query failed - use 0 as default
                                    $initialFavCount = 0;
                                }
                            @endphp
                            <span class="badge {{ $initialFavCount > 0 ? '' : 'hidden' }}"
                                id="favorites-count">{{ $initialFavCount }}</span>
                        </a>
                    </div>
                    @auth
                        <div class="header-icon">
                            <a href="{{ route('orders.index') }}"
                                style="color: inherit; text-decoration: none; position: relative;"
                                title="{{ __t('messages.my_orders') }}" aria-label="{{ __t('messages.my_orders') }}">
                                <i class="fas fa-box"></i>
                            </a>
                        </div>
                    @endauth
                    <div class="header-icon">
                        <a href="{{ route('cart.index') }}" style="color: inherit; text-decoration: none;"
                            aria-label="{{ __t('messages.cart') }}">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                // Get initial cart count from server to prevent flash
                                if (Auth::check()) {
                                    $initialCartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum(
                                        'quantity',
                                    );
                                } else {
                                    $sessionId = Session::getId();
                                    $initialCartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum(
                                        'quantity',
                                    );
                                }
                            @endphp
                            <span class="badge {{ $initialCartCount > 0 ? '' : 'hidden' }}"
                                id="cart-count">{{ $initialCartCount }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Category Navigation - Only show on home page --}}
        @if (isset($navigationCategories) && $navigationCategories->count() > 0 && request()->routeIs('home'))
            <x-category-nav :categories="$navigationCategories" />
        @endif

        <!-- Desktop Social Icons -->
        <div class="social-icons">
            <a href="https://facebook.com" target="_blank" class="social-icon" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="social-icon" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://wa.me/" target="_blank" class="social-icon" aria-label="WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>

        <!-- Mobile Social Icons Toggle -->
        <div class="social-icons-toggle" onclick="toggleMobileSocial()" role="button"
            aria-label="{{ __t('messages.share') }}" tabindex="0">
            <i class="fas fa-share-alt"></i>
        </div>

        <!-- Mobile Social Icons Popup -->
        <div class="social-icons-mobile">
            <a href="https://facebook.com" target="_blank" class="social-icon" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="social-icon" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://wa.me/" target="_blank" class="social-icon" aria-label="WhatsApp">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @sectionMissing('hideHeader')
        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo" width="161"
                            height="45">
                    </div>
                    <p>{{ __('messages.footer_description') }}</p>
                    <div class="footer-social">
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram"><i
                                class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/" target="_blank" aria-label="WhatsApp"><i
                                class="fab fa-whatsapp"></i></a>
                        <a href="https://twitter.com" target="_blank" aria-label="Twitter"><i
                                class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>{{ __('messages.quick_links') }}</h3>
                    <ul>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="{{ route('products') }}">{{ __('messages.products') }}</a></li>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="{{ route('contact') }}">{{ __('messages.contact_us') }}</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>{{ __('messages.footer_categories') }}</h3>
                    <ul>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="#">{{ __('messages.laptops') }}</a></li>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="#">{{ __('messages.desktops') }}</a></li>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="#">{{ __('messages.accessories') }}</a></li>
                        <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a
                                href="#">{{ __('messages.components') }}</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>{{ __('messages.contact_us') }}</h3>
                    <ul>
                        <li><i class="fas fa-phone"></i><a href="tel:0595910045">0595910045</a></li>
                        <li><i class="fas fa-envelope"></i><a
                                href="mailto:support@itcenter.vip">support@itcenter.vip</a></li>
                        <li><i class="fas fa-map-marker-alt"></i><span
                                style="color: #94a3b8;">{{ __('messages.location') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} <a href="{{ route('home') }}">IT Center</a>.
                    {{ __('messages.all_rights_reserved') }}</p>
            </div>
        </footer>
    @endif

    {{-- Layout JS (extracted to external file for browser caching) --}}
    <script src="{{ asset('js/layout.min.js') }}" defer></script>


    {{-- Horizontal Scroller JavaScript --}}
    <script src="{{ asset('js/horizontal-scroller.js') }}" defer></script>

    {{-- Search Autocomplete JavaScript --}}
    <script src="{{ asset('js/search-autocomplete.js') }}" defer></script>

    {{-- Smart Lazy Loading: dynamically apply loading="lazy" to offscreen images
         after initial render to avoid the browser "[Intervention] Images loaded lazily
         and replaced with placeholders" warning in Edge/Chrome --}}
    <script>
        (function() {
            function applyLazyLoading() {
                if (!('loading' in HTMLImageElement.prototype)) return;
                var imgs = document.querySelectorAll('img:not([loading])');
                for (var i = 0; i < imgs.length; i++) {
                    var rect = imgs[i].getBoundingClientRect();
                    if (rect.bottom < 0 || rect.top > window.innerHeight) {
                        imgs[i].loading = 'lazy';
                    }
                }
            }
            if (document.readyState === 'complete') {
                requestAnimationFrame(applyLazyLoading);
            } else {
                window.addEventListener('load', function() {
                    requestAnimationFrame(applyLazyLoading);
                });
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>
