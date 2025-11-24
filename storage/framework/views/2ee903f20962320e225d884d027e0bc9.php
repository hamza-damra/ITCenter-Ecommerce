<?php $__env->startSection('title', __t('messages.order_details') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Import Google Font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Override font for orders page - exclude Font Awesome icons */
    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
        min-height: 100vh;
    }

    .order-details-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Back Button */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        color: #2762f3;
        transform: translateX(<?php echo e(is_rtl() ? '5px' : '-5px'); ?>);
    }

    /* Order Header */
    .order-header {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 40px rgba(39, 98, 243, 0.3);
    }

    .order-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .order-number {
        font-size: 2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .order-status-badge {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .order-meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .order-meta-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .order-meta-value {
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Main Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Order Items Section */
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .order-item {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        border-radius: 15px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .order-item:hover {
        background: #f9fafb;
        border-color: #2762f3;
    }

    .order-item:not(:last-child) {
        margin-bottom: 1rem;
    }

    .item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 15px;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .item-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .item-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .item-name:hover {
        color: #2762f3;
    }

    .item-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        font-size: 0.95rem;
        color: #6b7280;
    }

    .item-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .item-pricing {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }

    .item-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2762f3;
    }

    .item-original-price {
        font-size: 1rem;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .item-subtotal {
        font-size: 0.9rem;
        color: #6b7280;
    }

    /* Order Summary */
    .order-summary {
        position: sticky;
        top: 2rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        font-size: 1rem;
    }

    .summary-row:not(:last-child) {
        border-bottom: 1px solid #e5e7eb;
    }

    .summary-row.total {
        padding-top: 1.5rem;
        border-top: 2px solid #e5e7eb;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
    }

    .summary-label {
        color: #6b7280;
    }

    .summary-value {
        font-weight: 600;
        color: #111827;
    }

    .summary-value.discount {
        color: #10b981;
    }

    .summary-value.total {
        color: #2762f3;
    }

    /* Customer Info Section */
    .info-grid {
        display: grid;
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        gap: 1rem;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
    }

    /* Actions */
    .order-actions {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e5e7eb;
        display: flex;
        gap: 1rem;
    }

    .btn {
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        font-size: 1rem;
        flex: 1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(39, 98, 243, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(39, 98, 243, 0.4);
    }

    .btn-danger {
        background: white;
        color: #ef4444;
        border: 2px solid #ef4444;
    }

    .btn-danger:hover {
        background: #ef4444;
        color: white;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .order-summary {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .order-details-page {
            padding: 1rem;
        }

        .order-header {
            padding: 1.5rem;
        }

        .order-number {
            font-size: 1.5rem;
        }

        .section-card {
            padding: 1.5rem;
        }

        .order-item {
            flex-direction: column;
        }

        .item-pricing {
            align-items: flex-start;
        }

        .order-actions {
            flex-direction: column;
        }

        .order-meta-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="order-details-page">
    <!-- Back Button -->
    <a href="<?php echo e(route('orders.index')); ?>" class="back-button">
        <i class="fas fa-arrow-<?php echo e(is_rtl() ? 'right' : 'left'); ?>"></i>
        <?php if(current_locale() === 'ar'): ?>
            العودة إلى الطلبات
        <?php elseif(current_locale() === 'he'): ?>
            חזרה להזמנות
        <?php else: ?>
            Back to Orders
        <?php endif; ?>
    </a>

    <!-- Order Header -->
    <div class="order-header">
        <div class="order-header-top">
            <div class="order-number">
                <i class="fas fa-receipt"></i>
                <?php echo e($order->order_number); ?>

            </div>
            <div class="order-status-badge">
                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                <?php echo e($order->status_label); ?>

            </div>
        </div>

        <div class="order-meta-grid">
            <div class="order-meta-item">
                <div class="order-meta-label">
                    <?php if(current_locale() === 'ar'): ?>
                        تاريخ الطلب
                    <?php elseif(current_locale() === 'he'): ?>
                        תאריך הזמנה
                    <?php else: ?>
                        Order Date
                    <?php endif; ?>
                </div>
                <div class="order-meta-value">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo e($order->created_at->format('d M Y, h:i A')); ?>

                </div>
            </div>

            <div class="order-meta-item">
                <div class="order-meta-label">
                    <?php if(current_locale() === 'ar'): ?>
                        حالة الدفع
                    <?php elseif(current_locale() === 'he'): ?>
                        סטטוס תשלום
                    <?php else: ?>
                        Payment Status
                    <?php endif; ?>
                </div>
                <div class="order-meta-value">
                    <i class="fas fa-credit-card"></i>
                    <?php echo e($order->payment_status_label); ?>

                </div>
            </div>

            <div class="order-meta-item">
                <div class="order-meta-label">
                    <?php if(current_locale() === 'ar'): ?>
                        طريقة الدفع
                    <?php elseif(current_locale() === 'he'): ?>
                        אמצעי תשלום
                    <?php else: ?>
                        Payment Method
                    <?php endif; ?>
                </div>
                <div class="order-meta-value">
                    <i class="fas fa-money-bill-wave"></i>
                    <?php if(current_locale() === 'ar'): ?>
                        الدفع عند الاستلام
                    <?php elseif(current_locale() === 'he'): ?>
                        תשלום במזומן
                    <?php else: ?>
                        Cash on Delivery
                    <?php endif; ?>
                </div>
            </div>

            <div class="order-meta-item">
                <div class="order-meta-label">
                    <?php if(current_locale() === 'ar'): ?>
                        عدد المنتجات
                    <?php elseif(current_locale() === 'he'): ?>
                        מספר פריטים
                    <?php else: ?>
                        Total Items
                    <?php endif; ?>
                </div>
                <div class="order-meta-value">
                    <i class="fas fa-box"></i>
                    <?php echo e($order->items->sum('quantity')); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Left Column: Order Items & Customer Info -->
        <div>
            <!-- Order Items -->
            <div class="section-card" style="margin-bottom: 2rem;">
                <h2 class="section-title">
                    <i class="fas fa-shopping-bag"></i>
                    <?php if(current_locale() === 'ar'): ?>
                        المنتجات المطلوبة
                    <?php elseif(current_locale() === 'he'): ?>
                        פריטי הזמנה
                    <?php else: ?>
                        Order Items
                    <?php endif; ?>
                </h2>

                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="order-item">
                        <?php
                            // Handle different image path formats
                            $imageSrc = asset('images/placeholder.png'); // default fallback
                            
                            if ($item->product_image) {
                                if (str_starts_with($item->product_image, 'http')) {
                                    // External URL
                                    $imageSrc = $item->product_image;
                                } elseif (str_starts_with($item->product_image, 'images/')) {
                                    // Public images folder
                                    $imageSrc = asset($item->product_image);
                                } else {
                                    // Storage folder
                                    $imageSrc = asset('storage/' . $item->product_image);
                                }
                            }
                        ?>
                        
                        <img src="<?php echo e($imageSrc); ?>" 
                             alt="<?php echo e($item->product_name); ?>" 
                             class="item-image"
                             onerror="this.src='<?php echo e(asset('images/placeholder.png')); ?>'">
                        
                        <div class="item-content">
                            <a href="<?php echo e($item->product_slug ? route('product.detail', $item->product_slug) : '#'); ?>" 
                               class="item-name">
                                <?php echo e($item->product_name); ?>

                            </a>
                            
                            <div class="item-meta">
                                <div class="item-meta-item">
                                    <i class="fas fa-hashtag"></i>
                                    <span><?php echo e($item->quantity); ?> x $<?php echo e(number_format($item->price, 2)); ?></span>
                                </div>
                                <?php if($item->product_sku): ?>
                                    <div class="item-meta-item">
                                        <i class="fas fa-barcode"></i>
                                        <span><?php echo e($item->product_sku); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="item-pricing">
                            <div class="item-price">$<?php echo e(number_format($item->subtotal, 2)); ?></div>
                            <?php if($item->has_discount): ?>
                                <div class="item-original-price">
                                    $<?php echo e(number_format($item->original_price * $item->quantity, 2)); ?>

                                </div>
                            <?php endif; ?>
                            <div class="item-subtotal">
                                <?php if(current_locale() === 'ar'): ?>
                                    المجموع الفرعي
                                <?php elseif(current_locale() === 'he'): ?>
                                    סכום ביניים
                                <?php else: ?>
                                    Subtotal
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Customer Information -->
            <div class="section-card">
                <h2 class="section-title">
                    <i class="fas fa-user"></i>
                    <?php if(current_locale() === 'ar'): ?>
                        معلومات العميل
                    <?php elseif(current_locale() === 'he'): ?>
                        פרטי לקוח
                    <?php else: ?>
                        Customer Information
                    <?php endif; ?>
                </h2>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">
                                <?php if(current_locale() === 'ar'): ?>
                                    الاسم
                                <?php elseif(current_locale() === 'he'): ?>
                                    שם
                                <?php else: ?>
                                    Name
                                <?php endif; ?>
                            </div>
                            <div class="info-value"><?php echo e($order->customer_name); ?></div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">
                                <?php if(current_locale() === 'ar'): ?>
                                    البريد الإلكتروني
                                <?php elseif(current_locale() === 'he'): ?>
                                    אימייל
                                <?php else: ?>
                                    Email
                                <?php endif; ?>
                            </div>
                            <div class="info-value"><?php echo e($order->customer_email); ?></div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">
                                <?php if(current_locale() === 'ar'): ?>
                                    رقم الهاتف
                                <?php elseif(current_locale() === 'he'): ?>
                                    טלפון
                                <?php else: ?>
                                    Phone
                                <?php endif; ?>
                            </div>
                            <div class="info-value"><?php echo e($order->customer_phone); ?></div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">
                                <?php if(current_locale() === 'ar'): ?>
                                    عنوان الشحن
                                <?php elseif(current_locale() === 'he'): ?>
                                    כתובת משלוח
                                <?php else: ?>
                                    Shipping Address
                                <?php endif; ?>
                            </div>
                            <div class="info-value">
                                <?php echo e($order->shipping_address); ?>, <?php echo e($order->shipping_city); ?><br>
                                <?php echo e($order->shipping_country); ?>

                                <?php if($order->shipping_postal_code): ?>
                                    , <?php echo e($order->shipping_postal_code); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if($order->notes): ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">
                                    <?php if(current_locale() === 'ar'): ?>
                                        ملاحظات
                                    <?php elseif(current_locale() === 'he'): ?>
                                        הערות
                                    <?php else: ?>
                                        Notes
                                    <?php endif; ?>
                                </div>
                                <div class="info-value"><?php echo e($order->notes); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Summary -->
        <div>
            <div class="section-card order-summary">
                <h2 class="section-title">
                    <i class="fas fa-calculator"></i>
                    <?php if(current_locale() === 'ar'): ?>
                        ملخص الطلب
                    <?php elseif(current_locale() === 'he'): ?>
                        סיכום הזמנה
                    <?php else: ?>
                        Order Summary
                    <?php endif; ?>
                </h2>

                <div class="summary-row">
                    <span class="summary-label">
                        <?php if(current_locale() === 'ar'): ?>
                            المجموع الفرعي
                        <?php elseif(current_locale() === 'he'): ?>
                            סכום ביניים
                        <?php else: ?>
                            Subtotal
                        <?php endif; ?>
                    </span>
                    <span class="summary-value">$<?php echo e(number_format($order->subtotal, 2)); ?></span>
                </div>

                <?php if($order->tax > 0): ?>
                    <div class="summary-row">
                        <span class="summary-label">
                            <?php if(current_locale() === 'ar'): ?>
                                الضريبة
                            <?php elseif(current_locale() === 'he'): ?>
                                מס
                            <?php else: ?>
                                Tax
                            <?php endif; ?>
                        </span>
                        <span class="summary-value">$<?php echo e(number_format($order->tax, 2)); ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-row">
                    <span class="summary-label">
                        <?php if(current_locale() === 'ar'): ?>
                            الشحن
                        <?php elseif(current_locale() === 'he'): ?>
                            משלוח
                        <?php else: ?>
                            Shipping
                        <?php endif; ?>
                    </span>
                    <span class="summary-value">
                        <?php if($order->shipping_cost > 0): ?>
                            $<?php echo e(number_format($order->shipping_cost, 2)); ?>

                        <?php else: ?>
                            <?php if(current_locale() === 'ar'): ?>
                                مجاني
                            <?php elseif(current_locale() === 'he'): ?>
                                חינם
                            <?php else: ?>
                                Free
                            <?php endif; ?>
                        <?php endif; ?>
                    </span>
                </div>

                <?php if($order->discount > 0): ?>
                    <div class="summary-row">
                        <span class="summary-label">
                            <?php if(current_locale() === 'ar'): ?>
                                الخصم
                            <?php elseif(current_locale() === 'he'): ?>
                                הנחה
                            <?php else: ?>
                                Discount
                            <?php endif; ?>
                        </span>
                        <span class="summary-value discount">-$<?php echo e(number_format($order->discount, 2)); ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-row total">
                    <span class="summary-label">
                        <?php if(current_locale() === 'ar'): ?>
                            المجموع الكلي
                        <?php elseif(current_locale() === 'he'): ?>
                            סה"כ
                        <?php else: ?>
                            Total
                        <?php endif; ?>
                    </span>
                    <span class="summary-value total">$<?php echo e(number_format($order->total, 2)); ?></span>
                </div>

                <!-- Actions -->
                <div class="order-actions">
                    <?php if($order->canBeCancelled()): ?>
                        <form action="<?php echo e(route('orders.cancel', $order->order_number)); ?>" method="POST" style="flex: 1;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('<?php echo e(__t('messages.confirm_cancel_order')); ?>')">
                                <i class="fas fa-times-circle"></i>
                                <?php if(current_locale() === 'ar'): ?>
                                    إلغاء الطلب
                                <?php elseif(current_locale() === 'he'): ?>
                                    בטל הזמנה
                                <?php else: ?>
                                    Cancel Order
                                <?php endif; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(session('order_completed')): ?>
<script>
    // Prevent back button navigation after order completion
    // This ensures users don't accidentally return to checkout/cart with empty cart
    (function() {
        if (window.history && window.history.pushState) {
            // Replace the current history entry to prevent going back to checkout
            window.history.pushState(null, null, window.location.href);
            
            // Listen for back button and redirect to orders list instead
            window.addEventListener('popstate', function(event) {
                window.history.pushState(null, null, window.location.href);
                
                // Optional: Show a message
                if (confirm('<?php echo e(__("messages.return_to_orders_list") ?? "Return to orders list?"); ?>')) {
                    window.location.href = '<?php echo e(route("orders.index")); ?>';
                }
            });
        }
    })();
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\orders\show.blade.php ENDPATH**/ ?>