@extends('admin.layout')

@section('title', __('messages.user_details'))

@section('content')
<style>
    .user-profile-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.3);
    }

    .user-profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
    }

    .user-large-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        font-weight: 700;
        border: 4px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .user-profile-info h2 {
        margin: 0 0 8px 0;
        font-size: 32px;
        font-weight: 700;
    }

    .user-profile-info p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-profile-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .user-detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .user-detail-label {
        font-size: 13px;
        opacity: 0.8;
        font-weight: 500;
    }

    .user-detail-value {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stats-grid-user {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card-user {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .stat-card-user:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-content h3 {
        margin: 0 0 8px 0;
        font-size: 13px;
        color: var(--secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-content .value {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon-purple {
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        color: #6b21a8;
    }

    .stat-icon-green {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .stat-icon-blue {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .stat-icon-red {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .section-title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-title-wrapper h3 {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }

    .order-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border-left: 4px solid var(--primary);
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .order-number {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
    }

    .order-items-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .order-item img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
    }

    .order-item-info {
        flex: 1;
    }

    .order-item-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--dark);
    }

    .order-item-meta {
        font-size: 12px;
        color: var(--secondary);
    }

    [dir="rtl"] .stat-content h3 {
        text-transform: none;
        letter-spacing: normal;
    }

    [dir="rtl"] .order-card:hover {
        transform: translateX(-4px);
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-user"></i> {{ __('messages.user_details') }}</h1>
        <p>{{ __('messages.view_complete_user_information') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_users') }}
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> {{ __('messages.edit_user') }}
        </a>
    </div>
</div>

<!-- User Profile Card -->
<div class="user-profile-card">
    <div class="user-profile-header">
        <div class="user-large-avatar">
            {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
        </div>
        <div class="user-profile-info">
            <h2>{{ $user->name }}</h2>
            <p>
                <i class="fas fa-envelope"></i>
                {{ $user->email }}
            </p>
        </div>
    </div>

    <div class="user-profile-details">
        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.user_id') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-hashtag"></i>
                {{ $user->id }}
            </span>
        </div>

        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.phone') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-phone"></i>
                {{ $user->phone ?? __('messages.not_provided') }}
            </span>
        </div>

        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.role') }}</span>
            <span class="user-detail-value">
                <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : 'fa-user' }}"></i>
                {{ $user->role === 'admin' ? __('messages.admin') : __('messages.customer') }}
            </span>
        </div>

        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.registration_date') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-calendar-plus"></i>
                {{ $user->created_at->format('Y-m-d') }}
            </span>
        </div>

        @if($userStats['last_order_date'])
        <div class="user-detail-item">
            <span class="user-detail-label">{{ __('messages.last_order_date') }}</span>
            <span class="user-detail-value">
                <i class="fas fa-shopping-bag"></i>
                {{ $userStats['last_order_date']->format('Y-m-d') }}
            </span>
        </div>
        @endif
    </div>
</div>

<!-- User Statistics -->
<div class="stats-grid-user">
    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.total_orders') }}</h3>
            <div class="value">{{ $userStats['total_orders'] }}</div>
        </div>
        <div class="stat-icon stat-icon-purple">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.total_spent') }}</h3>
            <div class="value">${{ number_format($userStats['total_spent'], 2) }}</div>
        </div>
        <div class="stat-icon stat-icon-green">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.average_order') }}</h3>
            <div class="value">${{ number_format($userStats['average_order_value'], 2) }}</div>
        </div>
        <div class="stat-icon stat-icon-blue">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="stat-card-user">
        <div class="stat-content">
            <h3>{{ __('messages.favorites') }}</h3>
            <div class="value">{{ $userStats['total_favorites'] }}</div>
        </div>
        <div class="stat-icon stat-icon-red">
            <i class="fas fa-heart"></i>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header">
        <div class="section-title-wrapper">
            <h3>
                <i class="fas fa-history"></i>
                {{ __('messages.recent_orders') }}
            </h3>
            @if($recentOrders->count() > 0)
            <a href="{{ route('admin.orders.index', ['customer_id' => $user->id]) }}" class="btn btn-sm btn-primary">
                {{ __('messages.view_all_orders') }}
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($recentOrders->count() > 0)
            @foreach($recentOrders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-number">{{ __('messages.order') }} #{{ $order->order_number }}</span>
                        <br>
                        <small style="color: var(--secondary);">{{ $order->created_at->format('Y-m-d H:i') }}</small>
                    </div>
                    <div style="text-align: right;">
                        <span class="status-pill status-{{ strtolower($order->status) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <br>
                        <strong style="font-size: 18px; color: var(--success); display: block; margin-top: 4px;">
                            ${{ number_format($order->total, 2) }}
                        </strong>
                    </div>
                </div>

                <div class="order-items-list">
                    @foreach($order->items->take(3) as $item)
                        @if($item->product)
                        <div class="order-item">
                            <img src="{{ $item->product->main_image }}" alt="{{ $item->product->name }}">
                            <div class="order-item-info">
                                <div class="order-item-name">{{ $item->product->name_en ?? $item->product->name }}</div>
                                <div class="order-item-meta">
                                    {{ __('messages.quantity') }}: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                </div>
                            </div>
                            <strong style="color: var(--primary);">${{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </div>
                        @else
                        <div class="order-item">
                            <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-box" style="color: #64748b;"></i>
                            </div>
                            <div class="order-item-info">
                                <div class="order-item-name" style="color: #64748b; font-style: italic;">{{ __('messages.product_deleted') }}</div>
                                <div class="order-item-meta">
                                    {{ __('messages.quantity') }}: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                </div>
                            </div>
                            <strong style="color: var(--primary);">${{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </div>
                        @endif
                    @endforeach

                    @if($order->items->count() > 3)
                    <div style="text-align: center; padding: 8px; color: var(--secondary); font-size: 13px;">
                        {{ __('messages.and_more_items', ['count' => $order->items->count() - 3]) }}
                    </div>
                    @endif
                </div>

                <div style="text-align: right; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border);">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> {{ __('messages.view_order_details') }}
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state-message">
                <i class="fas fa-shopping-bag"></i>
                <p>{{ __('messages.user_has_no_orders_yet') }}</p>
            </div>
        @endif
    </div>
</div>

<!-- User Favorites (if any) -->
@if($user->favorites->count() > 0)
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3>
            <i class="fas fa-heart"></i>
            {{ __('messages.user_favorites') }} ({{ $user->favorites->count() }})
        </h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
            @foreach($user->favorites->take(8) as $favorite)
                @if($favorite->product)
                <div style="background: #f8fafc; border-radius: 8px; padding: 12px; text-align: center;">
                    <img src="{{ $favorite->product->main_image }}" alt="{{ $favorite->product->name }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">
                    <div style="font-weight: 600; font-size: 13px; color: var(--dark); margin-bottom: 4px;">
                        {{ Str::limit($favorite->product->name_en ?? $favorite->product->name, 30) }}
                    </div>
                    <div style="color: var(--success); font-weight: 700;">
                        ${{ number_format($favorite->product->price, 2) }}
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        @if($user->favorites->count() > 8)
        <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border);">
            <span style="color: var(--secondary);">
                {{ __('messages.and_more_favorites', ['count' => $user->favorites->count() - 8]) }}
            </span>
        </div>
        @endif
    </div>
</div>
@endif

@endsection

