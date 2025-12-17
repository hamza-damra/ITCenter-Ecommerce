@extends('admin.layout')

@section('title', __('messages.promotional_offers_title'))

@section('content')
<style>
    /* Promotional Offers Page Styles */
    .promo-offers-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 18px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(238, 90, 36, 0.25);
        position: relative;
        overflow: hidden;
    }

    .promo-offers-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .promo-offers-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-text h1 {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-text h1 i {
        font-size: 1.5rem;
        background: rgba(255,255,255,0.2);
        padding: 0.5rem;
        border-radius: 10px;
    }

    .header-text p {
        opacity: 0.95;
        font-size: 1rem;
        margin: 0;
    }

    .header-actions .btn-add {
        background: white;
        color: #ee5a24;
        padding: 0.85rem 1.75rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .header-actions .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    /* Stats Grid */
    .promo-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .promo-stat-card {
        background: white;
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border-top: 4px solid;
        display: flex;
        flex-direction: column;
    }

    .promo-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .promo-stat-card.total { border-top-color: #667eea; }
    .promo-stat-card.active { border-top-color: #10b981; }
    .promo-stat-card.expired { border-top-color: #f59e0b; }
    .promo-stat-card.inactive { border-top-color: #ef4444; }

    .promo-stat-card h4 {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
    }

    .promo-stat-card .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
    }

    .promo-stat-card.total .stat-value { color: #667eea; }
    .promo-stat-card.active .stat-value { color: #10b981; }
    .promo-stat-card.expired .stat-value { color: #f59e0b; }
    .promo-stat-card.inactive .stat-value { color: #ef4444; }

    /* Table Container */
    .promo-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .promo-table {
        width: 100%;
        border-collapse: collapse;
    }

    .promo-table thead {
        background: #f8fafc;
    }

    .promo-table th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 700;
        color: #475569;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .promo-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .promo-table tbody tr:hover {
        background: linear-gradient(90deg, #fafafa 0%, #ffffff 100%);
    }

    .promo-table tbody tr:last-child {
        border-bottom: none;
    }

    .promo-table td {
        padding: 1.25rem;
        color: #334155;
        vertical-align: middle;
    }

    /* Product Cell */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .product-image {
        width: 55px;
        height: 55px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .product-image-placeholder {
        width: 55px;
        height: 55px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 1.25rem;
    }

    .product-info h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .product-info span {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Price Styles */
    .price-original {
        font-size: 0.85rem;
        color: #94a3b8;
        text-decoration: line-through;
    }

    .price-sale {
        font-size: 1rem;
        font-weight: 700;
        color: #ef4444;
    }

    /* Discount Badge */
    .discount-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 0.85rem;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    /* Date Display */
    .date-display {
        font-size: 0.85rem;
        color: #475569;
    }

    .date-display i {
        color: #94a3b8;
        margin-right: 0.35rem;
    }

    /* Status Badge */
    .status-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }

    .status-toggle.active {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-toggle.inactive {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .status-toggle:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
        transform: translateY(-2px);
    }

    .btn-delete {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-icon i {
        font-size: 2rem;
        color: #94a3b8;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        color: #334155;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    .empty-state .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.85rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }

    .empty-state .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    /* Alert */
    .alert-success-custom {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-left: 4px solid #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        border-top: 1px solid #f1f5f9;
    }

    /* RTL Support */
    [dir="rtl"] .promo-table th,
    [dir="rtl"] .promo-table td {
        text-align: right;
    }

    [dir="rtl"] .product-cell {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .header-content {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .header-text {
        text-align: right;
    }

    [dir="rtl"] .action-buttons {
        flex-direction: row-reverse;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .promo-table {
            font-size: 0.9rem;
        }
        
        .promo-table th,
        .promo-table td {
            padding: 1rem;
        }
    }

    @media (max-width: 768px) {
        .promo-offers-header {
            padding: 1.5rem;
        }

        .header-text h1 {
            font-size: 1.4rem;
        }

        .promo-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .action-buttons {
            flex-direction: column;
        }

        .product-image,
        .product-image-placeholder {
            width: 45px;
            height: 45px;
        }
    }
</style>

<!-- Page Header -->
<div class="promo-offers-header">
    <div class="header-content">
        <div class="header-text">
            <h1><i class="fas fa-bullhorn"></i> {{ __('messages.promotional_offers_title') }}</h1>
            <p>{{ __('messages.promotional_offers_subtitle') }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.promotional-offers.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> {{ __('messages.add_new_offer') }}
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert-success-custom">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<!-- Statistics -->
@php
    $totalOffers = $offers->total() ?? count($offers);
    $activeOffers = \App\Models\PromotionalOffer::where('is_active', true)->where('end_date', '>=', now())->count();
    $expiredOffers = \App\Models\PromotionalOffer::where('end_date', '<', now())->count();
    $inactiveOffers = \App\Models\PromotionalOffer::where('is_active', false)->count();
@endphp
<div class="promo-stats-grid">
    <div class="promo-stat-card total">
        <h4><i class="fas fa-tags"></i> {{ __('messages.total_offers') }}</h4>
        <div class="stat-value">{{ $totalOffers }}</div>
    </div>
    <div class="promo-stat-card active">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_offers') }}</h4>
        <div class="stat-value">{{ $activeOffers }}</div>
    </div>
    <div class="promo-stat-card expired">
        <h4><i class="fas fa-clock"></i> {{ __('messages.expired_offers') }}</h4>
        <div class="stat-value">{{ $expiredOffers }}</div>
    </div>
    <div class="promo-stat-card inactive">
        <h4><i class="fas fa-times-circle"></i> {{ __('messages.inactive_offers') }}</h4>
        <div class="stat-value">{{ $inactiveOffers }}</div>
    </div>
</div>

<!-- Offers Table -->
<div class="promo-table-container">
    <div class="table-header">
        <h3><i class="fas fa-list"></i> {{ __('messages.offers_list') }}</h3>
    </div>
    
    @if($offers->count() > 0)
    <div class="table-responsive">
        <table class="promo-table">
            <thead>
                <tr>
                    <th>{{ __('messages.product') }}</th>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.original_price') }}</th>
                    <th>{{ __('messages.sale_price') }}</th>
                    <th>{{ __('messages.discount') }}</th>
                    <th>{{ __('messages.duration') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                <tr>
                    <td>
                        <div class="product-cell">
                            @if($offer->product && $offer->product->main_image)
                                <img src="{{ $offer->product->main_image }}" alt="{{ $offer->title }}" class="product-image">
                            @else
                                <div class="product-image-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                            <div class="product-info">
                                <h5>{{ $offer->product ? ($offer->product->name_en ?? $offer->product->name) : 'N/A' }}</h5>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $offer->title }}</strong>
                    </td>
                    <td>
                        <span class="price-original">₪{{ number_format($offer->original_price, 2) }}</span>
                    </td>
                    <td>
                        <span class="price-sale">₪{{ number_format($offer->sale_price, 2) }}</span>
                    </td>
                    <td>
                        <span class="discount-badge">
                            <i class="fas fa-percent"></i>
                            {{ $offer->discount_percentage }}%
                        </span>
                    </td>
                    <td>
                        <div class="date-display">
                            <div><i class="fas fa-play"></i> {{ $offer->start_date->format('Y-m-d') }}</div>
                            <div><i class="fas fa-stop"></i> {{ $offer->end_date->format('Y-m-d') }}</div>
                        </div>
                    </td>
                    <td>
                        <button class="status-toggle {{ $offer->is_active ? 'active' : 'inactive' }}" 
                                onclick="toggleActive({{ $offer->id }})">
                            <i class="fas {{ $offer->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $offer->is_active ? __('messages.active') : __('messages.inactive') }}
                        </button>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.promotional-offers.edit', $offer->id) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.promotional-offers.destroy', $offer->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('{{ __('messages.confirm_delete_offer') }}')">
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

    @if($offers->hasPages())
    <div class="pagination-wrapper">
        {{ $offers->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-tags"></i>
        </div>
        <h3>{{ __('messages.no_offers_currently') }}</h3>
        <p>{{ __('messages.start_creating_offers') }}</p>
        <a href="{{ route('admin.promotional-offers.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.add_new_offer') }}
        </a>
    </div>
    @endif
</div>

<script>
function toggleActive(offerId) {
    if (!confirm('{{ __('messages.confirm_toggle_status') }}')) return;
    
    fetch(`/admin/promotional-offers/${offerId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
