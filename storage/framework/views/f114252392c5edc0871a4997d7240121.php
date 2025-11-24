<?php $__env->startSection('title', $brand->name); ?>

<?php $__env->startSection('content'); ?>
    <div class="brand-detail-page">
        
        <header class="brand-header" data-brand-id="<?php echo e($brand->id); ?>">
            <?php if($brand->logo): ?>
                <img src="<?php echo e(asset('storage/' . $brand->logo)); ?>" 
                     alt="<?php echo e($brand->name); ?>" 
                     class="brand-logo">
            <?php endif; ?>
            <div class="brand-info">
                <h1 class="brand-name"><?php echo e($brand->name); ?></h1>
                <?php if($brand->description): ?>
                    <p class="brand-description"><?php echo e($brand->description); ?></p>
                <?php endif; ?>
                <div class="brand-meta">
                    <span class="products-count"><?php echo e($brand->products_count); ?> <?php echo e(__('Products')); ?></span>
                    <?php if($brand->website): ?>
                        <a href="<?php echo e($brand->website); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="brand-website">
                            <?php echo e(__('Visit Website')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        
        <section class="brand-products">
            <div class="products-header">
                <h2><?php echo e(__('Products from')); ?> <?php echo e($brand->name); ?></h2>
                <div class="products-count-text">
                    <?php echo e($products->total()); ?> <?php echo e(__('products found')); ?>

                </div>
            </div>

            <div class="products-grid" data-component="products-grid">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="product-card" data-product-id="<?php echo e($product->id); ?>">
                        <a href="<?php echo e(route('product.detail', $product->slug)); ?>" class="product-link">
                            
                            <div class="product-image-wrapper">
                                <?php if($product->main_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->main_image)); ?>" 
                                         alt="<?php echo e($product->name); ?>" 
                                         class="product-image"
                                         loading="lazy">
                                <?php endif; ?>
                                
                                
                                <div class="product-badges">
                                    <?php if($product->is_new): ?>
                                        <span class="badge badge-new"><?php echo e(__('New')); ?></span>
                                    <?php endif; ?>
                                    <?php if($product->sale_price): ?>
                                        <span class="badge badge-sale"><?php echo e(__('Sale')); ?></span>
                                    <?php endif; ?>
                                    <?php if(!$product->is_in_stock): ?>
                                        <span class="badge badge-out-of-stock"><?php echo e(__('Out of Stock')); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="product-info">
                                <h3 class="product-name"><?php echo e($product->name); ?></h3>
                                
                                <?php if($product->category): ?>
                                    <p class="product-category"><?php echo e($product->category->name); ?></p>
                                <?php endif; ?>

                                <div class="product-pricing">
                                    <?php if($product->sale_price): ?>
                                        <span class="price-sale"><?php echo e(number_format($product->sale_price, 2)); ?> <?php echo e(__('currency')); ?></span>
                                        <span class="price-regular"><?php echo e(number_format($product->price, 2)); ?> <?php echo e(__('currency')); ?></span>
                                    <?php else: ?>
                                        <span class="price"><?php echo e(number_format($product->price, 2)); ?> <?php echo e(__('currency')); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if($product->avg_rating > 0): ?>
                                    <div class="product-rating" data-rating="<?php echo e($product->avg_rating); ?>">
                                        <span class="rating-stars" aria-label="<?php echo e(__('Rating')); ?>: <?php echo e($product->avg_rating); ?>">
                                            <?php echo e(str_repeat('★', floor($product->avg_rating))); ?><?php echo e(str_repeat('☆', 5 - floor($product->avg_rating))); ?>

                                        </span>
                                        <span class="rating-count">(<?php echo e($product->reviews_count); ?>)</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>

                        
                        <div class="product-actions">
                            <button type="button" 
                                    class="btn-add-to-cart" 
                                    data-product-id="<?php echo e($product->id); ?>"
                                    <?php echo e(!$product->is_in_stock ? 'disabled' : ''); ?>>
                                <?php echo e(__('Add to Cart')); ?>

                            </button>
                            <button type="button" 
                                    class="btn-favorite" 
                                    data-product-id="<?php echo e($product->id); ?>"
                                    aria-label="<?php echo e(__('Add to favorites')); ?>">
                                ♡
                            </button>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <p><?php echo e(__('No products found for this brand')); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if($products->hasPages()): ?>
                <nav class="pagination" aria-label="<?php echo e(__('Pagination')); ?>">
                    <?php echo e($products->links()); ?>

                </nav>
            <?php endif; ?>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\brand-products-clean.blade.php ENDPATH**/ ?>