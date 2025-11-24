
<div class="product-rating">
    <div class="stars">
        <?php for($i = 1; $i <= 5; $i++): ?>
            <?php if($i <= floor($product->avg_rating)): ?>
                <i class="fas fa-star"></i>
            <?php elseif($i - $product->avg_rating < 1): ?>
                <i class="fas fa-star-half-alt"></i>
            <?php else: ?>
                <i class="far fa-star"></i>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <span class="rating-text">
        <?php echo e(number_format($product->avg_rating, 1)); ?> 
        (<?php echo e($product->reviews_count); ?> <?php echo e(__('reviews')); ?>)
    </span>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\rating.blade.php ENDPATH**/ ?>