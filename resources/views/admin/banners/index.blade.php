@extends('admin.layout')

@section('title', __('messages.banners_management'))

@section('content')
<style>
    /* Banners Page Specific Styles */
    .banners-header {
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

    .banner-thumbnail {
        width: 120px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .banner-thumbnail-placeholder {
        width: 120px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4f8;
        color: #94a3b8;
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .source-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .source-badge.source-database {
        background: #dbeafe;
        color: #1e40af;
    }

    .source-badge.source-url {
        background: #d1fae5;
        color: #065f46;
    }

    .source-badge.source-file {
        background: #fef3c7;
        color: #92400e;
    }

    .image-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-start;
    }

    .banner-title-cell {
        max-width: 200px;
    }

    .banner-title-text {
        font-weight: 600;
        color: var(--dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .banner-subtitle-text {
        font-size: 12px;
        color: var(--secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        margin-top: 4px;
    }

    .order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        border-radius: 8px;
        font-weight: 700;
        color: var(--dark);
        font-size: 14px;
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
        <h1><i class="fas fa-images"></i> {{ __('messages.banners_management') }}</h1>
        <p>{{ __('messages.manage_banners_subtitle') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.banners.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_banner') }}
        </a>
    </div>
</div>

<!-- Stats Overview -->
@php
    $totalBanners = $banners->total() ?? count($banners);
    $activeBanners = \App\Models\Banner::where('is_active', true)->count();
    $inactiveBanners = \App\Models\Banner::where('is_active', false)->count();
@endphp
<div class="stats-overview">
    <div class="stat-mini-card">
        <h4><i class="fas fa-images"></i> {{ __('messages.total_banners') }}</h4>
        <div class="number">{{ $totalBanners }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--success);">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_banners') }}</h4>
        <div class="number" style="color: var(--success);">{{ $activeBanners }}</div>
    </div>
    <div class="stat-mini-card" style="border-left-color: var(--secondary);">
        <h4><i class="fas fa-eye-slash"></i> {{ __('messages.inactive_banners') }}</h4>
        <div class="number" style="color: var(--secondary);">{{ $inactiveBanners }}</div>
    </div>
</div>

<!-- Banners Table -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> {{ __('messages.banner_list') }}</h2>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($banners->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>{{ __('messages.image') }}</th>
                        <th>{{ __('messages.title') }}</th>
                        <th>{{ __('messages.link') }}</th>
                        <th>{{ __('messages.display_order') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td>
                                <div class="image-cell">
                                    @if($banner->image_path || $banner->image_data)
                                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title_en ?? 'Banner' }}" class="banner-thumbnail">
                                    @else
                                        <div class="banner-thumbnail-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    @php
                                        $sourceClass = match($banner->image_source) {
                                            'database' => 'source-database',
                                            'url' => 'source-url',
                                            default => 'source-file'
                                        };
                                        $sourceIcon = match($banner->image_source) {
                                            'database' => 'fa-database',
                                            'url' => 'fa-link',
                                            default => 'fa-file-image'
                                        };
                                    @endphp
                                    <span class="source-badge {{ $sourceClass }}">
                                        <i class="fas {{ $sourceIcon }}"></i>
                                        {{ $banner->image_source }}
                                    </span>
                                </div>
                            </td>
                            <td class="banner-title-cell">
                                <span class="banner-title-text" title="{{ $banner->title_en ?? $banner->title_ar ?? $banner->title_he ?? __('messages.no_title') }}">
                                    {{ $banner->title_en ?? $banner->title_ar ?? $banner->title_he ?? __('messages.no_title') }}
                                </span>
                                @if($banner->subtitle_en || $banner->subtitle_ar || $banner->subtitle_he)
                                    <span class="banner-subtitle-text" title="{{ $banner->subtitle_en ?? $banner->subtitle_ar ?? $banner->subtitle_he }}">
                                        {{ $banner->subtitle_en ?? $banner->subtitle_ar ?? $banner->subtitle_he }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="link-badge" title="{{ $banner->link }}">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ parse_url($banner->link, PHP_URL_HOST) ?? $banner->link }}
                                    </a>
                                @else
                                    <span class="no-link">{{ __('messages.no_link') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="order-badge">{{ $banner->display_order }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $banner->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $banner->is_active ? __('messages.active') : __('messages.inactive') }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" style="display: inline;"
                                          onsubmit="handleFormConfirm(event, {
                                              message: '{{ __('messages.delete_banner_confirm') }}',
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
                <i class="fas fa-images"></i>
                <h3>{{ __('messages.no_banners_found') }}</h3>
                <p>{{ __('messages.no_banners_description') }}</p>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_banner') }}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($banners->hasPages())
    <div style="margin-top: 24px;">
        {{ $banners->links() }}
    </div>
@endif

@endsection
