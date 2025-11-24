
<div class="quantity-section">
    <label class="quantity-label"><?php echo e(__('Quantity:')); ?></label>
    <div class="quantity-selector">
        <div class="quantity-controls">
            <button 
                class="quantity-btn" 
                onclick="decreaseQuantity()" 
                <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>

                aria-label="<?php echo e(__('Decrease quantity')); ?>"
            >
                -
            </button>
            <input 
                type="number" 
                class="quantity-input" 
                value="1" 
                min="1" 
                max="<?php echo e($product->track_stock ? $product->stock_quantity : 999); ?>" 
                id="quantity" 
                <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>

                aria-label="<?php echo e(__('Product quantity')); ?>"
            >
            <button 
                class="quantity-btn" 
                onclick="increaseQuantity()" 
                <?php echo e($product->stock_status === 'out_of_stock' ? 'disabled' : ''); ?>

                aria-label="<?php echo e(__('Increase quantity')); ?>"
            >
                +
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\quantity-selector.blade.php ENDPATH**/ ?>