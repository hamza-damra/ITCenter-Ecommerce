@extends('layouts.app')

@section('title', __('messages.categories') . ' - IT Center')

@section('content')
<style>
    .categories-section {
        padding: 3rem 2rem;
        background: #fff;
        direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #333;
    }

    .view-more {
        color: #333;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .view-more:hover {
        color: #667eea;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 3rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .category-item:hover {
        transform: translateY(-10px);
    }

    .category-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s;
        background: #fff;
    }

    .category-item:hover .category-icon {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .category-icon i {
        font-size: 3rem;
    }

    .category-name {
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
    }

    .icon-computers { color: #ff4757; }
    .icon-printer { color: #5f27cd; }
    .icon-mobile { color: #00d2d3; }
    .icon-bag { color: #1e90ff; }
    .icon-laptop { color: #ff6348; }
    .icon-accessories { color: #2e86de; }
    .icon-monitor { color: #341f97; }
    .icon-case { color: #ee5a6f; }
    .icon-motherboard { color: #0abde3; }
    .icon-cpu { color: #2d3436; }
    .icon-cooler { color: #00a8ff; }
    .icon-gpu { color: #e74c3c; }

    /* RTL Support */
    [dir="rtl"] .section-header {
        direction: rtl;
    }

    [dir="rtl"] .view-more {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .categories-grid {
        direction: rtl;
    }

    [dir="rtl"] .category-item {
        direction: rtl;
    }
</style>

<div class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>{{ __('messages.categories') }}</h2>
            <a href="#" class="view-more">
                {{ __('messages.view_more') }} <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
            </a>
        </div>

        <div class="categories-grid">
            @forelse($categories as $category)
            <div class="category-item" onclick="window.location.href='{{ route('products', ['category' => $category->slug]) }}'">
                <div class="category-icon">
                    @if($category->image)
                        @if(str_starts_with($category->image, 'http'))
                            <img src="{{ $category->image }}" alt="{{ $category->{'name_' . current_locale()} ?? $category->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            <img src="{{ asset($category->image) }}" alt="{{ $category->{'name_' . current_locale()} ?? $category->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @endif
                    @else
                        <i class="fas fa-folder icon-computers"></i>
                    @endif
                </div>
                <div class="category-name">{{ $category->{'name_' . current_locale()} ?? $category->name }}</div>
                @if($category->products_count > 0)
                    <small style="color: #999; font-size: 0.85rem;">({{ $category->products_count }} {{ $category->products_count == 1 ? __('messages.product') : __('messages.products') }})</small>
                @endif
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #999;">
                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                <p>{{ __('messages.no_categories') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
