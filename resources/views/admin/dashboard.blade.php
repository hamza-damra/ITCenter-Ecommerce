@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-pro" dir="{{ app()->getLocale() === 'ar' || app()->getLocale() === 'he' ? 'rtl' : 'ltr' }}">
    <!-- Welcome Hero -->
    <div class="welcome-hero">
        <div class="hero-content">
            <div class="welcome-text">
                <span class="greeting-time" id="greetingTime">{{ __('messages.welcome_back') }}</span>
                <h1>{{ __('messages.dashboard') }}</h1>
                <p>{{ __('messages.catalog_overview') }}</p>
            </div>
            <div class="hero-date">
                <div class="date-display">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ now()->format('l, F j, Y') }}</span>
                </div>
            </div>
        </div>
        <div class="hero-decoration">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="decoration-circle circle-3"></div>
        </div>
    </div>

    <!-- Primary Stats Row -->
    <div class="stats-section">
        <div class="section-label">
            <i class="fas fa-chart-pie"></i>
            <span>{{ __('messages.store_overview') }}</span>
        </div>
        <div class="primary-stats">
            <div class="stat-card stat-products">
                <div class="stat-icon">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['total_products'] }}</span>
                    <span class="stat-label">{{ __('messages.total_products') }}</span>
                    <span class="stat-sub">{{ __('messages.complete_inventory') }}</span>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
            </div>

            <div class="stat-card stat-active">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['active_products'] }}</span>
                    <span class="stat-label">{{ __('messages.active_products') }}</span>
                    <span class="stat-sub">{{ __('messages.currently_visible') }}</span>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-eye"></i>
                </div>
            </div>

            <div class="stat-card stat-categories">
                <div class="stat-icon">
                    <i class="fas fa-folder-tree"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['total_categories'] }}</span>
                    <span class="stat-label">{{ __('messages.total_categories') }}</span>
                    <span class="stat-sub">{{ __('messages.organize_products') }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>

            <div class="stat-card stat-brands">
                <div class="stat-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['total_brands'] }}</span>
                    <span class="stat-label">{{ __('messages.total_brands') }}</span>
                    <span class="stat-sub">{{ __('messages.in_your_store') }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-tag"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Grid -->
    <div class="secondary-stats">
        <div class="mini-stat featured">
            <div class="mini-stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="mini-stat-content">
                <span class="mini-stat-value">{{ $stats['featured_products'] }}</span>
                <span class="mini-stat-label">{{ __('messages.featured_products_count') }}</span>
            </div>
            <span class="mini-stat-badge">{{ __('messages.promoted_items') }}</span>
        </div>

        <div class="mini-stat danger">
            <div class="mini-stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="mini-stat-content">
                <span class="mini-stat-value">{{ $stats['out_of_stock'] }}</span>
                <span class="mini-stat-label">{{ __('messages.out_of_stock_count') }}</span>
            </div>
            <span class="mini-stat-badge alert">{{ __('messages.need_attention') }}</span>
        </div>

        <div class="mini-stat reviews">
            <div class="mini-stat-icon">
                <i class="fas fa-comments"></i>
            </div>
            <div class="mini-stat-content">
                <span class="mini-stat-value">{{ $stats['total_reviews'] }}</span>
                <span class="mini-stat-label">{{ __('messages.total_reviews') }}</span>
            </div>
            <span class="mini-stat-badge">{{ __('messages.customer_feedback') }}</span>
        </div>

        <div class="mini-stat offers">
            <div class="mini-stat-icon">
                <i class="fas fa-percent"></i>
            </div>
            <div class="mini-stat-content">
                <span class="mini-stat-value">{{ $stats['active_offers'] }}</span>
                <span class="mini-stat-label">{{ __('messages.active_offers') }}</span>
            </div>
            <span class="mini-stat-badge">{{ __('messages.running_campaigns') }}</span>
        </div>
    </div>

    <!-- User Statistics Section -->
    <div class="stats-section users-section">
        <div class="section-header-pro">
            <div class="section-label">
                <i class="fas fa-users"></i>
                <span>{{ __('messages.user_statistics') }}</span>
            </div>
            <div class="online-indicator">
                <span class="pulse-dot"></span>
                <span>{{ $stats['registered_online_users'] }} {{ __('messages.online_now') }}</span>
            </div>
        </div>

        <div class="user-stats-grid">
            <!-- Main User Card -->
            <div class="user-main-card">
                <div class="user-main-icon">
                    <i class="fas fa-user-group"></i>
                </div>
                <div class="user-main-info">
                    <span class="user-main-value">{{ $stats['total_users'] }}</span>
                    <span class="user-main-label">{{ __('messages.total_users') }}</span>
                </div>
                <div class="user-breakdown">
                    <div class="breakdown-item online">
                        <i class="fas fa-circle"></i>
                        <span>{{ $stats['registered_online_users'] }} {{ __('messages.online') }}</span>
                    </div>
                    <div class="breakdown-item offline">
                        <i class="fas fa-circle"></i>
                        <span>{{ $stats['registered_offline_users'] }} {{ __('messages.offline') }}</span>
                    </div>
                </div>
            </div>

            <!-- User Stats Cards -->
            <div class="user-stat-card">
                <div class="user-stat-header">
                    <i class="fas fa-user-secret"></i>
                    <span class="user-stat-value">{{ $stats['guest_active_sessions'] }}</span>
                </div>
                <span class="user-stat-label">{{ __('messages.guest_sessions') }}</span>
                <span class="user-stat-sub">{{ __('messages.non_registered_shoppers') }}</span>
            </div>

            <div class="user-stat-card">
                <div class="user-stat-header">
                    <i class="fas fa-user-shield"></i>
                    <span class="user-stat-value">{{ $stats['admin_users'] }}</span>
                </div>
                <span class="user-stat-label">{{ __('messages.admin_users') }}</span>
                <span class="user-stat-sub">{{ __('messages.admin_accounts') }}</span>
            </div>

            <div class="user-stat-card highlight">
                <div class="user-stat-header">
                    <i class="fas fa-calendar-week"></i>
                    <span class="user-stat-value">{{ $stats['users_this_week'] }}</span>
                </div>
                <span class="user-stat-label">{{ __('messages.new_this_week') }}</span>
                <span class="user-stat-sub">{{ __('messages.weekly_signups') }}</span>
            </div>

            <div class="user-stat-card">
                <div class="user-stat-header">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="user-stat-value">{{ $stats['users_this_month'] }}</span>
                </div>
                <span class="user-stat-label">{{ __('messages.new_this_month') }}</span>
                <span class="user-stat-sub">{{ __('messages.monthly_signups') }}</span>
            </div>

            <div class="user-stat-card">
                <div class="user-stat-header">
                    <i class="fas fa-chart-line"></i>
                    <span class="user-stat-value">{{ $stats['active_users_30days'] }}</span>
                </div>
                <span class="user-stat-label">{{ __('messages.active_30days') }}</span>
                <span class="user-stat-sub">{{ __('messages.recent_activity') }}</span>
            </div>
        </div>

        <!-- User Activity Metrics -->
        <div class="user-metrics">
            <div class="metric-card">
                <div class="metric-icon orders">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ $stats['users_with_orders'] }}</span>
                    <span class="metric-label">{{ __('messages.with_orders') }}</span>
                </div>
                <div class="metric-bar">
                    <div class="metric-fill" style="width: {{ $stats['total_users'] > 0 ? ($stats['users_with_orders'] / $stats['total_users']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon favorites">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ $stats['users_with_favorites'] }}</span>
                    <span class="metric-label">{{ __('messages.with_favorites') }}</span>
                </div>
                <div class="metric-bar">
                    <div class="metric-fill" style="width: {{ $stats['total_users'] > 0 ? ($stats['users_with_favorites'] / $stats['total_users']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon reviews">
                    <i class="fas fa-star"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-value">{{ $stats['users_with_reviews'] }}</span>
                    <span class="metric-label">{{ __('messages.with_reviews') }}</span>
                </div>
                <div class="metric-bar">
                    <div class="metric-fill" style="width: {{ $stats['total_users'] > 0 ? ($stats['users_with_reviews'] / $stats['total_users']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Recent Products -->
        <div class="content-card products-table-card">
            <div class="card-header-pro">
                <div class="header-title">
                    <i class="fas fa-clock"></i>
                    <h2>{{ __('messages.recent_products') }}</h2>
                </div>
                <a href="{{ route('admin.products.index', ['filter' => 'recent']) }}" class="view-all-btn">
                    {{ __('messages.view_all') }}
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body-pro">
                @if($recent_products->count() > 0)
                <div class="table-wrapper">
                    <table class="pro-table">
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
                                    <div class="product-cell-pro">
                                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                                        <div class="product-details">
                                            <span class="product-name-pro">{{ $product->name_en ?? $product->name }}</span>
                                            <span class="product-sku-pro">{{ $product->sku }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($product->category)
                                    <span class="category-badge">{{ $product->category->name_en ?? $product->category->name }}</span>
                                    @else
                                    <span class="category-badge empty">{{ __('messages.no_category') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="price-pro">₪{{ number_format($product->price, 2) }}</span>
                                </td>
                                <td>
                                    <span class="stock-badge {{ $product->stock_quantity > 10 ? 'good' : ($product->stock_quantity > 0 ? 'low' : 'out') }}">
                                        {{ $product->stock_quantity }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                                        <i class="fas {{ $product->is_active ? 'fa-check' : 'fa-times' }}"></i>
                                        {{ $product->is_active ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>{{ __('messages.no_products_yet') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="content-card quick-actions-card">
            <div class="card-header-pro accent">
                <div class="header-title">
                    <i class="fas fa-bolt"></i>
                    <h2>{{ __('messages.quick_actions') }}</h2>
                </div>
            </div>
            <div class="card-body-pro no-padding">
                <div class="quick-actions-grid">
                    <a href="{{ route('admin.products.create') }}" class="quick-action">
                        <div class="action-icon add-product">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span class="action-label">{{ __('messages.add_new_product') }}</span>
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="quick-action">
                        <div class="action-icon add-category">
                            <i class="fas fa-folder-plus"></i>
                        </div>
                        <span class="action-label">{{ __('messages.create_category') }}</span>
                    </a>
                    <a href="{{ route('admin.brands.create') }}" class="quick-action">
                        <div class="action-icon add-brand">
                            <i class="fas fa-tag"></i>
                        </div>
                        <span class="action-label">{{ __('messages.add_new_brand') }}</span>
                    </a>
                    <a href="{{ route('admin.promotional-offers.create') }}" class="quick-action">
                        <div class="action-icon add-offer">
                            <i class="fas fa-percent"></i>
                        </div>
                        <span class="action-label">{{ __('messages.add_new_offer') }}</span>
                    </a>
                </div>
                <div class="quick-links">
                    <a href="{{ route('admin.products.index') }}" class="quick-link">
                        <i class="fas fa-list"></i>
                        {{ __('messages.manage_products') }}
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="quick-link">
                        <i class="fas fa-folder"></i>
                        {{ __('messages.manage_categories') }}
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="quick-link">
                        <i class="fas fa-tags"></i>
                        {{ __('messages.manage_brands') }}
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="quick-link">
                        <i class="fas fa-shopping-bag"></i>
                        {{ __('messages.view_orders') }}
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="analytics-section">
        <div class="section-label">
            <i class="fas fa-chart-bar"></i>
            <span>{{ __('messages.analytics_overview') }}</span>
        </div>
        
        <div class="analytics-grid">
            <div class="analytics-card cart">
                <div class="analytics-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">₪{{ number_format($stats['cart_value'] ?? 0, 2) }}</span>
                    <span class="analytics-label">{{ __('messages.total_cart_value') }}</span>
                    <span class="analytics-sub">{{ $stats['active_carts'] ?? 0 }} {{ __('messages.active_carts') }}</span>
                </div>
            </div>

            <div class="analytics-card rating">
                <div class="analytics-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">
                        {{ number_format($stats['average_rating'] ?? 0, 1) }}
                        <span class="rating-star"><i class="fas fa-star"></i></span>
                    </span>
                    <span class="analytics-label">{{ __('messages.average_rating') }}</span>
                    <span class="analytics-sub">{{ __('messages.from') }} {{ $stats['total_reviews'] ?? 0 }} {{ __('messages.reviews') }}</span>
                </div>
            </div>

            <div class="analytics-card stock">
                <div class="analytics-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">₪{{ number_format($stats['total_stock_value'] ?? 0, 0) }}</span>
                    <span class="analytics-label">{{ __('messages.total_stock_value') }}</span>
                    <span class="analytics-sub">{{ __('messages.inventory_value') }}</span>
                </div>
            </div>

            <div class="analytics-card favorites">
                <div class="analytics-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">{{ $stats['total_favorites'] ?? 0 }}</span>
                    <span class="analytics-label">{{ __('messages.total_favorites') }}</span>
                    <span class="analytics-sub">{{ __('messages.customer_wishlists') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Grid: Top Rated & Low Stock -->
    <div class="content-grid bottom-grid">
        <!-- Top Rated Products -->
        <div class="content-card">
            <div class="card-header-pro">
                <div class="header-title">
                    <i class="fas fa-trophy"></i>
                    <h2>{{ __('messages.top_rated_products') }}</h2>
                </div>
                <a href="{{ route('admin.products.index', ['filter' => 'top_rated']) }}" class="view-all-btn">
                    {{ __('messages.view_all') }}
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="card-body-pro">
                @if(isset($top_rated_products) && $top_rated_products->count() > 0)
                <div class="rated-products-list">
                    @foreach($top_rated_products as $index => $product)
                    <div class="rated-product-item">
                        <span class="rank-badge">{{ $index + 1 }}</span>
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                        <div class="rated-product-info">
                            <span class="rated-product-name">{{ $product->name_en ?? $product->name }}</span>
                            <div class="rating-display">
                                <span class="rating-value">{{ number_format($product->reviews_avg_rating, 1) }}</span>
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($product->reviews_avg_rating) ? 'filled' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="reviews-count">({{ $product->reviews_count }})</span>
                            </div>
                        </div>
                        <span class="rated-product-price">₪{{ number_format($product->price, 2) }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <p>{{ __('messages.no_rated_products_yet') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="content-card alerts-card">
            <div class="card-header-pro warning">
                <div class="header-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h2>{{ __('messages.low_stock_alerts') }}</h2>
                </div>
                @if(isset($low_stock_products) && $low_stock_products->count() > 0)
                <span class="alert-count">{{ $low_stock_products->count() }}</span>
                @endif
            </div>
            <div class="card-body-pro">
                @if(isset($low_stock_products) && $low_stock_products->count() > 0)
                <div class="alerts-list">
                    @foreach($low_stock_products as $product)
                    <div class="alert-item {{ $product->stock_quantity == 0 ? 'critical' : 'warning' }}">
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                        <div class="alert-info">
                            <span class="alert-product-name">{{ $product->name_en ?? $product->name }}</span>
                            <span class="alert-category">
                                @if($product->category)
                                {{ $product->category->name_en ?? $product->category->name }}
                                @else
                                {{ __('messages.no_category') }}
                                @endif
                            </span>
                        </div>
                        <div class="alert-stock">
                            <span class="stock-number">{{ $product->stock_quantity ?? 0 }}</span>
                            <span class="stock-label">{{ __('messages.in_stock') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state success">
                    <i class="fas fa-check-circle"></i>
                    <p>{{ __('messages.all_products_well_stocked') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   PROFESSIONAL DASHBOARD STYLES
   ============================================ */

.dashboard-pro {
    --accent-blue: #0ea5e9;
    --accent-indigo: #6366f1;
    --accent-emerald: #10b981;
    --accent-amber: #f59e0b;
    --accent-rose: #f43f5e;
    --accent-violet: #8b5cf6;
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-muted: #94a3b8;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
}

/* Welcome Hero */
.welcome-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    border-radius: var(--radius-xl);
    padding: 2.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.hero-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.welcome-text .greeting-time {
    display: inline-block;
    background: rgba(14, 165, 233, 0.2);
    color: var(--accent-blue);
    padding: 0.375rem 0.875rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}

.welcome-text h1 {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.welcome-text p {
    color: #94a3b8;
    font-size: 1rem;
    margin: 0;
}

.hero-date {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1.25rem;
    border-radius: var(--radius-md);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.date-display {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
    font-size: 0.875rem;
    font-weight: 500;
}

.date-display i {
    color: var(--accent-blue);
}

.hero-decoration {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 50%;
    overflow: hidden;
}

.decoration-circle {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(99, 102, 241, 0.1) 100%);
}

.circle-1 {
    width: 300px;
    height: 300px;
    top: -100px;
    right: -50px;
}

.circle-2 {
    width: 200px;
    height: 200px;
    bottom: -50px;
    right: 100px;
}

.circle-3 {
    width: 150px;
    height: 150px;
    top: 50%;
    right: 30%;
    transform: translateY(-50%);
}

/* Stats Section */
.stats-section {
    margin-bottom: 1.5rem;
}

.section-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.section-label i {
    font-size: 0.875rem;
    color: var(--accent-blue);
}

/* Primary Stats */
.primary-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}

@media (max-width: 1200px) {
    .primary-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .primary-stats {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.stat-products::before { background: linear-gradient(90deg, var(--accent-blue), var(--accent-indigo)); }
.stat-card.stat-active::before { background: linear-gradient(90deg, var(--accent-emerald), #34d399); }
.stat-card.stat-categories::before { background: linear-gradient(90deg, var(--accent-amber), #fbbf24); }
.stat-card.stat-brands::before { background: linear-gradient(90deg, var(--accent-violet), #a78bfa); }

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-products .stat-icon {
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);
    color: var(--accent-blue);
}

.stat-active .stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(52, 211, 153, 0.15) 100%);
    color: var(--accent-emerald);
}

.stat-categories .stat-icon {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.15) 100%);
    color: var(--accent-amber);
}

.stat-brands .stat-icon {
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(167, 139, 250, 0.15) 100%);
    color: var(--accent-violet);
}

.stat-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
}

.stat-sub {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

.stat-trend {
    color: var(--text-muted);
    font-size: 1rem;
}

/* Secondary Stats (Mini Cards) */
.secondary-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (max-width: 1200px) {
    .secondary-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .secondary-stats {
        grid-template-columns: 1fr;
    }
}

.mini-stat {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    transition: all 0.3s ease;
}

.mini-stat:hover {
    border-color: var(--border-color);
    box-shadow: var(--shadow-md);
}

.mini-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

.mini-stat.featured .mini-stat-icon {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.15) 100%);
    color: var(--accent-amber);
}

.mini-stat.danger .mini-stat-icon {
    background: linear-gradient(135deg, rgba(244, 63, 94, 0.15) 0%, rgba(251, 113, 133, 0.15) 100%);
    color: var(--accent-rose);
}

.mini-stat.reviews .mini-stat-icon {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%);
    color: var(--accent-indigo);
}

.mini-stat.offers .mini-stat-icon {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(52, 211, 153, 0.15) 100%);
    color: var(--accent-emerald);
}

.mini-stat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.mini-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
}

.mini-stat-label {
    font-size: 0.8125rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.mini-stat-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    background: var(--bg-tertiary);
    padding: 0.25rem 0.5rem;
    border-radius: 100px;
}

.mini-stat-badge.alert {
    background: rgba(244, 63, 94, 0.1);
    color: var(--accent-rose);
}

/* User Statistics Section */
.users-section {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.section-header-pro {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.online-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--accent-emerald);
    background: rgba(16, 185, 129, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 100px;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background: var(--accent-emerald);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.user-stats-grid {
    display: grid;
    grid-template-columns: 2fr repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 1200px) {
    .user-stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .user-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .user-stats-grid {
        grid-template-columns: 1fr;
    }
}

/* Main User Card */
.user-main-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    grid-row: span 2;
}

@media (max-width: 1200px) {
    .user-main-card {
        grid-row: span 1;
        grid-column: span 3;
    }
}

@media (max-width: 768px) {
    .user-main-card {
        grid-column: span 2;
    }
}

@media (max-width: 480px) {
    .user-main-card {
        grid-column: span 1;
    }
}

.user-main-icon {
    width: 48px;
    height: 48px;
    background: rgba(14, 165, 233, 0.2);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-blue);
    font-size: 1.25rem;
}

.user-main-info {
    display: flex;
    flex-direction: column;
}

.user-main-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.user-main-label {
    font-size: 0.875rem;
    color: #94a3b8;
    font-weight: 500;
}

.user-breakdown {
    display: flex;
    gap: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.breakdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8125rem;
    color: #94a3b8;
}

.breakdown-item i {
    font-size: 0.5rem;
}

.breakdown-item.online i {
    color: var(--accent-emerald);
}

.breakdown-item.offline i {
    color: #64748b;
}

/* User Stat Cards */
.user-stat-card {
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.user-stat-card:hover {
    background: var(--bg-tertiary);
}

.user-stat-card.highlight {
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    border: 1px solid rgba(14, 165, 233, 0.2);
}

.user-stat-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-stat-header i {
    font-size: 1rem;
    color: var(--text-muted);
}

.user-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

.user-stat-label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-primary);
}

.user-stat-sub {
    font-size: 0.6875rem;
    color: var(--text-muted);
}

/* User Metrics */
.user-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

@media (max-width: 768px) {
    .user-metrics {
        grid-template-columns: 1fr;
    }
}

.metric-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    padding: 1rem 1.25rem;
}

.metric-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.metric-icon.orders {
    background: rgba(14, 165, 233, 0.15);
    color: var(--accent-blue);
}

.metric-icon.favorites {
    background: rgba(244, 63, 94, 0.15);
    color: var(--accent-rose);
}

.metric-icon.reviews {
    background: rgba(245, 158, 11, 0.15);
    color: var(--accent-amber);
}

.metric-info {
    flex: 1;
}

.metric-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
}

.metric-label {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.metric-bar {
    width: 60px;
    height: 6px;
    background: var(--border-color);
    border-radius: 100px;
    overflow: hidden;
}

.metric-fill {
    height: 100%;
    background: var(--accent-blue);
    border-radius: 100px;
    transition: width 1s ease;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

.bottom-grid {
    grid-template-columns: 1fr 1fr;
}

@media (max-width: 768px) {
    .bottom-grid {
        grid-template-columns: 1fr;
    }
}

/* Content Card */
.content-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.card-header-pro {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
}

.card-header-pro.accent {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-bottom: none;
}

.card-header-pro.accent .header-title {
    color: white;
}

.card-header-pro.accent .header-title i {
    color: var(--accent-amber);
}

.card-header-pro.warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
}

.card-header-pro.warning .header-title {
    color: #92400e;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-title i {
    font-size: 1.125rem;
    color: var(--accent-blue);
}

.header-title h2 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.view-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--accent-blue);
    text-decoration: none;
    padding: 0.5rem 1rem;
    background: rgba(14, 165, 233, 0.1);
    border-radius: var(--radius-sm);
    transition: all 0.3s ease;
}

.view-all-btn:hover {
    background: var(--accent-blue);
    color: white;
}

.card-body-pro {
    padding: 1.5rem;
}

.card-body-pro.no-padding {
    padding: 0;
}

/* Pro Table */
.table-wrapper {
    overflow-x: auto;
}

.pro-table {
    width: 100%;
    border-collapse: collapse;
}

.pro-table th {
    text-align: left;
    padding: 0.875rem 1rem;
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
}

.pro-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--bg-tertiary);
    vertical-align: middle;
}

.pro-table tbody tr {
    transition: background 0.2s ease;
}

.pro-table tbody tr:hover {
    background: var(--bg-secondary);
}

.pro-table tbody tr:last-child td {
    border-bottom: none;
}

.product-cell-pro {
    display: flex;
    align-items: center;
    gap: 0.875rem;
}

.product-cell-pro img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-color);
}

.product-details {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.product-name-pro {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-sku-pro {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.category-badge {
    display: inline-block;
    background: rgba(99, 102, 241, 0.1);
    color: var(--accent-indigo);
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
}

.category-badge.empty {
    background: var(--bg-tertiary);
    color: var(--text-muted);
}

.price-pro {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--accent-emerald);
}

.stock-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.8125rem;
    font-weight: 700;
}

.stock-badge.good {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-emerald);
}

.stock-badge.low {
    background: rgba(245, 158, 11, 0.1);
    color: var(--accent-amber);
}

.stock-badge.out {
    background: rgba(244, 63, 94, 0.1);
    color: var(--accent-rose);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-badge.active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-emerald);
}

.status-badge.inactive {
    background: rgba(244, 63, 94, 0.1);
    color: var(--accent-rose);
}

/* Quick Actions */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1px;
    background: var(--border-color);
}

.quick-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem 1rem;
    background: var(--bg-primary);
    text-decoration: none;
    transition: all 0.3s ease;
}

.quick-action:hover {
    background: var(--bg-secondary);
}

.quick-action:hover .action-icon {
    transform: scale(1.1);
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    transition: transform 0.3s ease;
}

.action-icon.add-product {
    background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-indigo) 100%);
    color: white;
}

.action-icon.add-category {
    background: linear-gradient(135deg, var(--accent-amber) 0%, #fbbf24 100%);
    color: white;
}

.action-icon.add-brand {
    background: linear-gradient(135deg, var(--accent-violet) 0%, #a78bfa 100%);
    color: white;
}

.action-icon.add-offer {
    background: linear-gradient(135deg, var(--accent-emerald) 0%, #34d399 100%);
    color: white;
}

.action-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-primary);
    text-align: center;
}

.quick-links {
    border-top: 1px solid var(--border-color);
}

.quick-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    text-decoration: none;
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    border-bottom: 1px solid var(--bg-tertiary);
    transition: all 0.3s ease;
}

.quick-link:last-child {
    border-bottom: none;
}

.quick-link:hover {
    background: var(--bg-secondary);
    color: var(--accent-blue);
    padding-left: 1.5rem;
}

[dir="rtl"] .quick-link:hover {
    padding-left: 1.25rem;
    padding-right: 1.5rem;
}

.quick-link i:first-child {
    width: 20px;
    text-align: center;
    color: var(--text-muted);
}

.quick-link i:last-child {
    margin-left: auto;
    font-size: 0.75rem;
    color: var(--text-muted);
}

[dir="rtl"] .quick-link i:last-child {
    margin-left: 0;
    margin-right: auto;
    transform: rotate(180deg);
}

/* Analytics Section */
.analytics-section {
    margin-bottom: 2rem;
}

.analytics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}

@media (max-width: 1200px) {
    .analytics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .analytics-grid {
        grid-template-columns: 1fr;
    }
}

.analytics-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    transition: all 0.3s ease;
}

.analytics-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.analytics-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

.analytics-card.cart .analytics-icon {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%);
    color: var(--accent-indigo);
}

.analytics-card.rating .analytics-icon {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(251, 191, 36, 0.15) 100%);
    color: var(--accent-amber);
}

.analytics-card.stock .analytics-icon {
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%);
    color: var(--accent-blue);
}

.analytics-card.favorites .analytics-icon {
    background: linear-gradient(135deg, rgba(244, 63, 94, 0.15) 0%, rgba(251, 113, 133, 0.15) 100%);
    color: var(--accent-rose);
}

.analytics-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.analytics-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-star {
    color: var(--accent-amber);
    font-size: 1rem;
}

.analytics-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
}

.analytics-sub {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Rated Products List */
.rated-products-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.rated-product-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem;
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
}

.rated-product-item:hover {
    background: var(--bg-tertiary);
}

.rank-badge {
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, var(--accent-amber) 0%, #fbbf24 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.rated-product-item img {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-color);
}

.rated-product-info {
    flex: 1;
    min-width: 0;
}

.rated-product-name {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.25rem;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-value {
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--accent-amber);
}

.stars {
    display: flex;
    gap: 2px;
}

.stars i {
    font-size: 0.625rem;
    color: var(--border-color);
}

.stars i.filled {
    color: var(--accent-amber);
}

.reviews-count {
    font-size: 0.6875rem;
    color: var(--text-muted);
}

.rated-product-price {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--accent-emerald);
}

/* Alerts List */
.alert-count {
    background: var(--accent-rose);
    color: white;
    padding: 0.25rem 0.625rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 700;
}

.alerts-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.alert-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem;
    border-radius: var(--radius-md);
    border-left: 4px solid;
}

.alert-item.warning {
    background: rgba(245, 158, 11, 0.08);
    border-left-color: var(--accent-amber);
}

.alert-item.critical {
    background: rgba(244, 63, 94, 0.08);
    border-left-color: var(--accent-rose);
}

.alert-item img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-color);
}

.alert-info {
    flex: 1;
    min-width: 0;
}

.alert-product-name {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.alert-category {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.alert-stock {
    text-align: center;
}

.stock-number {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
}

.alert-item.warning .stock-number {
    color: var(--accent-amber);
}

.alert-item.critical .stock-number {
    color: var(--accent-rose);
}

.stock-label {
    font-size: 0.625rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

/* Empty State */
.empty-state {
    padding: 3rem 1.5rem;
    text-align: center;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    display: block;
    opacity: 0.4;
}

.empty-state p {
    margin: 0;
    font-size: 0.9375rem;
}

.empty-state.success {
    color: var(--accent-emerald);
}

.empty-state.success i {
    opacity: 0.6;
}

/* RTL Adjustments */
[dir="rtl"] .welcome-text {
    text-align: right;
}

[dir="rtl"] .hero-decoration {
    right: auto;
    left: 0;
}

[dir="rtl"] .pro-table th,
[dir="rtl"] .pro-table td {
    text-align: right;
}

[dir="rtl"] .alert-item {
    border-left: none;
    border-right: 4px solid;
}

[dir="rtl"] .alert-item.warning {
    border-right-color: var(--accent-amber);
}

[dir="rtl"] .alert-item.critical {
    border-right-color: var(--accent-rose);
}

/* RTL for Primary Stats Cards */
[dir="rtl"] .primary-stats {
    direction: rtl;
}

[dir="rtl"] .stat-card {
    flex-direction: row-reverse;
    text-align: right;
}

[dir="rtl"] .stat-info {
    text-align: right;
    align-items: flex-end;
}

[dir="rtl"] .stat-trend {
    margin-left: 0;
    margin-right: auto;
}

/* RTL for Secondary Stats (Mini Stats) */
[dir="rtl"] .secondary-stats {
    direction: rtl;
}

[dir="rtl"] .mini-stat {
    flex-direction: row-reverse;
    text-align: right;
}

[dir="rtl"] .mini-stat-content {
    text-align: right;
    align-items: flex-end;
}

[dir="rtl"] .mini-stat-badge {
    margin-left: 0;
    margin-right: auto;
}

/* RTL for User Stats */
[dir="rtl"] .user-stats-grid {
    direction: rtl;
}

[dir="rtl"] .user-main-card {
    text-align: right;
}

[dir="rtl"] .user-main-info {
    text-align: right;
}

[dir="rtl"] .user-stat-card {
    text-align: right;
}

[dir="rtl"] .user-stat-header {
    flex-direction: row-reverse;
}

[dir="rtl"] .breakdown-item {
    flex-direction: row-reverse;
}

/* RTL for Analytics Cards */
[dir="rtl"] .analytics-grid {
    direction: rtl;
}

[dir="rtl"] .analytics-card {
    flex-direction: row-reverse;
    text-align: right;
}

[dir="rtl"] .analytics-content {
    text-align: right;
    align-items: flex-end;
}

/* RTL for Metric Cards */
[dir="rtl"] .user-metrics {
    direction: rtl;
}

[dir="rtl"] .metric-card {
    flex-direction: row-reverse;
    text-align: right;
}

[dir="rtl"] .metric-info {
    text-align: right;
}

/* RTL for Section Labels */
[dir="rtl"] .section-label {
    flex-direction: row-reverse;
}

[dir="rtl"] .section-header-pro {
    flex-direction: row-reverse;
}

[dir="rtl"] .online-indicator {
    flex-direction: row-reverse;
}

/* RTL for Card Headers */
[dir="rtl"] .card-header-pro {
    flex-direction: row-reverse;
}

[dir="rtl"] .header-title {
    flex-direction: row-reverse;
}

[dir="rtl"] .view-all-btn {
    flex-direction: row-reverse;
}

[dir="rtl"] .view-all-btn i {
    transform: rotate(180deg);
}

/* RTL for Content Grid */
[dir="rtl"] .content-grid {
    direction: rtl;
}

/* RTL for Quick Actions */
[dir="rtl"] .quick-actions-grid {
    direction: rtl;
}

[dir="rtl"] .quick-links {
    direction: rtl;
}

/* RTL for Rated Products */
[dir="rtl"] .rated-product-item {
    flex-direction: row-reverse;
    text-align: right;
}

[dir="rtl"] .rated-product-info {
    text-align: right;
}

[dir="rtl"] .rating-display {
    flex-direction: row-reverse;
}

/* RTL for Alert Items */
[dir="rtl"] .alert-info {
    text-align: right;
}

[dir="rtl"] .alert-stock {
    text-align: right;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic greeting based on time
    const hour = new Date().getHours();
    const greetingEl = document.getElementById('greetingTime');
    let greeting = '{{ __("messages.welcome_back") }}';
    
    if (hour < 12) {
        greeting = '{{ __("messages.good_morning") }}';
    } else if (hour < 18) {
        greeting = '{{ __("messages.good_afternoon") }}';
    } else {
        greeting = '{{ __("messages.good_evening") }}';
    }
    
    if (greetingEl) {
        greetingEl.textContent = greeting;
    }

    // Animate stat values
    document.querySelectorAll('.stat-value, .mini-stat-value, .user-main-value, .user-stat-value, .analytics-value').forEach(el => {
        const value = el.textContent;
        if (!isNaN(parseInt(value))) {
            el.style.opacity = '0';
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '1';
            }, 100);
        }
    });
});
</script>
@endsection
