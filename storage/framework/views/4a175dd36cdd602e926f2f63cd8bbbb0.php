
<div class="product-info">
    
    <div class="product-category">
        <?php echo e($product->category->name ?? __('Uncategorized')); ?>

        <?php if($product->brand): ?>
            / <?php echo e($product->brand->name); ?>

        <?php endif; ?>
    </div>

    
    <h1 class="product-title"><?php echo e($product->name); ?></h1>

    
    <?php echo $__env->make('partials.product-detail.rating', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('partials.product-detail.price', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('partials.product-detail.stock-status', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <p class="product-description">
        <?php echo e($product->short_description ?? $product->description); ?>

    </p>

    
    <?php echo $__env->make('partials.product-detail.features', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('partials.product-detail.quantity-selector', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('partials.product-detail.action-buttons', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\product-info.blade.php ENDPATH**/ ?>