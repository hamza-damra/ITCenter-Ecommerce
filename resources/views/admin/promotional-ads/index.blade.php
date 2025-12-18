@extends('admin.layout')

@section('title', __('messages.promotional_ads_management'))

@section('content')
<style>
    /* Promotional Ads Page Styles - Using unified admin components */
    
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

    /* Image Cell */
    .ad-thumbnail {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .ad-thumbnail-placeholder {
        width: 120px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #94a3b8;
        font-size: 1.5rem;
        border-radius: 10px;
        border: 2px dashed #cbd5e1;
    }

    /* Position Badge */
    .position-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .position-left {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .position-right {
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        color: #9d174d;
    }

    /* Link Badge */
    .link-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.5rem 0.75rem;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e40af;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .link-badge:hover {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        transform: translateY(-1px);
    }

    .no-link {
        color: #94a3b8;
        font-size: 0.85rem;
        font-style: italic;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.active {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-badge.inactive {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    /* Date Text */
    .date-text {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 0.85rem;
        border-radius: 8px;
        font-size: 0.8rem;
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

    [dir="rtl"] .action-buttons {
        flex-direction: row-reverse;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .ad-thumbnail,
        .ad-thumbnail-placeholder {
            width: 100px;
            height: 65px;
        }
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-ad"></i>
            </div>
            <div>
                <h1>{{ __('messages.promotional_ads_management') }}</h1>
                <p>{{ __('messages.manage_promotional_ads_subtitle') }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.promotional-ads.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.add_promotional_ad') }}
            </a>
        </div>
    </div>
</div>

<!-- Statistics - Using unified admin-stats-grid component -->
@php
    $totalAds = $promotionalAds->total() ?? count($promotionalAds);
    $activeAds = \App\Models\PromotionalAd::where('is_active', true)->count();
    $leftAds = \App\Models\PromotionalAd::where('position', 'left')->where('is_active', true)->count();
    $rightAds = \App\Models\PromotionalAd::where('position', 'right')->where('is_active', true)->count();
@endphp
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-violet">
        <h4><i class="fas fa-ad"></i> {{ __('messages.total_ads') }}</h4>
        <div class="stat-value">{{ $totalAds }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_ads') }}</h4>
        <div class="stat-value">{{ $activeAds }}</div>
    </div>
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-arrow-left"></i> {{ __('messages.left_position') }}</h4>
        <div class="stat-value">{{ $leftAds }}</div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-arrow-right"></i> {{ __('messages.right_position') }}</h4>
        <div class="stat-value">{{ $rightAds }}</div>
    </div>
</div>

<!-- Promotional Ads Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> {{ __('messages.promotional_ad_list') }}</h3>
    </div>
    
    @if($promotionalAds->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('messages.image') }}</th>
                    <th>{{ __('messages.position') }}</th>
                    <th>{{ __('messages.link') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.updated_at') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotionalAds as $ad)
                <tr>
                    <td>
                        @if($ad->image_path)
                            <img src="{{ $ad->image_url }}" alt="{{ __('messages.promotional_ad') }}" class="ad-thumbnail">
                        @else
                            <div class="ad-thumbnail-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="position-badge position-{{ $ad->position }}">
                            <i class="fas fa-arrow-{{ $ad->position }}"></i>
                            {{ __('messages.' . $ad->position) }}
                        </span>
                    </td>
                    <td>
                        @if($ad->link)
                            <a href="{{ $ad->link }}" target="_blank" class="link-badge" title="{{ $ad->link }}">
                                <i class="fas fa-external-link-alt"></i>
                                {{ parse_url($ad->link, PHP_URL_HOST) ?? $ad->link }}
                            </a>
                        @else
                            <span class="no-link">{{ __('messages.no_link') }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $ad->is_active ? 'active' : 'inactive' }}">
                            <i class="fas {{ $ad->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $ad->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>
                    <td>
                        <span class="date-text">{{ $ad->updated_at->format('Y-m-d H:i') }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.promotional-ads.edit', $ad) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                            </a>
                            <form action="{{ route('admin.promotional-ads.destroy', $ad) }}" method="POST" style="display: inline;"
                                  onsubmit="handleFormConfirm(event, {
                                      message: '{{ __('messages.delete_promotional_ad_confirm') }}',
                                      confirmText: '{{ __('messages.yes_delete') }}',
                                      type: 'danger',
                                      confirmButtonType: 'danger'
                                  })">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($promotionalAds->hasPages())
    <div class="pagination-wrapper">
        {{ $promotionalAds->links() }}
    </div>
    @endif
    @else
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-ad"></i>
        </div>
        <h3>{{ __('messages.no_promotional_ads_found') }}</h3>
        <p>{{ __('messages.no_promotional_ads_description') }}</p>
        <a href="{{ route('admin.promotional-ads.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_promotional_ad') }}
        </a>
    </div>
    @endif
</div>
@endsection
