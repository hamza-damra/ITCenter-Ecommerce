{{-- Hero Section - Slider --}}
@if(isset($banners) && $banners->count() > 0)
    {{-- Preload LCP image (first banner) for faster Largest Contentful Paint --}}
    @push('head')
        <link rel="preload" as="image" href="{{ $banners->first()->image_url }}" fetchpriority="high">
    @endpush
    <div class="hero-section">
        <div class="hero-slider">
            @foreach($banners as $index => $banner)
                @if($banner->link)
                    <a href="{{ $banner->link }}" class="hero-slide {{ $index === 0 ? 'active' : '' }}"
                        style="background-image: url('{{ $banner->image_url }}');">
                @else
                        <div class="hero-slide {{ $index === 0 ? 'active' : '' }}"
                            style="background-image: url('{{ $banner->image_url }}');">
                    @endif
                        <div class="hero-slide-content">
                            @if($banner->title)
                                <h1 @if($banner->title_color) style="background: none; -webkit-background-clip: unset; background-clip: unset; -webkit-text-fill-color: {{ $banner->title_color }}; color: {{ $banner->title_color }};" @endif>{{ $banner->title }}</h1>
                            @endif
                            @if($banner->subtitle)
                                <p @if($banner->subtitle_color) style="color: {{ $banner->subtitle_color }};" @endif>{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->button_text)
                                <div class="hero-cta-buttons">
                                    @php
                                        $buttonStyle = '';
                                        if($banner->button_bg_color) {
                                            $buttonStyle .= "background: {$banner->button_bg_color}; ";
                                        }
                                        if($banner->button_text_color) {
                                            $buttonStyle .= "color: {$banner->button_text_color}; ";
                                        }
                                    @endphp
                                    @if($banner->link)
                                        {{-- Use span when slide is already wrapped in <a> to avoid nested links --}}
                                        <span class="hero-cta-btn primary" @if($buttonStyle) style="{{ $buttonStyle }}" @endif>
                                            <i class="fas fa-shopping-bag"></i>
                                            {{ $banner->button_text }}
                                        </span>
                                    @else
                                        <span class="hero-cta-btn primary" style="cursor: default; {{ $buttonStyle }}">
                                            <i class="fas fa-shopping-bag"></i>
                                            {{ $banner->button_text }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if($banner->link)
                            </a>
                        @else
                    </div>
                @endif
            @endforeach

        <!-- Navigation Arrows -->
        @if($banners->count() > 1)
            <div class="slider-arrow prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="slider-arrow next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </div>

            <!-- Navigation Dots -->
            <div class="slider-dots">
                @foreach($banners as $index => $banner)
                    <div class="slider-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
                @endforeach
            </div>
        @endif

        <!-- Progress Bar -->
        <div class="slider-progress">
            <div class="slider-progress-bar" id="sliderProgressBar"></div>
        </div>
    </div>
    </div>
@else
    <!-- Fallback: Static Hero Section when no banners exist -->
    <div class="hero-section">
        <div class="hero-slider">
            <div class="hero-slide active" style="background-image: url('{{ asset('images/assets/Banner.jpg') }}');">
                <div class="hero-slide-content">
                    <h1>{{ is_rtl() ? 'أحدث التقنيات' : 'Latest Technology' }}</h1>
                    <p>{{ is_rtl() ? 'اكتشف أفضل الأجهزة الإلكترونية والإكسسوارات بأسعار لا تقبل المنافسة' : 'Discover the best electronics and accessories at unbeatable prices' }}
                    </p>
                    <div class="hero-cta-buttons">
                        <a href="{{ route('products') }}" class="hero-cta-btn primary">
                            <i class="fas fa-shopping-bag"></i>
                            {{ is_rtl() ? 'تسوق الآن' : 'Shop Now' }}
                        </a>
                        <a href="{{ route('products', ['filter' => 'sale']) }}" class="hero-cta-btn secondary">
                            <i class="fas fa-tags"></i>
                            {{ is_rtl() ? 'العروض الخاصة' : 'Special Offers' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="slider-progress">
                <div class="slider-progress-bar" id="sliderProgressBar"></div>
            </div>
        </div>
    </div>
@endif
