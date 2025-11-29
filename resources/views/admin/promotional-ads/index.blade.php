@extends('admin.layout')

@section('title', __('messages.promotional_ads_management'))

@section('content')
<style>
    /* Promotional Ads Page Specific Styles */
    .promo-ads-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        border-left: 4px solid var(--primary);
    }

    .stat-mini-card h4 {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    [dir="rtl"] .stat-mini-card h4 {
        text-transform: none;
        letter-spacing: normal;
    }

    .stat-mini-card .number {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
    }

    .ad-thumbnail {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .ad-thumbnail-placeholder {
        width: 120px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        color: #94a3b8;
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .position-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    [dir="rtl"] .position-badge {
        text-transform: none;
    }

    .position-left {
        background: #dbeafe;
        color: #1e40af;
    }

    .position-right {
        background: #fce7f3;
        color: #9d174d;
    }

    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
        display: block;
    }

    .empty-state h3 {
        font-size: 20px;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--secondary);
        margin-bottom: 24px;
    }

    .link-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        background: #eff6ff;
        color: var(--primary);
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .link-badge:hover {
        background: #dbeafe;
    }

    .no-link {
        color: #94a3b8;
        font-size: 12px;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .stats-overview {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-ad"></i> {{ __('messages.promotional_ads_management') }}</h1>
        <p>{{ __('messages.manage_promotional_ads_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.promotional-ads.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> {{ __('messages.add_promotional_ad') }}
        </a>
    </div>
</div>

<!-- Stats Overview -->
@php
    $totalAds = $promotionalAds->total() ?? count($promotionalAds);
    $activeAds = \App\Models\PromotionalAd::where('is_active', true)->count();
    $leftAds = \App\Models\PromotionalAd::where('position', 'left')->where('is_active', true)->count();
    $rightAds = \App\Models\PromotionalAd::where('position', 'right')->where('is_active', true)->count();
@endphp
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-ad"></i> {{ __('messages.total_ads') }}</h4>
        <div class="number">{{ $totalAds }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_ads') }}</h4>
        <div class="number" style="color: var(--success);">{{ $activeAds }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: #1e40af;">
        <h4><i class="fas fa-arrow-left"></i> {{ __('messages.left_position') }}</h4>
        <div class="number" style="color: #1e40af;">{{ $leftAds }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: #9d174d;">
        <h4><i class="fas fa-arrow-right"></i> {{ __('messages.right_position') }}</h4>
        <div class="number" style="color: #9d174d;">{{ $rightAds }}</div>
    </div>
</div>

<!-- Promotional Ads Table -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> {{ __('messages.promotional_ad_list') }}</h2>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($promotionalAds->count() > 0)
            <table>
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
                                <span class="badge {{ $ad->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $ad->is_active ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </td>
                            <td>
                                {{ $ad->updated_at->format('Y-m-d H:i') }}
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.promotional-ads.edit', $ad) }}" class="btn btn-primary btn-sm">
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
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fas fa-ad"></i>
                <h3>{{ __('messages.no_promotional_ads_found') }}</h3>
                <p>{{ __('messages.no_promotional_ads_description') }}</p>
                <a href="{{ route('admin.promotional-ads.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_promotional_ad') }}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($promotionalAds->hasPages())
    <div style="margin-top: 24px;">
        {{ $promotionalAds->links() }}
    </div>
@endif

@endsection
