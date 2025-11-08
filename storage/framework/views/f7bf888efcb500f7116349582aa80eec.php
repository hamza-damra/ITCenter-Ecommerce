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
        background: #fff;
        padding: 3rem 0;
        min-height: 60vh;
    }

    .favorites-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #4169E1;
    }

    .favorites-header h1 {
        font-size: 2.5rem;
        color: #333;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .favorites-header h1 i {
        color: #4169E1;
        font-size: 2rem;
    }

    .favorites-count {
        color: #666;
        font-size: 1rem;
        font-weight: 500;
    }

    /* Empty State */
    .empty-favorites {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-favorites i {
        font-size: 5rem;
        color: #e0e0e0;
        margin-bottom: 1.5rem;
    }

    .empty-favorites h2 {
        font-size: 1.5rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .empty-favorites p {
        color: #999;
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
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
    }

    .empty-favorites .btn-primary:hover {
        background: #1E90FF;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(65, 105, 225, 0.4);
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        border: 1px solid rgba(230, 146, 112, 0.08);
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2762f3 0%, #1a4dbf 50%, #333333 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(39, 98, 243, 0.12), 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: rgba(39, 98, 243, 0.2);
    }

    .product-card:hover::before {
        opacity: 1;
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
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
        border: 1px solid rgba(39, 98, 243, 0.1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }

    .wishlist-btn:hover {
        background: #2762f3;
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.3);
    }

    .wishlist-btn.active {
        background: #2762f3;
        color: #fff;
        border-color: #2762f3;
    }

    .wishlist-btn i {
        font-size: 1rem;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        <?php echo e(is_rtl() ? 'left' : 'right'); ?>: 10px;
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.4);
        text-transform: uppercase;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1e293b;
        transition: color 0.3s ease;
    }

    .product-card:hover .product-title {
        color: #2762f3;
    }

    .product-description {
        font-size: 0.85rem;
        color: #64748b;
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
        color: #2762f3;
    }

    .add-to-cart {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: #fff;
        padding: 0.6rem 1rem;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.25);
        position: relative;
        overflow: hidden;
    }

    .add-to-cart::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .add-to-cart:hover::before {
        left: 100%;
    }

    .add-to-cart:hover {
        background: linear-gradient(135deg, #1a4dbf 0%, #133a99 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(39, 98, 243, 0.35);
    }
        min-width: 140px;
        white-space: nowrap;
    }

    .add-to-cart:hover {
        background: #1E90FF;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(65, 105, 225, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .product-footer {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .add-to-cart {
            width: 100%;
            min-width: unset;
        }
        
        .product-price {
            width: 100%;
            text-align: center;
        }
    }
    
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
        
        .add-to-cart {
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
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
                <div class="product-card" onclick="window.location.href='<?php echo e(route('product.detail', $product->slug)); ?>'">
                    <div class="product-image">
                        <button class="wishlist-btn active" 
                                data-product-id="<?php echo e($product->id); ?>"
                                onclick="event.stopPropagation(); toggleFavorite(<?php echo e($product->id); ?>, this);">
                            <i class="fas fa-heart"></i>
                        </button>
                        <?php if($product->is_new): ?>
                        <div class="product-badge">NEW</div>
                        <?php elseif($product->sale_price && $product->sale_price < $product->price): ?>
                        <div class="product-badge">SALE</div>
                        <?php elseif($product->is_featured): ?>
                        <div class="product-badge">HOT</div>
                        <?php endif; ?>
                        <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>">
                    </div>
                    <div class="product-info">
                        <div class="product-title"><?php echo e($product->name); ?></div>
                        <div class="product-description"><?php echo e(Str::limit($product->short_description, 60)); ?></div>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                    <span style="text-decoration: line-through; color: #999; font-size: 0.9rem;">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                    ₪ <?php echo e(number_format($product->sale_price, 0)); ?>

                                <?php else: ?>
                                    ₪ <?php echo e(number_format($product->price, 0)); ?>

                                <?php endif; ?>
                            </div>
                            <button class="add-to-cart" onclick="event.stopPropagation();"><?php echo e(__t('messages.add_to_cart')); ?></button>
                        </div>
                    </div>
                </div>
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

// CSRF Token setup for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

/**
 * Toggle favorite status for a product
 */
function toggleFavorite(productId, button) {
    const icon = button.querySelector('i');
    const isActive = button.classList.contains('active');
    
    // Optimistic UI update
    button.classList.toggle('active');
    icon.classList.toggle('fas');
    icon.classList.toggle('far');
    
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
        if (data.success) {
            // Update favorites count in header
            updateFavoritesCount();
            
            // If removed, remove the card from the page after a short delay
            if (data.action === 'removed') {
                setTimeout(() => {
                    const card = button.closest('.product-card');
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
                }, 200);
            }
        } else {
            // Revert UI if request failed
            button.classList.toggle('active');
            icon.classList.toggle('fas');
            icon.classList.toggle('far');
        }
    })
    .catch(error => {
        console.error('Error toggling favorite:', error);
        // Revert UI on error
        button.classList.toggle('active');
        icon.classList.toggle('fas');
        icon.classList.toggle('far');
    });
}

/**
 * Update the favorites count in the header
 */
function updateFavoritesCount() {
    fetch('/favorites/ids')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.header-icon .fa-heart').parentElement.querySelector('.badge');
            if (badge) {
                badge.textContent = data.favoriteIds.length;
            }
        })
        .catch(error => console.error('Error updating favorites count:', error));
}

// Update favorites count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateFavoritesCount();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/favorites.blade.php ENDPATH**/ ?>