<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ locale_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Center')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(is_rtl())
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: {{ is_rtl() ? "'Cairo', sans-serif" : "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif" }};
            line-height: 1.6;
            background-color: #f5f5f5;
            color: #333;
            direction: {{ locale_direction() }};
        }

        header {
            background: rgba(0, 0, 0, 1);
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background 0.3s ease, backdrop-filter 0.3s ease;
        }

        header.scrolled {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo img {
            height: 35px;
            width: auto;
            object-fit: contain;
            margin-top:3px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: bold;
            color: #d4af37;
            font-style: italic;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: #ecececff;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
            font-weight: 500;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: #e69270ff;
        }

        /* Search Bar Styles */
        .search-bar {
            display: flex;
            flex-direction: {{ is_rtl() ? 'row-reverse' : 'row' }};
            flex: 1;
            max-width: 500px;
            gap: 0;
            align-items: center;
        }

        .search-bar input {
            flex: 1;
            height: 45px;
            padding: 0 20px;
            border: 1px solid #e0e0e0;
            border-radius: {{ is_rtl() ? '8px 0 0 8px' : '8px 0 0 8px' }};
            background: #ffffff;
            color: #333;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            text-align: {{ is_rtl() ? 'right' : 'left' }};
        }

        .search-bar input::-webkit-search-decoration,
        .search-bar input::-webkit-search-cancel-button {
            -webkit-appearance: none;
        }

        .search-bar input::placeholder {
            color: #999;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            text-align: {{ is_rtl() ? 'right' : 'left' }};
        }

        .search-bar input:focus {
            border-color: #000;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .search-btn {
            height: 45px;
            padding: 0 30px;
            background: #2762f3;
            color: #ffffff;
            border: none;
            border-radius: {{ is_rtl() ? '0 8px 8px 0' : '0 8px 8px 0' }};
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            unicode-bidi: embed;
        }

        .search-btn:hover {
            background: #1a4dbf;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(39, 98, 243, 0.4);
        }

        .search-btn:active {
            transform: translateY(0);
        }

        .search-btn span {
            display: inline-block;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            unicode-bidi: embed;
        }

        .header-icons {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .header-icon {
            position: relative;
            cursor: pointer;
            color: #ffffffff;
            font-size: 1.3rem;
        }

        .header-icon .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #000;
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            transition: opacity 0.2s ease;
        }
        
        /* Prevent flash on page load */
        .header-icon .badge.badge-loading {
            /* Badge shows initial server-side count, no need to hide */
            opacity: 1;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        main {
            min-height: calc(100vh - 200px);
            background-color: #f5f5f5;
        }

        footer {
            background: #1a1a1a;
            color: #fff;
            padding: 3rem 0 1rem;
            margin-top: 4rem;

        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #d4af37;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #d4af37;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #333;
            color: #888;
        }

        .social-icons {
            position: fixed;
            left: 20px;
            top: 80%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            z-index: 999;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            background: #000000ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
            color: #ffffffff;
            font-size: 1.2rem;
        }

        .social-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo">
                </a>
            </div>

            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __t('messages.home') }}</a></li>
                <li><a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}">{{ __t('messages.categories') }}</a></li>
                <li><a href="{{ route('brands') }}" class="{{ request()->routeIs('brands') ? 'active' : '' }}">{{ __t('messages.brands') }}</a></li>
                <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}">{{ __t('messages.products') }}</a></li>
                <li><a href="{{ route('offers') }}" class="{{ request()->routeIs('offers') ? 'active' : '' }}">{{ __t('messages.offers') }}</a></li>
                <li><a href="{{ route('favorites') }}" class="{{ request()->routeIs('favorites') ? 'active' : '' }}">{{ __t('messages.favorites') }}</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __t('messages.about') }}</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __t('messages.contact') }}</a></li>
            </ul>



            <div class="header-icons">

                <form action="" class="search-bar">
                    <input type="search" name="search" placeholder="{{ __t('messages.search') }}">
                    <button class="search-btn" type="submit">
                        <span>{{ __t('messages.search') }}</span>
                    </button>
                </form>
                <div class="header-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="header-icon dropdown" style="position: relative;">
                    <i class="fas fa-globe" style="cursor: pointer;"></i>
                    <div class="dropdown-menu" style="display: none; position: absolute; top: 100%; {{ is_rtl() ? 'left: 0;' : 'right: 0;' }} background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; min-width: 120px; margin-top: 10px; z-index: 1000;">
                        @foreach(available_locales() as $locale)
                            <a href="{{ switch_locale_url($locale) }}" 
                               style="display: block; padding: 0.75rem 1rem; color: #333; text-decoration: none; transition: background 0.3s; {{ $locale === current_locale() ? 'background: #f0f0f0; font-weight: 600;' : '' }}"
                               onmouseover="this.style.background='#f8f8f8'" 
                               onmouseout="this.style.background='{{ $locale === current_locale() ? '#f0f0f0' : 'transparent' }}'">
                                {{ locale_name($locale) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="header-icon">
                    <a href="{{ route('favorites') }}" style="color: inherit; text-decoration: none;">
                        <i class="fas fa-heart"></i>
                        <span class="badge badge-loading" id="favorites-count">
                            @php
                                // Get initial favorites count from server to prevent flash
                                if (Auth::check()) {
                                    $initialFavCount = Auth::user()->favoriteProducts()->count();
                                } else {
                                    $initialFavCount = count(Session::get('favorites', []));
                                }
                            @endphp
                            {{ $initialFavCount }}
                        </span>
                    </a>
                </div>
                <div class="header-icon">
                    <a href="{{ route('cart.index') }}" style="color: inherit; text-decoration: none;">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge badge-loading" id="cart-count">
                            @php
                                // Get initial cart count from server to prevent flash
                                if (Auth::check()) {
                                    $initialCartCount = Auth::user()->cartItems()->sum('quantity');
                                } else {
                                    $sessionId = Session::get('cart_session_id', Session::getId());
                                    $initialCartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum('quantity');
                                }
                            @endphp
                            {{ $initialCartCount }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="social-icons">
        <div class="social-icon">
            <i class="fab fa-facebook-f"></i>
        </div>
        <div class="social-icon">
            <i class="fab fa-instagram"></i>
        </div>
        <div class="social-icon">
            <i class="fab fa-whatsapp"></i>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>IT Center</h3>
                <p>{{ __('messages.footer_description') }}</p>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.quick_links') }}</h3>
                <ul>
                    <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                    <li><a href="{{ route('products') }}">{{ __('messages.products') }}</a></li>
                    <li><a href="{{ route('offers') }}">{{ __('messages.special_offers') }}</a></li>
                    <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.footer_categories') }}</h3>
                <ul>
                    <li><a href="#">{{ __('messages.laptops') }}</a></li>
                    <li><a href="#">{{ __('messages.desktops') }}</a></li>
                    <li><a href="#">{{ __('messages.accessories') }}</a></li>
                    <li><a href="#">{{ __('messages.components') }}</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.contact_us') }}</h3>
                <ul>
                    <li><i class="fas fa-phone"></i>&nbsp;&nbsp;0595910045</li>
                    <li><i class="fas fa-envelope"></i>&nbsp;&nbsp;support@itcenter.vip</li>
                    <li><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;{{ __('messages.location') }}</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} IT Center. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            const scrollThreshold = window.innerHeight * 0.1; // 10% of viewport height

            if (window.scrollY > scrollThreshold) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Language dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.dropdown');
            const dropdownMenu = dropdown.querySelector('.dropdown-menu');
            
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdownMenu.style.display = 'none';
            });

            // Load and update favorites count
            updateFavoritesCount();

            // Load and update cart count
            updateCartCount();

            // Initialize all wishlist buttons on the page
            initializeWishlistButtons();
        });

        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        /**
         * Update the favorites count in header
         */
        function updateFavoritesCount(skipButtonUpdate = false) {
            fetch('/favorites/ids')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('favorites-count');
                    const newCount = data.favoriteIds ? data.favoriteIds.length : 0;
                    
                    if (badge) {
                        // Only update if count changed to prevent unnecessary DOM updates
                        const currentCount = parseInt(badge.textContent);
                        if (currentCount !== newCount) {
                            badge.textContent = newCount;
                        }
                        // Remove loading class after first update
                        badge.classList.remove('badge-loading');
                    }
                    
                    // Store favorite IDs globally for quick checks
                    window.favoriteIds = data.favoriteIds || [];
                    
                    // ALWAYS update button states to ensure UI is in sync with server
                    // This fixes issues where visual state doesn't match actual state
                    updateWishlistButtonStates();
                })
                .catch(error => console.error('Error updating favorites count:', error));
        }

        /**
         * Update all wishlist button states based on current favorites
         */
        function updateWishlistButtonStates() {
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            
            // Ensure favoriteIds is an array of integers
            if (!window.favoriteIds || !Array.isArray(window.favoriteIds)) {
                window.favoriteIds = [];
            }
            
            wishlistButtons.forEach(button => {
                const productId = parseInt(button.getAttribute('data-product-id'));
                const isInFavorites = window.favoriteIds.includes(productId);
                
                if (isInFavorites) {
                    button.classList.add('active');
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        icon.style.setProperty('color', '#ff0000', 'important');
                    }
                } else {
                    button.classList.remove('active');
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        icon.style.setProperty('color', '#666', 'important');
                    }
                }
            });
        }

        /**
         * Initialize wishlist button click handlers
         */
        function initializeWishlistButtons() {
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            wishlistButtons.forEach(button => {
                // Check if already initialized
                if (button.dataset.initialized) {
                    return;
                }
                
                button.dataset.initialized = 'true';
                
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const productId = this.getAttribute('data-product-id');
                    if (productId) {
                        toggleFavorite(productId, this);
                    }
                });
            });
        }

        /**
         * Toggle favorite status for a product
         */
        function toggleFavorite(productId, button) {
            // Prevent double-clicking by disabling the button temporarily
            if (button.dataset.processing === 'true') {
                return;
            }
            
            button.dataset.processing = 'true';
            const icon = button.querySelector('i');
            
            // Optimistic UI update
            button.classList.toggle('active');
            if (icon) {
                icon.classList.toggle('fas');
                icon.classList.toggle('far');
                
                // Force color change with !important priority
                if (icon.classList.contains('fas')) {
                    icon.style.setProperty('color', '#ff0000', 'important');
                } else {
                    icon.style.setProperty('color', '#666', 'important');
                }
            }
            
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
                button.dataset.processing = 'false';
                
                if (data.success) {
                    const badge = document.getElementById('favorites-count');
                    const wasAdded = data.action === 'added';
                    
                    // Update global favoriteIds array
                    if (!window.favoriteIds) {
                        window.favoriteIds = [];
                    }
                    
                    const productIdInt = parseInt(productId);
                    
                    if (wasAdded) {
                        // Add to favorites
                        if (!window.favoriteIds.includes(productIdInt)) {
                            window.favoriteIds.push(productIdInt);
                        }
                    } else {
                        // Remove from favorites
                        window.favoriteIds = window.favoriteIds.filter(id => id !== productIdInt);
                    }
                    
                    // Update badge count
                    if (badge) {
                        badge.textContent = window.favoriteIds.length;
                    }
                    
                    // If we reached 0 favorites, force a full UI refresh to ensure all hearts are gray
                    if (window.favoriteIds.length === 0) {
                        updateWishlistButtonStates();
                    }
                    
                    // Show a subtle notification
                    showNotification(data.message);
                } else {
                    // Revert UI if request failed
                    button.classList.toggle('active');
                    if (icon) {
                        icon.classList.toggle('fas');
                        icon.classList.toggle('far');
                        // Revert color
                        if (icon.classList.contains('fas')) {
                            icon.style.setProperty('color', '#ff0000', 'important');
                        } else {
                            icon.style.setProperty('color', '#666', 'important');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling favorite:', error);
                button.dataset.processing = 'false';
                
                // Revert UI on error
                button.classList.toggle('active');
                if (icon) {
                    icon.classList.toggle('fas');
                    icon.classList.toggle('far');
                    // Revert color
                    if (icon.classList.contains('fas')) {
                        icon.style.setProperty('color', '#ff0000', 'important');
                    } else {
                        icon.style.setProperty('color', '#666', 'important');
                    }
                }
            });
        }

        /**
         * Show a notification message
         */
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 2 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        }

        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        /**
         * CART FUNCTIONS
         */

        /**
         * Add product to cart
         */
        function addToCart(productId, button) {
            // Disable button temporarily
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button state
                    button.classList.add('in-cart');
                    button.innerHTML = '<i class="fas fa-check"></i> Added';
                    
                    // Update cart count
                    updateCartCount();
                    
                    // Show notification
                    showNotification(data.message);
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);
                } else {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    showNotification(data.message || 'Failed to add to cart');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                showNotification('Error adding to cart');
            });
        }

        /**
         * Update cart count in header
         */
        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cart-count');
                    const newCount = data.count || 0;
                    
                    if (badge) {
                        // Only update if count changed to prevent unnecessary DOM updates
                        const currentCount = parseInt(badge.textContent);
                        if (currentCount !== newCount) {
                            badge.textContent = newCount;
                        }
                        // Remove loading class after first update
                        badge.classList.remove('badge-loading');
                    }
                    
                    // Store cart product IDs globally
                    return fetch('/cart/products');
                })
                .then(response => response.json())
                .then(data => {
                    window.cartProductIds = data.productIds || [];
                    updateCartButtonStates();
                })
                .catch(error => console.error('Error updating cart count:', error));
        }

        /**
         * Update all add-to-cart button states
         */
        function updateCartButtonStates() {
            const cartButtons = document.querySelectorAll('.add-to-cart[data-product-id]');
            cartButtons.forEach(button => {
                const productId = parseInt(button.getAttribute('data-product-id'));
                if (window.cartProductIds && window.cartProductIds.includes(productId)) {
                    button.classList.add('in-cart');
                    // Optionally change the text
                    const icon = button.querySelector('i');
                    if (icon && !button.innerHTML.includes('Added')) {
                        // Keep the original functionality
                    }
                } else {
                    button.classList.remove('in-cart');
                }
            });
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</body>
</html>
