@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
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
        <h1><i class="fas fa-chart-line"></i> {{ __('messages.dashboard') }}</h1>
        <p>{{ __('messages.welcome_back') }} {{ __('messages.catalog_overview') }}</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid-dashboard">
    <!-- Total Products - Purple -->
    <div class="stat-card-large products-sold">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.total_products') }}</div>
            <div class="stat-card-value">{{ $stats['total_products'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.complete_inventory') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

    <!-- Active Products - Pink/Red -->
    <div class="stat-card-large revenue">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.active_products') }}</div>
            <div class="stat-card-value">{{ $stats['active_products'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.currently_visible') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <!-- Categories - Orange -->
    <div class="stat-card-large customers">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.total_categories') }}</div>
            <div class="stat-card-value">{{ $stats['total_categories'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.organize_products') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <!-- Customer Satisfaction - Blue -->
    <div class="stat-card-large satisfaction">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.total_brands') }}</div>
            <div class="stat-card-value">{{ $stats['total_brands'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.in_your_store') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-heart"></i>
        </div>
    </div>

    <!-- Featured Products - Green -->
    <div class="stat-card-large success">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.featured_products_count') }}</div>
            <div class="stat-card-value">{{ $stats['featured_products'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.promoted_items') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-star"></i>
        </div>
    </div>

    <!-- Out of Stock - Red -->
    <div class="stat-card-large danger">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.out_of_stock_count') }}</div>
            <div class="stat-card-value">{{ $stats['out_of_stock'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.need_attention') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
    </div>

    <!-- Total Reviews - Purple -->
    <div class="stat-card-large info">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.total_reviews') }}</div>
            <div class="stat-card-value">{{ $stats['total_reviews'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.customer_feedback') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-comments"></i>
        </div>
    </div>

    <!-- Active Offers - Yellow/Orange -->
    <div class="stat-card-large warning">
        <div class="stat-card-content">
            <div class="stat-card-label">{{ __('messages.active_offers') }}</div>
            <div class="stat-card-value">{{ $stats['active_offers'] }}</div>
            <div class="stat-card-footer">
                <i class="fas fa-calendar"></i> {{ __('messages.running_campaigns') }}
            </div>
        </div>
        <div class="stat-card-icon-wrapper">
            <i class="fas fa-gift"></i>
        </div>
    </div>
</div>

<!-- Main Content Sections -->
<div class="dashboard-sections">
    <!-- Recent Products -->
    <div class="recent-products-card">
        <div class="card-header">
            <h2><i class="fas fa-clock"></i> {{ __('messages.recent_products') }}</h2>
            <a href="{{ route('admin.products.index') }}" class="view-all-link">
                {{ __('messages.view_all') }} <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($recent_products->count() > 0)
                <table class="recent-products-table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product_name') }}</th>
                            <th>{{ __('messages.category') }}</th>
                            <th>{{ __('messages.price') }}</th>
                            <th>{{ __('messages.stock') }}</th>
                            <th>{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_products as $product)
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name_en ?? $product->name }}</div>
                                        <div class="product-sku">{{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #3730a3; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 12px;">
                                    {{ $product->category->name_en ?? $product->category->name }}
                                </span>
                            </td>
                            <td>
                                <div class="price-cell">${{ number_format($product->price, 2) }}</div>
                            </td>
                            <td>
                                <span class="stock-cell {{ $product->stock_quantity > 10 ? 'good' : 'low' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>
                                <span class="status-pill {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                    <i class="fas {{ $product->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $product->is_active ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state-message">
                    <i class="fas fa-inbox"></i>
                    <p>{{ __('messages.no_products_yet') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-card">
        <div class="card-header">
            <h2><i class="fas fa-bolt"></i> {{ __('messages.quick_actions') }}</h2>
        </div>
        <ul class="quick-actions-list">
            <li>
                <a href="{{ route('admin.products.create') }}" class="quick-action-link">
                    <i class="fas fa-plus-circle"></i>
                    <span>{{ __('messages.add_new_product') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.create') }}" class="quick-action-link">
                    <i class="fas fa-folder-plus"></i>
                    <span>{{ __('messages.create_category') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.brands.create') }}" class="quick-action-link">
                    <i class="fas fa-tag"></i>
                    <span>{{ __('messages.add_new_brand') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="quick-action-link">
                    <i class="fas fa-list-ul"></i>
                    <span>{{ __('messages.manage_products') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="quick-action-link">
                    <i class="fas fa-th-large"></i>
                    <span>{{ __('messages.manage_categories') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.brands.index') }}" class="quick-action-link">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('messages.manage_brands') }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>

@endsection
