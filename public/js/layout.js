/* ============================================================
   ITCenter Layout JS — Extracted from app.blade.php
   Reads RTL/CSRF/translations from DOM instead of Blade.
   ============================================================ */

// Global configuration — derived from DOM
const isRTL = document.documentElement.dir === 'rtl';

// Header scroll effect
window.addEventListener('scroll', function () {
    const header = document.querySelector('header');
    if (!header) return;
    const scrollThreshold = window.innerHeight * 0.1;
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    if (window.scrollY > scrollThreshold) {
        header.classList.add('scrolled');
        if (mobileSearchBtn) mobileSearchBtn.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
        if (mobileSearchBtn) mobileSearchBtn.classList.remove('scrolled');
    }
});

// Mobile Social Icons Toggle Function
function toggleMobileSocial() {
    const toggle = document.querySelector('.social-icons-toggle');
    const popup = document.querySelector('.social-icons-mobile');
    if (toggle && popup) {
        toggle.classList.toggle('active');
        popup.classList.toggle('active');
    }
}

// Close mobile social icons when clicking outside
document.addEventListener('click', function (e) {
    const toggle = document.querySelector('.social-icons-toggle');
    const popup = document.querySelector('.social-icons-mobile');
    if (toggle && popup && !toggle.contains(e.target) && !popup.contains(e.target)) {
        toggle.classList.remove('active');
        popup.classList.remove('active');
    }
});

// Language and User dropdown toggle
document.addEventListener('DOMContentLoaded', function () {
    // Language Dropdown
    const languageDropdown = document.querySelector('.language-dropdown');
    const languageToggle = languageDropdown ? languageDropdown.querySelector('.language-toggle') : null;
    const languageMenu = languageDropdown ? languageDropdown.querySelector('.language-dropdown-menu') : null;

    // User Dropdown
    const userDropdown = document.querySelector('.user-dropdown');
    const userToggle = userDropdown ? userDropdown.querySelector('.user-toggle') : null;
    const userMenu = userDropdown ? userDropdown.querySelector('.user-dropdown-menu') : null;

    // Helper function to close all dropdowns
    function closeAllDropdowns() {
        if (languageDropdown) {
            languageDropdown.classList.remove('active');
        }
        if (userDropdown && userMenu) {
            userDropdown.classList.remove('active');
            userMenu.style.opacity = '0';
            userMenu.style.transform = 'translateY(-10px)';
            setTimeout(() => { userMenu.style.display = 'none'; }, 300);
        }
    }

    // Language dropdown toggle
    if (languageToggle && languageMenu) {
        languageToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            if (userDropdown && userDropdown.classList.contains('active')) {
                userDropdown.classList.remove('active');
                if (userMenu) {
                    userMenu.style.opacity = '0';
                    userMenu.style.transform = 'translateY(-10px)';
                    setTimeout(() => { userMenu.style.display = 'none'; }, 300);
                }
            }
            languageDropdown.classList.toggle('active');
        });

        const languageOptions = languageMenu.querySelectorAll('.language-option');
        languageOptions.forEach(option => {
            option.addEventListener('click', function () {
                languageDropdown.classList.remove('active');
            });
        });
    }

    // User dropdown toggle
    if (userToggle && userMenu) {
        const menuItems = userMenu.querySelectorAll('.user-menu-item');
        menuItems.forEach(item => {
            item.style.removeProperty('color');
            const icons = item.querySelectorAll('i');
            const spans = item.querySelectorAll('span');
            icons.forEach(icon => icon.style.removeProperty('color'));
            spans.forEach(span => span.style.removeProperty('color'));
        });

        userToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            if (languageDropdown && languageDropdown.classList.contains('active')) {
                languageDropdown.classList.remove('active');
            }
            const isCurrentlyActive = userDropdown.classList.contains('active');
            userDropdown.classList.toggle('active');
            if (!isCurrentlyActive) {
                userMenu.style.display = 'block';
                setTimeout(() => {
                    userMenu.style.opacity = '1';
                    userMenu.style.transform = 'translateY(0)';
                }, 10);
            } else {
                userMenu.style.opacity = '0';
                userMenu.style.transform = 'translateY(-10px)';
                setTimeout(() => { userMenu.style.display = 'none'; }, 300);
            }
        });

        const userMenuItems = userMenu.querySelectorAll('a, button');
        userMenuItems.forEach(item => {
            item.addEventListener('mouseenter', function () {
                this.style.background = 'rgba(59, 130, 246, 0.08)';
                this.style.color = '#3b82f6';
            });
            item.addEventListener('mouseleave', function () {
                this.style.background = '';
                this.style.color = '';
            });
        });
    }

    // Global click-outside handler for all dropdowns
    document.addEventListener('click', function (e) {
        const isLanguageClick = languageDropdown && languageDropdown.contains(e.target);
        const isUserClick = userDropdown && userDropdown.contains(e.target);
        if (!isLanguageClick && !isUserClick) {
            closeAllDropdowns();
        }
    });

    // Sync header counters on page load
    refreshHeaderCounters();

    // Load favorites IDs for wishlist button states
    updateFavoritesCount();

    // Load cart product IDs for button states
    updateCartCount();

    // Initialize all wishlist buttons on the page
    initializeWishlistButtons();
});

// CSRF Token for AJAX requests
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

/**
 * GLOBAL FUNCTION: Refresh both cart and favorites counters
 */
async function refreshHeaderCounters() {
    try {
        const [cartRes, favRes] = await Promise.all([
            fetch('/cart/count', { credentials: 'same-origin' }),
            fetch('/favorites/count', { credentials: 'same-origin' }),
        ]);

        const cart = await cartRes.json();
        const fav = await favRes.json();

        const cartEl = document.getElementById('cart-count');
        const mobileCartEl = document.getElementById('mobile-cart-count');
        const favEl = document.getElementById('favorites-count');

        if (cartEl && typeof cart.count !== 'undefined') {
            cartEl.textContent = cart.count;
            cart.count > 0 ? cartEl.classList.remove('hidden') : cartEl.classList.add('hidden');
        }

        if (mobileCartEl && typeof cart.count !== 'undefined') {
            mobileCartEl.textContent = cart.count;
            cart.count > 0 ? mobileCartEl.classList.remove('hidden') : mobileCartEl.classList.add('hidden');
        }

        // Sync mobile header icons cart badge
        const mhiCartEl = document.getElementById('mhi-cart-count');
        if (mhiCartEl && typeof cart.count !== 'undefined') {
            mhiCartEl.textContent = cart.count;
            cart.count > 0 ? mhiCartEl.classList.remove('hidden') : mhiCartEl.classList.add('hidden');
        }

        if (favEl && typeof fav.count !== 'undefined') {
            favEl.textContent = fav.count;
            fav.count > 0 ? favEl.classList.remove('hidden') : favEl.classList.add('hidden');
        }
    } catch (error) {
        console.error('Failed to refresh header counters:', error);
    }
}

/**
 * Update the favorites count in header
 */
function updateFavoritesCount(skipButtonUpdate) {
    fetch('/favorites/ids')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('favorites-count');
            const newCount = data.favoriteIds ? data.favoriteIds.length : 0;

            if (badge) {
                badge.textContent = newCount;
                newCount > 0 ? badge.classList.remove('hidden') : badge.classList.add('hidden');
            }

            window.favoriteIds = data.favoriteIds || [];
            updateWishlistButtonStates();
        })
        .catch(error => {
            console.error('Error updating favorites count:', error);
        });
}

/**
 * Update all wishlist button states based on current favorites
 */
function updateWishlistButtonStates() {
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');

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
        if (button.dataset.initialized) return;
        button.dataset.initialized = 'true';
        button.addEventListener('click', function (e) {
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
    if (button.dataset.processing === 'true') return;
    button.dataset.processing = 'true';
    const icon = button.querySelector('i');

    // Optimistic UI update
    button.classList.toggle('active');
    if (icon) {
        icon.classList.toggle('fas');
        icon.classList.toggle('far');
        if (icon.classList.contains('fas')) {
            icon.style.setProperty('color', '#ff0000', 'important');
        } else {
            icon.style.setProperty('color', '#666', 'important');
        }
    }

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

            if (!window.favoriteIds) window.favoriteIds = [];
            const productIdInt = parseInt(productId);

            if (wasAdded) {
                if (!window.favoriteIds.includes(productIdInt)) {
                    window.favoriteIds.push(productIdInt);
                }
            } else {
                window.favoriteIds = window.favoriteIds.filter(id => id !== productIdInt);
            }

            if (badge) {
                const newCount = window.favoriteIds.length;
                badge.textContent = newCount;
                newCount > 0 ? badge.classList.remove('hidden') : badge.classList.add('hidden');
            }

            if (window.favoriteIds.length === 0) {
                updateWishlistButtonStates();
            }

            showNotification(data.message);
        } else {
            // Revert UI on error
            button.classList.toggle('active');
            if (icon) {
                icon.classList.toggle('fas');
                icon.classList.toggle('far');
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
    const notification = document.createElement('div');
    notification.textContent = message;
    const positionSide = isRTL ? 'left: 20px;' : 'right: 20px;';
    const animName = isRTL ? 'slideInRTL' : 'slideIn';
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        ${positionSide}
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 10000;
        direction: ${isRTL ? 'rtl' : 'ltr'};
        font-family: ${isRTL ? "'Cairo', sans-serif" : "inherit"};
        animation: ${animName} 0.3s ease-out;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = (isRTL ? 'slideOutRTL' : 'slideOut') + ' 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

/**
 * GLOBAL HELPER: Handle 403 responses
 */
window.handleAccountStatus = function (response) {
    if (response.status === 403) {
        return response.json().then(data => {
            showNotification(data.message || 'Access denied');
            if (data.redirect) {
                setTimeout(() => { window.location.href = data.redirect; }, 2000);
            }
            throw new Error('Access denied');
        });
    }
    return Promise.resolve(response);
};

/**
 * Add product to cart
 */
function addToCart(productId, button) {
    button.disabled = true;
    const originalText = button.innerHTML;
    const addedText = button.getAttribute('data-added-text') || 'Added';
    const originalTextAttr = button.getAttribute('data-original-text') || 'Add to Cart';
    const isIconButton = button.classList.contains('add-to-cart-icon');

    if (isIconButton) {
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    } else {
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    }

    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => handleAccountStatus(response))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.add('in-cart');

            if (isIconButton) {
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.setAttribute('title', addedText);
                button.setAttribute('aria-label', addedText);
            } else {
                if (isRTL) {
                    button.innerHTML = addedText + ' <i class="fas fa-check"></i>';
                } else {
                    button.innerHTML = '<i class="fas fa-check"></i> ' + addedText;
                }
            }

            if (window.cartProductIds && !window.cartProductIds.includes(productId)) {
                window.cartProductIds.push(productId);
            }

            refreshHeaderCounters();
            showNotification(data.message);
            button.disabled = false;
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
 * Request out-of-stock product
 * Reads translations from data attributes on body element.
 */
function requestProduct(productId, productName) {
    var escapedName = productName.replace(/'/g, "\\'");
    var requestMsg = document.body.getAttribute('data-t-request-product') || 'Request Product';
    var contactMsg = document.body.getAttribute('data-t-contact-us') || 'Contact Us';
    if (confirm(requestMsg + ': ' + productName + '?\n\n' + contactMsg + ': 0599-123456')) {
        showNotification(requestMsg + ': ' + productName);
    }
}

/**
 * Update cart count in header and load product IDs for button states
 */
function updateCartCount() {
    fetch('/cart/products')
        .then(response => response.json())
        .then(data => {
            window.cartProductIds = data.productIds || [];
            updateCartButtonStates();
        })
        .catch(error => {
            console.error('Error loading cart products:', error);
        });
}

/**
 * Update all add-to-cart button states
 */
function updateCartButtonStates() {
    const cartButtons = document.querySelectorAll('.add-to-cart[data-product-id]');
    cartButtons.forEach(button => {
        const productId = parseInt(button.getAttribute('data-product-id'));
        const addedText = button.getAttribute('data-added-text') || 'In Cart';
        const originalText = button.getAttribute('data-original-text') || 'Add to Cart';

        if (window.cartProductIds && window.cartProductIds.includes(productId)) {
            button.classList.add('in-cart');
            if (!button.innerHTML.includes('check')) {
                if (isRTL) {
                    button.innerHTML = addedText + ' <i class="fas fa-check"></i>';
                } else {
                    button.innerHTML = '<i class="fas fa-check"></i> ' + addedText;
                }
            }
        } else {
            button.classList.remove('in-cart');
            if (button.innerHTML.includes('check')) {
                if (isRTL) {
                    button.innerHTML = originalText + ' <i class="fas fa-shopping-cart"></i>';
                } else {
                    button.innerHTML = '<i class="fas fa-shopping-cart"></i> ' + originalText;
                }
            }
        }
    });
}

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', function () {
    updateCartCount();
});

// Ensure page loading indicator stops after page is fully loaded
window.addEventListener('load', function () {
    document.body.classList.add('loaded');

    var loadingElements = document.querySelectorAll('.loading, .spinner, [class*="loading"]');
    loadingElements.forEach(function (el) { el.style.display = 'none'; });

    var images = document.querySelectorAll('img');
    var loadedImages = 0;
    var totalImages = images.length;

    if (totalImages === 0) {
        document.documentElement.classList.add('page-loaded');
    } else {
        images.forEach(function (img) {
            if (img.complete) {
                loadedImages++;
            } else {
                img.addEventListener('load', function () {
                    loadedImages++;
                    if (loadedImages === totalImages) document.documentElement.classList.add('page-loaded');
                });
                img.addEventListener('error', function () {
                    loadedImages++;
                    if (loadedImages === totalImages) document.documentElement.classList.add('page-loaded');
                });
            }
        });
        setTimeout(function () { document.documentElement.classList.add('page-loaded'); }, 3000);
    }
});

// Additional fix for browser loading indicator
document.addEventListener('DOMContentLoaded', function () {
    document.documentElement.classList.add('page-interactive');
    setTimeout(function () {
        document.documentElement.classList.add('page-loaded');
        document.body.classList.add('loaded');
    }, 100);
});

// Handle page visibility changes
document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible') {
        document.documentElement.classList.add('page-loaded');
        document.body.classList.add('loaded');
    }
});

// Mobile Sidebar Toggle
(function () {
    'use strict';

    function initMobileSidebar() {
        var mobileMenuToggle = document.getElementById('mobileMenuToggle');
        var mobileHeaderMenuBtn = document.getElementById('mobileHeaderMenuBtn');
        var navMenu = document.getElementById('navMenu');
        var mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        if (!navMenu || !mobileMenuOverlay) return;

        function openSidebar() {
            navMenu.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            if (mobileMenuToggle) mobileMenuToggle.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            navMenu.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            if (mobileMenuToggle) mobileMenuToggle.classList.remove('active');
            document.body.style.overflow = '';
        }

        function toggleSidebar(e) {
            e.preventDefault();
            e.stopPropagation();
            navMenu.classList.contains('active') ? closeSidebar() : openSidebar();
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.onclick = toggleSidebar;
        }

        if (mobileHeaderMenuBtn) {
            mobileHeaderMenuBtn.onclick = toggleSidebar;
        }

        mobileMenuOverlay.onclick = function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
        };

        navMenu.onclick = function (e) {
            if (e.target.tagName === 'A' || e.target.closest('a')) return true;
            e.stopPropagation();
        };

        var menuLinks = navMenu.querySelectorAll('.nav-menu-list a');
        menuLinks.forEach(function (link) {
            link.onclick = function () {
                closeSidebar();
                return true;
            };
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) closeSidebar();
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768 && navMenu.classList.contains('active')) closeSidebar();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileSidebar);
    } else {
        initMobileSidebar();
    }
})();

// Mobile Search Overlay
(function () {
    'use strict';

    function initMobileSearch() {
        var searchBtn = document.getElementById('mobileSearchBtn');
        var searchBtnHeader = document.getElementById('mobileHeaderSearchBtn');
        var overlay = document.getElementById('mobileSearchOverlay');
        var closeBtn = document.getElementById('mobileSearchClose');
        var searchInput = document.getElementById('mobileSearchInput');
        var resultsContainer = document.getElementById('mobileSearchResults');

        if ((!searchBtn && !searchBtnHeader) || !overlay || !searchInput || !resultsContainer) return;

        var debounceTimer = null;
        var abortController = null;
        var currentRequestId = 0;
        var minChars = 2;
        var debounceMs = 350;
        var apiEndpoint = '/api/v1/search/suggestions';

        function getTranslation(key) {
            var lang = document.documentElement.lang || 'en';
            var t = {
                en: { products: 'Products', viewAll: 'View all results for', noResults: 'No products were found', searching: 'Searching...', startTyping: 'Start typing to search products' },
                ar: { products: 'المنتجات', viewAll: 'عرض جميع النتائج لـ', noResults: 'لم يتم العثور على منتجات', searching: 'جاري البحث...', startTyping: 'ابدأ بالكتابة للبحث عن المنتجات' },
                he: { products: 'מוצרים', viewAll: 'הצג את כל התוצאות עבור', noResults: 'לא נמצאו מוצרים', searching: 'מחפש...', startTyping: 'התחל להקליד כדי לחפש מוצרים' }
            };
            return t[lang] && t[lang][key] ? t[lang][key] : (t.en[key] || key);
        }

        function highlightMatch(text, query) {
            if (!text || !query) return text || '';
            try {
                var escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                return text.replace(new RegExp('(' + escaped + ')', 'gi'), '<mark>$1</mark>');
            } catch (e) {
                return text;
            }
        }

        function openOverlay() {
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(function () { searchInput.focus(); }, 100);
            if (!searchInput.value.trim()) {
                showEmptyState();
            }
        }

        function closeOverlay() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            if (debounceTimer) clearTimeout(debounceTimer);
            if (abortController) abortController.abort();
        }

        function showEmptyState() {
            resultsContainer.innerHTML =
                '<div class="mobile-search-empty">' +
                    '<i class="fas fa-search"></i>' +
                    '<p>' + getTranslation('startTyping') + '</p>' +
                '</div>';
        }

        function showLoading() {
            if (!resultsContainer.querySelector('.mobile-search-card')) {
                resultsContainer.innerHTML =
                    '<div class="mobile-search-loading">' +
                        '<div class="spinner"></div>' +
                        '<span>' + getTranslation('searching') + '</span>' +
                    '</div>';
            }
        }

        function showNoResults(query) {
            resultsContainer.innerHTML =
                '<div class="mobile-search-no-results">' +
                    '<i class="fas fa-search"></i>' +
                    '<p>' + getTranslation('noResults') + '</p>' +
                '</div>' +
                '<a href="/products?search=' + encodeURIComponent(query) + '" class="mobile-search-view-all">' +
                    '<i class="fas fa-search"></i>' +
                    '<span>' + getTranslation('viewAll') + ' "' + query + '"</span>' +
                    '<i class="fas fa-arrow-' + (isRTL ? 'left' : 'right') + '"></i>' +
                '</a>';
        }

        function renderResults(data, query) {
            var products = data.products || [];
            var total = data.total || 0;
            var arrow = isRTL ? 'left' : 'right';

            if (total === 0) {
                showNoResults(query);
                return;
            }

            var html = '';
            products.forEach(function (item) {
                var hasDiscount = item.original_price && parseFloat(item.original_price) > parseFloat(item.price);
                html +=
                    '<a href="' + item.url + '" class="mobile-search-card">' +
                        '<div class="mobile-search-card-image">' +
                            '<img src="' + (item.image || '/images/products/default.png') + '" alt="' + (item.name || '') + '" loading="lazy">' +
                        '</div>' +
                        '<div class="mobile-search-card-info">' +
                            '<div class="mobile-search-card-name">' + highlightMatch(item.name, query) + '</div>' +
                            '<div class="mobile-search-card-price">' +
                                '<span class="current-price">₪' + parseFloat(item.price).toFixed(2) + '</span>' +
                                (hasDiscount ? '<span class="original-price">₪' + parseFloat(item.original_price).toFixed(2) + '</span>' : '') +
                            '</div>' +
                        '</div>' +
                        '<i class="fas fa-chevron-' + arrow + ' mobile-search-card-arrow"></i>' +
                    '</a>';
            });

            html += '<a href="/products?search=' + encodeURIComponent(query) + '" class="mobile-search-view-all">' +
                '<i class="fas fa-search"></i>' +
                '<span>' + getTranslation('viewAll') + ' "' + query + '"</span>' +
                '<i class="fas fa-arrow-' + arrow + '"></i>' +
            '</a>';

            resultsContainer.innerHTML = html;
        }

        function fetchResults(query) {
            if (abortController) abortController.abort();
            abortController = new AbortController();
            var requestId = ++currentRequestId;

            showLoading();

            fetch(apiEndpoint + '?q=' + encodeURIComponent(query) + '&limit=15', {
                signal: abortController.signal,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (response) {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(function (data) {
                if (requestId !== currentRequestId) return;
                if (data.success && data.data) {
                    renderResults(data.data, query);
                } else {
                    showNoResults(query);
                }
            })
            .catch(function (error) {
                if (error.name === 'AbortError') return;
                if (requestId === currentRequestId) {
                    showNoResults(query);
                }
            });
        }

        function handleInput() {
            var query = searchInput.value.trim();
            if (debounceTimer) clearTimeout(debounceTimer);

            if (query.length < minChars) {
                if (query.length === 0) {
                    showEmptyState();
                }
                return;
            }

            debounceTimer = setTimeout(function () {
                fetchResults(query);
            }, debounceMs);
        }

        // Event Listeners
        function handleSearchOpen(e) {
            e.preventDefault();
            e.stopPropagation();
            openOverlay();
        }

        if (searchBtn) searchBtn.addEventListener('click', handleSearchOpen);
        if (searchBtnHeader) searchBtnHeader.addEventListener('click', handleSearchOpen);

        if (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                closeOverlay();
            });
        }

        searchInput.addEventListener('input', handleInput);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('active')) {
                closeOverlay();
            }
        });

        // Close overlay on resize to desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth > 768 && overlay.classList.contains('active')) {
                closeOverlay();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileSearch);
    } else {
        initMobileSearch();
    }
})();

// Global image error handler for broken external URLs
document.addEventListener('error', function (e) {
    if (e.target.tagName === 'IMG' && !e.target.classList.contains('error-handled')) {
        e.target.classList.add('error-handled');
        var parent = e.target.parentElement;
        if (!parent) return;

        var existingPlaceholder = parent.querySelector('.no-image');
        if (existingPlaceholder) {
            e.target.style.display = 'none';
            existingPlaceholder.style.display = 'flex';
            return;
        }

        var div = document.createElement('div');
        div.className = 'no-image';
        div.innerHTML = '<i class="fas fa-image"></i>';
        div.style.cssText = 'display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:#f5f5f5;color:#999;';

        try {
            parent.replaceChild(div, e.target);
        } catch (error) {
            e.target.style.display = 'none';
            parent.appendChild(div);
        }
    }
}, true);
