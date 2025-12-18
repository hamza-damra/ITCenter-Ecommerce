@extends('admin.layout')

@section('title', __('messages.banners_management'))

@section('content')
<style>
    /* Page-specific styles that extend unified components */
    
    /* Image Cell */
    .image-cell {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }

    .banner-thumbnail {
        width: 120px;
        height: 60px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .banner-thumbnail-placeholder {
        width: 120px;
        height: 60px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #94a3b8;
        font-size: 1.25rem;
        border-radius: 10px;
        border: 2px dashed #cbd5e1;
    }

    .banner-thumbnail-placeholder small {
        font-size: 0.65rem;
        margin-top: 0.25rem;
    }

    .source-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.6rem;
        border-radius: 15px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .source-badge.source-database {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .source-badge.source-url {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .source-badge.source-legacy {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
    }

    /* Title Cell */
    .banner-title-cell {
        max-width: 200px;
    }

    .banner-title-text {
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .banner-subtitle-text {
        font-size: 0.8rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        margin-top: 0.25rem;
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

    /* Order Badge */
    .order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 10px;
        font-weight: 700;
        color: #475569;
        font-size: 0.95rem;
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

    /* Action Buttons */
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

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        border-top: 1px solid #f1f5f9;
    }

    /* RTL Support */
    [dir="rtl"] .image-cell {
        align-items: flex-end;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .banner-thumbnail,
        .banner-thumbnail-placeholder {
            width: 100px;
            height: 50px;
        }
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-images"></i>
            </div>
            <div>
                <h1>{{ __('messages.banners_management') }}</h1>
                <p>{{ __('messages.manage_banners_subtitle') }}</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.banners.create') }}" class="btn-add">
                <i class="fas fa-plus-circle"></i> {{ __('messages.add_new_banner') }}
            </a>
        </div>
    </div>
</div>

<!-- Statistics - Using unified admin-stats-grid component -->
@php
    $totalBanners = $banners->total() ?? count($banners);
    $activeBanners = \App\Models\Banner::where('is_active', true)->count();
    $inactiveBanners = \App\Models\Banner::where('is_active', false)->count();
@endphp
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-info">
        <h4><i class="fas fa-images"></i> {{ __('messages.total_banners') }}</h4>
        <div class="stat-value">{{ $totalBanners }}</div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-check-circle"></i> {{ __('messages.active_banners') }}</h4>
        <div class="stat-value">{{ $activeBanners }}</div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-eye-slash"></i> {{ __('messages.inactive_banners') }}</h4>
        <div class="stat-value">{{ $inactiveBanners }}</div>
    </div>
</div>

<!-- Banners Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> {{ __('messages.banner_list') }}</h3>
    </div>
    
    @if($banners->count() > 0)
    <div class="table-responsive">
        <table class="admin-table">
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
                            @php
                                $hasValidImage = $banner->isImageInDatabase() || $banner->isImageFromUrl() || $banner->isImageInFile();
                            @endphp
                            @if($hasValidImage)
                                <img src="{{ $banner->image_url }}" 
                                     alt="{{ $banner->title_en ?? 'Banner' }}" 
                                     class="banner-thumbnail"
                                     onerror="this.onerror=null; this.src='{{ asset('images/assets/Banner.jpg') }}';">
                            @else
                                <div class="banner-thumbnail-placeholder">
                                    <i class="fas fa-image"></i>
                                    <small>{{ __('messages.no_image') }}</small>
                                </div>
                            @endif
                            @php
                                $sourceClass = match($banner->image_source) {
                                    'database' => 'source-database',
                                    'url' => 'source-url',
                                    default => 'source-legacy'
                                };
                                $sourceIcon = match($banner->image_source) {
                                    'database' => 'fa-database',
                                    'url' => 'fa-link',
                                    default => 'fa-exclamation-triangle'
                                };
                            @endphp
                            <span class="source-badge {{ $sourceClass }}" @if($banner->image_source === 'file') title="{{ __('messages.legacy_file_warning') }}" @endif>
                                <i class="fas {{ $sourceIcon }}"></i>
                                {{ $banner->image_source_label }}
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
                        <span class="status-badge {{ $banner->is_active ? 'active' : 'inactive' }}">
                            <i class="fas {{ $banner->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $banner->is_active ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn-action btn-edit">
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

    @if($banners->hasPages())
    <div class="pagination-wrapper">
        {{ $banners->links() }}
    </div>
    @endif
    @else
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-images"></i>
        </div>
        <h3>{{ __('messages.no_banners_found') }}</h3>
        <p>{{ __('messages.no_banners_description') }}</p>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> {{ __('messages.create_first_banner') }}
        </a>
    </div>
    @endif
</div>
@endsection
