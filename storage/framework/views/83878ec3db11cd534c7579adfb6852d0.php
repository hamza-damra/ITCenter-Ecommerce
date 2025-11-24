<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Dashboard Specific Styles */
    .dashboard-header {
        margin-bottom: 32px;
    }

    .greeting {
        font-size: 16px;
        color: var(--secondary);
        margin-bottom: 8px;
    }

    .stats-grid-dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card-large {
        padding: 28px 24px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card-large:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    /* Products Sold Card - Purple Gradient */
    .stat-card-large.products-sold {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    /* Revenue Card - Pink Gradient */
    .stat-card-large.revenue {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    /* Customers Card - Orange Gradient */
    .stat-card-large.customers {
        background: linear-gradient(135deg, #fa8e42 0%, #feb47b 100%);
        color: white;
    }

    /* Satisfaction Card - Blue Gradient */
    .stat-card-large.satisfaction {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    /* Green Gradient for success metrics */
    .stat-card-large.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    /* Warning Yellow/Orange Gradient */
    .stat-card-large.warning {
        background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
        color: white;
    }

    /* Danger Red Gradient */
    .stat-card-large.danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: white;
    }

    /* Info Indigo Gradient */
    .stat-card-large.info {
        background: linear-gradient(135deg, #5f72bd 0%, #9b23ea 100%);
        color: white;
    }

    .stat-card-content {
        flex: 1;
        z-index: 2;
    }

    .stat-card-label {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 8px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .stat-card-value {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 4px;
        line-height: 1;
    }

    .stat-card-footer {
        font-size: 13px;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
    }

    .stat-card-icon-wrapper {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        z-index: 2;
    }

    .stat-card-icon-wrapper i {
        font-size: 32px;
        opacity: 0.9;
    }

    /* Decorative background elements */
    .stat-card-large::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 0;
    }

    .stat-card-large::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        z-index: 0;
    }

    .dashboard-sections {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .recent-products-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
    }

    .recent-products-card .card-header {
        padding: 24px 28px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recent-products-card .card-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .view-all-link {
        font-size: 14px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #eff6ff;
        border-radius: 8px;
    }

    .view-all-link:hover {
        background: var(--primary);
        color: white;
        gap: 10px;
        transform: translateX(-2px);
    }

    .recent-products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .recent-products-table thead {
        background: linear-gradient(135deg, #fafbfc 0%, #f4f6f8 100%);
    }

    .recent-products-table th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 700;
        color: var(--dark);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border);
    }

    .recent-products-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        color: var(--dark);
    }

    .recent-products-table tbody tr {
        transition: all 0.2s ease;
    }

    .recent-products-table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .product-cell img {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .product-name {
        font-weight: 600;
        font-size: 15px;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--dark);
    }

    .product-sku {
        font-size: 13px;
        color: var(--secondary);
        font-weight: 500;
    }

    .price-cell {
        font-weight: 700;
        color: var(--success);
        font-size: 16px;
    }

    .stock-cell {
        font-weight: 700;
        font-size: 15px;
    }

    .stock-cell.low {
        color: var(--danger);
    }

    .stock-cell.good {
        color: var(--success);
    }

    .stock-cell.out {
        color: #dc2626;
        background: #fee2e2;
        padding: 4px 12px;
        border-radius: 6px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-inactive {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #7f1d1d;
    }

    .status-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #78350f;
    }

    .quick-actions-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
    }

    .quick-actions-card .card-header {
        padding: 24px 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-bottom: none;
    }

    .quick-actions-card .card-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .quick-actions-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .quick-actions-list li {
        padding: 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .quick-actions-list li:last-child {
        border-bottom: none;
    }

    .quick-actions-list li:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
    }

    .quick-action-link {
        text-decoration: none;
        color: var(--dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 28px;
        transition: all 0.3s ease;
    }

    .quick-action-link:hover {
        gap: 18px;
        padding-left: 32px;
        color: var(--primary);
    }

    .quick-action-link i {
        font-size: 20px;
        width: 28px;
        text-align: center;
        color: var(--primary);
    }

    .empty-state-message {
        padding: 60px 24px;
        text-align: center;
        color: var(--secondary);
    }

    .empty-state-message i {
        font-size: 48px;
        margin-bottom: 16px;
        display: block;
        color: #cbd5e1;
    }

    @media (max-width: 1024px) {
        .dashboard-sections {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid-dashboard {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .stat-card-value {
            font-size: 36px;
        }

        .stat-card-icon-wrapper {
            width: 60px;
            height: 60px;
        }

        .stat-card-icon-wrapper i {
            font-size: 28px;
        }

        .recent-products-table {
            font-size: 13px;
        }

        .recent-products-table td,
        .recent-products-table th {
            padding: 12px 14px;
        }

        .product-cell img {
            width: 45px;
            height: 45px;
        }

        .product-name {
            max-width: 120px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header" style="border-left-color: var(--primary);">
    <div class="page-header-content">
        <h1><i class="fas fa-chart-line"></i> <?php echo e(__('messages.dashboard')); ?></h1>
        <p><?php echo e(__('messages.welcome_back')); ?> <?php echo e(__('messages.catalog_overview')); ?></p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid-dashboard">
    <!-- Total Products - Purple -->
    <div class="stat-card-large products-sold">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_products')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['total_products']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.complete_inventory')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <!-- Active Products - Pink/Red -->
    <div class="stat-card-large revenue">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.active_products')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['active_products']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.currently_visible')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <!-- Categories - Orange -->
    <div class="stat-card-large customers">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_categories')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['total_categories']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.organize_products')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <!-- Customer Satisfaction - Blue -->
    <div class="stat-card-large satisfaction">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_brands')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['total_brands']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.in_your_store')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-heart"></i>
        </div>
    </div>

    <!-- Featured Products - Green -->
    <div class="stat-card-large success">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.featured_products_count')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['featured_products']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.promoted_items')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-star"></i>
        </div>
    </div>

    <!-- Out of Stock - Red -->
    <div class="stat-card-large danger">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.out_of_stock_count')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['out_of_stock']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.need_attention')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
    </div>

    <!-- Total Reviews - Purple -->
    <div class="stat-card-large info">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_reviews')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['total_reviews']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.customer_feedback')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-comments"></i>
        </div>
    </div>

    <!-- Active Offers - Yellow/Orange -->
    <div class="stat-card-large warning">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.active_offers')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['active_offers']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> <?php echo e(__('messages.running_campaigns')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-gift"></i>
        </div>
    </div>
</div>

<!-- User Statistics Section -->
<div style="margin-top: 32px; margin-bottom: 16px;">
    <h2 style="font-size: 20px; font-weight: 700; color: var(--dark); display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-users"></i> <?php echo e(__('messages.user_statistics')); ?>

    </h2>
</div>

<div class="stats-grid-dashboard">
    <!-- Total Users - Purple Gradient -->
    <div class="stat-card-large products-sold">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_users')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['total_users']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-user-plus"></i> <?php echo e(__('messages.all_registered_users')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <!-- Registered Online Users - Green -->
    <div class="stat-card-large success">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.online_users')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['registered_online_users']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-circle" style="color: #22c55e;"></i> <?php echo e(__('messages.active_now_5min')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-check"></i>
        </div>
    </div>

    <!-- Registered Offline Users - Blue -->
    <div class="stat-card-large satisfaction">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.offline_users')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['registered_offline_users']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-circle" style="color: #94a3b8;"></i> <?php echo e(__('messages.inactive_users')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-clock"></i>
        </div>
    </div>

    <!-- Guest Active Sessions - Orange -->
    <div class="stat-card-large customers">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.guest_sessions')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['guest_active_sessions']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-shopping-cart"></i> <?php echo e(__('messages.non_registered_shoppers')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-secret"></i>
        </div>
    </div>

    <!-- Admin Users - Info/Indigo -->
    <div class="stat-card-large info">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.admin_users')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['admin_users']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-shield-alt"></i> <?php echo e(__('messages.admin_accounts')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-shield"></i>
        </div>
    </div>

    <!-- Active Users (30 days) - Success Green -->
    <div class="stat-card-large success">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.active_30days')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['active_users_30days']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-chart-line"></i> <?php echo e(__('messages.recent_activity')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-clock"></i>
        </div>
    </div>

    <!-- New Users This Week - Warning -->
    <div class="stat-card-large warning">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.new_this_week')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['users_this_week']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar-week"></i> <?php echo e(__('messages.weekly_signups')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-plus"></i>
        </div>
    </div>

    <!-- New Users This Month - Purple -->
    <div class="stat-card-large products-sold">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.new_this_month')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['users_this_month']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar-alt"></i> <?php echo e(__('messages.monthly_signups')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-user-plus"></i>
        </div>
    </div>

    <!-- Users with Orders - Success -->
    <div class="stat-card-large success">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.with_orders')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['users_with_orders']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-shopping-bag"></i> <?php echo e(__('messages.customers_who_bought')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-receipt"></i>
        </div>
    </div>

    <!-- Users with Favorites - Pink -->
    <div class="stat-card-large revenue">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.with_favorites')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['users_with_favorites']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-heart"></i> <?php echo e(__('messages.users_with_wishlist')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-heart"></i>
        </div>
    </div>

    <!-- Users with Reviews - Blue -->
    <div class="stat-card-large satisfaction">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.with_reviews')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['users_with_reviews']); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-star"></i> <?php echo e(__('messages.active_reviewers')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-comment-dots"></i>
        </div>
    </div>
</div>

<!-- Main Content Sections -->
<div class="dashboard-sections">
    <!-- Recent Products -->
    <div class="recent-products-card">
        <div class="card-header">
            <h2><i class="fas fa-clock"></i> <?php echo e(__('messages.recent_products')); ?></h2>
            <a href="<?php echo e(route('admin.products.index', ['filter' => 'recent'])); ?>" class="view-all-link">
                <?php echo e(__('messages.view_all')); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if($recent_products->count() > 0): ?>
                <table class="recent-products-table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.product_name')); ?></th>
                            <th><?php echo e(__('messages.category')); ?></th>
                            <th><?php echo e(__('messages.price')); ?></th>
                            <th><?php echo e(__('messages.stock')); ?></th>
                            <th><?php echo e(__('messages.status')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recent_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>">
                                    <div class="product-info">
                                        <div class="product-name"><?php echo e($product->name_en ?? $product->name); ?></div>
                                        <div class="product-sku"><?php echo e($product->sku); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($product->category): ?>
                                    <span style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #3730a3; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px;">
                                        <?php echo e($product->category->name_en ?? $product->category->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); color: #6b7280; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px;">
                                        <?php echo e(__('messages.no_category')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="price-cell">$<?php echo e(number_format($product->price, 2)); ?></div>
                            </td>
                            <td>
                                <span class="stock-cell <?php echo e($product->stock_quantity > 10 ? 'good' : 'low'); ?>">
                                    <?php echo e($product->stock_quantity); ?>

                                </span>
                            </td>
                            <td>
                                <span class="status-pill <?php echo e($product->is_active ? 'status-active' : 'status-inactive'); ?>">
                                    <i class="fas <?php echo e($product->is_active ? 'fa-check-circle' : 'fa-times-circle'); ?>"></i>
                                    <?php echo e($product->is_active ? __('messages.active') : __('messages.inactive')); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state-message">
                    <i class="fas fa-inbox"></i>
                    <p><?php echo e(__('messages.no_products_yet')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-card">
        <div class="card-header">
            <h2><i class="fas fa-bolt"></i> <?php echo e(__('messages.quick_actions')); ?></h2>
        </div>
        <ul class="quick-actions-list">
            <li>
                <a href="<?php echo e(route('admin.products.create')); ?>" class="quick-action-link">
                    <i class="fas fa-plus-circle"></i>
                    <span><?php echo e(__('messages.add_new_product')); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.categories.create')); ?>" class="quick-action-link">
                    <i class="fas fa-folder-plus"></i>
                    <span><?php echo e(__('messages.create_category')); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.brands.create')); ?>" class="quick-action-link">
                    <i class="fas fa-tag"></i>
                    <span><?php echo e(__('messages.add_new_brand')); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="quick-action-link">
                    <i class="fas fa-list-ul"></i>
                    <span><?php echo e(__('messages.manage_products')); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.categories.index')); ?>" class="quick-action-link">
                    <i class="fas fa-th-large"></i>
                    <span><?php echo e(__('messages.manage_categories')); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.brands.index')); ?>" class="quick-action-link">
                    <i class="fas fa-tags"></i>
                    <span><?php echo e(__('messages.manage_brands')); ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Additional Analytics Section -->
<div class="dashboard-sections" style="margin-top: 24px;">
    <!-- Top Rated Products -->
    <div class="recent-products-card">
        <div class="card-header">
            <h2><i class="fas fa-star"></i> <?php echo e(__('messages.top_rated_products')); ?></h2>
            <a href="<?php echo e(route('admin.products.index', ['filter' => 'top_rated'])); ?>" class="view-all-link">
                <?php echo e(__('messages.view_all')); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if(isset($top_rated_products) && $top_rated_products->count() > 0): ?>
                <table class="recent-products-table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.product_name')); ?></th>
                            <th><?php echo e(__('messages.rating')); ?></th>
                            <th><?php echo e(__('messages.reviews')); ?></th>
                            <th><?php echo e(__('messages.price')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $top_rated_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>">
                                    <div class="product-info">
                                        <div class="product-name"><?php echo e($product->name_en ?? $product->name); ?></div>
                                        <div class="product-sku"><?php echo e($product->sku); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="color: #f59e0b; font-weight: 700; font-size: 16px;">
                                        <?php echo e(number_format($product->reviews_avg_rating, 1)); ?>

                                    </span>
                                    <span style="color: #f59e0b;">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star" style="font-size: 12px;"></i>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px;">
                                    <?php echo e($product->reviews_count); ?> <?php echo e(__('messages.reviews')); ?>

                                </span>
                            </td>
                            <td>
                                <div class="price-cell">$<?php echo e(number_format($product->price, 2)); ?></div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state-message">
                    <i class="fas fa-star"></i>
                    <p><?php echo e(__('messages.no_rated_products_yet')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="recent-products-card">
        <div class="card-header">
            <h2><i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.low_stock_alerts')); ?></h2>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if(isset($low_stock_products) && $low_stock_products->count() > 0): ?>
                <table class="recent-products-table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.product_name')); ?></th>
                            <th><?php echo e(__('messages.category')); ?></th>
                            <th><?php echo e(__('messages.current_stock')); ?></th>
                            <th><?php echo e(__('messages.status')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $low_stock_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>">
                                    <div class="product-info">
                                        <div class="product-name"><?php echo e($product->name_en ?? $product->name); ?></div>
                                        <div class="product-sku"><?php echo e($product->sku); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($product->category): ?>
                                    <span style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #3730a3; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px;">
                                        <?php echo e($product->category->name_en ?? $product->category->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); color: #6b7280; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px;">
                                        <?php echo e(__('messages.no_category')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="stock-cell <?php echo e($product->stock_quantity == 0 ? 'out' : 'low'); ?>">
                                    <?php echo e($product->stock_quantity ?? 0); ?>

                                </span>
                            </td>
                            <td>
                                <span class="status-pill <?php echo e($product->stock_status == 'out_of_stock' ? 'status-inactive' : 'status-warning'); ?>">
                                    <i class="fas <?php echo e($product->stock_status == 'out_of_stock' ? 'fa-times-circle' : 'fa-exclamation-circle'); ?>"></i>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $product->stock_status))); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state-message">
                    <i class="fas fa-check-circle"></i>
                    <p><?php echo e(__('messages.all_products_well_stocked')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Analytics Overview -->
<div class="stats-grid-dashboard" style="margin-top: 24px;">
    <!-- Cart Statistics -->
    <div class="stat-card-large info">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_cart_value')); ?></div>
            <div class="stat-card-value">$<?php echo e(number_format($stats['cart_value'] ?? 0, 2)); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-shopping-cart"></i> <?php echo e($stats['active_carts'] ?? 0); ?> <?php echo e(__('messages.active_carts')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <!-- Average Rating -->
    <div class="stat-card-large success">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.average_rating')); ?></div>
            <div class="stat-card-value"><?php echo e(number_format($stats['average_rating'] ?? 0, 1)); ?> <i class="fas fa-star" style="font-size: 24px; color: #fbbf24;"></i></div>
            <div class="stat-card-footer">
                <i class="fas fa-comments"></i> <?php echo e(__('messages.from')); ?> <?php echo e($stats['total_reviews'] ?? 0); ?> <?php echo e(__('messages.reviews')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-star"></i>
        </div>
    </div>

    <!-- Stock Value -->
    <div class="stat-card-large customers">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_stock_value')); ?></div>
            <div class="stat-card-value">$<?php echo e(number_format($stats['total_stock_value'] ?? 0, 2)); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-box"></i> <?php echo e(__('messages.inventory_value')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <!-- Total Favorites -->
    <div class="stat-card-large revenue">
        <div class="stat-card-content">
            <div class="stat-card-label"><?php echo e(__('messages.total_favorites')); ?></div>
            <div class="stat-card-value"><?php echo e($stats['total_favorites'] ?? 0); ?></div>
            <div class="stat-card-footer">
                <i class="fas fa-heart"></i> <?php echo e(__('messages.customer_wishlists')); ?>

            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-heart"></i>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>