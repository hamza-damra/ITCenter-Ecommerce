
<?php if(isset($promotionalAds['left'])): ?>
    <section class="home-section gift-ideas-section strong-offers-section" dir="<?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>">
        <div class="container">
            <div class="gift-ideas-grid">
                
                <div class="gift-ideas-item gift-banner-item strong-offers-banner">
                    <?php if($promotionalAds['left']->link): ?>
                        <a href="<?php echo e($promotionalAds['left']->link); ?>"
                            class="product-item-section gift-idea-banner promotional-ad-link promotional-ad-overlay"
                            style="background-image: url('<?php echo e($promotionalAds['left']->image_url); ?>'); cursor: pointer; display: block; text-decoration: none;">
                            <?php if($promotionalAds['left']->hasTitle() || $promotionalAds['left']->hasSubtitle() || $promotionalAds['left']->hasButton()): ?>
                                <div class="promotional-ad-content">
                                    <?php if($promotionalAds['left']->hasTitle()): ?>
                                        <h3 class="promotional-ad-title" style="color: <?php echo e($promotionalAds['left']->title_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['left']->title_font_size ?? '32px'); ?>;"><?php echo e($promotionalAds['left']->title); ?></h3>
                                    <?php endif; ?>
                                    <?php if($promotionalAds['left']->hasSubtitle()): ?>
                                        <p class="promotional-ad-subtitle" style="color: <?php echo e($promotionalAds['left']->subtitle_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['left']->subtitle_font_size ?? '16px'); ?>;"><?php echo e($promotionalAds['left']->subtitle); ?></p>
                                    <?php endif; ?>
                                    <?php if($promotionalAds['left']->hasButton()): ?>
                                        <span class="promotional-ad-button" style="background-color: <?php echo e($promotionalAds['left']->button_bg_color ?? '#2563eb'); ?>; color: <?php echo e($promotionalAds['left']->button_text_color ?? '#FFFFFF'); ?>;"><?php echo e($promotionalAds['left']->button_text); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    <?php else: ?>
                        <div class="product-item-section gift-idea-banner promotional-ad-overlay"
                            style="background-image: url('<?php echo e($promotionalAds['left']->image_url); ?>');">
                            <?php if($promotionalAds['left']->hasTitle() || $promotionalAds['left']->hasSubtitle() || $promotionalAds['left']->hasButton()): ?>
                                <div class="promotional-ad-content">
                                    <?php if($promotionalAds['left']->hasTitle()): ?>
                                        <h3 class="promotional-ad-title" style="color: <?php echo e($promotionalAds['left']->title_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['left']->title_font_size ?? '32px'); ?>;"><?php echo e($promotionalAds['left']->title); ?></h3>
                                    <?php endif; ?>
                                    <?php if($promotionalAds['left']->hasSubtitle()): ?>
                                        <p class="promotional-ad-subtitle" style="color: <?php echo e($promotionalAds['left']->subtitle_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['left']->subtitle_font_size ?? '16px'); ?>;"><?php echo e($promotionalAds['left']->subtitle); ?></p>
                                    <?php endif; ?>
                                    <?php if($promotionalAds['left']->hasButton()): ?>
                                        <span class="promotional-ad-button" style="background-color: <?php echo e($promotionalAds['left']->button_bg_color ?? '#2563eb'); ?>; color: <?php echo e($promotionalAds['left']->button_text_color ?? '#FFFFFF'); ?>;"><?php echo e($promotionalAds['left']->button_text); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                
                <?php if(isset($featuredProducts[6])): ?>
                    <div class="gift-ideas-item gift-product-item strong-offers-product">
                        <a href="<?php echo e(route('product.detail', $featuredProducts[6])); ?>" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    <?php if($featuredProducts[6]->is_new): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                                    <?php elseif($featuredProducts[6]->is_featured): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                                    <?php endif; ?>
                                    <div class="wishlist-btn" data-product-id="<?php echo e($featuredProducts[6]->id); ?>"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="<?php echo e($featuredProducts[6]->main_image); ?>" alt="<?php echo e($featuredProducts[6]->name); ?>"
                                        loading="lazy">
                                </div>
                                <div class="product-info">
                                    <div class="product-title"><?php echo e($featuredProducts[6]->name); ?></div>
                                    <div class="product-description"><?php echo e(Str::limit($featuredProducts[6]->short_description, 60)); ?>

                                    </div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            <?php if($featuredProducts[6]->sale_price && $featuredProducts[6]->sale_price < $featuredProducts[6]->price): ?>
                                                <span class="original-price">₪
                                                    <?php echo e(number_format($featuredProducts[6]->price, 0)); ?></span>
                                                <span class="current-price">₪
                                                    <?php echo e(number_format($featuredProducts[6]->sale_price, 0)); ?></span>
                                            <?php else: ?>
                                                <span class="current-price">₪ <?php echo e(number_format($featuredProducts[6]->price, 0)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($featuredProducts[6]->stock_status === 'out_of_stock'): ?>
                                            <button class="add-to-cart-icon out-of-stock"
                                                data-product-id="<?php echo e($featuredProducts[6]->id); ?>"
                                                data-product-name="<?php echo e($featuredProducts[6]->name); ?>"
                                                title="<?php echo e(__t('messages.request_product')); ?>"
                                                aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct(<?php echo e($featuredProducts[6]->id); ?>, '<?php echo e($featuredProducts[6]->name); ?>');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        <?php else: ?>
                                            <button
                                                class="add-to-cart-icon <?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                                data-product-id="<?php echo e($featuredProducts[6]->id); ?>"
                                                title="<?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                aria-label="<?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?php echo e($featuredProducts[6]->id); ?>, this);">
                                                <i
                                                    class="fas <?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                
                <?php if(isset($featuredProducts[7])): ?>
                    <div class="gift-ideas-item gift-product-item strong-offers-product">
                        <a href="<?php echo e(route('product.detail', $featuredProducts[7])); ?>" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    <?php if($featuredProducts[7]->is_new): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                                    <?php elseif($featuredProducts[7]->is_featured): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                                    <?php endif; ?>
                                    <div class="wishlist-btn" data-product-id="<?php echo e($featuredProducts[7]->id); ?>"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="<?php echo e($featuredProducts[7]->main_image); ?>" alt="<?php echo e($featuredProducts[7]->name); ?>"
                                        loading="lazy">
                                </div>
                                <div class="product-info">
                                    <div class="product-title"><?php echo e($featuredProducts[7]->name); ?></div>
                                    <div class="product-description"><?php echo e(Str::limit($featuredProducts[7]->short_description, 60)); ?>

                                    </div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            <?php if($featuredProducts[7]->sale_price && $featuredProducts[7]->sale_price < $featuredProducts[7]->price): ?>
                                                <span class="original-price">₪
                                                    <?php echo e(number_format($featuredProducts[7]->price, 0)); ?></span>
                                                <span class="current-price">₪
                                                    <?php echo e(number_format($featuredProducts[7]->sale_price, 0)); ?></span>
                                            <?php else: ?>
                                                <span class="current-price">₪ <?php echo e(number_format($featuredProducts[7]->price, 0)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($featuredProducts[7]->stock_status === 'out_of_stock'): ?>
                                            <button class="add-to-cart-icon out-of-stock"
                                                data-product-id="<?php echo e($featuredProducts[7]->id); ?>"
                                                data-product-name="<?php echo e($featuredProducts[7]->name); ?>"
                                                title="<?php echo e(__t('messages.request_product')); ?>"
                                                aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct(<?php echo e($featuredProducts[7]->id); ?>, '<?php echo e($featuredProducts[7]->name); ?>');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        <?php else: ?>
                                            <button
                                                class="add-to-cart-icon <?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                                data-product-id="<?php echo e($featuredProducts[7]->id); ?>"
                                                title="<?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                aria-label="<?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?php echo e($featuredProducts[7]->id); ?>, this);">
                                                <i
                                                    class="fas <?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/home/sections/special-offers-banner.blade.php ENDPATH**/ ?>