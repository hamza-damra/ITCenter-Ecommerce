
<div class="specifications-section">
    <h2 class="section-title"><?php echo e(__('Technical Specifications')); ?></h2>
    <div class="specs-grid">
        <?php if($product->specifications && is_array($product->specifications)): ?>
            
            <?php $__currentLoopData = $product->specifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="spec-item">
                    <span class="spec-label"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>:</span>
                    <span class="spec-value"><?php echo e($value); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            
            <div class="spec-item">
                <span class="spec-label"><?php echo e(__('SKU')); ?>:</span>
                <span class="spec-value"><?php echo e($product->sku); ?></span>
            </div>
            
            <?php if($product->weight): ?>
                <div class="spec-item">
                    <span class="spec-label"><?php echo e(__('Weight')); ?>:</span>
                    <span class="spec-value"><?php echo e($product->weight); ?> <?php echo e(__('kg')); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($product->warranty): ?>
                <div class="spec-item">
                    <span class="spec-label"><?php echo e(__('Warranty')); ?>:</span>
                    <span class="spec-value"><?php echo e($product->warranty); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($product->length && $product->width && $product->height): ?>
                <div class="spec-item">
                    <span class="spec-label"><?php echo e(__('Dimensions')); ?>:</span>
                    <span class="spec-value">
                        <?php echo e($product->length); ?> x <?php echo e($product->width); ?> x <?php echo e($product->height); ?> <?php echo e(__('cm')); ?>

                    </span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\specifications.blade.php ENDPATH**/ ?>