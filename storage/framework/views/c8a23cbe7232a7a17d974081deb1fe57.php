
<div class="related-products">
    <div class="container">
        <h2 class="related-title"><?php echo e(__('Related Products')); ?></h2>
        <div class="products-grid">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('product.detail', $relatedProduct->slug)); ?>" style="text-decoration: none; color: inherit;">
                    <div class="product-card">
                        <div class="product-card-image">
                            <?php
                                $relatedImageUrl = $relatedProduct->main_image 
                                    ? (filter_var($relatedProduct->main_image, FILTER_VALIDATE_URL) 
                                        ? $relatedProduct->main_image 
                                        : asset('storage/' . $relatedProduct->main_image))
                                    : 'https://via.placeholder.com/300x200/f5f5f5/666666?text=' . urlencode($relatedProduct->name);
                            ?>
                            <img 
                                src="<?php echo e($relatedImageUrl); ?>" 
                                alt="<?php echo e($relatedProduct->name); ?>" 
                                onerror="this.src='https://via.placeholder.com/300x200/f5f5f5/666666?text=No+Image'"
                            >
                        </div>
                        <div class="product-card-content">
                            <h3 class="product-card-title"><?php echo e($relatedProduct->name); ?></h3>
                            <div class="product-card-price">$<?php echo e(number_format($relatedProduct->final_price, 2)); ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\related-products.blade.php ENDPATH**/ ?>