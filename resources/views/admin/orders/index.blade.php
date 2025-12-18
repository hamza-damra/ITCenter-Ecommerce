@extends('admin.layout')

@section('title', __('messages.orders_management'))

@section('content')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="orders-page">
    <!-- Page Header -->
    <div class="admin-hero">
        <div class="admin-hero-content">
            <div class="admin-hero-text">
                <div class="admin-hero-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div>
                    <h1>{{ __('messages.orders_management') }}</h1>
                    <p>{{ __('messages.manage_track_orders') }}</p>
                </div>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="hero-stat-value">{{ $stats['total_orders'] }}</span>
                    <span class="hero-stat-label">{{ __('messages.total') }}</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat highlight">
                    <span class="hero-stat-value">₪{{ number_format($stats['total_revenue'], 0) }}</span>
                    <span class="hero-stat-label">{{ __('messages.revenue') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Pipeline -->
    <div class="admin-stats-grid orders-pipeline">
        <div class="admin-stat-card stat-warning pipeline-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h4>{{ __('messages.pending') }}</h4>
            <div class="stat-value">{{ $stats['pending_orders'] }}</div>
        </div>

        <div class="admin-stat-card stat-info pipeline-card">
            <div class="stat-icon">
                <i class="fas fa-cog"></i>
            </div>
            <h4>{{ __('messages.processing') }}</h4>
            <div class="stat-value">{{ $stats['processing_orders'] }}</div>
        </div>

        <div class="admin-stat-card stat-violet pipeline-card">
            <div class="stat-icon">
                <i class="fas fa-truck"></i>
            </div>
            <h4>{{ __('messages.shipped') }}</h4>
            <div class="stat-value">{{ $stats['shipped_orders'] }}</div>
        </div>

        <div class="admin-stat-card stat-success pipeline-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h4>{{ __('messages.delivered') }}</h4>
            <div class="stat-value">{{ $stats['delivered_orders'] }}</div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <div class="filters-title">
                <i class="fas fa-sliders-h"></i>
                <span>{{ __('messages.filters') }}</span>
            </div>
            <button type="button" class="filters-toggle" id="filtersToggle">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        
        <form method="GET" action="{{ route('admin.orders.index') }}" class="filters-form" id="filtersForm">
            <div class="filters-grid">
                <div class="filter-field">
                    <label>
                        <i class="fas fa-search"></i>
                        {{ __('messages.search_orders') }}
                    </label>
                    <div class="search-input-wrapper">
                        <input type="text" name="search" 
                               placeholder="{{ __('messages.search_placeholder') }}" 
                               value="{{ request('search') }}">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>

                <div class="filter-field">
                    <label>
                        <i class="fas fa-tasks"></i>
                        {{ __('messages.status') }}
                    </label>
                    <div class="select-wrapper">
                        <select name="status">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>{{ __('messages.all_statuses') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>{{ __('messages.processing') }}</option>
                            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>{{ __('messages.shipped') }}</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>{{ __('messages.delivered') }}</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                        </select>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <div class="filter-field">
                    <label>
                        <i class="fas fa-credit-card"></i>
                        {{ __('messages.payment_status') }}
                    </label>
                    <div class="select-wrapper">
                        <select name="payment_status">
                            <option value="all" {{ request('payment_status') === 'all' ? 'selected' : '' }}>{{ __('messages.all') }}</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>{{ __('messages.failed') }}</option>
                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>{{ __('messages.refunded') }}</option>
                        </select>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <div class="filter-field">
                    <label>
                        <i class="fas fa-calendar-alt"></i>
                        {{ __('messages.date_from') }}
                    </label>
                    <input type="text" name="date_from" value="{{ request('date_from') }}" 
                           class="date-input" 
                           placeholder="{{ __('messages.select_date') }}"
                           readonly>
                </div>

                <div class="filter-field">
                    <label>
                        <i class="fas fa-calendar-alt"></i>
                        {{ __('messages.date_to') }}
                    </label>
                    <input type="text" name="date_to" value="{{ request('date_to') }}" 
                           class="date-input" 
                           placeholder="{{ __('messages.select_date') }}"
                           readonly>
                </div>
            </div>

            <div class="filters-actions">
                <button type="submit" class="filter-btn primary">
                    <i class="fas fa-search"></i>
                    {{ __('messages.filter') }}
                </button>
                <a href="{{ route('admin.orders.index') }}" class="filter-btn secondary">
                    <i class="fas fa-redo"></i>
                    {{ __('messages.reset') }}
                </a>
                <a href="{{ route('admin.orders.export', request()->all()) }}" class="filter-btn export">
                    <i class="fas fa-file-export"></i>
                    {{ __('messages.export_csv') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="admin-table-container">
        <div class="admin-table-header">
            <h3>
                <i class="fas fa-list"></i>
                {{ __('messages.orders_list') }}
                <span class="orders-count">{{ $orders->total() }} {{ __('messages.orders') }}</span>
            </h3>
            
            <div class="bulk-selector" id="bulkSelector" style="display: none;">
                <span class="selected-count"><span id="selectedCount">0</span> {{ __('messages.selected') }}</span>
                <select id="bulkStatus" class="bulk-status-select">
                    <option value="">{{ __('messages.change_status') }}</option>
                    <option value="pending">{{ __('messages.pending') }}</option>
                    <option value="processing">{{ __('messages.processing') }}</option>
                    <option value="shipped">{{ __('messages.shipped') }}</option>
                    <option value="delivered">{{ __('messages.delivered') }}</option>
                    <option value="cancelled">{{ __('messages.cancelled') }}</option>
                </select>
                <button type="button" class="bulk-apply-btn" id="bulkApplyBtn">
                    <i class="fas fa-check"></i>
                    {{ __('messages.apply') }}
                </button>
            </div>
        </div>

        @if($orders->count() > 0)
        <div class="table-container">
            <table class="admin-table orders-table">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="selectAll">
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th>{{ __('messages.order') }}</th>
                        <th>{{ __('messages.customer') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.items') }}</th>
                        <th>{{ __('messages.total') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.payment') }}</th>
                        <th class="actions-col">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="order-row" data-order-id="{{ $order->id }}">
                        <td class="checkbox-col">
                            <label class="custom-checkbox">
                                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="order-link">
                                <span class="order-number">#{{ $order->order_number }}</span>
                            </a>
                        </td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar">
                                    {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                </div>
                                <div class="customer-details">
                                    <span class="customer-name">{{ $order->customer_name }}</span>
                                    <span class="customer-email">{{ $order->customer_email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <span class="date-main">{{ $order->created_at->format('M d, Y') }}</span>
                                <span class="date-time">{{ $order->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="items-badge">{{ $order->items->count() }}</span>
                        </td>
                        <td>
                            <span class="order-total">₪{{ number_format($order->total, 2) }}</span>
                        </td>
                        <td>
                            <span class="status-chip status-{{ $order->status }}">
                                <i class="status-dot"></i>
                                {{ __t($order->status . '_status') }}
                            </span>
                        </td>
                        <td>
                            <span class="payment-chip payment-{{ $order->payment_status }}">
                                @if($order->payment_status === 'paid')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($order->payment_status === 'pending')
                                    <i class="fas fa-clock"></i>
                                @elseif($order->payment_status === 'failed')
                                    <i class="fas fa-times-circle"></i>
                                @else
                                    <i class="fas fa-undo"></i>
                                @endif
                                {{ __t($order->payment_status === 'pending' ? 'pending' : $order->payment_status) }}
                            </span>
                        </td>
                        <td class="actions-col">
                            <div class="action-buttons">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="action-btn view" title="{{ __('messages.view_details') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="action-btn delete" 
                                        title="{{ __('messages.delete') }}"
                                        onclick="confirmDelete({{ $order->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $order->id }}" 
                                  action="{{ route('admin.orders.destroy', $order->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="pagination-wrapper">
            {{ $orders->links() }}
        </div>
        @endif

        @else
        <div class="admin-empty-state">
            <div class="admin-empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h3>{{ __('messages.no_orders_found') }}</h3>
            <p>{{ __('messages.no_orders_match_filters') }}</p>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                <i class="fas fa-redo"></i>
                {{ __('messages.clear_filters') }}
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Bulk Update Form (hidden) -->
<form id="bulkUpdateForm" action="{{ route('admin.orders.bulk-update') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="order_ids" id="bulkOrderIds">
    <input type="hidden" name="status" id="bulkStatusInput">
</form>

<style>
/* ============================================
   ORDERS MANAGEMENT - PAGE-SPECIFIC STYLES
   Uses unified admin components from layout.blade.php
   ============================================ */

.orders-page {
    --accent-cyan: #06b6d4;
}

/* Hero Stats (inside admin-hero) */
.hero-stats {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.hero-stat {
    text-align: center;
}

.hero-stat-value {
    display: block;
    font-size: 1.75rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}

.hero-stat-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.25rem;
    display: block;
}

.hero-stat.highlight .hero-stat-value {
    color: var(--accent-emerald, #10b981);
}

.hero-stat-divider {
    width: 1px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
}

/* Orders Pipeline - extends admin-stats-grid */
.orders-pipeline {
    grid-template-columns: repeat(4, 1fr);
}

@media (max-width: 1024px) {
    .orders-pipeline {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .orders-pipeline {
        grid-template-columns: 1fr;
    }
}

/* Filters Section */
.filters-section {
    background: var(--bg-primary, #ffffff);
    border: 1px solid var(--border, #e2e8f0);
    border-radius: var(--radius-lg, 16px);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: var(--bg-secondary, #f8fafc);
    border-bottom: 1px solid var(--border, #e2e8f0);
}

.filters-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark, #0f172a);
}

.filters-title i {
    color: var(--accent-blue, #0ea5e9);
}

.filters-toggle {
    background: none;
    border: none;
    color: var(--secondary, #64748b);
    cursor: pointer;
    padding: 0.5rem;
    transition: transform 0.3s ease;
}

.filters-toggle.collapsed {
    transform: rotate(-90deg);
}

[dir="rtl"] .filters-toggle.collapsed {
    transform: rotate(90deg);
}

.filters-form {
    padding: 1.5rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 1.25rem;
}

@media (max-width: 1200px) {
    .filters-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .filters-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .filters-grid {
        grid-template-columns: 1fr;
    }
}

.filter-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-field label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--dark, #0f172a);
}

.filter-field label i {
    font-size: 0.75rem;
    color: var(--secondary, #64748b);
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid var(--border, #e2e8f0);
    border-radius: var(--radius-sm, 8px);
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

[dir="rtl"] .search-input-wrapper input {
    padding: 0.75rem 2.5rem 0.75rem 1rem;
}

.search-input-wrapper .search-icon {
    position: absolute;
    left: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary, #64748b);
    font-size: 0.875rem;
}

[dir="rtl"] .search-input-wrapper .search-icon {
    left: auto;
    right: 0.875rem;
}

.select-wrapper {
    position: relative;
}

.select-wrapper select {
    width: 100%;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    border: 2px solid var(--border, #e2e8f0);
    border-radius: var(--radius-sm, 8px);
    font-size: 0.875rem;
    background: var(--bg-primary, #ffffff);
    cursor: pointer;
    appearance: none;
    transition: all 0.2s ease;
}

[dir="rtl"] .select-wrapper select {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
}

.select-wrapper i {
    position: absolute;
    right: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary, #64748b);
    pointer-events: none;
    font-size: 0.75rem;
}

[dir="rtl"] .select-wrapper i {
    right: auto;
    left: 0.875rem;
}

.filter-field input,
.filter-field select {
    transition: all 0.2s ease;
}

.filter-field input:focus,
.filter-field select:focus {
    outline: none;
    border-color: var(--accent-blue, #0ea5e9);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.date-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border, #e2e8f0);
    border-radius: var(--radius-sm, 8px);
    font-size: 0.875rem;
    cursor: pointer;
    background: var(--bg-primary, #ffffff);
}

.filters-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: var(--radius-sm, 8px);
    font-size: 0.875rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
}

.filter-btn.primary {
    background: linear-gradient(135deg, var(--accent-blue, #0ea5e9) 0%, var(--accent-indigo, #6366f1) 100%);
    color: white;
}

.filter-btn.primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
}

.filter-btn.secondary {
    background: var(--bg-secondary, #f8fafc);
    color: var(--secondary, #64748b);
    border: 2px solid var(--border, #e2e8f0);
}

.filter-btn.secondary:hover {
    background: var(--bg-tertiary, #f1f5f9);
}

.filter-btn.export {
    background: var(--accent-emerald, #10b981);
    color: white;
}

.filter-btn.export:hover {
    background: #059669;
}

/* Orders Table Header Extensions */
.admin-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.orders-count {
    background: var(--bg-tertiary, #f1f5f9);
    color: var(--secondary, #64748b);
    padding: 0.25rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

[dir="rtl"] .orders-count {
    margin-left: 0;
    margin-right: 0.5rem;
}

.bulk-selector {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

.selected-count {
    font-size: 0.8125rem;
    color: var(--accent-blue, #0ea5e9);
    font-weight: 600;
}

.bulk-status-select {
    padding: 0.5rem 0.75rem;
    border: 2px solid var(--border, #e2e8f0);
    border-radius: var(--radius-sm, 8px);
    font-size: 0.8125rem;
    background: var(--bg-primary, #ffffff);
}

.bulk-apply-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    background: var(--accent-blue, #0ea5e9);
    color: white;
    border: none;
    border-radius: var(--radius-sm, 8px);
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.bulk-apply-btn:hover {
    background: #0284c7;
}

/* Table Styles - extends admin-table */
.table-container {
    overflow-x: auto;
}

/* Orders-specific table overrides */
.orders-table th {
    font-size: 0.6875rem;
    white-space: nowrap;
}

.checkbox-col {
    width: 48px;
    text-align: center;
}

.actions-col {
    width: 100px;
    text-align: center;
}

/* Custom Checkbox */
.custom-checkbox {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.custom-checkbox input {
    display: none;
}

.custom-checkbox .checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid var(--border, #e2e8f0);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.custom-checkbox input:checked + .checkmark {
    background: var(--accent-blue, #0ea5e9);
    border-color: var(--accent-blue, #0ea5e9);
}

.custom-checkbox input:checked + .checkmark::after {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: 0.625rem;
    color: white;
}

/* Order Link */
.order-link {
    text-decoration: none;
}

.order-number {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--accent-blue, #0ea5e9);
    transition: color 0.2s ease;
}

.order-link:hover .order-number {
    color: var(--accent-indigo, #6366f1);
}

/* Customer Info */
.customer-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.customer-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--accent-indigo, #6366f1) 0%, var(--accent-violet, #8b5cf6) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}

.customer-details {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    min-width: 0;
}

.customer-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark, #0f172a);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.customer-email {
    font-size: 0.75rem;
    color: var(--secondary, #64748b);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Date Info */
.date-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.date-main {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--dark, #0f172a);
}

.date-time {
    font-size: 0.75rem;
    color: var(--secondary, #64748b);
}

/* Items Badge */
.items-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    background: var(--bg-tertiary, #f1f5f9);
    color: var(--secondary, #64748b);
    border-radius: 100px;
    font-size: 0.8125rem;
    font-weight: 600;
}

/* Order Total */
.order-total {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--accent-emerald, #10b981);
}

/* Status Chips */
.status-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 100px;
    font-size: 0.6875rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.status-chip.status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

.status-chip.status-processing {
    background: rgba(14, 165, 233, 0.1);
    color: #0369a1;
}

.status-chip.status-shipped {
    background: rgba(139, 92, 246, 0.1);
    color: #6d28d9;
}

.status-chip.status-delivered {
    background: rgba(16, 185, 129, 0.1);
    color: #047857;
}

.status-chip.status-cancelled {
    background: rgba(244, 63, 94, 0.1);
    color: #be123c;
}

/* Payment Chips */
.payment-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm, 8px);
    font-size: 0.6875rem;
    font-weight: 600;
}

.payment-chip.payment-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #b45309;
}

.payment-chip.payment-paid {
    background: rgba(16, 185, 129, 0.1);
    color: #047857;
}

.payment-chip.payment-failed {
    background: rgba(244, 63, 94, 0.1);
    color: #be123c;
}

.payment-chip.payment-refunded {
    background: rgba(99, 102, 241, 0.1);
    color: #4338ca;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: var(--radius-sm, 8px);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn.view {
    background: rgba(14, 165, 233, 0.1);
    color: var(--accent-blue, #0ea5e9);
}

.action-btn.view:hover {
    background: var(--accent-blue, #0ea5e9);
    color: white;
}

.action-btn.delete {
    background: rgba(244, 63, 94, 0.1);
    color: var(--accent-rose, #f43f5e);
}

.action-btn.delete:hover {
    background: var(--accent-rose, #f43f5e);
    color: white;
}

/* Pagination */
.pagination-wrapper {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid var(--border, #e2e8f0);
    display: flex;
    justify-content: center;
}

/* RTL Adjustments */
[dir="rtl"] .orders-table th,
[dir="rtl"] .orders-table td {
    text-align: right;
}

[dir="rtl"] .customer-info {
    flex-direction: row-reverse;
}

[dir="rtl"] .customer-details {
    text-align: right;
}

[dir="rtl"] .action-buttons {
    flex-direction: row-reverse;
}

/* Responsive Hero Stats */
@media (max-width: 768px) {
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero-stat-divider {
        width: 40px;
        height: 1px;
    }
}
</style>

<!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@if(app()->getLocale() === 'ar')
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
@elseif(app()->getLocale() === 'he')
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/he.js"></script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Flatpickr for date inputs
    const currentLocale = '{{ app()->getLocale() }}';
    const dateInputs = document.querySelectorAll('.date-input');
    
    const flatpickrConfig = {
        dateFormat: 'Y-m-d',
        allowInput: true,
        disableMobile: true,
    };

    if (currentLocale === 'ar') {
        flatpickrConfig.locale = 'ar';
    } else if (currentLocale === 'he') {
        flatpickrConfig.locale = 'he';
    }

    dateInputs.forEach(input => {
        flatpickr(input, flatpickrConfig);
    });

    // Filters Toggle
    const filtersToggle = document.getElementById('filtersToggle');
    const filtersForm = document.getElementById('filtersForm');
    
    if (filtersToggle && filtersForm) {
        filtersToggle.addEventListener('click', function() {
            this.classList.toggle('collapsed');
            filtersForm.style.display = filtersForm.style.display === 'none' ? 'block' : 'none';
        });
    }

    // Select All Checkbox
    const selectAll = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkSelector = document.getElementById('bulkSelector');
    const selectedCountEl = document.getElementById('selectedCount');

    selectAll?.addEventListener('change', function() {
        orderCheckboxes.forEach(cb => cb.checked = this.checked);
        updateBulkSelector();
    });

    orderCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkSelector);
    });

    function updateBulkSelector() {
        const checked = document.querySelectorAll('.order-checkbox:checked');
        const count = checked.length;
        
        if (count > 0) {
            bulkSelector.style.display = 'flex';
            selectedCountEl.textContent = count;
        } else {
            bulkSelector.style.display = 'none';
        }
    }

    // Bulk Apply
    const bulkApplyBtn = document.getElementById('bulkApplyBtn');
    const bulkStatus = document.getElementById('bulkStatus');
    const bulkUpdateForm = document.getElementById('bulkUpdateForm');
    const bulkOrderIds = document.getElementById('bulkOrderIds');
    const bulkStatusInput = document.getElementById('bulkStatusInput');

    bulkApplyBtn?.addEventListener('click', function() {
        const status = bulkStatus.value;
        if (!status) {
            alert('{{ __("messages.select_status") }}');
            return;
        }

        const checked = document.querySelectorAll('.order-checkbox:checked');
        const ids = Array.from(checked).map(cb => cb.value);
        
        if (ids.length === 0) {
            alert('{{ __("messages.select_orders") }}');
            return;
        }

        bulkOrderIds.value = JSON.stringify(ids);
        bulkStatusInput.value = status;
        bulkUpdateForm.submit();
    });
});

function confirmDelete(orderId) {
    if (confirm('{{ __("messages.delete_order_confirm") }}')) {
        document.getElementById('delete-form-' + orderId).submit();
    }
}
</script>
@endsection
