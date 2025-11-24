
<div class="product-images">
    
    <div class="main-image">
        <?php
            $mainImageUrl = $product->main_image 
                ? (filter_var($product->main_image, FILTER_VALIDATE_URL) 
                    ? $product->main_image 
                    : asset('storage/' . $product->main_image))
                : 'https://via.placeholder.com/800x800/f5f5f5/666666?text=' . urlencode($product->name);
        ?>
        <img 
            src="<?php echo e($mainImageUrl); ?>" 
            alt="<?php echo e($product->name); ?>" 
            id="mainImage" 
            onerror="this.src='https://via.placeholder.com/800x800/f5f5f5/666666?text=No+Image'"
        >
    </div>

    
    <div class="thumbnail-images">
        <?php if($product->images->count() > 0): ?>
            <?php $__currentLoopData = $product->images->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="thumbnail <?php echo e($index === 0 ? 'active' : ''); ?>">
                    <?php
                        $thumbnailUrl = $image->image_path 
                            ? (filter_var($image->image_path, FILTER_VALIDATE_URL) 
                                ? $image->image_path 
                                : asset('storage/' . $image->image_path))
                            : 'https://via.placeholder.com/200x200/f5f5f5/666666?text=Image+' . ($index + 1);
                    ?>
                    <img 
                        src="<?php echo e($thumbnailUrl); ?>" 
                        alt="<?php echo e($product->name); ?>" 
                        onclick="changeImage(this)" 
                        onerror="this.src='https://via.placeholder.com/200x200/f5f5f5/666666?text=No+Image'"
                    >
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="thumbnail active">
                <img 
                    src="<?php echo e($mainImageUrl); ?>" 
                    alt="<?php echo e($product->name); ?>" 
                    onclick="changeImage(this)" 
                    onerror="this.src='https://via.placeholder.com/200x200/f5f5f5/666666?text=No+Image'"
                >
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\product-detail\image-gallery.blade.php ENDPATH**/ ?>