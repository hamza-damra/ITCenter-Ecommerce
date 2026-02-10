<!DOCTYPE html>
<html lang="<?php echo e(current_locale()); ?>" dir="<?php echo e(locale_direction()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'IT Center'); ?></title>
    <?php echo $__env->yieldContent('meta'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    
    <link rel="stylesheet" href="<?php echo e(asset('css/horizontal-scroller.css')); ?>">
    
    
    <link rel="stylesheet" href="<?php echo e(asset('css/search-autocomplete.css')); ?>">
    
    <?php if(is_rtl()): ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?php echo e(asset('css/layout.css')); ?>">
</head>
<body data-t-request-product="<?php echo e(__t('messages.request_product')); ?>" data-t-contact-us="<?php echo e(__t('messages.contact_us')); ?>">
    <?php if (empty(trim($__env->yieldContent('hideHeader')))): ?>
    <!-- Mobile Menu Toggle - Outside header for proper fixed positioning -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu" type="button">
        <div class="hamburger-icon" style="display: flex; flex-direction: column; gap: 5px; width: 20px;">
            <span style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
            <span style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
            <span style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
        </div>
    </button>

    <!-- Mobile Search Icon Button -->
    <button class="mobile-search-btn" id="mobileSearchBtn" aria-label="<?php echo e(__t('messages.search')); ?>" type="button">
        <i class="fas fa-search"></i>
    </button>

    <!-- Mobile Search Overlay -->
    <div class="mobile-search-overlay" id="mobileSearchOverlay">
        <div class="mobile-search-overlay-header">
            <form action="<?php echo e(route('products')); ?>" method="GET" class="mobile-search-form" autocomplete="off">
                <button type="submit" class="mobile-search-submit-btn" aria-label="<?php echo e(__t('messages.search')); ?>">
                    <i class="fas fa-search"></i>
                </button>
                <input type="search" 
                       name="search" 
                       class="mobile-search-input"
                       id="mobileSearchInput"
                       placeholder="<?php echo e(__t('messages.search')); ?>"
                       autocomplete="off"
                       autofocus>
            </form>
            <button class="mobile-search-close" id="mobileSearchClose" type="button" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-search-results" id="mobileSearchResults">
            
        </div>
    </div>

    <!-- Mobile Menu Overlay - positioned to NOT cover the nav-menu -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1040; opacity: 0; visibility: hidden; pointer-events: none;"></div>
    
    <!-- Mobile Navigation Menu - OUTSIDE header for proper z-index -->
    <nav class="nav-menu" id="navMenu" style="display: none;">
        <div class="nav-menu-header">
            <img src="<?php echo e(asset('images/assets/logo.png')); ?>" alt="IT Center Logo">
        </div>
        <ul class="nav-menu-list">
            <li><a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>"><i class="fas fa-home"></i> <?php echo e(__t('messages.home')); ?></a></li>
            <li><a href="<?php echo e(route('categories')); ?>" class="<?php echo e(request()->routeIs('categories') ? 'active' : ''); ?>"><i class="fas fa-th-large"></i> <?php echo e(__t('messages.categories')); ?></a></li>
            <li><a href="<?php echo e(route('products')); ?>" class="<?php echo e(request()->routeIs('products') ? 'active' : ''); ?>"><i class="fas fa-box"></i> <?php echo e(__t('messages.products')); ?></a></li>
            <li><a href="<?php echo e(route('about')); ?>" class="<?php echo e(request()->routeIs('about') ? 'active' : ''); ?>"><i class="fas fa-info-circle"></i> <?php echo e(__t('messages.about')); ?></a></li>
            <li><a href="<?php echo e(route('contact')); ?>" class="<?php echo e(request()->routeIs('contact') ? 'active' : ''); ?>"><i class="fas fa-envelope"></i> <?php echo e(__t('messages.contact')); ?></a></li>
        </ul>
        
        
        <div class="nav-menu-icons-section">
            <?php
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
            ?>
            
            <a href="<?php echo e(route('cart.index')); ?>" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-shopping-cart"></i>
                    <span><?php echo e(__t('messages.cart')); ?></span>
                </span>
                <span class="nav-badge <?php echo e($mobileCartCount > 0 ? '' : 'hidden'); ?>" id="mobile-cart-count"><?php echo e($mobileCartCount); ?></span>
            </a>
            
            <a href="<?php echo e(route('favorites')); ?>" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-heart"></i>
                    <span><?php echo e(__t('messages.favorites')); ?></span>
                </span>
                <span class="nav-badge <?php echo e($mobileFavCount > 0 ? '' : 'hidden'); ?>" id="mobile-favorites-count"><?php echo e($mobileFavCount); ?></span>
            </a>
            
            <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('login')); ?>" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-user"></i>
                    <span><?php echo e(__t('messages.login')); ?></span>
                </span>
            </a>
            <a href="<?php echo e(route('register')); ?>" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-user-plus"></i>
                    <span><?php echo e(__t('messages.register')); ?></span>
                </span>
            </a>
            <?php else: ?>
            <a href="<?php echo e(route('profile.index')); ?>" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo e(__t('messages.my_profile')); ?></span>
                </span>
            </a>
            <a href="<?php echo e(route('orders.index')); ?>" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-box"></i>
                    <span><?php echo e(__t('messages.my_orders')); ?></span>
                </span>
            </a>
            <form action="<?php echo e(route('logout')); ?>" method="POST" style="margin: 0;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-icon-item" style="width: 100%; border: none; cursor: pointer; font-family: inherit;">
                    <span class="nav-icon-content">
                        <i class="fas fa-sign-out-alt" style="color: #dc3545 !important;"></i>
                        <span style="color: #dc3545;"><?php echo e(__t('messages.logout')); ?></span>
                    </span>
                </button>
            </form>
            <?php endif; ?>
        </div>
        
        
        <div class="nav-menu-language-section">
            <div class="language-title"><?php echo e(__t('messages.language')); ?></div>
            <?php $__currentLoopData = available_locales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(switch_locale_url($locale)); ?>" class="nav-lang-item <?php echo e($locale === current_locale() ? 'active' : ''); ?>">
                    <span class="lang-flag">
                        <?php if($locale === 'en'): ?>
                            🇬🇧
                        <?php elseif($locale === 'ar'): ?>
                            🇵🇸
                        <?php elseif($locale === 'he'): ?>
                            🇮🇱
                        <?php else: ?>
                            🌐
                        <?php endif; ?>
                    </span>
                    <span><?php echo e(locale_name($locale)); ?></span>
                    <?php if($locale === current_locale()): ?>
                        <i class="fas fa-check"></i>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </nav>
    
    
    <header>
        <div class="header-container">
            
            <div class="mobile-header-icons">
                <button type="button" class="mhi-btn" id="mobileHeaderMenuBtn" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="<?php echo e(route('cart.index')); ?>" class="mhi-btn mhi-cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="mhi-badge <?php echo e($mobileCartCount > 0 ? '' : 'hidden'); ?>" id="mhi-cart-count"><?php echo e($mobileCartCount); ?></span>
                </a>
                <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>" class="mhi-btn" aria-label="<?php echo e(__t('messages.login')); ?>">
                    <i class="fas fa-user"></i>
                </a>
                <?php else: ?>
                <a href="<?php echo e(route('profile.index')); ?>" class="mhi-btn" aria-label="<?php echo e(__t('messages.my_profile')); ?>">
                    <i class="fas fa-user"></i>
                </a>
                <?php endif; ?>
                <button type="button" class="mhi-btn" id="mobileHeaderSearchBtn" aria-label="<?php echo e(__t('messages.search')); ?>">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <div class="logo">
                <a href="<?php echo e(route('home')); ?>">
                    <img src="<?php echo e(asset('images/assets/logo.png')); ?>" alt="IT Center Logo">
                </a>
            </div>

            <form action="<?php echo e(route('products')); ?>" method="GET" class="search-bar" role="search" autocomplete="off">
                <input type="search" 
                       name="search" 
                       placeholder="<?php echo e(__t('messages.search')); ?>"
                       autocomplete="off"
                       role="combobox"
                       aria-autocomplete="list"
                       aria-expanded="false"
                       aria-haspopup="listbox"
                       aria-controls="search-autocomplete-listbox"
                       aria-label="<?php echo e(__t('messages.search')); ?>"
                       value="<?php echo e(request('search')); ?>">
                <!--<button class="search-btn" type="submit" aria-label="<?php echo e(__t('messages.search')); ?>">
                    <i class="fas fa-search"></i>
                </button>-->
            </form>

            <div class="header-icons">
                <?php if(auth()->guard()->guest()): ?>
                <div class="header-icon" style="position: relative;">
                    <a href="<?php echo e(route('login')); ?>" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;" aria-label="<?php echo e(__t('messages.login')); ?>">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
                <?php else: ?>
                <div class="header-icon user-dropdown" style="position: relative;">
                    <div class="user-toggle" style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user-circle"></i>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.3s;"></i>
                    </div>
                    <div class="user-dropdown-menu" style="display: none; position: absolute; top: calc(100% + 10px); inset-inline-end: 0; background: #ffffff; backdrop-filter: blur(10px); border: 2px solid #e8eef7; border-radius: 12px; min-width: max-content; box-shadow: 0 8px 24px rgba(39, 98, 243, 0.12); overflow: hidden; z-index: 1001; opacity: 0; transform: translateY(-10px); transition: opacity 0.3s ease, transform 0.3s ease;">
                        <a href="<?php echo e(route('profile.index')); ?>" class="user-menu-item" style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: start; font-family: inherit; font-size: inherit; text-decoration: none; color: inherit; white-space: nowrap;">
                            <i class="fas fa-user"></i>
                            <span><?php echo e(__t('messages.my_profile')); ?></span>
                        </a>
                        <a href="<?php echo e(route('orders.index')); ?>" class="user-menu-item" style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: start; font-family: inherit; font-size: inherit; text-decoration: none; color: inherit; white-space: nowrap;">
                            <i class="fas fa-box"></i>
                            <span><?php echo e(__t('messages.my_orders')); ?></span>
                        </a>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" style="margin: 0;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="user-menu-item" style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: start; font-family: inherit; font-size: inherit; text-decoration: none; color: #dc3545; white-space: nowrap;">
                                <i class="fas fa-sign-out-alt"></i>
                                <span><?php echo e(__t('messages.logout')); ?></span>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <div class="header-icon language-dropdown" style="position: relative;">
                    <div class="language-toggle" style="cursor: pointer; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="fas fa-globe"></i>
                        <span class="current-lang" style="font-size: 0.85rem; font-weight: 600;"><?php echo e(strtoupper(current_locale())); ?></span>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.3s;"></i>
                    </div>
                    <div class="language-dropdown-menu">
                        <?php $__currentLoopData = available_locales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(switch_locale_url($locale)); ?>" 
                               class="language-option <?php echo e($locale === current_locale() ? 'active' : ''); ?>"
                               data-locale="<?php echo e($locale); ?>">
                                <span class="lang-icon">
                                    <?php if($locale === 'en'): ?>
                                        🇬🇧
                                    <?php else: ?>
                                        🇵🇸
                                    <?php endif; ?>
                                </span>
                                <span class="lang-name"><?php echo e(locale_name($locale)); ?></span>
                                <?php if($locale === current_locale()): ?>
                                    <i class="fas fa-check lang-check"></i>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="header-icon">
                    <a href="<?php echo e(route('favorites')); ?>" style="color: inherit; text-decoration: none;" aria-label="<?php echo e(__t('messages.favorites')); ?>">
                        <i class="fas fa-heart"></i>
                        <?php
                            // Get initial favorites count from server to prevent flash
                            $initialFavCount = 0;
                            try {
                                // Check if database is available before querying
                                if (\App\Services\DatabaseStateService::isDatabaseAvailable()) {
                                    if (Auth::check()) {
                                        $initialFavCount = \App\Models\Favorite::where('user_id', Auth::id())->count();
                                    } else {
                                        $sessionId = Session::getId();
                                        $initialFavCount = \App\Models\Favorite::where('session_id', $sessionId)->count();
                                    }
                                }
                            } catch (\Exception $e) {
                                // Database not available or query failed - use 0 as default
                                $initialFavCount = 0;
                            }
                        ?>
                        <span class="badge <?php echo e($initialFavCount > 0 ? '' : 'hidden'); ?>" id="favorites-count"><?php echo e($initialFavCount); ?></span>
                    </a>
                </div>
                <?php if(auth()->guard()->check()): ?>
                <div class="header-icon">
                    <a href="<?php echo e(route('orders.index')); ?>" style="color: inherit; text-decoration: none; position: relative;" title="<?php echo e(__t('messages.my_orders')); ?>" aria-label="<?php echo e(__t('messages.my_orders')); ?>">
                        <i class="fas fa-box"></i>
                    </a>
                </div>
                <?php endif; ?>
                <div class="header-icon">
                    <a href="<?php echo e(route('cart.index')); ?>" style="color: inherit; text-decoration: none;" aria-label="<?php echo e(__t('messages.cart')); ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                            // Get initial cart count from server to prevent flash
                            if (Auth::check()) {
                                $initialCartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                            } else {
                                $sessionId = Session::getId();
                                $initialCartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum('quantity');
                            }
                        ?>
                        <span class="badge <?php echo e($initialCartCount > 0 ? '' : 'hidden'); ?>" id="cart-count"><?php echo e($initialCartCount); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    
    <?php if(isset($navigationCategories) && $navigationCategories->count() > 0 && request()->routeIs('home')): ?>
        <?php if (isset($component)) { $__componentOriginal3abf49ce9a3dee012fc0cb151cc636d5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3abf49ce9a3dee012fc0cb151cc636d5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.category-nav','data' => ['categories' => $navigationCategories]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('category-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navigationCategories)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3abf49ce9a3dee012fc0cb151cc636d5)): ?>
<?php $attributes = $__attributesOriginal3abf49ce9a3dee012fc0cb151cc636d5; ?>
<?php unset($__attributesOriginal3abf49ce9a3dee012fc0cb151cc636d5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3abf49ce9a3dee012fc0cb151cc636d5)): ?>
<?php $component = $__componentOriginal3abf49ce9a3dee012fc0cb151cc636d5; ?>
<?php unset($__componentOriginal3abf49ce9a3dee012fc0cb151cc636d5); ?>
<?php endif; ?>
    <?php endif; ?>

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
    <div class="social-icons-toggle" onclick="toggleMobileSocial()" role="button" aria-label="<?php echo e(__t('messages.share')); ?>" tabindex="0">
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
    <?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if (empty(trim($__env->yieldContent('hideHeader')))): ?>
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="<?php echo e(asset('images/assets/logo.png')); ?>" alt="IT Center Logo">
                </div>
                <p><?php echo e(__('messages.footer_description')); ?></p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3><?php echo e(__('messages.quick_links')); ?></h3>
                <ul>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="<?php echo e(route('home')); ?>"><?php echo e(__('messages.home')); ?></a></li>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="<?php echo e(route('products')); ?>"><?php echo e(__('messages.products')); ?></a></li>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="<?php echo e(route('about')); ?>"><?php echo e(__('messages.about')); ?></a></li>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="<?php echo e(route('contact')); ?>"><?php echo e(__('messages.contact_us')); ?></a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3><?php echo e(__('messages.footer_categories')); ?></h3>
                <ul>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="#"><?php echo e(__('messages.laptops')); ?></a></li>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="#"><?php echo e(__('messages.desktops')); ?></a></li>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="#"><?php echo e(__('messages.accessories')); ?></a></li>
                    <li><i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i><a href="#"><?php echo e(__('messages.components')); ?></a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3><?php echo e(__('messages.contact_us')); ?></h3>
                <ul>
                    <li><i class="fas fa-phone"></i><a href="tel:0595910045">0595910045</a></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:support@itcenter.vip">support@itcenter.vip</a></li>
                    <li><i class="fas fa-map-marker-alt"></i><span style="color: #94a3b8;"><?php echo e(__('messages.location')); ?></span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo e(date('Y')); ?> <a href="<?php echo e(route('home')); ?>">IT Center</a>. <?php echo e(__('messages.all_rights_reserved')); ?></p>
        </div>
    </footer>
    <?php endif; ?>

    
    <script src="<?php echo e(asset('js/layout.js')); ?>" defer></script>

    
    
    <script src="<?php echo e(asset('js/horizontal-scroller.js')); ?>"></script>
    
    
    <script src="<?php echo e(asset('js/search-autocomplete.js')); ?>"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/layouts/app.blade.php ENDPATH**/ ?>