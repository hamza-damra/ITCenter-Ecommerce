
<?php if(isset($promotionalAds['right'])): ?>
    <section class="home-section gift-ideas-section" dir="<?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>">
        <div class="container">
            <div class="gift-ideas-grid">
                
                <?php if(is_rtl()): ?>
                    
                    <div class="gift-ideas-item gift-banner-item">
                        <?php if($promotionalAds['right']->link): ?>
                            <a href="<?php echo e($promotionalAds['right']->link); ?>"
                                class="product-item-section gift-idea-banner promotional-ad-link promotional-ad-overlay"
                                style="background-image: url('<?php echo e($promotionalAds['right']->image_url); ?>'); cursor: pointer; display: block; text-decoration: none;">
                                <?php if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton()): ?>
                                    <div class="promotional-ad-content">
                                        <?php if($promotionalAds['right']->hasTitle()): ?>
                                            <h3 class="promotional-ad-title" style="color: <?php echo e($promotionalAds['right']->title_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->title_font_size ?? '32px'); ?>;"><?php echo e($promotionalAds['right']->title); ?></h3>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasSubtitle()): ?>
                                            <p class="promotional-ad-subtitle" style="color: <?php echo e($promotionalAds['right']->subtitle_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->subtitle_font_size ?? '16px'); ?>;"><?php echo e($promotionalAds['right']->subtitle); ?></p>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasButton()): ?>
                                            <span class="promotional-ad-button" style="background-color: <?php echo e($promotionalAds['right']->button_bg_color ?? '#2563eb'); ?>; color: <?php echo e($promotionalAds['right']->button_text_color ?? '#FFFFFF'); ?>;"><?php echo e($promotionalAds['right']->button_text); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <div class="product-item-section gift-idea-banner promotional-ad-overlay"
                                style="background-image: url('<?php echo e($promotionalAds['right']->image_url); ?>');">
                                <?php if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton()): ?>
                                    <div class="promotional-ad-content">
                                        <?php if($promotionalAds['right']->hasTitle()): ?>
                                            <h3 class="promotional-ad-title" style="color: <?php echo e($promotionalAds['right']->title_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->title_font_size ?? '32px'); ?>;"><?php echo e($promotionalAds['right']->title); ?></h3>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasSubtitle()): ?>
                                            <p class="promotional-ad-subtitle" style="color: <?php echo e($promotionalAds['right']->subtitle_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->subtitle_font_size ?? '16px'); ?>;"><?php echo e($promotionalAds['right']->subtitle); ?></p>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasButton()): ?>
                                            <span class="promotional-ad-button" style="background-color: <?php echo e($promotionalAds['right']->button_bg_color ?? '#2563eb'); ?>; color: <?php echo e($promotionalAds['right']->button_text_color ?? '#FFFFFF'); ?>;"><?php echo e($promotionalAds['right']->button_text); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                
                <?php if(isset($giftIdeas[0])): ?>
                    <div class="gift-ideas-item gift-product-item">
                        <a href="<?php echo e(route('product.detail', $giftIdeas[0])); ?>" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    <?php if($giftIdeas[0]->is_new): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                                    <?php elseif($giftIdeas[0]->is_featured): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                                    <?php endif; ?>
                                    <div class="wishlist-btn" data-product-id="<?php echo e($giftIdeas[0]->id); ?>"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="<?php echo e($giftIdeas[0]->main_image); ?>" alt="<?php echo e($giftIdeas[0]->name); ?>" loading="lazy">
                                </div>
                                <div class="product-info">
                                    <div class="product-title"><?php echo e($giftIdeas[0]->name); ?></div>
                                    <div class="product-description"><?php echo e(Str::limit($giftIdeas[0]->short_description, 60)); ?></div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            <?php if($giftIdeas[0]->sale_price && $giftIdeas[0]->sale_price < $giftIdeas[0]->price): ?>
                                                <span class="original-price">₪ <?php echo e(number_format($giftIdeas[0]->price, 0)); ?></span>
                                                <span class="current-price">₪ <?php echo e(number_format($giftIdeas[0]->sale_price, 0)); ?></span>
                                            <?php else: ?>
                                                <span class="current-price">₪ <?php echo e(number_format($giftIdeas[0]->price, 0)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($giftIdeas[0]->stock_status === 'out_of_stock'): ?>
                                            <button class="add-to-cart-icon out-of-stock" data-product-id="<?php echo e($giftIdeas[0]->id); ?>"
                                                data-product-name="<?php echo e($giftIdeas[0]->name); ?>"
                                                title="<?php echo e(__t('messages.request_product')); ?>"
                                                aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct(<?php echo e($giftIdeas[0]->id); ?>, '<?php echo e($giftIdeas[0]->name); ?>');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        <?php else: ?>
                                            <button
                                                class="add-to-cart-icon <?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                                data-product-id="<?php echo e($giftIdeas[0]->id); ?>"
                                                title="<?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                aria-label="<?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?php echo e($giftIdeas[0]->id); ?>, this);">
                                                <i
                                                class="fas <?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                
                <?php if(isset($giftIdeas[1])): ?>
                    <div class="gift-ideas-item gift-product-item">
                        <a href="<?php echo e(route('product.detail', $giftIdeas[1])); ?>" class="product-card-link">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    <?php if($giftIdeas[1]->is_new): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                                    <?php elseif($giftIdeas[1]->is_featured): ?>
                                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                                    <?php endif; ?>
                                    <div class="wishlist-btn" data-product-id="<?php echo e($giftIdeas[1]->id); ?>"
                                        onclick="event.preventDefault(); event.stopPropagation();">
                                        <i class="far fa-heart"></i>
                                    </div>
                                    <img src="<?php echo e($giftIdeas[1]->main_image); ?>" alt="<?php echo e($giftIdeas[1]->name); ?>" loading="lazy">
                                </div>
                                <div class="product-info">
                                    <div class="product-title"><?php echo e($giftIdeas[1]->name); ?></div>
                                    <div class="product-description"><?php echo e(Str::limit($giftIdeas[1]->short_description, 60)); ?></div>
                                    <div class="product-footer">
                                        <div class="product-price">
                                            <?php if($giftIdeas[1]->sale_price && $giftIdeas[1]->sale_price < $giftIdeas[1]->price): ?>
                                                <span class="original-price">₪ <?php echo e(number_format($giftIdeas[1]->price, 0)); ?></span>
                                                <span class="current-price">₪ <?php echo e(number_format($giftIdeas[1]->sale_price, 0)); ?></span>
                                            <?php else: ?>
                                                <span class="current-price">₪ <?php echo e(number_format($giftIdeas[1]->price, 0)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($giftIdeas[1]->stock_status === 'out_of_stock'): ?>
                                            <button class="add-to-cart-icon out-of-stock" data-product-id="<?php echo e($giftIdeas[1]->id); ?>"
                                                data-product-name="<?php echo e($giftIdeas[1]->name); ?>"
                                                title="<?php echo e(__t('messages.request_product')); ?>"
                                                aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); requestProduct(<?php echo e($giftIdeas[1]->id); ?>, '<?php echo e($giftIdeas[1]->name); ?>');">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        <?php else: ?>
                                            <button
                                                class="add-to-cart-icon <?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                                data-product-id="<?php echo e($giftIdeas[1]->id); ?>"
                                                title="<?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                aria-label="<?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?php echo e($giftIdeas[1]->id); ?>, this);">
                                                <i
                                                    class="fas <?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                
                <?php if(!is_rtl()): ?>
                    
                    <div class="gift-ideas-item gift-banner-item">
                        <?php if($promotionalAds['right']->link): ?>
                            <a href="<?php echo e($promotionalAds['right']->link); ?>"
                                class="product-item-section gift-idea-banner promotional-ad-link promotional-ad-overlay"
                                style="background-image: url('<?php echo e($promotionalAds['right']->image_url); ?>'); cursor: pointer; display: block; text-decoration: none;">
                                <?php if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton()): ?>
                                    <div class="promotional-ad-content">
                                        <?php if($promotionalAds['right']->hasTitle()): ?>
                                            <h3 class="promotional-ad-title" style="color: <?php echo e($promotionalAds['right']->title_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->title_font_size ?? '32px'); ?>;"><?php echo e($promotionalAds['right']->title); ?></h3>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasSubtitle()): ?>
                                            <p class="promotional-ad-subtitle" style="color: <?php echo e($promotionalAds['right']->subtitle_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->subtitle_font_size ?? '16px'); ?>;"><?php echo e($promotionalAds['right']->subtitle); ?></p>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasButton()): ?>
                                            <span class="promotional-ad-button" style="background-color: <?php echo e($promotionalAds['right']->button_bg_color ?? '#2563eb'); ?>; color: <?php echo e($promotionalAds['right']->button_text_color ?? '#FFFFFF'); ?>;"><?php echo e($promotionalAds['right']->button_text); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <div class="product-item-section gift-idea-banner promotional-ad-overlay"
                                style="background-image: url('<?php echo e($promotionalAds['right']->image_url); ?>');">
                                <?php if($promotionalAds['right']->hasTitle() || $promotionalAds['right']->hasSubtitle() || $promotionalAds['right']->hasButton()): ?>
                                    <div class="promotional-ad-content">
                                        <?php if($promotionalAds['right']->hasTitle()): ?>
                                            <h3 class="promotional-ad-title" style="color: <?php echo e($promotionalAds['right']->title_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->title_font_size ?? '32px'); ?>;"><?php echo e($promotionalAds['right']->title); ?></h3>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasSubtitle()): ?>
                                            <p class="promotional-ad-subtitle" style="color: <?php echo e($promotionalAds['right']->subtitle_color ?? '#FFFFFF'); ?>; font-size: <?php echo e($promotionalAds['right']->subtitle_font_size ?? '16px'); ?>;"><?php echo e($promotionalAds['right']->subtitle); ?></p>
                                        <?php endif; ?>
                                        <?php if($promotionalAds['right']->hasButton()): ?>
                                            <span class="promotional-ad-button" style="background-color: <?php echo e($promotionalAds['right']->button_bg_color ?? '#2563eb'); ?>; color: <?php echo e($promotionalAds['right']->button_text_color ?? '#FFFFFF'); ?>;"><?php echo e($promotionalAds['right']->button_text); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/home/sections/gift-ideas-banner.blade.php ENDPATH**/ ?>