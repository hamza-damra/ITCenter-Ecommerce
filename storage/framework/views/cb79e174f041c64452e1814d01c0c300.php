
<div class="stock-status <?php echo e($product->stock_status === 'out_of_stock' ? 'out-of-stock' : ''); ?>">
    <?php if($product->stock_status === 'in_stock'): ?>
        <i class="fas fa-check-circle"></i>
        <span><?php echo e(__('In Stock - Ready to Ship')); ?></span>
    <?php else: ?>
        <i class="fas fa-times-circle"></i>
        <span><?php echo e(__('Out of Stock')); ?></span>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\stock-status.blade.php ENDPATH**/ ?>