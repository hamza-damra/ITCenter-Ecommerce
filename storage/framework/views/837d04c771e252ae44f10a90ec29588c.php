
<div class="action-buttons">
    <button 
        class="btn-add-cart" 
        <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>

        aria-label="<?php echo e(__('Add to cart')); ?>"
    >
        <i class="fas fa-shopping-cart"></i>
        <?php echo e($product->stock_status === 'out_of_stock' ? __('Out of Stock') : __('Add to Cart')); ?>

    </button>
    
    <button 
        class="btn-buy-now" 
        <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>

        aria-label="<?php echo e(__('Buy now')); ?>"
    >
        <?php echo e($product->stock_status === 'out_of_stock' ? __('Unavailable') : __('Buy Now')); ?>

    </button>
    
    <button 
        class="btn-wishlist"
        aria-label="<?php echo e(__('Add to wishlist')); ?>"
    >
        <i class="far fa-heart"></i>
    </button>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\action-buttons.blade.php ENDPATH**/ ?>