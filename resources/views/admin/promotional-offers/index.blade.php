@extends('admin.layout')

@section('title', __('messages.promotional_offers_title'))

@section('content')
<style>
    /* Promotional Offers Page Styles - Using unified admin components */
    
    /* Hero Add Button */
    .admin-hero .btn-add {
        background: white;
        color: var(--accent-blue);
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

    .admin-hero .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
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

    [dir="rtl"] .alert-success-custom {
        border-left: none;
        border-right: 4px solid #10b981;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        border-top: 1px solid #f1f5f9;
    }

    /* RTL Support */
    [dir="rtl"] .admin-table th,
    [dir="rtl"] .admin-table td {
        text-align: right;
    }

    [dir="rtl"] .product-cell {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .action-buttons {
        flex-direction: row-reverse;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .admin-table {
            font-size: 0.9rem;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 1rem;
        }
    }

    @media (max-width: 768px) {
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

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div>
                <h1>{{ __('messages.promotional_offers_title') }}</h1>
                <p>{{ __('messages.promotional_offers_subtitle') }}</p>
            </div>
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

<!-- Statistics - Using unified admin-stats-grid component -->
@php
    $totalOffers = $offers->total() ?? count($offers);
    $activeOffers = \App\Models\PromotionalOffer::where('is_active', true)->where('end_date', '>=', now())->count();
    $expiredOffers = \App\Models\PromotionalOffer::where('end_date', '<', now())->count();
    $inactiveOffers = \App\Models\PromotionalOffer::where('is_active', false)->count();
@endphp
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-indigo">
        <h4><i class="fas fa-tags"></i> {{ __('messages.total_offers') }}</h4>
        <div class="stat-value">{{ $totalOffers }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_offers') }}</h4>
        <div class="stat-value">{{ $activeOffers }}</div>
    </div>
    <div class="admin-stat-card stat-warning">
        <h4><i class="fas fa-clock"></i> {{ __('messages.expired_offers') }}</h4>
        <div class="stat-value">{{ $expiredOffers }}</div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-times-circle"></i> {{ __('messages.inactive_offers') }}</h4>
        <div class="stat-value">{{ $inactiveOffers }}</div>
    </div>
</div>

<!-- Offers Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> {{ __('messages.offers_list') }}</h3>
    </div>
    
    @if($offers->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
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
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-tags"></i>
        </div>
        <h3>{{ __('messages.no_offers_currently') }}</h3>
        <p>{{ __('messages.start_creating_offers') }}</p>
        <a href="{{ route('admin.promotional-offers.create') }}" class="btn btn-primary">
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
