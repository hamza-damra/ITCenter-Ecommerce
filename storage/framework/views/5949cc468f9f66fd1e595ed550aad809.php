
<?php if(isset($featuredProducts) && $featuredProducts->count() > 0): ?>
    <div class="featured-section">
        <div class="container">
            <div class="product-grid" id="featuredProducts">
                <?php if(isset($specialOfferProducts) && $specialOfferProducts->count() > 0): ?>
                    <div class="promo-featured-card special-offer-swapper" id="specialOfferSwapper">
                        <div class="special-offer-header"><?php echo e(is_rtl() ? 'عرض خاص' : 'Special Offer'); ?></div>

                        
                        <div class="special-offer-slides">
                            <?php $__currentLoopData = $specialOfferProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $offerProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="special-offer-slide <?php echo e($index === 0 ? 'active' : ''); ?>" data-index="<?php echo e($index); ?>">
                                    <?php if($offerProduct->sale_price && $offerProduct->sale_price < $offerProduct->price): ?>
                                        <div class="badge-save">
                                            <span class="save-label"><?php echo e(is_rtl() ? 'وفر' : 'Save'); ?></span>
                                            <span
                                                class="save-amount">₪<?php echo e(number_format($offerProduct->price - $offerProduct->sale_price, 0)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="promo-media">
                                        <img src="<?php echo e($offerProduct->main_image); ?>" 
                                             alt="<?php echo e($offerProduct->name); ?>"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='<?php echo e(\App\Helpers\ImageHelper::assetUrl('images/products/default.png')); ?>';">
                                    </div>
                                    <div class="promo-body">
                                        <div class="promo-product-name"><?php echo e($offerProduct->name); ?></div>
                                        <div class="promo-prices">
                                            <?php if($offerProduct->sale_price && $offerProduct->sale_price < $offerProduct->price): ?>
                                                <span class="orig">₪<?php echo e(number_format($offerProduct->price, 0)); ?></span>
                                                <span class="sale">₪<?php echo e(number_format($offerProduct->sale_price, 0)); ?></span>
                                            <?php else: ?>
                                                <span class="sale">₪<?php echo e(number_format($offerProduct->price, 0)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="promo-cta">
                                            <a
                                                href="<?php echo e(route('product.detail', $offerProduct)); ?>"><?php echo e(is_rtl() ? 'تسوق الآن' : 'Shop Now'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        
                        <?php if($specialOfferProducts->count() > 1): ?>
                            <div class="special-offer-dots">
                                <?php $__currentLoopData = $specialOfferProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $offerProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button class="special-offer-dot <?php echo e($index === 0 ? 'active' : ''); ?>" data-index="<?php echo e($index); ?>"
                                        aria-label="Go to product <?php echo e($index + 1); ?>"></button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif(isset($promotionalOffers) && $promotionalOffers->count() > 0): ?>
                    <?php $promo = $promotionalOffers->first(); ?>
                    <div class="promo-featured-card">
                        <div class="special-offer-header"><?php echo e(is_rtl() ? 'عرض خاص' : 'Special Offer'); ?></div>
                        <div class="badge-save">
                            <span class="save-label"><?php echo e(is_rtl() ? 'وفر' : 'Save'); ?></span>
                            <span
                                class="save-amount">₪<?php echo e(number_format($promo->original_price - $promo->sale_price, 0)); ?></span>
                        </div>
                        <div class="promo-media">
                            <?php
                                $img = null;
                                if ($promo->product && $promo->product->main_image) {
                                    $path = $promo->product->main_image;
                                    if (str_starts_with($path, 'http')) {
                                        $img = $path;
                                    } elseif (str_starts_with($path, 'storage/') || str_starts_with($path, 'images/')) {
                                        $img = asset($path);
                                    } else {
                                        $img = asset('storage/' . $path);
                                    }
                                }
                            ?>
                            <img src="<?php echo e($img ?? asset('images/placeholder.png')); ?>" alt="<?php echo e($promo->title); ?>">
                        </div>
                        <div class="promo-body">
                            <div class="promo-title"><?php echo e($promo->title); ?></div>
                            <?php if($promo->product): ?>
                                <div class="promo-product-name"><?php echo e($promo->product->name); ?></div>
                            <?php endif; ?>
                            <div class="promo-prices">
                                <span class="orig">₪<?php echo e(number_format($promo->original_price, 0)); ?></span>
                                <span class="sale">₪<?php echo e(number_format($promo->sale_price, 0)); ?></span>
                            </div>
                            <?php if($promo->end_date): ?>
                                <div class="promo-countdown" data-end="<?php echo e(optional($promo->end_date)->format('c')); ?>">
                                    <div class="label"><?php echo e(is_rtl() ? 'العرض ينتهي خلال:' : 'Hurry up! Offer ends in:'); ?></div>
                                    <div class="boxes">
                                        <div class="box"><span class="num cd-hours">00</span><span
                                                class="unit"><?php echo e(is_rtl() ? 'ساعات' : 'HRS'); ?></span></div>
                                        <div class="box"><span class="num cd-mins">00</span><span
                                                class="unit"><?php echo e(is_rtl() ? 'دقائق' : 'MINS'); ?></span></div>
                                        <div class="box"><span class="num cd-secs">00</span><span
                                                class="unit"><?php echo e(is_rtl() ? 'ثواني' : 'SECS'); ?></span></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($promo->product): ?>
                                <div class="promo-cta">
                                    <a href="<?php echo e(route('product.detail', $promo->product)); ?>">
                                        <?php if(is_rtl()): ?>
                                            <?php echo e('اطلب الآن'); ?> <i class="fas fa-shopping-cart"></i>
                                        <?php else: ?>
                                            <i class="fas fa-shopping-cart"></i> <?php echo e('Order Now'); ?>

                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php $__currentLoopData = $featuredProducts->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('product.detail', $product)); ?>" class="product-card-link">
                        <div class="product-card">
                            <div class="product-image">
                                <?php if($product->is_new): ?>
                                    <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                                <?php elseif($product->is_featured): ?>
                                    <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                                <?php endif; ?>
                                <div class="wishlist-btn" data-product-id="<?php echo e($product->id); ?>" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </div>

                                <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>" loading="lazy" decoding="async">
                            </div>
                            <div class="product-info">
                                
                                <div class="product-rating">
                                    <div class="stars">
                                        <?php
                                            $rating = $product->average_rating ?? 4.5;
                                            $fullStars = floor($rating);
                                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                        ?>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $fullStars): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-count">(<?php echo e($product->reviews_count ?? rand(10, 150)); ?>)</span>
                                </div>

                                <div class="product-title"><?php echo e($product->name); ?></div>
                                <div class="product-description"><?php echo e(Str::limit($product->short_description, 60)); ?></div>
                                <div class="product-footer">
                                    <div class="product-price">
                                        <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                            <span class="original-price">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                            <span class="current-price">₪ <?php echo e(number_format($product->sale_price, 0)); ?></span>
                                        <?php else: ?>
                                            <span class="current-price">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($product->stock_status === 'out_of_stock'): ?>
                                        <button class="add-to-cart-icon out-of-stock" data-product-id="<?php echo e($product->id); ?>"
                                            data-product-name="<?php echo e($product->name); ?>" title="<?php echo e(__t('messages.request_product')); ?>"
                                            aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                            onclick="event.preventDefault(); event.stopPropagation(); requestProduct(<?php echo e($product->id); ?>, '<?php echo e($product->name); ?>');">
                                            <i class="fas fa-bell"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="add-to-cart-icon <?php echo e(in_array($product->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                            data-product-id="<?php echo e($product->id); ?>"
                                            title="<?php echo e(in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                            aria-label="<?php echo e(in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                            onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this);">
                                            <i
                                                class="fas <?php echo e(in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    </div>
<?php else: ?>
    <!-- Empty State for Featured Products -->
    <div class="featured-section">
        <div class="container">
            <div class="empty-state-mini">
                <div class="empty-state-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="empty-state-title"><?php echo e(__t('messages.no_products_title')); ?></h3>
                <p class="empty-state-subtitle"><?php echo e(__t('messages.no_products_subtitle')); ?></p>
                <div class="empty-state-actions">
                    <a href="<?php echo e(route('contact')); ?>" class="empty-state-btn secondary">
                        <i class="fas fa-envelope"></i>
                        <?php echo e(__t('messages.contact_support')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/home/sections/featured-products.blade.php ENDPATH**/ ?>