<?php $__env->startSection('title', __t('messages.my_orders') . ' - IT Center'); ?>

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

    .orders-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 40px rgba(39, 98, 243, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        <?php echo e(is_rtl() ? 'left' : 'right'); ?>: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Status Filter Tabs */
    .status-tabs {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
    }

    .status-tabs-list {
        display: flex;
        gap: 1rem;
        min-width: max-content;
    }

    .status-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .status-tab:hover {
        border-color: #2762f3;
        color: #2762f3;
        transform: translateY(-2px);
    }

    .status-tab.active {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        border-color: #2762f3;
        color: white;
        box-shadow: 0 4px 15px rgba(39, 98, 243, 0.4);
    }

    .status-count {
        background: rgba(0, 0, 0, 0.1);
        padding: 0.25rem 0.6rem;
        border-radius: 50px;
        font-size: 0.85rem;
    }

    .status-tab.active .status-count {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Orders List */
    .orders-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .order-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .order-header {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 1.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .order-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-header-bottom {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .order-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .order-info-item i {
        color: #2762f3;
    }

    /* Order Items */
    .order-items {
        padding: 1.5rem;
    }

    .order-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .order-item:hover {
        background: #f9fafb;
    }

    .order-item:not(:last-child) {
        border-bottom: 1px solid #f3f4f6;
    }

    .item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .item-name {
        font-weight: 600;
        color: #111827;
        font-size: 1rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .item-name:hover {
        color: #2762f3;
    }

    .item-info {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .item-price {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.25rem;
    }

    .current-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2762f3;
    }

    .original-price {
        font-size: 0.9rem;
        color: #9ca3af;
        text-decoration: line-through;
    }

    /* Order Footer */
    .order-footer {
        background: #f9fafb;
        padding: 1.5rem;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-total {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .order-total-label {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .order-total-amount {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
    }

    .order-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.95rem;
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

    .btn-secondary {
        background: white;
        color: #ef4444;
        border: 2px solid #ef4444;
    }

    .btn-secondary:hover {
        background: #ef4444;
        color: white;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .empty-state-icon {
        font-size: 5rem;
        color: #e5e7eb;
        margin-bottom: 1rem;
    }

    .empty-state-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        font-size: 1.1rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .orders-page {
            padding: 1rem;
        }

        .page-header {
            padding: 2rem 1.5rem;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .order-header-top,
        .order-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-item {
            flex-direction: column;
        }

        .item-price {
            align-items: flex-start;
        }

        .status-tabs {
            padding: 1rem;
        }

        .order-actions {
            width: 100%;
        }

        .btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="orders-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <i class="fas fa-shopping-bag"></i>
                <?php if(current_locale() === 'ar'): ?>
                    طلباتي
                <?php elseif(current_locale() === 'he'): ?>
                    ההזמנות שלי
                <?php else: ?>
                    My Orders
                <?php endif; ?>
            </h1>
            <p class="page-subtitle">
                <?php if(current_locale() === 'ar'): ?>
                    تتبع وإدارة جميع طلباتك من مكان واحد
                <?php elseif(current_locale() === 'he'): ?>
                    עקוב וניהל את כל ההזמנות שלך ממקום אחד
                <?php else: ?>
                    Track and manage all your orders in one place
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="status-tabs">
        <div class="status-tabs-list">
            <a href="<?php echo e(route('orders.index', ['status' => 'all'])); ?>" 
               class="status-tab <?php echo e((!request('status') || request('status') === 'all') ? 'active' : ''); ?>">
                <i class="fas fa-list"></i>
                <?php if(current_locale() === 'ar'): ?>
                    جميع الطلبات
                <?php elseif(current_locale() === 'he'): ?>
                    כל ההזמנות
                <?php else: ?>
                    All Orders
                <?php endif; ?>
                <span class="status-count"><?php echo e($statusCounts['all']); ?></span>
            </a>

            <a href="<?php echo e(route('orders.index', ['status' => 'pending'])); ?>" 
               class="status-tab <?php echo e(request('status') === 'pending' ? 'active' : ''); ?>">
                <i class="fas fa-clock"></i>
                <?php if(current_locale() === 'ar'): ?>
                    قيد الانتظار
                <?php elseif(current_locale() === 'he'): ?>
                    ממתין
                <?php else: ?>
                    Pending
                <?php endif; ?>
                <span class="status-count"><?php echo e($statusCounts['pending']); ?></span>
            </a>

            <a href="<?php echo e(route('orders.index', ['status' => 'processing'])); ?>" 
               class="status-tab <?php echo e(request('status') === 'processing' ? 'active' : ''); ?>">
                <i class="fas fa-cog"></i>
                <?php if(current_locale() === 'ar'): ?>
                    قيد المعالجة
                <?php elseif(current_locale() === 'he'): ?>
                    בעיבוד
                <?php else: ?>
                    Processing
                <?php endif; ?>
                <span class="status-count"><?php echo e($statusCounts['processing']); ?></span>
            </a>

            <a href="<?php echo e(route('orders.index', ['status' => 'shipped'])); ?>" 
               class="status-tab <?php echo e(request('status') === 'shipped' ? 'active' : ''); ?>">
                <i class="fas fa-shipping-fast"></i>
                <?php if(current_locale() === 'ar'): ?>
                    تم الشحن
                <?php elseif(current_locale() === 'he'): ?>
                    נשלח
                <?php else: ?>
                    Shipped
                <?php endif; ?>
                <span class="status-count"><?php echo e($statusCounts['shipped']); ?></span>
            </a>

            <a href="<?php echo e(route('orders.index', ['status' => 'delivered'])); ?>" 
               class="status-tab <?php echo e(request('status') === 'delivered' ? 'active' : ''); ?>">
                <i class="fas fa-check-circle"></i>
                <?php if(current_locale() === 'ar'): ?>
                    تم التوصيل
                <?php elseif(current_locale() === 'he'): ?>
                    נמסר
                <?php else: ?>
                    Delivered
                <?php endif; ?>
                <span class="status-count"><?php echo e($statusCounts['delivered']); ?></span>
            </a>

            <a href="<?php echo e(route('orders.index', ['status' => 'cancelled'])); ?>" 
               class="status-tab <?php echo e(request('status') === 'cancelled' ? 'active' : ''); ?>">
                <i class="fas fa-times-circle"></i>
                <?php if(current_locale() === 'ar'): ?>
                    ملغي
                <?php elseif(current_locale() === 'he'): ?>
                    מבוטל
                <?php else: ?>
                    Cancelled
                <?php endif; ?>
                <span class="status-count"><?php echo e($statusCounts['cancelled']); ?></span>
            </a>
        </div>
    </div>

    <!-- Orders List -->
    <?php if($orders->count() > 0): ?>
        <div class="orders-container">
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="order-card">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div class="order-header-top">
                            <div class="order-number">
                                <i class="fas fa-hashtag"></i>
                                <?php echo e($order->order_number); ?>

                            </div>
                            <div class="order-status-badge" style="background: <?php echo e($order->status_color); ?>20; color: <?php echo e($order->status_color); ?>;">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                <?php echo e($order->status_label); ?>

                            </div>
                        </div>
                        <div class="order-header-bottom">
                            <div class="order-info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?php echo e($order->created_at->format('d M Y')); ?></span>
                            </div>
                            <div class="order-info-item">
                                <i class="fas fa-box"></i>
                                <span><?php echo e($order->items->count()); ?> 
                                    <?php if(current_locale() === 'ar'): ?>
                                        منتج
                                    <?php elseif(current_locale() === 'he'): ?>
                                        מוצרים
                                    <?php else: ?>
                                        items
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="order-info-item">
                                <i class="fas fa-credit-card"></i>
                                <span><?php echo e($order->payment_status_label); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items">
                        <?php $__currentLoopData = $order->items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="order-item">
                                <img src="<?php echo e($item->product_image ? asset('storage/' . $item->product_image) : asset('images/placeholder.png')); ?>" 
                                     alt="<?php echo e($item->product_name); ?>" 
                                     class="item-image">
                                <div class="item-details">
                                    <a href="<?php echo e($item->product_slug ? route('product.detail', $item->product_slug) : '#'); ?>" 
                                       class="item-name">
                                        <?php echo e($item->product_name); ?>

                                    </a>
                                    <div class="item-info">
                                        <span>
                                            <?php if(current_locale() === 'ar'): ?>
                                                الكمية:
                                            <?php elseif(current_locale() === 'he'): ?>
                                                כמות:
                                            <?php else: ?>
                                                Qty:
                                            <?php endif; ?>
                                            <?php echo e($item->quantity); ?>

                                        </span>
                                        <?php if($item->product_sku): ?>
                                            <span>SKU: <?php echo e($item->product_sku); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="item-price">
                                    <div class="current-price">$<?php echo e(number_format($item->price, 2)); ?></div>
                                    <?php if($item->has_discount): ?>
                                        <div class="original-price">$<?php echo e(number_format($item->original_price, 2)); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($order->items->count() > 3): ?>
                            <div style="text-align: center; padding: 1rem; color: #6b7280;">
                                <?php if(current_locale() === 'ar'): ?>
                                    + <?php echo e($order->items->count() - 3); ?> منتجات أخرى
                                <?php elseif(current_locale() === 'he'): ?>
                                    + <?php echo e($order->items->count() - 3); ?> מוצרים נוספים
                                <?php else: ?>
                                    + <?php echo e($order->items->count() - 3); ?> more items
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Footer -->
                    <div class="order-footer">
                        <div class="order-total">
                            <div class="order-total-label">
                                <?php if(current_locale() === 'ar'): ?>
                                    المجموع الكلي
                                <?php elseif(current_locale() === 'he'): ?>
                                    סה"כ
                                <?php else: ?>
                                    Total Amount
                                <?php endif; ?>
                            </div>
                            <div class="order-total-amount">$<?php echo e(number_format($order->total, 2)); ?></div>
                        </div>
                        <div class="order-actions">
                            <a href="<?php echo e(route('orders.show', $order->order_number)); ?>" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                <?php if(current_locale() === 'ar'): ?>
                                    عرض التفاصيل
                                <?php elseif(current_locale() === 'he'): ?>
                                    צפה בפרטים
                                <?php else: ?>
                                    View Details
                                <?php endif; ?>
                            </a>
                            <?php if($order->canBeCancelled()): ?>
                                <form action="<?php echo e(route('orders.cancel', $order->order_number)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-secondary" 
                                            onclick="return confirm('<?php echo e(__t('messages.confirm_cancel_order')); ?>')">
                                        <i class="fas fa-times"></i>
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            <?php echo e($orders->links()); ?>

        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2 class="empty-state-title">
                <?php if(current_locale() === 'ar'): ?>
                    لا توجد طلبات
                <?php elseif(current_locale() === 'he'): ?>
                    אין הזמנות
                <?php else: ?>
                    No Orders Found
                <?php endif; ?>
            </h2>
            <p class="empty-state-text">
                <?php if(current_locale() === 'ar'): ?>
                    لم تقم بأي طلبات بعد. ابدأ التسوق الآن!
                <?php elseif(current_locale() === 'he'): ?>
                    עדיין לא ביצעת הזמנות. התחל לקנות עכשיו!
                <?php else: ?>
                    You haven't placed any orders yet. Start shopping now!
                <?php endif; ?>
            </p>
            <a href="<?php echo e(route('products')); ?>" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i>
                <?php if(current_locale() === 'ar'): ?>
                    تصفح المنتجات
                <?php elseif(current_locale() === 'he'): ?>
                    עיין במוצרים
                <?php else: ?>
                    Browse Products
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\ITCenter-Ecommerce\resources\views/orders/index.blade.php ENDPATH**/ ?>