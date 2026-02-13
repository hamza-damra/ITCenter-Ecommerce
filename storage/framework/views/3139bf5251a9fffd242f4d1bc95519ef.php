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
                            <a href="<?php echo e($item->product_id ? route('product.detail', $item->product_id) : '#'); ?>" 
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
                <div class="order-actions" id="orderActionsSection">
                    <?php if($order->canBeCancelled()): ?>
                        <button type="button" class="btn btn-danger" id="openCancelModalBtn" onclick="openCancelModal()">
                            <i class="fas fa-times-circle"></i>
                            <?php if(current_locale() === 'ar'): ?>
                                إلغاء الطلب
                            <?php elseif(current_locale() === 'he'): ?>
                                בטל הזמנה
                            <?php else: ?>
                                Cancel Order
                            <?php endif; ?>
                        </button>
                        <?php if($order->cancellation_window_remaining > 0): ?>
                            <div style="text-align: center; margin-top: 0.75rem; font-size: 0.85rem; color: #6b7280;">
                                <i class="fas fa-clock"></i>
                                <span id="cancellationCountdown" data-minutes="<?php echo e($order->cancellation_window_remaining); ?>"></span>
                            </div>
                        <?php endif; ?>
                    <?php elseif($order->status !== 'cancelled'): ?>
                        <div style="text-align: center; padding: 1rem; background: #fef3cd; border-radius: 12px; color: #856404; font-size: 0.9rem;">
                            <i class="fas fa-info-circle"></i>
                            <?php if(current_locale() === 'ar'): ?>
                                لا يمكن إلغاء هذا الطلب بعد الآن. يرجى التواصل مع الدعم.
                            <?php elseif(current_locale() === 'he'): ?>
                                לא ניתן לבטל הזמנה זו יותר. אנא צור קשר עם התמיכה.
                            <?php else: ?>
                                This order can no longer be cancelled. Please contact support.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($order->canBeCancelled()): ?>
<!-- Cancellation Modal Overlay -->
<div class="cancel-modal-overlay" id="cancelModalOverlay" onclick="closeCancelModal(event)">
    <div class="cancel-modal" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="cancel-modal-header">
            <div class="cancel-modal-title">
                <i class="fas fa-times-circle"></i>
                <?php if(current_locale() === 'ar'): ?>
                    إلغاء الطلب <?php echo e($order->order_number); ?>

                <?php elseif(current_locale() === 'he'): ?>
                    ביטול הזמנה <?php echo e($order->order_number); ?>

                <?php else: ?>
                    Cancel Order <?php echo e($order->order_number); ?>

                <?php endif; ?>
            </div>
            <button class="cancel-modal-close" onclick="closeCancelModal()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="cancel-modal-body">
            <p class="cancel-modal-question">
                <?php if(current_locale() === 'ar'): ?>
                    لماذا تريد إلغاء هذا الطلب؟
                <?php elseif(current_locale() === 'he'): ?>
                    מדוע ברצונך לבטל הזמנה זו?
                <?php else: ?>
                    Why are you cancelling this order?
                <?php endif; ?>
            </p>

            <div class="cancel-reasons" id="cancelReasons">
                <label class="cancel-reason-option">
                    <input type="radio" name="cancel_reason" value="ordered_by_mistake">
                    <span class="cancel-reason-radio"></span>
                    <span class="cancel-reason-text">
                        <?php if(current_locale() === 'ar'): ?>
                            طلب عن طريق الخطأ
                        <?php elseif(current_locale() === 'he'): ?>
                            הוזמן בטעות
                        <?php else: ?>
                            Ordered by mistake
                        <?php endif; ?>
                    </span>
                </label>
                <label class="cancel-reason-option">
                    <input type="radio" name="cancel_reason" value="found_better_price">
                    <span class="cancel-reason-radio"></span>
                    <span class="cancel-reason-text">
                        <?php if(current_locale() === 'ar'): ?>
                            وجدت سعراً أفضل
                        <?php elseif(current_locale() === 'he'): ?>
                            מצאתי מחיר טוב יותר
                        <?php else: ?>
                            Found a better price
                        <?php endif; ?>
                    </span>
                </label>
                <label class="cancel-reason-option">
                    <input type="radio" name="cancel_reason" value="delivery_too_long">
                    <span class="cancel-reason-radio"></span>
                    <span class="cancel-reason-text">
                        <?php if(current_locale() === 'ar'): ?>
                            وقت التوصيل طويل جداً
                        <?php elseif(current_locale() === 'he'): ?>
                            זמן המשלוח ארוך מדי
                        <?php else: ?>
                            Delivery time too long
                        <?php endif; ?>
                    </span>
                </label>
                <label class="cancel-reason-option">
                    <input type="radio" name="cancel_reason" value="payment_issue">
                    <span class="cancel-reason-radio"></span>
                    <span class="cancel-reason-text">
                        <?php if(current_locale() === 'ar'): ?>
                            مشكلة في الدفع
                        <?php elseif(current_locale() === 'he'): ?>
                            בעיית תשלום
                        <?php else: ?>
                            Payment issue
                        <?php endif; ?>
                    </span>
                </label>
                <label class="cancel-reason-option">
                    <input type="radio" name="cancel_reason" value="other">
                    <span class="cancel-reason-radio"></span>
                    <span class="cancel-reason-text">
                        <?php if(current_locale() === 'ar'): ?>
                            سبب آخر
                        <?php elseif(current_locale() === 'he'): ?>
                            סיבה אחרת
                        <?php else: ?>
                            Other
                        <?php endif; ?>
                    </span>
                </label>
            </div>

            <!-- Other reason textarea (hidden by default) -->
            <div class="cancel-note-wrapper" id="cancelNoteWrapper" style="display: none;">
                <textarea id="cancelNote" class="cancel-note-textarea" rows="3"
                    placeholder="<?php if(current_locale() === 'ar'): ?>يرجى توضيح السبب...<?php elseif(current_locale() === 'he'): ?>אנא פרט את הסיבה...@elsePlease specify your reason...<?php endif; ?>"></textarea>
            </div>

            <!-- Refund Info -->
            <div class="cancel-refund-info">
                <i class="fas fa-info-circle"></i>
                <?php if($order->payment_method === 'cash_on_delivery'): ?>
                    <?php if(current_locale() === 'ar'): ?>
                        لم يتم خصم أي مبلغ من حسابك.
                    <?php elseif(current_locale() === 'he'): ?>
                        לא חויבת בתשלום.
                    <?php else: ?>
                        No payment has been charged.
                    <?php endif; ?>
                <?php else: ?>
                    <?php if(current_locale() === 'ar'): ?>
                        سيتم إرجاع المبلغ إلى وسيلة الدفع الأصلية خلال 3-5 أيام عمل.
                    <?php elseif(current_locale() === 'he'): ?>
                        ההחזר יועבר לאמצעי התשלום המקורי שלך תוך 3-5 ימי עסקים.
                    <?php else: ?>
                        Your refund will be processed to your original payment method within 3–5 business days.
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Validation Error -->
            <div class="cancel-error" id="cancelError" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <span id="cancelErrorText"></span>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="cancel-modal-footer">
            <button type="button" class="cancel-modal-btn cancel-modal-btn-keep" onclick="closeCancelModal()">
                <i class="fas fa-shopping-bag"></i>
                <?php if(current_locale() === 'ar'): ?>
                    الاحتفاظ بالطلب
                <?php elseif(current_locale() === 'he'): ?>
                    שמור הזמנה
                <?php else: ?>
                    Keep Order
                <?php endif; ?>
            </button>
            <button type="button" class="cancel-modal-btn cancel-modal-btn-confirm" id="confirmCancelBtn" onclick="confirmCancellation()">
                <i class="fas fa-times-circle" id="confirmCancelIcon"></i>
                <span class="cancel-spinner" id="cancelSpinner" style="display: none;"></span>
                <span id="confirmCancelText">
                    <?php if(current_locale() === 'ar'): ?>
                        تأكيد الإلغاء
                    <?php elseif(current_locale() === 'he'): ?>
                        אשר ביטול
                    <?php else: ?>
                        Confirm Cancellation
                    <?php endif; ?>
                </span>
            </button>
        </div>
    </div>
</div>

<style>
    /* Cancel Modal Overlay */
    .cancel-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        animation: fadeIn 0.2s ease;
    }

    .cancel-modal-overlay.active {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Modal Container */
    .cancel-modal {
        background: white;
        border-radius: 20px;
        max-width: 520px;
        width: 100%;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }

    /* Modal Header */
    .cancel-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        background: #fef2f2;
    }

    .cancel-modal-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .cancel-modal-close {
        width: 36px;
        height: 36px;
        border: none;
        background: rgba(220, 38, 38, 0.1);
        color: #dc2626;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .cancel-modal-close:hover {
        background: rgba(220, 38, 38, 0.2);
        transform: rotate(90deg);
    }

    /* Modal Body */
    .cancel-modal-body {
        padding: 2rem;
    }

    .cancel-modal-question {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1.25rem;
    }

    /* Reason Options */
    .cancel-reasons {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .cancel-reason-option {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .cancel-reason-option:hover {
        border-color: #dc2626;
        background: #fef2f2;
    }

    .cancel-reason-option input[type="radio"] {
        display: none;
    }

    .cancel-reason-option input[type="radio"]:checked ~ .cancel-reason-radio {
        border-color: #dc2626;
        background: #dc2626;
    }

    .cancel-reason-option input[type="radio"]:checked ~ .cancel-reason-radio::after {
        transform: scale(1);
    }

    .cancel-reason-option input[type="radio"]:checked ~ .cancel-reason-text {
        color: #dc2626;
        font-weight: 600;
    }

    .cancel-reason-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        flex-shrink: 0;
        position: relative;
        transition: all 0.2s ease;
    }

    .cancel-reason-radio::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        transition: transform 0.2s ease;
    }

    .cancel-reason-option input[type="radio"]:checked ~ .cancel-reason-radio::after {
        transform: translate(-50%, -50%) scale(1);
    }

    .cancel-reason-text {
        font-size: 0.95rem;
        color: #374151;
        transition: all 0.2s ease;
    }

    /* Note Textarea */
    .cancel-note-wrapper {
        margin-bottom: 1.25rem;
    }

    .cancel-note-textarea {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        font-family: inherit;
        resize: vertical;
        transition: border-color 0.2s ease;
        outline: none;
    }

    .cancel-note-textarea:focus {
        border-color: #dc2626;
    }

    /* Refund Info */
    .cancel-refund-info {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: #eff6ff;
        border-radius: 12px;
        font-size: 0.9rem;
        color: #1e40af;
        line-height: 1.5;
    }

    .cancel-refund-info i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Error Message */
    .cancel-error {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 1rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        color: #dc2626;
        font-size: 0.9rem;
        margin-top: 1rem;
    }

    /* Modal Footer */
    .cancel-modal-footer {
        display: flex;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .cancel-modal-btn {
        flex: 1;
        padding: 0.85rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .cancel-modal-btn-keep {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .cancel-modal-btn-keep:hover {
        border-color: #2762f3;
        color: #2762f3;
        background: #eff6ff;
    }

    .cancel-modal-btn-confirm {
        background: #dc2626;
        color: white;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .cancel-modal-btn-confirm:hover:not(:disabled) {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
    }

    .cancel-modal-btn-confirm:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Spinner */
    .cancel-spinner {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Toast Notification */
    .cancel-toast {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 10001;
        padding: 1rem 1.5rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        animation: toastSlideIn 0.4s ease;
        max-width: 400px;
    }

    .cancel-toast.success {
        background: #10b981;
        color: white;
    }

    .cancel-toast.error {
        background: #ef4444;
        color: white;
    }

    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes toastSlideOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(50px); }
    }

    /* Responsive Modal */
    @media (max-width: 600px) {
        .cancel-modal {
            max-width: 100%;
            border-radius: 16px;
        }

        .cancel-modal-header,
        .cancel-modal-body,
        .cancel-modal-footer {
            padding: 1.25rem;
        }

        .cancel-modal-footer {
            flex-direction: column-reverse;
        }

        .cancel-toast {
            left: 1rem;
            right: 1rem;
            max-width: none;
        }
    }
</style>
<?php endif; ?>

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

<?php if($order->canBeCancelled()): ?>
<script>
    // ========== Cancellation Modal Logic ==========
    const cancelUrl = '<?php echo e(route("orders.cancel", $order->order_number)); ?>';
    const csrfToken = '<?php echo e(csrf_token()); ?>';

    function openCancelModal() {
        document.getElementById('cancelModalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCancelModal(event) {
        if (event && event.target !== document.getElementById('cancelModalOverlay')) return;
        document.getElementById('cancelModalOverlay').classList.remove('active');
        document.body.style.overflow = '';
        resetModal();
    }

    function resetModal() {
        document.querySelectorAll('input[name="cancel_reason"]').forEach(r => r.checked = false);
        document.getElementById('cancelNoteWrapper').style.display = 'none';
        document.getElementById('cancelNote').value = '';
        document.getElementById('cancelError').style.display = 'none';
        const btn = document.getElementById('confirmCancelBtn');
        btn.disabled = false;
        document.getElementById('confirmCancelIcon').style.display = '';
        document.getElementById('cancelSpinner').style.display = 'none';
    }

    // Show/hide note textarea when "Other" is selected
    document.querySelectorAll('input[name="cancel_reason"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const noteWrapper = document.getElementById('cancelNoteWrapper');
            noteWrapper.style.display = this.value === 'other' ? 'block' : 'none';
            document.getElementById('cancelError').style.display = 'none';
        });
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('cancelModalOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    function confirmCancellation() {
        const selected = document.querySelector('input[name="cancel_reason"]:checked');
        if (!selected) {
            showCancelError(
                <?php if(current_locale() === 'ar'): ?>
                    'يرجى اختيار سبب الإلغاء'
                <?php elseif(current_locale() === 'he'): ?>
                    'אנא בחר סיבת ביטול'
                <?php else: ?>
                    'Please select a cancellation reason'
                <?php endif; ?>
            );
            return;
        }

        const reason = selected.value;
        const note = reason === 'other' ? document.getElementById('cancelNote').value.trim() : null;

        if (reason === 'other' && !note) {
            showCancelError(
                <?php if(current_locale() === 'ar'): ?>
                    'يرجى توضيح سبب الإلغاء'
                <?php elseif(current_locale() === 'he'): ?>
                    'אנא פרט את סיבת הביטול'
                <?php else: ?>
                    'Please specify your cancellation reason'
                <?php endif; ?>
            );
            return;
        }

        // Disable button and show spinner
        const btn = document.getElementById('confirmCancelBtn');
        btn.disabled = true;
        document.getElementById('confirmCancelIcon').style.display = 'none';
        document.getElementById('cancelSpinner').style.display = '';
        document.getElementById('cancelError').style.display = 'none';

        fetch(cancelUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ reason: reason, note: note }),
        })
        .then(function(response) {
            return response.json().then(function(data) {
                return { ok: response.ok, data: data };
            });
        })
        .then(function(result) {
            if (result.ok) {
                // Close modal
                document.getElementById('cancelModalOverlay').classList.remove('active');
                document.body.style.overflow = '';

                // Update status badge to Cancelled
                const badge = document.querySelector('.order-status-badge');
                if (badge) {
                    badge.innerHTML = '<i class="fas fa-circle" style="font-size: 0.5rem; color: #ef4444;"></i> ' +
                        <?php if(current_locale() === 'ar'): ?>
                            'ملغي';
                        <?php elseif(current_locale() === 'he'): ?>
                            'בוטל';
                        <?php else: ?>
                            'Cancelled';
                        <?php endif; ?>
                    badge.style.color = '#ef4444';
                }

                // Replace cancel button with nothing (remove actions)
                var actionsSection = document.getElementById('orderActionsSection');
                if (actionsSection) {
                    actionsSection.innerHTML = '';
                }

                // Show success toast
                showToast('success', result.data.message ||
                    <?php if(current_locale() === 'ar'): ?>
                        'تم إلغاء الطلب بنجاح.'
                    <?php elseif(current_locale() === 'he'): ?>
                        'ההזמנה בוטלה בהצלחה.'
                    <?php else: ?>
                        'Order successfully cancelled.'
                    <?php endif; ?>
                );
            } else {
                // Re-enable button
                btn.disabled = false;
                document.getElementById('confirmCancelIcon').style.display = '';
                document.getElementById('cancelSpinner').style.display = 'none';

                showCancelError(result.data.message ||
                    <?php if(current_locale() === 'ar'): ?>
                        'لا يمكن إلغاء هذا الطلب.'
                    <?php elseif(current_locale() === 'he'): ?>
                        'לא ניתן לבטל הזמנה זו.'
                    <?php else: ?>
                        'This order can no longer be cancelled.'
                    <?php endif; ?>
                );
            }
        })
        .catch(function() {
            btn.disabled = false;
            document.getElementById('confirmCancelIcon').style.display = '';
            document.getElementById('cancelSpinner').style.display = 'none';
            showCancelError(
                <?php if(current_locale() === 'ar'): ?>
                    'حدث خطأ. يرجى المحاولة مرة أخرى.'
                <?php elseif(current_locale() === 'he'): ?>
                    'אירעה שגיאה. אנא נסה שוב.'
                <?php else: ?>
                    'An error occurred. Please try again.'
                <?php endif; ?>
            );
        });
    }

    function showCancelError(msg) {
        var el = document.getElementById('cancelError');
        document.getElementById('cancelErrorText').textContent = msg;
        el.style.display = 'flex';
    }

    function showToast(type, message) {
        var toast = document.createElement('div');
        toast.className = 'cancel-toast ' + type;
        toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i>' + message;
        document.body.appendChild(toast);

        setTimeout(function() {
            toast.style.animation = 'toastSlideOut 0.4s ease forwards';
            setTimeout(function() {
                toast.remove();
            }, 400);
        }, 4000);
    }

    // ========== Countdown Timer ==========
    (function() {
        var el = document.getElementById('cancellationCountdown');
        if (!el) return;
        var totalSeconds = parseInt(el.dataset.minutes) * 60;
        if (totalSeconds <= 0) return;

        function updateCountdown() {
            if (totalSeconds <= 0) {
                // Time expired - hide cancel button, show contact support message
                var actionsSection = document.getElementById('orderActionsSection');
                if (actionsSection) {
                    actionsSection.innerHTML =
                        '<div style="text-align:center;padding:1rem;background:#fef3cd;border-radius:12px;color:#856404;font-size:0.9rem;">' +
                        '<i class="fas fa-info-circle"></i> ' +
                        <?php if(current_locale() === 'ar'): ?>
                            'لا يمكن إلغاء هذا الطلب بعد الآن. يرجى التواصل مع الدعم.'
                        <?php elseif(current_locale() === 'he'): ?>
                            'לא ניתן לבטל הזמנה זו יותר. אנא צור קשר עם התמיכה.'
                        <?php else: ?>
                            'This order can no longer be cancelled. Please contact support.'
                        <?php endif; ?>
                    + '</div>';
                }
                return;
            }

            var mins = Math.floor(totalSeconds / 60);
            var secs = totalSeconds % 60;

            <?php if(current_locale() === 'ar'): ?>
                el.textContent = 'يمكنك الإلغاء خلال ' + mins + ':' + (secs < 10 ? '0' : '') + secs;
            <?php elseif(current_locale() === 'he'): ?>
                el.textContent = 'ניתן לבטל תוך ' + mins + ':' + (secs < 10 ? '0' : '') + secs;
            <?php else: ?>
                el.textContent = 'You can cancel within ' + mins + ':' + (secs < 10 ? '0' : '') + secs;
            <?php endif; ?>

            totalSeconds--;
            setTimeout(updateCountdown, 1000);
        }

        updateCountdown();
    })();
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/orders/show.blade.php ENDPATH**/ ?>