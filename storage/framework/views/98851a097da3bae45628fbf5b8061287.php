
<?php if(isset($banners) && $banners->count() > 0): ?>
    <div class="hero-section">
        <div class="hero-slider">
            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($banner->link): ?>
                    <a href="<?php echo e($banner->link); ?>" class="hero-slide <?php echo e($index === 0 ? 'active' : ''); ?>"
                        style="background-image: url('<?php echo e($banner->image_url); ?>');">
                <?php else: ?>
                        <div class="hero-slide <?php echo e($index === 0 ? 'active' : ''); ?>"
                            style="background-image: url('<?php echo e($banner->image_url); ?>');">
                    <?php endif; ?>
                        <div class="hero-slide-content">
                            <?php if($banner->title): ?>
                                <h1 <?php if($banner->title_color): ?> style="background: none; -webkit-background-clip: unset; background-clip: unset; -webkit-text-fill-color: <?php echo e($banner->title_color); ?>; color: <?php echo e($banner->title_color); ?>;" <?php endif; ?>><?php echo e($banner->title); ?></h1>
                            <?php endif; ?>
                            <?php if($banner->subtitle): ?>
                                <p <?php if($banner->subtitle_color): ?> style="color: <?php echo e($banner->subtitle_color); ?>;" <?php endif; ?>><?php echo e($banner->subtitle); ?></p>
                            <?php endif; ?>
                            <?php if($banner->button_text): ?>
                                <div class="hero-cta-buttons">
                                    <?php
                                        $buttonStyle = '';
                                        if($banner->button_bg_color) {
                                            $buttonStyle .= "background: {$banner->button_bg_color}; ";
                                        }
                                        if($banner->button_text_color) {
                                            $buttonStyle .= "color: {$banner->button_text_color}; ";
                                        }
                                    ?>
                                    <?php if($banner->link): ?>
                                        
                                        <span class="hero-cta-btn primary" <?php if($buttonStyle): ?> style="<?php echo e($buttonStyle); ?>" <?php endif; ?>>
                                            <i class="fas fa-shopping-bag"></i>
                                            <?php echo e($banner->button_text); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="hero-cta-btn primary" style="cursor: default; <?php echo e($buttonStyle); ?>">
                                            <i class="fas fa-shopping-bag"></i>
                                            <?php echo e($banner->button_text); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if($banner->link): ?>
                            </a>
                        <?php else: ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Navigation Arrows -->
        <?php if($banners->count() > 1): ?>
            <div class="slider-arrow prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="slider-arrow next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </div>

            <!-- Navigation Dots -->
            <div class="slider-dots">
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="slider-dot <?php echo e($index === 0 ? 'active' : ''); ?>" onclick="goToSlide(<?php echo e($index); ?>)"></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <!-- Progress Bar -->
        <div class="slider-progress">
            <div class="slider-progress-bar" id="sliderProgressBar"></div>
        </div>
    </div>
    </div>
<?php else: ?>
    <!-- Fallback: Static Hero Section when no banners exist -->
    <div class="hero-section">
        <div class="hero-slider">
            <div class="hero-slide active" style="background-image: url('<?php echo e(asset('images/assets/Banner.jpg')); ?>');">
                <div class="hero-slide-content">
                    <h1><?php echo e(is_rtl() ? 'أحدث التقنيات' : 'Latest Technology'); ?></h1>
                    <p><?php echo e(is_rtl() ? 'اكتشف أفضل الأجهزة الإلكترونية والإكسسوارات بأسعار لا تقبل المنافسة' : 'Discover the best electronics and accessories at unbeatable prices'); ?>

                    </p>
                    <div class="hero-cta-buttons">
                        <a href="<?php echo e(route('products')); ?>" class="hero-cta-btn primary">
                            <i class="fas fa-shopping-bag"></i>
                            <?php echo e(is_rtl() ? 'تسوق الآن' : 'Shop Now'); ?>

                        </a>
                        <a href="<?php echo e(route('products', ['filter' => 'sale'])); ?>" class="hero-cta-btn secondary">
                            <i class="fas fa-tags"></i>
                            <?php echo e(is_rtl() ? 'العروض الخاصة' : 'Special Offers'); ?>

                        </a>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="slider-progress">
                <div class="slider-progress-bar" id="sliderProgressBar"></div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/home/sections/hero-banner.blade.php ENDPATH**/ ?>