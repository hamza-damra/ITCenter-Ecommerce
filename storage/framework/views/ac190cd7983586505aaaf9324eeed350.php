
<div class="product-price">
    <span class="current-price">$<?php echo e(number_format($product->final_price, 2)); ?></span>
    
    <?php if($product->is_on_sale): ?>
        <span class="original-price">$<?php echo e(number_format($product->price, 2)); ?></span>
        <span class="discount-badge">-<?php echo e($product->discount_percentage); ?>%</span>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\price.blade.php ENDPATH**/ ?>