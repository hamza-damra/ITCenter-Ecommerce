


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product', 'showWishlist' => true]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['product', 'showWishlist' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e(route('product.detail', $product->slug)); ?>" class="product-card-link" data-product-id="<?php echo e($product->id); ?>">
    <div class="product-card">
        
        <div class="product-card-image">
            <img src="<?php echo e($product->main_image); ?>" 
                 alt="<?php echo e($product->name); ?>" 
                 loading="lazy"
                 onerror="this.src='<?php echo e(asset('images/products/default.png')); ?>'">

            
            <?php if($product->discount_percentage > 0): ?>
                <div class="product-badge discount-badge">
                    -<?php echo e($product->discount_percentage); ?>%
                </div>
            <?php elseif($product->is_featured): ?>
                <div class="product-badge">
                    <?php echo e(__('messages.featured')); ?>

                </div>
            <?php elseif($product->is_new): ?>
                <div class="product-badge">
                    <?php echo e(__('messages.new')); ?>

                </div>
            <?php endif; ?>

            
            <?php if($showWishlist): ?>
                <button class="wishlist-btn <?php echo e(in_array($product->id, session('wishlist', [])) ? 'active' : ''); ?>" 
                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(<?php echo e($product->id); ?>)">
                    <i class="<?php echo e(in_array($product->id, session('wishlist', [])) ? 'fas' : 'far'); ?> fa-heart"></i>
                </button>
            <?php endif; ?>
        </div>

        
        <div class="product-card-content">
            
            <h3 class="product-card-title"><?php echo e($product->name); ?></h3>

            
            <?php if($product->short_description): ?>
                <p class="product-card-description">
                    <?php echo e(Str::limit($product->short_description, 100)); ?>

                </p>
            <?php endif; ?>

            
            <?php if($product->reviews_count > 0): ?>
                <div class="product-rating">
                    <div class="stars">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo e($i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-count">(<?php echo e($product->reviews_count); ?>)</span>
                </div>
            <?php endif; ?>

            
            <div class="product-card-footer">
                
                <div class="product-price">
                    <?php if($product->discount_price && $product->discount_price < $product->price): ?>
                        <span class="product-price-original">$<?php echo e(number_format($product->price, 2)); ?></span>
                        <span class="current-price">$<?php echo e(number_format($product->discount_price, 2)); ?></span>
                        <?php if($product->discount_percentage > 0): ?>
                            <span class="discount-percentage"><?php echo e($product->discount_percentage); ?>% <?php echo e(__('messages.off')); ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="current-price">$<?php echo e(number_format($product->price, 2)); ?></span>
                    <?php endif; ?>
                </div>

                
                <?php if($product->stock_quantity > 0): ?>
                    <button class="btn btn-primary btn-sm add-to-cart" 
                            onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?php echo e($product->id); ?>)"
                            data-product-id="<?php echo e($product->id); ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <?php echo e(__('messages.add_to_cart')); ?>

                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary btn-sm add-to-cart out-of-stock" disabled 
                            onclick="event.preventDefault(); event.stopPropagation();">
                        <i class="fas fa-bell"></i>
                        <?php echo e(__('messages.notify_me')); ?>

                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</a>


<style>
    .product-card-link {
        display: block;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .product-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-bounce);
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid transparent;
    }

    .product-card-link:hover .product-card {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-blue);
    }

    .product-card-image {
        width: 100%;
        height: 200px;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: var(--space-4);
    }

    .product-card-image img {
        max-width: 85%;
        max-height: 85%;
        object-fit: contain;
        transition: transform var(--transition-normal);
    }

    .product-card-link:hover .product-card-image img {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: var(--space-3);
        <?php echo e(is_rtl() ? 'right' : 'left'); ?>: var(--space-3);
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-gray) 100%);
        color: var(--text-white);
        padding: var(--space-1) var(--space-3);
        border-radius: var(--radius-md);
        font-size: var(--text-xs);
        font-weight: 700;
        z-index: 5;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-badge.discount-badge {
        background: linear-gradient(135deg, var(--secondary-red) 0%, #dc2626 100%);
    }

    .wishlist-btn {
        position: absolute;
        top: var(--space-3);
        <?php echo e(is_rtl() ? 'left' : 'right'); ?>: var(--space-3);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-bounce);
        z-index: 10;
    }

    .wishlist-btn:hover {
        background: var(--text-white);
        transform: scale(1.1);
        box-shadow: var(--shadow-sm);
    }

    .wishlist-btn.active i {
        color: var(--secondary-red);
    }

    .product-card-content {
        padding: var(--space-4);
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-card-title {
        font-size: var(--text-sm);
        font-weight: 600;
        margin-bottom: var(--space-2);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-card-title {
        color: var(--text-primary);
        transition: color var(--transition-normal);
    }

    .product-card-link:hover .product-card-title {
        color: var(--primary-blue);
    }

    .product-card-description {
        font-size: var(--text-xs);
        color: var(--text-secondary);
        margin-bottom: var(--space-3);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        margin-bottom: var(--space-3);
        font-size: var(--text-xs);
    }

    .product-rating .stars {
        display: flex;
        gap: 1px;
    }

    .product-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: var(--space-3);
        border-top: 1px solid #f1f5f9;
        gap: var(--space-3);
    }

    .product-price {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-1);
        flex: 1;
    }

    .product-price-original {
        text-decoration: line-through;
        color: var(--text-muted);
        font-size: var(--text-xs);
        font-weight: 500;
    }

    .current-price {
        color: var(--text-primary);
        font-weight: 700;
        font-size: var(--text-lg);
    }

    .discount-percentage {
        font-size: var(--text-xs);
        color: var(--secondary-green);
        font-weight: 600;
        background: rgba(16, 185, 129, 0.1);
        padding: var(--space-1) var(--space-2);
        border-radius: var(--radius-sm);
    }

    .add-to-cart {
        white-space: nowrap;
        min-width: auto;
    }

    .add-to-cart.out-of-stock {
        background: transparent;
        color: var(--secondary-yellow);
        border-color: var(--secondary-yellow);
    }

    .add-to-cart.out-of-stock:hover {
        background: var(--secondary-yellow);
        color: var(--text-white);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .product-card-footer {
            flex-direction: column;
            gap: var(--space-2);
        }

        .add-to-cart {
            width: 100%;
        }
    }
</style>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/components/product-card.blade.php ENDPATH**/ ?>