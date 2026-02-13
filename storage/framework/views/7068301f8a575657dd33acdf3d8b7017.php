<?php $__env->startSection('title', __('messages.order_confirmed_title') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Import Google Font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Override font - exclude Font Awesome icons */
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

    .confirmation-page {
        max-width: 900px;
        margin: 0 auto;
        padding: 2.5rem 2rem;
        min-height: calc(100vh - 200px);
    }

    /* Progress Steps */
    .checkout-progress {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        position: relative;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .checkout-progress::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 3px;
        background: #10b981;
        z-index: 0;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 1;
    }

    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #10b981;
        border: 3px solid #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #fff;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .step-label {
        font-size: 0.9rem;
        color: #10b981;
        font-weight: 600;
        text-align: center;
    }

    /* Success Hero */
    .confirmation-hero {
        text-align: center;
        margin-bottom: 3rem;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        animation: scaleIn 0.5s ease-out;
        box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
    }

    .success-icon i {
        font-size: 2.5rem;
        color: #fff;
    }

    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }

    .confirmation-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 0.75rem;
    }

    .confirmation-hero p {
        font-size: 1.1rem;
        color: #6b7280;
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Order Info Card */
    .order-info-card {
        background: #fff;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .order-info-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 1.5rem;
    }

    .order-info-header .order-num {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .order-info-header .order-num i {
        color: #2762f3;
    }

    .order-info-header .order-date-val {
        font-size: 0.95rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        background: #fef3c7;
        color: #92400e;
    }

    /* Order Items List */
    .confirmation-items {
        margin-bottom: 1.5rem;
    }

    .confirmation-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 12px;
        transition: background 0.2s;
    }

    .confirmation-item:not(:last-child) {
        border-bottom: 1px solid #f3f4f6;
    }

    .confirmation-item:hover {
        background: #f9fafb;
    }

    .conf-item-image {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .conf-item-details {
        flex: 1;
        min-width: 0;
    }

    .conf-item-name {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .conf-item-qty {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .conf-item-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2762f3;
        flex-shrink: 0;
    }

    /* Summary Totals */
    .confirmation-totals {
        padding-top: 1.5rem;
        border-top: 2px solid #f3f4f6;
    }

    .conf-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        font-size: 1rem;
    }

    .conf-total-row:not(:last-child) {
        border-bottom: 1px solid #f3f4f6;
    }

    .conf-total-row .label {
        color: #6b7280;
    }

    .conf-total-row .value {
        font-weight: 600;
        color: #111827;
    }

    .conf-total-row.grand-total {
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px solid #e5e7eb;
        font-size: 1.4rem;
        font-weight: 700;
    }

    .conf-total-row.grand-total .value {
        color: #2762f3;
    }

    /* Shipping Info */
    .shipping-info-card {
        background: #fff;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .shipping-info-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .shipping-info-card h3 i {
        color: #2762f3;
    }

    .shipping-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .shipping-detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .shipping-detail-item .detail-label {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
    }

    .shipping-detail-item .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
    }

    /* Note Banner */
    .confirmation-note {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        margin-bottom: 2rem;
        font-size: 0.95rem;
        color: #1e40af;
        line-height: 1.5;
    }

    .confirmation-note i {
        font-size: 1.5rem;
        color: #3b82f6;
        flex-shrink: 0;
    }

    /* Action Buttons */
    .confirmation-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .conf-btn {
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .conf-btn-primary {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(39, 98, 243, 0.3);
    }

    .conf-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(39, 98, 243, 0.4);
        color: #fff;
        text-decoration: none;
    }

    .conf-btn-secondary {
        background: #fff;
        color: #374151;
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .conf-btn-secondary:hover {
        border-color: #2762f3;
        color: #2762f3;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .confirmation-page {
            padding: 1.5rem 1rem;
        }

        .checkout-progress {
            margin-bottom: 2rem;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            font-size: 0.85rem;
        }

        .step-label {
            font-size: 0.75rem;
        }

        .checkout-progress::before {
            top: 18px;
            height: 2px;
        }

        .confirmation-hero h1 {
            font-size: 1.5rem;
        }

        .confirmation-hero p {
            font-size: 0.95rem;
        }

        .success-icon {
            width: 80px;
            height: 80px;
        }

        .success-icon i {
            font-size: 2rem;
        }

        .order-info-card,
        .shipping-info-card {
            padding: 1.25rem;
            border-radius: 14px;
        }

        .order-info-header .order-num {
            font-size: 1.15rem;
        }

        .shipping-details {
            grid-template-columns: 1fr;
        }

        .confirmation-item {
            padding: 0.75rem 0;
        }

        .conf-item-image {
            width: 56px;
            height: 56px;
        }

        .conf-item-name {
            font-size: 0.9rem;
        }

        .conf-item-price {
            font-size: 0.95rem;
        }

        .conf-total-row.grand-total {
            font-size: 1.2rem;
        }

        .confirmation-actions {
            flex-direction: column;
        }

        .conf-btn {
            justify-content: center;
            padding: 0.9rem 2rem;
        }
    }

    @media (max-width: 480px) {
        .confirmation-page {
            padding: 1rem 0.75rem;
        }

        .confirmation-hero h1 {
            font-size: 1.3rem;
        }

        .success-icon {
            width: 70px;
            height: 70px;
            margin-bottom: 1rem;
        }

        .success-icon i {
            font-size: 1.75rem;
        }

        .order-info-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            font-size: 0.7rem;
        }

        .step-label {
            font-size: 0.65rem;
        }

        .checkout-progress::before {
            top: 15px;
        }
    }
</style>

<div class="confirmation-page">
    <!-- Progress Steps - All Completed -->
    <div class="checkout-progress">
        <div class="progress-step">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label"><?php echo e(__('messages.cart')); ?></div>
        </div>
        <div class="progress-step">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label"><?php echo e(__('messages.checkout')); ?></div>
        </div>
        <div class="progress-step">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label"><?php echo e(__('messages.confirmation')); ?></div>
        </div>
    </div>

    <!-- Success Hero -->
    <div class="confirmation-hero">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h1><?php echo e(__('messages.order_confirmed_title')); ?></h1>
        <p><?php echo e(__('messages.order_confirmed_subtitle')); ?></p>
    </div>

    <!-- Order Info Card -->
    <div class="order-info-card">
        <div class="order-info-header">
            <div class="order-num">
                <i class="fas fa-receipt"></i>
                <?php echo e($order->order_number); ?>

            </div>
            <div class="order-date-val">
                <i class="fas fa-calendar-alt"></i>
                <?php echo e($order->created_at->format('d M Y, h:i A')); ?>

            </div>
        </div>

        <!-- Order Items -->
        <div class="confirmation-items">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="confirmation-item">
                    <?php
                        $imageSrc = asset('images/placeholder.png');
                        if ($item->product_image) {
                            if (str_starts_with($item->product_image, 'http')) {
                                $imageSrc = $item->product_image;
                            } elseif (str_starts_with($item->product_image, 'images/')) {
                                $imageSrc = asset($item->product_image);
                            } else {
                                $imageSrc = asset('storage/' . $item->product_image);
                            }
                        }
                    ?>
                    <img src="<?php echo e($imageSrc); ?>" 
                         alt="<?php echo e($item->product_name); ?>" 
                         class="conf-item-image"
                         onerror="this.src='<?php echo e(asset('images/placeholder.png')); ?>'">
                    <div class="conf-item-details">
                        <div class="conf-item-name"><?php echo e($item->product_name); ?></div>
                        <div class="conf-item-qty"><?php echo e(__('messages.quantity')); ?>: <?php echo e($item->quantity); ?> × ₪<?php echo e(number_format($item->price, 2)); ?></div>
                    </div>
                    <div class="conf-item-price">₪<?php echo e(number_format($item->subtotal, 2)); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Totals -->
        <div class="confirmation-totals">
            <div class="conf-total-row">
                <span class="label"><?php echo e(__('messages.subtotal')); ?></span>
                <span class="value">₪<?php echo e(number_format($order->subtotal, 2)); ?></span>
            </div>
            <?php if($order->tax > 0): ?>
                <div class="conf-total-row">
                    <span class="label"><?php echo e(__('messages.tax')); ?></span>
                    <span class="value">₪<?php echo e(number_format($order->tax, 2)); ?></span>
                </div>
            <?php endif; ?>
            <div class="conf-total-row">
                <span class="label"><?php echo e(__('messages.shipping') ?? 'Shipping'); ?></span>
                <span class="value">
                    <?php if($order->shipping_cost > 0): ?>
                        ₪<?php echo e(number_format($order->shipping_cost, 2)); ?>

                    <?php else: ?>
                        <?php echo e(__('messages.free')); ?>

                    <?php endif; ?>
                </span>
            </div>
            <?php if($order->discount > 0): ?>
                <div class="conf-total-row">
                    <span class="label"><?php echo e(__('messages.discount') ?? 'Discount'); ?></span>
                    <span class="value" style="color: #10b981;">-₪<?php echo e(number_format($order->discount, 2)); ?></span>
                </div>
            <?php endif; ?>
            <div class="conf-total-row grand-total">
                <span class="label"><?php echo e(__('messages.total')); ?></span>
                <span class="value">₪<?php echo e(number_format($order->total, 2)); ?></span>
            </div>
        </div>
    </div>

    <!-- Shipping Information -->
    <div class="shipping-info-card">
        <h3>
            <i class="fas fa-truck"></i>
            <?php echo e(__('messages.shipping_address')); ?>

        </h3>
        <div class="shipping-details">
            <div class="shipping-detail-item">
                <span class="detail-label"><?php echo e(__('messages.name') ?? 'Name'); ?></span>
                <span class="detail-value"><?php echo e($order->customer_name); ?></span>
            </div>
            <div class="shipping-detail-item">
                <span class="detail-label"><?php echo e(__('messages.email')); ?></span>
                <span class="detail-value"><?php echo e($order->customer_email); ?></span>
            </div>
            <div class="shipping-detail-item">
                <span class="detail-label"><?php echo e(__('messages.phone')); ?></span>
                <span class="detail-value"><?php echo e($order->customer_phone); ?></span>
            </div>
            <div class="shipping-detail-item">
                <span class="detail-label"><?php echo e(__('messages.street_address')); ?></span>
                <span class="detail-value">
                    <?php echo e($order->shipping_address); ?>, <?php echo e($order->shipping_city); ?>

                    <?php if($order->shipping_state): ?>, <?php echo e($order->shipping_state); ?><?php endif; ?>
                    <br><?php echo e($order->shipping_country); ?>

                    <?php if($order->shipping_postal_code): ?> — <?php echo e($order->shipping_postal_code); ?><?php endif; ?>
                </span>
            </div>
            <div class="shipping-detail-item">
                <span class="detail-label"><?php echo e(__('messages.payment_method')); ?></span>
                <span class="detail-value"><?php echo e(__('messages.cash_on_delivery')); ?></span>
            </div>
            <?php if($order->notes): ?>
                <div class="shipping-detail-item" style="grid-column: 1 / -1;">
                    <span class="detail-label"><?php echo e(__('messages.order_notes')); ?></span>
                    <span class="detail-value"><?php echo e($order->notes); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Note -->
    <div class="confirmation-note">
        <i class="fas fa-info-circle"></i>
        <span><?php echo e(__('messages.order_confirmed_note')); ?></span>
    </div>

    <!-- Action Buttons -->
    <div class="confirmation-actions">
        <a href="<?php echo e(route('orders.show', $order->order_number)); ?>" class="conf-btn conf-btn-primary">
            <i class="fas fa-eye"></i>
            <?php echo e(__('messages.view_order_details')); ?>

        </a>
        <a href="<?php echo e(route('home')); ?>" class="conf-btn conf-btn-secondary">
            <i class="fas fa-shopping-bag"></i>
            <?php echo e(__('messages.continue_shopping_home')); ?>

        </a>
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
                
                if (confirm('<?php echo e(__("messages.return_to_orders_list")); ?>')) {
                    window.location.href = '<?php echo e(route("orders.index")); ?>';
                }
            });
        }
    })();
</script>
<?php endif; ?>


<script>
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page served from bfcache — no action needed here since this is confirmation
        // But ensure we don't have stale state
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/orders/confirmation.blade.php ENDPATH**/ ?>