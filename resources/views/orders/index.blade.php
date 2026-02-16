@extends('layouts.app')

@section('title', __t('messages.my_orders') . ' - IT Center')

@section('content')
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
        {{ is_rtl() ? 'left' : 'right' }}: -10%;
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

    /* Responsive - Tablet */
    @media (max-width: 968px) {
        .orders-page {
            padding: 1.5rem 1rem;
        }

        .page-header {
            padding: 2rem 1.5rem;
            border-radius: 15px;
        }

        .page-title {
            font-size: 2rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .status-tabs {
            padding: 1rem;
            border-radius: 12px;
        }

        .status-tab {
            padding: 0.6rem 1.25rem;
            font-size: 0.9rem;
        }

        .order-card {
            border-radius: 12px;
        }

        .order-header {
            padding: 1.25rem;
        }

        .order-items {
            padding: 1.25rem;
        }

        .order-footer {
            padding: 1.25rem;
        }
    }

    /* Responsive - Phone */
    @media (max-width: 768px) {
        .orders-page {
            padding: 1rem 0.75rem;
        }

        .page-header {
            padding: 1.5rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .page-header::before {
            width: 200px;
            height: 200px;
        }

        .page-title {
            font-size: 1.5rem;
            gap: 0.75rem;
        }

        .page-title i {
            font-size: 1.3rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        /* Status Tabs Mobile */
        .status-tabs {
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
        }

        .status-tabs-list {
            gap: 0.5rem;
            padding-bottom: 0.25rem;
        }

        .status-tab {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border-radius: 25px;
        }

        .status-tab i {
            font-size: 0.85rem;
        }

        .status-count {
            padding: 0.15rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Order Card Mobile */
        .orders-container {
            gap: 1rem;
        }

        .order-card {
            border-radius: 10px;
        }

        .order-header {
            padding: 1rem;
        }

        .order-header-top {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .order-number {
            font-size: 1.1rem;
        }

        .order-status-badge {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }

        .order-header-bottom {
            gap: 1rem;
            font-size: 0.8rem;
        }

        .order-info-item {
            gap: 0.35rem;
        }

        .order-info-item i {
            font-size: 0.85rem;
        }

        /* Order Items Mobile */
        .order-items {
            padding: 1rem;
        }

        .order-item {
            flex-direction: row;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
        }

        .item-details {
            gap: 0.35rem;
            min-width: 0;
            flex: 1;
        }

        .item-name {
            font-size: 0.9rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-info {
            font-size: 0.8rem;
            gap: 0.5rem;
        }

        .item-price {
            align-items: flex-end;
            flex-shrink: 0;
        }

        .current-price {
            font-size: 0.95rem;
        }

        .original-price {
            font-size: 0.8rem;
        }

        /* Order Footer Mobile */
        .order-footer {
            padding: 1rem;
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .order-total {
            text-align: center;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-total-label {
            font-size: 0.85rem;
        }

        .order-total-amount {
            font-size: 1.5rem;
        }

        .order-actions {
            width: 100%;
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        /* Empty State Mobile */
        .empty-state {
            padding: 2.5rem 1.5rem;
            border-radius: 12px;
        }

        .empty-state-icon {
            font-size: 3.5rem;
        }

        .empty-state-title {
            font-size: 1.35rem;
        }

        .empty-state-text {
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }
    }

    /* Responsive - Small Phone */
    @media (max-width: 480px) {
        .orders-page {
            padding: 0.75rem 0.5rem;
        }

        .page-header {
            padding: 1.25rem 1rem;
            margin-bottom: 1rem;
        }

        .page-title {
            font-size: 1.25rem;
            gap: 0.5rem;
        }

        .page-title i {
            font-size: 1.1rem;
        }

        .page-subtitle {
            font-size: 0.8rem;
        }

        /* Status Tabs Small Phone */
        .status-tabs {
            padding: 0.5rem;
            margin-bottom: 1rem;
        }

        .status-tab {
            padding: 0.4rem 0.75rem;
            font-size: 0.75rem;
            gap: 0.35rem;
        }

        .status-tab i {
            font-size: 0.75rem;
        }

        .status-count {
            padding: 0.1rem 0.4rem;
            font-size: 0.7rem;
        }

        /* Order Card Small Phone */
        .order-header {
            padding: 0.875rem;
        }

        .order-number {
            font-size: 1rem;
        }

        .order-status-badge {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
        }

        .order-header-bottom {
            gap: 0.75rem;
            font-size: 0.75rem;
            flex-direction: column;
        }

        .order-items {
            padding: 0.875rem;
        }

        .order-item {
            padding: 0.5rem;
            gap: 0.5rem;
        }

        .item-image {
            width: 50px;
            height: 50px;
        }

        .item-name {
            font-size: 0.85rem;
        }

        .item-info {
            font-size: 0.75rem;
        }

        .current-price {
            font-size: 0.9rem;
        }

        .original-price {
            font-size: 0.75rem;
        }

        .order-footer {
            padding: 0.875rem;
        }

        .order-total-amount {
            font-size: 1.35rem;
        }

        .btn {
            padding: 0.65rem 0.875rem;
            font-size: 0.85rem;
        }

        /* Empty State Small Phone */
        .empty-state {
            padding: 2rem 1rem;
        }

        .empty-state-icon {
            font-size: 3rem;
        }

        .empty-state-title {
            font-size: 1.2rem;
        }

        .empty-state-text {
            font-size: 0.9rem;
        }
    }

    /* RTL Mobile Adjustments */
    @media (max-width: 768px) {
        [dir="rtl"] .order-header-top {
            align-items: flex-end;
        }

        [dir="rtl"] .order-header-bottom {
            justify-content: flex-end;
        }

        [dir="rtl"] .item-price {
            align-items: flex-start;
        }

        [dir="rtl"] .order-total {
            text-align: center;
        }
    }
</style>

<div class="orders-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <i class="fas fa-shopping-bag"></i>
                {{ __t('messages.my_orders') }}
            </h1>
            <p class="page-subtitle">
                @if(current_locale() === 'ar')
                    ØªØªØ¨Ø¹ ÙˆØ¥Ø¯Ø§Ø±Ø© Ø¬Ù…ÙŠØ¹ Ø·Ù„Ø¨Ø§ØªÙƒ Ù…Ù† Ù…ÙƒØ§Ù† ÙˆØ§Ø­Ø¯
                @elseif(current_locale() === 'he')
                    ×¢×§×•×‘ ×•× ×™×”×œ ××ª ×›×œ ×”×”×–×ž× ×•×ª ×©×œ×š ×ž×ž×§×•× ××—×“
                @else
                    Track and manage all your orders in one place
                @endif
            </p>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="status-tabs">
        <div class="status-tabs-list">
            <a href="{{ route('orders.index', ['status' => 'all']) }}" 
               class="status-tab {{ (!request('status') || request('status') === 'all') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                @if(current_locale() === 'ar')
                    Ø¬Ù…ÙŠØ¹ Ø§Ù„Ø·Ù„Ø¨Ø§Øª
                @elseif(current_locale() === 'he')
                    ×›×œ ×”×”×–×ž× ×•×ª
                @else
                    All Orders
                @endif
                <span class="status-count">{{ $statusCounts['all'] }}</span>
            </a>

            <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
               class="status-tab {{ request('status') === 'pending' ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                {{ __t('messages.pending') }}
                <span class="status-count">{{ $statusCounts['pending'] }}</span>
            </a>

            <a href="{{ route('orders.index', ['status' => 'processing']) }}" 
               class="status-tab {{ request('status') === 'processing' ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                {{ __t('messages.processing') }}
                <span class="status-count">{{ $statusCounts['processing'] }}</span>
            </a>

            <a href="{{ route('orders.index', ['status' => 'shipped']) }}" 
               class="status-tab {{ request('status') === 'shipped' ? 'active' : '' }}">
                <i class="fas fa-shipping-fast"></i>
                {{ __t('messages.shipped') }}
                <span class="status-count">{{ $statusCounts['shipped'] }}</span>
            </a>

            <a href="{{ route('orders.index', ['status' => 'delivered']) }}" 
               class="status-tab {{ request('status') === 'delivered' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                {{ __t('messages.delivered') }}
                <span class="status-count">{{ $statusCounts['delivered'] }}</span>
            </a>

            <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" 
               class="status-tab {{ request('status') === 'cancelled' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i>
                {{ __t('messages.order_status_cancelled') }}
                <span class="status-count">{{ $statusCounts['cancelled'] }}</span>
            </a>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="orders-container">
            @foreach($orders as $order)
                <div class="order-card">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div class="order-header-top">
                            <div class="order-number">
                                <i class="fas fa-hashtag"></i>
                                {{ $order->order_number }}
                            </div>
                            <div class="order-status-badge" style="background: {{ $order->status_color }}20; color: {{ $order->status_color }};">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                {{ $order->status_label }}
                            </div>
                        </div>
                        <div class="order-header-bottom">
                            <div class="order-info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="order-info-item">
                                <i class="fas fa-box"></i>
                                <span>{{ $order->items->count() }} 
                                    @if(current_locale() === 'ar')
                                        Ù…Ù†ØªØ¬
                                    @elseif(current_locale() === 'he')
                                        ×ž×•×¦×¨×™×
                                    @else
                                        items
                                    @endif
                                </span>
                            </div>
                            <div class="order-info-item">
                                <i class="fas fa-credit-card"></i>
                                <span>{{ $order->payment_status_label }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items">
                        @foreach($order->items->take(3) as $item)
                            <div class="order-item">
                                @php
                                    $productImage = $item->product_image;
                                    $imageSrc = asset('images/placeholder.png'); // default
                                    
                                    if ($productImage) {
                                        if (str_starts_with($productImage, 'http')) {
                                            $imageSrc = $productImage;
                                        } elseif (str_starts_with($productImage, 'images/')) {
                                            $imageSrc = asset($productImage);
                                        } else {
                                            $imageSrc = asset('media/' . $productImage);
                                        }
                                    }
                                @endphp
                                <img src="{{ $imageSrc }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="item-image"
                                     onerror="this.src='{{ asset('images/placeholder.png') }}'">
                                <div class="item-details">
                                    <a href="{{ $item->product_id ? route('product.detail', $item->product_id) : '#' }}" 
                                       class="item-name">
                                        {{ $item->product_name }}
                                    </a>
                                    <div class="item-info">
                                        <span>
                                            {{ __t('messages.quantity') }}
                                            {{ $item->quantity }}
                                        </span>
                                        @if($item->product_sku)
                                            <span>SKU: {{ $item->product_sku }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item-price">
                                    <div class="current-price">&#8362;{{ number_format($item->price, 2) }}</div>
                                    @if($item->has_discount)
                                        <div class="original-price">&#8362;{{ number_format($item->original_price, 2) }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($order->items->count() > 3)
                            <div style="text-align: center; padding: 1rem; color: #6b7280;">
                                {{ __t('messages.more_products_count', ['count' => $order->items->count() - 3]) }}
                            </div>
                        @endif
                    </div>

                    <!-- Order Footer -->
                    <div class="order-footer">
                        <div class="order-total">
                            <div class="order-total-label">
                                {{ __t('messages.total_amount') }}
                            </div>
                            <div class="order-total-amount">&#8362;{{ number_format($order->total, 2) }}</div>
                        </div>
                        <div class="order-actions">
                            <a href="{{ route('orders.show', $order->order_number) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                {{ __t('messages.view_details') }}
                            </a>
                            @if($order->canBeCancelled())
                                <form action="{{ route('orders.cancel', $order->order_number) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" 
                                            onclick="return confirm('{{ __t('messages.confirm_cancel_order') }}')">
                                        <i class="fas fa-times"></i>
                                        @if(current_locale() === 'ar')
                                            Ø¥Ù„ØºØ§Ø¡ Ø§Ù„Ø·Ù„Ø¨
                                        @elseif(current_locale() === 'he')
                                            ×‘×˜×œ ×”×–×ž× ×”
                                        @else
                                            Cancel Order
                                        @endif
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h2 class="empty-state-title">
                @if(current_locale() === 'ar')
                    Ù„Ø§ ØªÙˆØ¬Ø¯ Ø·Ù„Ø¨Ø§Øª
                @elseif(current_locale() === 'he')
                    ××™×Ÿ ×”×–×ž× ×•×ª
                @else
                    No Orders Found
                @endif
            </h2>
            <p class="empty-state-text">
                @if(current_locale() === 'ar')
                    Ù„Ù… ØªÙ‚Ù… Ø¨Ø£ÙŠ Ø·Ù„Ø¨Ø§Øª Ø¨Ø¹Ø¯. Ø§Ø¨Ø¯Ø£ Ø§Ù„ØªØ³ÙˆÙ‚ Ø§Ù„Ø¢Ù†!
                @elseif(current_locale() === 'he')
                    ×¢×“×™×™×Ÿ ×œ× ×‘×™×¦×¢×ª ×”×–×ž× ×•×ª. ×”×ª×—×œ ×œ×§× ×•×ª ×¢×›×©×™×•!
                @else
                    You haven't placed any orders yet. Start shopping now!
                @endif
            </p>
            <a href="{{ route('products') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i>
                @if(current_locale() === 'ar')
                    ØªØµÙØ­ Ø§Ù„Ù…Ù†ØªØ¬Ø§Øª
                @elseif(current_locale() === 'he')
                    ×¢×™×™×Ÿ ×‘×ž×•×¦×¨×™×
                @else
                    Browse Products
                @endif
            </a>
        </div>
    @endif
</div>
@endsection
