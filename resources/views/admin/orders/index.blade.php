@extends('admin.layout')

@section('title', __('messages.orders_management'))

@section('content')
<style>
    /* RTL Support */
    [dir="rtl"] .orders-header h1,
    [dir="rtl"] .orders-header p {
        text-align: right;
    }

    [dir="rtl"] .orders-header h1 i {
        margin-left: 0.5rem;
        margin-right: 0;
    }

    [dir="rtl"] .stat-card {
        text-align: right;
    }

    [dir="rtl"] .filter-group label,
    [dir="rtl"] .filter-group input,
    [dir="rtl"] .filter-group select {
        text-align: right;
    }

    [dir="rtl"] .filter-group label i {
        margin-left: 0.3rem;
        margin-right: 0;
    }

    [dir="rtl"] th {
        text-align: right;
    }

    [dir="rtl"] td {
        text-align: right;
    }

    [dir="rtl"] .action-buttons {
        justify-content: flex-start;
    }

    [dir="rtl"] .back-link {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .btn i {
        margin-left: 0.5rem;
        margin-right: 0;
    }

    [dir="rtl"] .filter-actions {
        justify-content: flex-start;
    }

    [dir="rtl"] .filter-actions .btn {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .empty-state {
        text-align: center;
    }

    .orders-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }

    .orders-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .orders-header p {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
    }

    .filters-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
    }

    .filter-group input,
    .filter-group select {
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
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
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .orders-table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    tr:hover {
        background: #f9fafb;
    }

    .order-number {
        font-weight: 600;
        color: #667eea;
        text-decoration: none;
    }

    .order-number:hover {
        text-decoration: underline;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .status-processing {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .status-shipped {
        background: #E9D5FF;
        color: #6B21A8;
    }

    .status-delivered {
        background: #D1FAE5;
        color: #065F46;
    }

    .status-cancelled {
        background: #FEE2E2;
        color: #991B1B;
    }

    .payment-pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .payment-paid {
        background: #D1FAE5;
        color: #065F46;
    }

    .payment-failed {
        background: #FEE2E2;
        color: #991B1B;
    }

    .payment-refunded {
        background: #E0E7FF;
        color: #3730A3;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-info {
        background: #667eea;
        color: white;
    }

    .btn-info:hover {
        background: #5568d3;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .bulk-actions {
        padding: 1rem;
        background: #f9fafb;
        border-top: 2px solid #e5e7eb;
        display: none;
    }

    .bulk-actions.active {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .checkbox-cell {
        width: 40px;
    }

    .checkbox-cell input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .pagination {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filters-row {
            grid-template-columns: 1fr;
        }

        .table-wrapper {
            font-size: 0.85rem;
        }

        th, td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<div class="orders-header">
    <h1><i class="fas fa-shopping-bag"></i> {{ __('messages.orders_management') }}</h1>
    <p>{{ __('messages.manage_track_orders') }}</p>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-label">{{ __('messages.total_orders') }}</div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #FEF3C7; color: #92400E;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-label">{{ __('messages.pending') }}</div>
        <div class="stat-value">{{ $stats['pending_orders'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #DBEAFE; color: #1E40AF;">
            <i class="fas fa-cog"></i>
        </div>
        <div class="stat-label">{{ __('messages.processing') }}</div>
        <div class="stat-value">{{ $stats['processing_orders'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #E9D5FF; color: #6B21A8;">
            <i class="fas fa-truck"></i>
        </div>
        <div class="stat-label">{{ __('messages.shipped') }}</div>
        <div class="stat-value">{{ $stats['shipped_orders'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #D1FAE5; color: #065F46;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-label">{{ __('messages.delivered') }}</div>
        <div class="stat-value">{{ $stats['delivered_orders'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #10b981; color: white;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-label">{{ __('messages.total_revenue') }}</div>
        <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
    </div>
</div>

<!-- Filters -->
<div class="filters-card">
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="filters-row">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> {{ __('messages.search_orders') }}</label>
                <input type="text" name="search" placeholder="{{ __('messages.search_placeholder') }}" 
                       value="{{ request('search') }}">
            </div>

            <div class="filter-group">
                <label><i class="fas fa-filter"></i> {{ __('messages.status') }}</label>
                <select name="status">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>{{ __('messages.all_statuses') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>{{ __('messages.processing') }}</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>{{ __('messages.shipped') }}</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>{{ __('messages.delivered') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                </select>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-credit-card"></i> {{ __('messages.payment_status') }}</label>
                <select name="payment_status">
                    <option value="all" {{ request('payment_status') === 'all' ? 'selected' : '' }}>{{ __('messages.all') }}</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>{{ __('messages.failed') }}</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>{{ __('messages.refunded') }}</option>
                </select>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> {{ __('messages.date_from') }}</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>

            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> {{ __('messages.date_to') }}</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> {{ __('messages.filter') }}
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> {{ __('messages.reset') }}
            </a>
            <a href="{{ route('admin.orders.export', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> {{ __('messages.export_csv') }}
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="orders-table-card">
    @if($orders->count() > 0)
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>{{ __('messages.order_number') }}</th>
                        <th>{{ __('messages.customer') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.items') }}</th>
                        <th>{{ __('messages.total') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.payment') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="order-number">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                                <div style="font-size: 0.85rem; color: #6b7280;">{{ $order->customer_email }}</div>
                            </td>
                            <td>
                                <div>{{ $order->created_at->format('d M Y') }}</div>
                                <div style="font-size: 0.85rem; color: #6b7280;">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td>{{ $order->items->count() }} {{ __('messages.items') }}</td>
                            <td style="font-weight: 700; color: #667eea;">${{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ __t($order->status . '_status') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge payment-{{ $order->payment_status }}">
                                    {{ __t($order->payment_status === 'pending' ? 'pending' : $order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="btn btn-sm btn-info" title="{{ __('messages.view_details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" 
                                          method="POST" style="display: inline;"
                                          onsubmit="return confirm('{{ __('messages.delete_order_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('messages.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulk-actions">
            <span id="selected-count">0 {{ __('messages.selected') }}</span>
            <form action="{{ route('admin.orders.bulk-update') }}" method="POST" style="display: flex; gap: 1rem; align-items: center;">
                @csrf
                <input type="hidden" name="order_ids" id="bulk-order-ids">
                <select name="status" class="filter-group" required style="margin: 0; width: 200px;">
                    <option value="">{{ __('messages.select_status') }}</option>
                    <option value="pending">{{ __('messages.pending') }}</option>
                    <option value="processing">{{ __('messages.processing') }}</option>
                    <option value="shipped">{{ __('messages.shipped') }}</option>
                    <option value="delivered">{{ __('messages.delivered') }}</option>
                    <option value="cancelled">{{ __('messages.cancelled') }}</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> {{ __('messages.update_selected') }}
                </button>
            </form>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $orders->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>{{ __('messages.no_orders_found') }}</h3>
            <p>{{ __('messages.no_orders_match_filters') }}</p>
        </div>
    @endif
</div>

<script>
    // Select All Checkbox
    document.getElementById('select-all')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    // Individual Checkboxes
    document.querySelectorAll('.order-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        const bulkOrderIds = document.getElementById('bulk-order-ids');

        if (checked.length > 0) {
            bulkActions.classList.add('active');
            selectedCount.textContent = `${checked.length} {{ __('messages.selected') }}`;
            
            const ids = Array.from(checked).map(cb => cb.value);
            bulkOrderIds.value = JSON.stringify(ids);
        } else {
            bulkActions.classList.remove('active');
        }
    }
</script>
@endsection

