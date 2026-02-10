

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'products',
    'title' => '',
    'viewMoreUrl' => null,
    'autoScroll' => false,
    'autoScrollInterval' => 3000,
    'cardsToScroll' => 1,
    'containerId' => 'scroller-' . uniqid(),
    'cartProductIds' => [],
    'showDiscountPercentage' => false,
    'hideSaleBadge' => false
]));

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

foreach (array_filter(([
    'products',
    'title' => '',
    'viewMoreUrl' => null,
    'autoScroll' => false,
    'autoScrollInterval' => 3000,
    'cardsToScroll' => 1,
    'containerId' => 'scroller-' . uniqid(),
    'cartProductIds' => [],
    'showDiscountPercentage' => false,
    'hideSaleBadge' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="horizontal-scroller-section">
    <?php if($title || $viewMoreUrl): ?>
    <div class="scroller-header">
        <?php if($title): ?>
            <h2 class="scroller-title"><?php echo e($title); ?></h2>
        <?php endif; ?>
        <?php if($viewMoreUrl): ?>
            <a href="<?php echo e($viewMoreUrl); ?>" class="scroller-view-more">
                <?php if(is_rtl()): ?>
                    <i class="fas fa-arrow-left"></i> <?php echo e(__t('messages.view_more')); ?>

                <?php else: ?>
                    <?php echo e(__t('messages.view_more')); ?> <i class="fas fa-arrow-right"></i>
                <?php endif; ?>
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="horizontal-scroller-wrapper" id="<?php echo e($containerId); ?>">
        <!-- Left Arrow (shows on right in RTL) -->
        <button class="scroller-arrow scroller-arrow-left" aria-label="<?php echo e(__t('messages.previous')); ?>">
            <i class="fas fa-chevron-<?php echo e(is_rtl() ? 'right' : 'left'); ?>"></i>
        </button>

        <!-- Scrollable Container -->
        <div class="scroller-container" 
             data-auto-scroll="<?php echo e($autoScroll ? 'true' : 'false'); ?>"
             data-auto-scroll-interval="<?php echo e($autoScrollInterval); ?>"
             data-cards-to-scroll="<?php echo e($cardsToScroll); ?>">
            <div class="scroller-track">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="scroller-card-wrapper">
                    <div class="product-card" onclick="window.location.href='<?php echo e(route('product.detail', $product)); ?>'">
                        <div class="product-image">
                            <?php if($showDiscountPercentage && $product->sale_price && $product->sale_price < $product->price): ?>
                            <?php
                                $discountPercentage = round((($product->price - $product->sale_price) / $product->price) * 100);
                            ?>
                            <div class="product-badge discount-badge">-<?php echo e($discountPercentage); ?>%</div>
                            <?php elseif(!$hideSaleBadge && $product->is_new): ?>
                            <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                            <?php elseif(!$hideSaleBadge && $product->sale_price && $product->sale_price < $product->price): ?>
                            <div class="product-badge"><?php echo e(__t('messages.sale')); ?></div>
                            <?php elseif(!$hideSaleBadge && $product->is_featured): ?>
                            <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                            <?php endif; ?>
                            <div class="wishlist-btn" data-product-id="<?php echo e($product->id); ?>" onclick="event.stopPropagation();">
                                <i class="far fa-heart"></i>
                            </div>
                            <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>" loading="lazy" decoding="async">
                        </div>
                        <div class="product-info">
                            <div class="product-title"><?php echo e($product->name); ?></div>
                            <div class="product-description"><?php echo e(Str::limit($product->short_description, 60)); ?></div>
                            <div class="product-footer">
                                <div class="product-price">
                                    <?php if($showDiscountPercentage && $product->sale_price && $product->sale_price < $product->price): ?>
                                        <span class="original-price">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                        <span class="current-price">₪ <?php echo e(number_format($product->sale_price, 0)); ?></span>
                                    <?php else: ?>
                                        <span class="current-price">₪ <?php echo e(number_format($product->sale_price ?? $product->price, 0)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if($product->stock_status === 'out_of_stock'): ?>
                                <button class="add-to-cart-icon out-of-stock"
                                        data-product-id="<?php echo e($product->id); ?>"
                                        data-product-name="<?php echo e($product->name); ?>"
                                        title="<?php echo e(__t('messages.request_product')); ?>"
                                        aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                        onclick="event.stopPropagation(); requestProduct(<?php echo e($product->id); ?>, '<?php echo e($product->name); ?>');">
                                    <i class="fas fa-bell"></i>
                                </button>
                                <?php else: ?>
                                <button class="add-to-cart-icon <?php echo e(in_array($product->id, $cartProductIds ?? []) ? 'in-cart' : ''); ?>"
                                        data-product-id="<?php echo e($product->id); ?>"
                                        title="<?php echo e(in_array($product->id, $cartProductIds ?? []) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                        aria-label="<?php echo e(in_array($product->id, $cartProductIds ?? []) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                        onclick="event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this);">
                                    <i class="fas <?php echo e(in_array($product->id, $cartProductIds ?? []) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Right Arrow (shows on left in RTL) -->
        <button class="scroller-arrow scroller-arrow-right" aria-label="<?php echo e(__t('messages.next')); ?>">
            <i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i>
        </button>
    </div>

    <!-- Progress Dots (optional) -->
    <div class="scroller-dots"></div>
</div>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/components/horizontal-product-scroller.blade.php ENDPATH**/ ?>