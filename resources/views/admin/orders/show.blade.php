@extends('admin.layout')

@section('title', __('messages.order_details') . ' - ' . $order->order_number)

@section('content')
<style>
    /* RTL Support */
    [dir="rtl"] .back-link {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .back-link i {
        margin-left: 0.5rem;
        margin-right: 0;
        transform: rotate(180deg);
    }

    [dir="rtl"] .order-title,
    [dir="rtl"] .meta-label,
    [dir="rtl"] .info-label {
        text-align: right;
    }

    [dir="rtl"] .order-title i {
        margin-left: 0.5rem;
        margin-right: 0;
    }

    [dir="rtl"] .card-title {
        text-align: right;
        direction: rtl;
    }

    [dir="rtl"] .card-title i {
        margin-right: 0.75rem;
        margin-left: 0;
    }

    [dir="rtl"] .order-item {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .item-price {
        text-align: left;
    }

    [dir="rtl"] .info-value {
        text-align: right;
    }

    [dir="rtl"] .summary-row {
        direction: rtl;
    }

    [dir="rtl"] .summary-row span:first-child {
        text-align: right;
    }

    [dir="rtl"] .summary-row strong {
        text-align: left;
        direction: ltr;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        gap: 0.75rem;
    }

    .order-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }

    .order-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .order-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .meta-value {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #111827;
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
        min-width: 80px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        background: #fff;
    }

    [dir="rtl"] .item-image {
        margin-left: 1rem;
        margin-right: 0;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .item-info {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .item-price {
        text-align: right;
        font-weight: 700;
        color: #667eea;
        font-size: 1.1rem;
    }

    .info-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.9rem;
        color: #6b7280;
        min-width: 120px;
    }

    .info-value {
        font-weight: 600;
        color: #111827;
        flex: 1;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .summary-row span:first-child {
        flex: 1;
    }

    .summary-row strong {
        white-space: nowrap;
        margin-inline-start: 1rem;
    }

    .summary-row.total {
        border-top: 2px solid #e5e7eb;
        padding-top: 1rem;
        margin-top: 0.5rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #667eea;
    }

    .status-update-form {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 10px;
    }

    .status-update-form select {
        flex: 1;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .status-update-form select:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending { background: #FEF3C7; color: #92400E; }
    .status-processing { background: #DBEAFE; color: #1E40AF; }
    .status-shipped { background: #E9D5FF; color: #6B21A8; }
    .status-delivered { background: #D1FAE5; color: #065F46; }
    .status-cancelled { background: #FEE2E2; color: #991B1B; }

    .payment-pending { background: #FEF3C7; color: #92400E; }
    .payment-paid { background: #D1FAE5; color: #065F46; }
    .payment-failed { background: #FEE2E2; color: #991B1B; }
    .payment-refunded { background: #E0E7FF; color: #3730A3; }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .order-meta {
            grid-template-columns: 1fr;
        }

        .order-item {
            flex-direction: column;
        }

        .item-price {
            text-align: left;
        }
    }
</style>

<a href="{{ route('admin.orders.index') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_orders') }}
</a>

<div class="order-header">
    <h1 class="order-title">
        <i class="fas fa-receipt"></i>
        {{ __('messages.order_number') }} {{ $order->order_number }}
    </h1>
    <div class="order-meta">
        <div class="meta-item">
            <div class="meta-label">{{ __('messages.order_date') }}</div>
            <div class="meta-value">{{ $order->created_at->format('d M Y, h:i A') }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">{{ __('messages.order_status') }}</div>
            <div class="meta-value">
                <span class="status-badge status-{{ $order->status }}">
                    {{ __('messages.' . $order->status . '_status') }}
                </span>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-label">{{ __('messages.payment_status') }}</div>
            <div class="meta-value">
                <span class="status-badge payment-{{ $order->payment_status }}">
                    {{ __('messages.' . ($order->payment_status === 'pending' ? 'pending' : $order->payment_status)) }}
                </span>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-label">{{ __('messages.total_amount') }}</div>
            <div class="meta-value">&#8362;{{ number_format($order->total, 2) }}</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <!-- Left Column -->
    <div>
        <!-- Order Items -->
        <div class="card" style="margin-bottom: 2rem;">
            <h2 class="card-title">
                <i class="fas fa-shopping-bag"></i>
                {{ __('messages.order_items') }} ({{ $order->items->count() }})
            </h2>
            
            @foreach($order->items as $item)
                <div class="order-item">
                    @if($item->product_image)
                        <img src="{{ $item->product_image }}" 
                             alt="{{ $item->product_name }}" 
                             class="item-image"
                             onerror="this.src='{{ asset('images/placeholder.png') }}'">
                    @else
                        <div class="item-image" style="display: flex; align-items: center; justify-content: center; background: #f3f4f6;">
                            <i class="fas fa-image" style="font-size: 2rem; color: #9ca3af;"></i>
                        </div>
                    @endif
                    <div class="item-details">
                        <div class="item-name">{{ $item->product_name }}</div>
                        <div class="item-info">
                            {{ __('messages.quantity') }}: {{ $item->quantity }} × &#8362;{{ number_format($item->price, 2) }}
                            @if($item->product_sku)
                                | {{ __('messages.sku') }}: {{ $item->product_sku }}
                            @endif
                        </div>
                    </div>
                    <div class="item-price">
                        &#8362;{{ number_format($item->subtotal, 2) }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Customer Information -->
        <div class="card">
            <h2 class="card-title">
                <i class="fas fa-user"></i>
                {{ __('messages.customer_information') }}
            </h2>
            
            <div class="info-row">
                <div class="info-label">{{ __('messages.name') }}:</div>
                <div class="info-value">{{ $order->customer_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('messages.email') }}:</div>
                <div class="info-value">{{ $order->customer_email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('messages.phone') }}:</div>
                <div class="info-value">{{ $order->customer_phone }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">{{ __('messages.shipping_address') }}:</div>
                <div class="info-value">
                    {{ $order->shipping_address }}, {{ $order->shipping_city }}<br>
                    {{ $order->shipping_country }}
                    @if($order->shipping_postal_code)
                        , {{ $order->shipping_postal_code }}
                    @endif
                </div>
            </div>
            @if($order->notes)
                <div class="info-row">
                    <div class="info-label">{{ __('messages.notes') }}:</div>
                    <div class="info-value">{{ $order->notes }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column -->
    <div>
        <!-- Order Summary -->
        <div class="card" style="margin-bottom: 2rem;">
            <h2 class="card-title">
                <i class="fas fa-calculator"></i>
                {{ __('messages.order_summary') }}
            </h2>
            
            <div class="summary-row">
                <span>{{ __('messages.subtotal') }}:</span>
                <strong>&#8362;{{ number_format($order->subtotal, 2) }}</strong>
            </div>
            @if($order->tax > 0)
                <div class="summary-row">
                    <span>{{ __('messages.tax') }}:</span>
                    <strong>&#8362;{{ number_format($order->tax, 2) }}</strong>
                </div>
            @endif
            <div class="summary-row">
                <span>{{ __('messages.shipping') }}:</span>
                <strong>
                    @if($order->shipping_cost > 0)
                        &#8362;{{ number_format($order->shipping_cost, 2) }}
                    @else
                        {{ __('messages.free') }}
                    @endif
                </strong>
            </div>
            @if($order->discount > 0)
                <div class="summary-row">
                    <span>{{ __('messages.discount') }}:</span>
                    <strong style="color: #10b981;">-&#8362;{{ number_format($order->discount, 2) }}</strong>
                </div>
            @endif
            <div class="summary-row total">
                <span>{{ __('messages.total') }}:</span>
                <span>&#8362;{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Update Order Status -->
        <div class="card" style="margin-bottom: 2rem;">
            <h2 class="card-title">
                <i class="fas fa-edit"></i>
                {{ __('messages.update_order_status') }}
            </h2>
            
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="status-update-form">
                @csrf
                <select name="status" required>
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('messages.processing') }}</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>{{ __('messages.shipped') }}</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('messages.delivered') }}</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> {{ __('messages.update') }}
                </button>
            </form>
        </div>

        <!-- Update Payment Status -->
        <div class="card" style="margin-bottom: 2rem;">
            <h2 class="card-title">
                <i class="fas fa-credit-card"></i>
                {{ __('messages.payment_status') }}
            </h2>
            
            <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST" class="status-update-form">
                @csrf
                <select name="payment_status" required>
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>{{ __('messages.failed') }}</option>
                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>{{ __('messages.refunded') }}</option>
                </select>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> {{ __('messages.update') }}
                </button>
            </form>
        </div>

        <!-- Timestamps -->
        @if($order->shipped_at || $order->delivered_at || $order->cancelled_at)
            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-history"></i>
                    {{ __('messages.timeline') }}
                </h2>
                
                <div class="info-row">
                    <div class="info-label">{{ __('messages.created') }}:</div>
                    <div class="info-value">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>
                @if($order->paid_at)
                    <div class="info-row">
                        <div class="info-label">{{ __('messages.paid') }}:</div>
                        <div class="info-value">{{ $order->paid_at->format('d M Y, h:i A') }}</div>
                    </div>
                @endif
                @if($order->shipped_at)
                    <div class="info-row">
                        <div class="info-label">{{ __('messages.shipped') }}:</div>
                        <div class="info-value">{{ $order->shipped_at->format('d M Y, h:i A') }}</div>
                    </div>
                @endif
                @if($order->delivered_at)
                    <div class="info-row">
                        <div class="info-label">{{ __('messages.delivered') }}:</div>
                        <div class="info-value">{{ $order->delivered_at->format('d M Y, h:i A') }}</div>
                    </div>
                @endif
                @if($order->cancelled_at)
                    <div class="info-row">
                        <div class="info-label">{{ __('messages.cancelled') }}:</div>
                        <div class="info-value">{{ $order->cancelled_at->format('d M Y, h:i A') }}</div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

