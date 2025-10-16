{{-- Clean Brands View - No Inline CSS/JS --}}
@extends('layouts.app')

@section('title', __('Brands'))

@section('content')
    <div class="brands-page">
        {{-- Page Header --}}
        <header class="page-header">
            <h1>{{ __('Our Brands') }}</h1>
            <p>{{ __('Discover products from top brands') }}</p>
        </header>

        {{-- Featured Brands Section --}}
        @if($featuredBrands->isNotEmpty())
            <section class="featured-brands">
                <h2>{{ __('Featured Brands') }}</h2>
                <div class="brands-grid" data-component="brands-grid" data-type="featured">
                    @foreach($featuredBrands as $brand)
                        <article class="brand-card" data-brand-id="{{ $brand->id }}">
                            <a href="{{ route('brand.show', $brand->slug) }}" class="brand-link">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" 
                                         alt="{{ $brand->name }}" 
                                         class="brand-logo"
                                         loading="lazy">
                                @endif
                                <h3 class="brand-name">{{ $brand->name }}</h3>
                                <p class="products-count">{{ $brand->products_count }} {{ __('Products') }}</p>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- All Brands Section --}}
        <section class="all-brands">
            <h2>{{ __('All Brands') }}</h2>
            <div class="brands-grid" data-component="brands-grid" data-type="all">
                @forelse($brands as $brand)
                    <article class="brand-card" data-brand-id="{{ $brand->id }}">
                        <a href="{{ route('brand.show', $brand->slug) }}" class="brand-link">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" 
                                     alt="{{ $brand->name }}" 
                                     class="brand-logo"
                                     loading="lazy">
                            @endif
                            <h3 class="brand-name">{{ $brand->name }}</h3>
                            @if($brand->description)
                                <p class="brand-description">{{ Str::limit($brand->description, 100) }}</p>
                            @endif
                            <p class="products-count">{{ $brand->products_count }} {{ __('Products') }}</p>
                        </a>
                    </article>
                @empty
                    <div class="empty-state">
                        <p>{{ __('No brands available') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
