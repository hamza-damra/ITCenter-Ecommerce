<?php $__env->startSection('title', __t('messages.favorites') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Import Google Font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Override font - exclude Font Awesome icons */
    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    /* Favorites Section */
    .favorites-container {
        background: #f5f5f5;
        padding: 3rem 0;
        min-height: calc(100vh - 200px);
    }

    .favorites-header {
        max-width: 1400px;
        margin: 0 auto 2rem;
        padding: 0 2rem 1.5rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .favorites-header h1 {
        font-size: 2.5rem;
        color: #1f2937;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 0;
    }

    .favorites-header h1 i {
        color: #1f2937;
        font-size: 2rem;
    }

    .favorites-count {
        color: #6b7280;
        font-size: 1rem;
        font-weight: 600;
        background: #fff;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    /* Empty State */
    .empty-favorites {
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-favorites i {
        font-size: 5rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }

    .empty-favorites h2 {
        font-size: 1.5rem;
        color: #1f2937;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .empty-favorites p {
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .empty-favorites .btn-primary {
        background: #4169E1;
        color: #fff;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
    }

    .empty-favorites .btn-primary:hover {
        background: #1E90FF;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(31, 41, 55, 0.4);
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .product-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .product-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        border: 2px solid #e2e8f0;
    }

    .product-card-link:hover .product-card {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        border-color: #1f2937;
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 1rem;
    }

    .product-image img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .wishlist-btn {
        position: absolute;
        top: 10px;
        <?php echo e(is_rtl() ? 'right' : 'left'); ?>: 10px;
        background: rgba(255, 255, 255, 0.95);
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .wishlist-btn:hover {
        background: #1f2937;
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
        border-color: #1f2937;
    }

    .wishlist-btn.active {
        background: #1f2937;
        color: #fff;
        border-color: #1f2937;
    }

    .wishlist-btn i {
        font-size: 1rem;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        <?php echo e(is_rtl() ? 'left' : 'right'); ?>: 10px;
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(31, 41, 55, 0.4);
        text-transform: uppercase;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1f2937;
        transition: color 0.3s ease;
    }

    .product-card:hover .product-title {
        color: #1f2937;
    }

    .product-description {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
    }

    /* Icon-Only Add to Cart Button */
    .add-to-cart-icon {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #ffffff;
        border: none;
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.25);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .add-to-cart-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a4dbf 0%, #0f3a8f 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
        z-index: 0;
    }

    .add-to-cart-icon i {
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .add-to-cart-icon:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(39, 98, 243, 0.4), 0 2px 8px rgba(39, 98, 243, 0.2);
    }

    .add-to-cart-icon:hover::before {
        opacity: 1;
    }

    .add-to-cart-icon:hover i {
        transform: scale(1.1);
    }

    .add-to-cart-icon:active {
        transform: translateY(0) scale(1);
    }

    /* Success state - green with check icon */
    .add-to-cart-icon.in-cart {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
    }

    .add-to-cart-icon.in-cart::before {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .add-to-cart-icon.in-cart:hover {
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4), 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .add-to-cart-icon.in-cart i {
        animation: cartBounce 0.5s ease;
    }

    /* Out of stock state - orange with bell icon */
    .add-to-cart-icon.out-of-stock {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.25);
        cursor: pointer;
    }

    .add-to-cart-icon.out-of-stock::before {
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    }

    .add-to-cart-icon.out-of-stock:hover {
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4), 0 2px 8px rgba(249, 115, 22, 0.2);
    }

    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    /* Price styling */
    .product-price .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }

    .product-price .current-price {
        color: #2762f3;
        font-weight: 700;
        font-size: 1.2rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .product-footer {
            gap: 0.75rem;
        }

        .add-to-cart-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }

        .add-to-cart-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 1rem;
        }

        .favorites-header h1 {
            font-size: 1.5rem;
        }

        .favorites-count {
            font-size: 0.9rem;
        }
    }

    /* RTL Support */
    [dir="rtl"] .product-footer {
        flex-direction: row-reverse;
    }

    /* Loading State */
    .loading {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .loading i {
        font-size: 3rem;
        color: #4169E1;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Scroll Animation - Bottom to Top */
    .scroll-animate {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .scroll-animate.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="favorites-container">
    <div class="container">
        <?php if($favorites->count() > 0): ?>
            <div class="favorites-header">
                <h1>
                    <i class="fas fa-heart"></i>
                    <?php echo e(__t('messages.my_favorites')); ?>

                </h1>
                <div class="favorites-count">
                    <?php echo e($favorites->count()); ?> <?php echo e($favorites->count() == 1 ? __t('messages.item') : __t('messages.items')); ?>

                </div>
            </div>

            <div class="product-grid">
                <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('product.detail', $product)); ?>" class="product-card-link">
                    <div class="product-card">
                        <div class="product-image">
                            <button class="wishlist-btn active" 
                                    data-product-id="<?php echo e($product->id); ?>"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleFavorite(<?php echo e($product->id); ?>, this);">
                                <i class="fas fa-heart"></i>
                            </button>
                            <?php if($product->is_new): ?>
                            <div class="product-badge">NEW</div>
                            <?php elseif($product->sale_price && $product->sale_price < $product->price): ?>
                            <div class="product-badge">SALE</div>
                            <?php elseif($product->is_featured): ?>
                            <div class="product-badge">HOT</div>
                            <?php endif; ?>
                            <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>" loading="lazy" decoding="async">
                        </div>
                        <div class="product-info">
                            <div class="product-title"><?php echo e($product->name); ?></div>
                            <div class="product-description"><?php echo e(Str::limit($product->short_description, 60)); ?></div>
                            <div class="product-footer">
                                <div class="product-price">
                                    <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                        <span class="original-price">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                        <span class="current-price">₪ <?php echo e(number_format($product->sale_price, 0)); ?></span>
                                    <?php else: ?>
                                        <span class="current-price">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if($product->stock_status === 'out_of_stock'): ?>
                                <button class="add-to-cart-icon out-of-stock"
                                        data-product-id="<?php echo e($product->id); ?>"
                                        data-product-name="<?php echo e($product->name); ?>"
                                        title="<?php echo e(__t('messages.request_product')); ?>"
                                        aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                        onclick="event.preventDefault(); event.stopPropagation(); requestProduct(<?php echo e($product->id); ?>, '<?php echo e($product->name); ?>');">
                                    <i class="fas fa-bell"></i>
                                </button>
                                <?php else: ?>
                                <button class="add-to-cart-icon <?php echo e(in_array($product->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                        data-product-id="<?php echo e($product->id); ?>"
                                        title="<?php echo e(in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                        aria-label="<?php echo e(in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                        onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this);">
                                    <i class="fas <?php echo e(in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="empty-favorites">
                <i class="far fa-heart"></i>
                <h2><?php echo e(__t('messages.no_favorites')); ?></h2>
                <p><?php echo e(__t('messages.no_favorites_description')); ?></p>
                <a href="<?php echo e(route('products')); ?>" class="btn-primary"><?php echo e(__t('messages.start_shopping')); ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Scroll Animation - Bottom to Top
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all product cards
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.classList.add('scroll-animate');
        observer.observe(card);
    });
});

// Override toggleFavorite for favorites page to handle card removal
(function() {
    // Store reference to the original global toggleFavorite function
    const originalToggleFavorite = window.toggleFavorite;

    // Override with favorites page specific behavior
    window.toggleFavorite = function(productId, button) {
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            button.dataset.processing = 'false';

            if (data.success) {
                // Update favorites count in header using global function
                if (typeof updateFavoritesCount === 'function') {
                    updateFavoritesCount();
                }

                // If removed, remove the card from the page after a short delay
                if (data.action === 'removed') {
                    setTimeout(() => {
                        const card = button.closest('.product-card');
                        if (card) {
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.8)';

                            setTimeout(() => {
                                card.remove();

                                // Check if there are no more favorites and reload the page
                                const remainingCards = document.querySelectorAll('.product-card');
                                if (remainingCards.length === 0) {
                                    location.reload();
                                } else {
                                    // Update the count display
                                    const countElement = document.querySelector('.favorites-count');
                                    if (countElement) {
                                        const newCount = remainingCards.length;
                                        const itemText = newCount === 1 ? '<?php echo e(__t("messages.item")); ?>' : '<?php echo e(__t("messages.items")); ?>';
                                        countElement.textContent = `${newCount} ${itemText}`;
                                    }
                                }
                            }, 300);
                        }
                    }, 200);
                }

                // Show notification using global function
                if (typeof showNotification === 'function') {
                    showNotification(data.message);
                }
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
    };
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/favorites.blade.php ENDPATH**/ ?>