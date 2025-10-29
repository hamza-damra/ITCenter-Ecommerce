@extends('layouts.app')

@section('title', __t('messages.home') . ' - IT Center')

@section('content')
<style>
    /* Import Google Font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Override font - exclude Font Awesome icons */
    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    /* Hero Section - Slider */
    .hero-section {
        padding: 0;
        margin: 1.5rem 1.5rem 3rem 1.5rem;
        border-radius: 20px;
        position: relative;
        height: 500px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        max-width: 100%;
        width: calc(100% - 3rem);
    }

    .hero-slider {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .hero-slide.active {
        opacity: 1;
        z-index: 1;
    }

    .hero-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .hero-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        height: 100%;
        position: relative;
        z-index: 2;
        padding: 6rem 2rem;
    }

    .hero-content {
        max-width: 600px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 2;
        display: none;
    }

    /* Slider Navigation Controls */
    .slider-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 10;
    }

    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        border: 2px solid rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .slider-dot:hover {
        background: rgba(255, 255, 255, 0.6);
        transform: scale(1.2);
    }

    .slider-dot.active {
        background: #fff;
        width: 30px;
        border-radius: 6px;
    }

    /* Slider Arrow Controls */
    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .slider-arrow:hover {
        background: rgba(255, 255, 255, 0.4);
        border-color: rgba(255, 255, 255, 0.8);
        transform: translateY(-50%) scale(1.1);
    }

    .slider-arrow i {
        font-size: 20px;
        color: #fff;
    }

    .slider-arrow.prev {
        left: 30px;
    }

    .slider-arrow.next {
        right: 30px;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        font-weight: 700;
    }

    .hero-content p {
        color: rgba(255,255,255,1);
        font-size: 1.2rem;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .hero-btn {
        background: #fff;
        color: #333;
        padding: 1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        background: #f0f0f0;
    }

    /* Section Header - Used by multiple sections */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #333;
        font-weight: 700;
    }

    .view-more {
        color: #666;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .view-more:hover {
        color: #6366f1;
    }

    /* RTL Support for arrows */
    [dir="rtl"] .view-more {
        flex-direction: row-reverse;
    }

    /* Featured Brands Slider */
    .brands-section {
        background: #1a1a1a;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .brands-section .section-header h2 {
        color: #fff;
    }

    .brands-section .view-more {
        color: #aaa;
    }

    .brands-section .view-more:hover {
        color: #fff;
    }

    .brands-slider {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 1rem 0;
        scrollbar-width: none;
        /* Fix for RTL horizontal scrolling - force LTR for scrolling */
        direction: ltr !important;
    }
    
    /* Restore RTL for brand cards content */
    html[dir="rtl"] .brands-slider .brand-card,
    [dir="rtl"] .brands-slider .brand-card {
        direction: rtl;
    }

    .brands-slider::-webkit-scrollbar {
        display: none;
    }

    .brand-card {
        min-width: 200px;
        height: 120px;
        background: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .brand-card:hover {
        transform: scale(1.05);
    }

    .brand-card img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Banner Sliders */
    .banners-section {
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .banners-slider {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .banner-large, .banner-small {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .banner-large {
        height: 350px;
    }

    .banner-small {
        height: 350px;
    }

    .banner-large:hover, .banner-small:hover {
        transform: scale(1.02);
    }

    .banner-large img, .banner-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Special Cards Grid */
    .special-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .special-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
        cursor: pointer;
        transition: all 0.3s;
    }

    .special-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.2), 0 4px 16px rgba(0,0,0,0.12);
    }

    .special-card-image {
        height: 250px;
        position: relative;
        overflow: hidden;
    }

    .special-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease-in-out, filter 0.4s ease-in-out;
        will-change: transform;
    }

    .special-card:hover .special-card-image img {
        transform: scale(1.08);
        filter: brightness(1.05);
    }

    .special-card-content {
        padding: 1.5rem;
    }

    .special-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .special-card-subtitle {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .special-card-btn {
        color: #e69270ff;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }



    /* Explore Products Section - Carousel Design (Redragon Style) */
    .explore-products-section {
        padding: 4rem 0;
        background: #ffffff;
        position: relative;
    }

    /* Discover Header with Decorative Elements */
    .discover-header {
        text-align: center;
        margin-bottom: 3rem;
        position: relative;
    }

    .discover-icon {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .discover-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 1rem 0;
        letter-spacing: -0.02em;
        text-transform: capitalize;
    }

    .discover-underline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .underline-bar {
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #e74c3c, transparent);
    }

    .underline-dot {
        width: 8px;
        height: 8px;
        background: #e74c3c;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
    }

    /* Category Carousel Wrapper */
    .category-carousel-wrapper {
        position: relative;
        padding: 0 60px;
        margin-bottom: 2rem;
    }

    /* Navigation Buttons */
    .carousel-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #e5e7eb;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .carousel-nav-btn:hover {
        background: #e74c3c;
        border-color: #e74c3c;
        color: #ffffff;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .carousel-prev {
        left: 0;
    }

    .carousel-next {
        right: 0;
    }

    /* Carousel Container */
    .category-carousel-container {
        overflow: hidden;
        width: 100%;
    }

    .category-carousel-track {
        display: flex;
        gap: 1.5rem;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Category Carousel Card */
    .category-carousel-card {
        flex: 0 0 calc(25% - 1.125rem);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 1rem;
        border-radius: 12px;
        background: #ffffff;
    }

    .category-carousel-card:hover {
        transform: translateY(-10px);
    }

    .category-carousel-image {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
        border-radius: 8px;
        background: #f9fafb;
    }

    .category-carousel-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
    }

    .category-carousel-card:hover .category-carousel-image img {
        transform: scale(1.1);
    }

    .category-carousel-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        text-align: center;
        transition: color 0.3s ease;
        margin-top: 0.5rem;
    }

    .category-carousel-card:hover .category-carousel-name {
        color: #e74c3c;
    }

    /* Pagination Dots */
    .category-carousel-dots {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d1d5db;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-dot:hover {
        background: #9ca3af;
        transform: scale(1.2);
    }

    .carousel-dot.active {
        background: #e74c3c;
        width: 30px;
        border-radius: 5px;
    }

    /* Responsive Design for Category Carousel */
    @media (max-width: 1023px) {
        .category-carousel-card {
            flex: 0 0 calc(33.333% - 1rem);
        }

        .discover-title {
            font-size: 1.875rem;
        }

        .category-carousel-wrapper {
            padding: 0 50px;
        }

        .carousel-nav-btn {
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 767px) {
        .category-carousel-card {
            flex: 0 0 calc(50% - 0.75rem);
        }

        .explore-products-section {
            padding: 3rem 0;
        }

        .discover-title {
            font-size: 1.5rem;
        }

        .discover-header {
            margin-bottom: 2rem;
        }

        .category-carousel-wrapper {
            padding: 0 45px;
        }

        .carousel-nav-btn {
            width: 35px;
            height: 35px;
            font-size: 0.875rem;
        }

        .category-carousel-name {
            font-size: 1rem;
        }
    }

    @media (max-width: 479px) {
        .category-carousel-card {
            flex: 0 0 100%;
        }

        .category-carousel-wrapper {
            padding: 0 40px;
        }

        .discover-icon svg {
            width: 30px;
            height: 30px;
        }
    }

    /* Popular Tags */
    .popular-tags {
        background: #1a1a1a;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 3rem;
    }

    .popular-tags h3 {
        color: #fff;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        font-style: italic;
        font-weight: 700;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .tag {
        background: rgba(255,255,255,0.1);
        color: #fff;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .tag:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }

    /* Product Grid - Modern Responsive Layout */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
        padding: 0.5rem;
    }

    .product-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid rgba(0, 0, 0, 0.06);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    /* Subtle gradient overlay on hover */
    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(39, 98, 243, 0.03) 0%, rgba(26, 77, 191, 0.02) 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
        pointer-events: none;
        border-radius: 20px;
    }

    /* Glowing border effect on hover */
    .product-card::after {
        content: '';
        position: absolute;
        top: -1px;
        left: -1px;
        right: -1px;
        bottom: -1px;
        background: linear-gradient(135deg, #2762f3, #1a4dbf, #64748b);
        border-radius: 20px;
        opacity: 0;
        z-index: -1;
        transition: opacity 0.35s ease;
    }

    .product-card:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 20px 40px rgba(39, 98, 243, 0.08), 0 8px 16px rgba(0, 0, 0, 0.06);
        border-color: rgba(39, 98, 243, 0.15);
    }

    .product-card:hover::before {
        opacity: 1;
    }

    .product-card:hover::after {
        opacity: 0.4;
    }

    .product-image {
        width: 100%;
        height: 240px;
        background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 1rem;
    }


    .product-image img {
        max-width: 85%;
        max-height: 85%;
        object-fit: contain;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        transition: transform 0.4s ease-in-out, filter 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
        filter: brightness(1);
        will-change: transform;
    }

    .product-card:hover .product-image img {
        transform: scale(1.08);
        filter: brightness(1.05);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .wishlist-btn {
        position: absolute !important;
        top: 14px !important;
        bottom: auto !important;
        @if(is_rtl())
        left: 14px !important;
        right: auto !important;
        @else
        right: 14px !important;
        left: auto !important;
        @endif
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .wishlist-btn:hover {
        background: rgba(255, 255, 255, 0.98);
        transform: scale(1.12);
        box-shadow: 0 4px 16px rgba(39, 98, 243, 0.18);
        border-color: rgba(39, 98, 243, 0.2);
    }

    .wishlist-btn:hover i {
        color: #2762f3 !important;
    }

    .wishlist-btn.active {
        background: rgba(39, 98, 243, 0.1) !important;
        border-color: #2762f3 !important;
    }

    .wishlist-btn.active i {
        color: #2762f3 !important;
    }

    .wishlist-btn i {
        font-size: 1rem;
        color: #64748b;
        transition: all 0.3s ease;
    }

    /* Solid heart icon should be blue */
    .wishlist-btn i.fas.fa-heart {
        color: #2762f3 !important;
    }

    .product-badge {
        position: absolute !important;
        top: 14px !important;
        bottom: auto !important;
        @if(is_rtl())
        right: 14px !important;
        left: auto !important;
        @else
        left: 14px !important;
        right: auto !important;
        @endif
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: #fff;
        padding: 0.35rem 0.75rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.3);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .product-info {
        padding: 1.25rem 1.25rem 1.25rem 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        background: transparent;
    }

    .product-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        color: #1e293b;
        text-align: start;
        line-height: 1.35;
        transition: color 0.3s ease;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.7rem;
    }

    .product-card:hover .product-title {
        color: #2762f3;
    }

    .product-description {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.875rem;
        line-height: 1.4;
        text-align: start;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-footer {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
        align-items: stretch;
        margin-top: auto;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.04);
    }

    .product-price {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        text-align: start;
        display: flex;
        flex-direction: row;
        align-items: baseline;
        gap: 0.5rem;
    }

    .product-price .original-price {
        text-decoration: line-through;
        color: #94a3b8;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .product-price .discount-percentage {
        font-size: 0.75rem;
        color: #10b981;
        font-weight: 600;
        background: rgba(16, 185, 129, 0.1);
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        margin-left: auto;
    }

    /* Modern Outlined Button with Glow Effect */
    .add-to-cart {
        background: transparent;
        color: #2762f3;
        padding: 0.65rem 1.25rem;
        border-radius: 50px;
        border: 1.5px solid #2762f3;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        white-space: nowrap;
        font-size: 0.85rem;
        box-shadow: 0 0 0 rgba(39, 98, 243, 0);
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
    }

    /* Glowing background on hover */
    .add-to-cart::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
        z-index: -1;
    }

    .add-to-cart:hover {
        color: #ffffff;
        border-color: #2762f3;
        box-shadow: 0 0 20px rgba(39, 98, 243, 0.4), 0 4px 12px rgba(39, 98, 243, 0.2);
        transform: translateY(-1px);
    }

    .add-to-cart:hover::before {
        opacity: 1;
    }

    .add-to-cart:active {
        transform: translateY(0);
    }

    /* Success state - filled green */
    .add-to-cart.in-cart {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
        color: #ffffff;
    }

    .add-to-cart.in-cart::before {
        opacity: 1;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .add-to-cart.in-cart:hover {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4), 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .add-to-cart.in-cart i {
        animation: cartBounce 0.5s ease;
    }

    /* Out of stock state - outlined orange */
    .add-to-cart.out-of-stock {
        background: transparent;
        color: #f97316;
        border-color: #f97316;
        cursor: not-allowed;
    }

    .add-to-cart.out-of-stock::before {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }

    .add-to-cart.out-of-stock:hover {
        color: #ffffff;
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.3), 0 4px 12px rgba(249, 115, 22, 0.15);
    }

    .add-to-cart.out-of-stock:hover::before {
        opacity: 1;
    }

    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    /* Tablet Responsive */
    @media (max-width: 1200px) {
        .hero-section {
            height: 400px;
            margin: 1rem 1rem 2rem 1rem;
        }
        
        .special-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .product-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            padding: 0.25rem;
        }

        .product-card {
            border-radius: 18px;
        }

        .product-image {
            height: 220px;
        }

        .product-info {
            padding: 1.125rem;
        }

        .product-title {
            font-size: 0.9rem;
            min-height: 2.5rem;
        }

        .product-description {
            font-size: 0.75rem;
        }
    }

    /* Mobile Landscape */
    @media (max-width: 968px) {
        .container {
            padding: 0 1.5rem;
        }
        
        .hero-section {
            height: 350px;
            margin: 1rem 1rem 2rem 1rem;
        }
        
        .hero-content h1 {
            font-size: 2.5rem;
        }
        
        .hero-content p {
            font-size: 1rem;
        }
        
        .section-header h2 {
            font-size: 1.8rem;
        }
        
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 0.25rem;
        }

        .product-card {
            border-radius: 16px;
        }

        .product-image {
            height: 200px;
        }

        .product-info {
            padding: 1rem;
        }

        .product-title {
            font-size: 0.875rem;
            min-height: 2.4rem;
        }

        .product-price {
            font-size: 1.2rem;
        }

        .add-to-cart {
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
        }
        
        .product-card {
            border-radius: 8px;
        }
        
        .product-image {
            height: 200px;
        }
        
        .product-title {
            font-size: 0.95rem;
        }
        
        .product-price {
            font-size: 1.3rem;
        }
        
        .add-to-cart {
            padding: 0.75rem 1.2rem;
            font-size: 0.9rem;
        }
        
        .banners-slider {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .banner-large,
        .banner-small {
            height: 250px;
        }
        
        .special-cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .featured-section {
            padding: 2rem 0;
        }
    }

    /* Mobile Portrait */
    @media (max-width: 768px) {
        .container {
            padding: 0 1rem;
            max-width: 100%;
        }
        
        .hero-section {
            height: 300px;
            margin: 0.5rem;
            border-radius: 15px;
            width: calc(100% - 1rem);
        }
        
        .hero-content h1 {
            font-size: 2rem;
        }
        
        .hero-content p {
            font-size: 0.9rem;
        }
        
        .hero-btn {
            padding: 0.8rem 2rem;
            font-size: 0.9rem;
        }
        
        .slider-arrow {
            width: 40px;
            height: 40px;
        }
        
        .slider-arrow.prev {
            left: 15px;
        }
        
        .slider-arrow.next {
            right: 15px;
        }
        
        .slider-dots {
            bottom: 15px;
            gap: 8px;
        }
        
        .slider-dot {
            width: 10px;
            height: 10px;
        }
        
        .categories-section {
            padding: 2rem 0;
        }
        
        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .section-header h2 {
            font-size: 1.5rem;
        }
        
        .view-more {
            font-size: 0.9rem;
        }
        
        .categories-wrapper {
            padding: 0 40px;
        }
        
        .category-nav-btn {
            width: 35px;
            height: 35px;
        }
        
        .category-nav-btn i {
            font-size: 1rem;
        }
        
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
            padding: 0.25rem;
        }
        
        .product-card {
            border-radius: 14px;
        }
        
        .product-image {
            height: 180px;
        }
        
        .product-badge {
            font-size: 0.65rem;
            padding: 0.3rem 0.65rem;
            border-radius: 10px;
            top: 10px;
            @if(is_rtl())
            right: 10px;
            @else
            left: 10px;
            @endif
        }
        
        .wishlist-btn {
            width: 32px;
            height: 32px;
            top: 10px;
            @if(is_rtl())
            left: 10px;
            @else
            right: 10px;
            @endif
        }

        .wishlist-btn i {
            font-size: 0.9rem;
        }

        .product-info {
            padding: 0.875rem;
        }

        .product-title {
            font-size: 0.85rem;
            min-height: 2.2rem;
        }

        .product-description {
            font-size: 0.725rem;
            margin-bottom: 0.75rem;
        }

        .product-price {
            font-size: 1.15rem;
        }

        .add-to-cart {
            padding: 0.55rem 0.875rem;
            font-size: 0.75rem;
        }
        
        .wishlist-btn i {
            font-size: 1rem;
        }
        
        .product-info {
            padding: 1rem;
        }
        
        .product-title {
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }
        
        .product-description {
            font-size: 0.8rem;
            margin-bottom: 0.8rem;
        }
        
        .product-footer {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .product-price {
            font-size: 1.2rem;
            width: 100%;
            align-items: center;
        }
        
        .product-price .original-price {
            font-size: 0.9rem;
        }
        
        .add-to-cart {
            width: 100%;
            padding: 0.7rem 1rem;
            font-size: 0.85rem;
            min-width: unset;
        }
        
        .banners-slider {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .banner-large,
        .banner-small {
            height: 200px;
        }
        
        .special-cards {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .brands-section {
            padding: 2rem 0;
        }
        
        .brand-card {
            min-width: 150px;
            height: 100px;
            padding: 1rem;
        }
        
        .popular-tags {
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .popular-tags h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        
        .tag {
            font-size: 0.8rem;
            padding: 0.4rem 1rem;
        }
    }

    /* Small Mobile */
    @media (max-width: 480px) {
        .container {
            padding: 0 0.8rem;
        }
        
        .hero-section {
            height: 250px;
            margin: 0.5rem;
            border-radius: 12px;
            width: calc(100% - 1rem);
        }
        
        .hero-content h1 {
            font-size: 1.5rem;
        }
        
        .hero-content p {
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
        
        .slider-arrow {
            width: 35px;
            height: 35px;
        }
        
        .slider-arrow i {
            font-size: 16px;
        }
        
        .slider-arrow.prev {
            left: 10px;
        }
        
        .slider-arrow.next {
            right: 10px;
        }
        
        .slider-dots {
            bottom: 10px;
            gap: 6px;
        }
        
        .slider-dot {
            width: 8px;
            height: 8px;
        }
        
        .slider-dot.active {
            width: 20px;
        }
        
        .section-header h2 {
            font-size: 1.3rem;
        }
        
        .categories-wrapper {
            padding: 0 35px;
        }
        
        .category-nav-btn {
            width: 30px;
            height: 30px;
        }
        
        .product-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 0.25rem;
        }
        
        .product-card {
            max-width: 100%;
            border-radius: 16px;
        }
        
        .product-image {
            height: 240px;
        }
        
        .product-info {
            padding: 1rem;
        }
        
        .product-title {
            font-size: 0.95rem;
            min-height: auto;
        }
        
        .product-description {
            font-size: 0.8rem;
            margin-bottom: 0.875rem;
        }
        
        .product-price {
            font-size: 1.3rem;
        }

        .product-footer {
            gap: 0.75rem;
        }
        
        .add-to-cart {
            padding: 0.7rem 1.25rem;
            font-size: 0.85rem;
        }
    }

    /* Extra Small Mobile */
    @media (max-width: 360px) {
        .container {
            padding: 0 0.5rem;
        }
        
        .hero-section {
            height: 200px;
            margin: 0.5rem;
            width: calc(100% - 1rem);
        }
        
        .hero-content h1 {
            font-size: 1.3rem;
        }
        
        .section-header h2 {
            font-size: 1.2rem;
        }
        
        .product-image {
            height: 200px;
        }
        
        .product-title {
            font-size: 0.95rem;
        }
        
        .product-price {
            font-size: 1.3rem;
        }
    }

    /* Special Offer Card */
    .special-offer-section {
        background: transparent;
        padding: 3rem 0;
        margin-bottom: 3rem;
        width: 100%;
        overflow-x: hidden;
    }

    .special-offer-card {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        width: 100%;
        box-sizing: border-box;
    }

    .offer-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: 3px solid #FFD700;
        border-radius: 24px;
        padding: 0;
        position: relative;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        transition: all 0.4s ease;
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        min-height: 500px;
    }

    .offer-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 70px rgba(102, 126, 234, 0.4);
    }

    .offer-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .offer-left-section {
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        z-index: 1;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
    }

    .offer-right-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        padding: 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        border-radius: 0 21px 21px 0;
    }

    .offer-right-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(102,126,234,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .discount-badge {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #000;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 900;
        font-size: 1.1rem;
        position: absolute;
        top: -15px;
        @if(is_rtl())
        right: 30px;
        @else
        left: 30px;
        @endif
        box-shadow: 0 8px 25px rgba(255, 165, 0, 0.5);
        animation: pulse 2s infinite;
        z-index: 100;
        letter-spacing: 1px;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    .offer-header {
        margin-bottom: 2rem;
    }

    .offer-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #ffffff;
        margin: 0 0 1rem 0;
        line-height: 1.2;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .offer-product-name {
        font-size: 1.4rem;
        font-weight: 600;
        color: rgba(255,255,255,0.9);
        margin-bottom: 2rem;
        line-height: 1.4;
    }

    .offer-pricing {
        display: flex;
        align-items: baseline;
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .current-price {
        font-size: 3.5rem;
        font-weight: 900;
        color: #FFD700;
        margin: 0;
        text-shadow: 2px 2px 8px rgba(255, 215, 0, 0.4);
    }

    .original-price {
        font-size: 1.6rem;
        color: rgba(255,255,255,0.6);
        text-decoration: line-through;
        margin: 0;
        position: relative;
    }

    .savings-text {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.95rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
    }

    .urgency-message {
        text-align: center;
        color: #667eea;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .countdown-timer {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }

    .countdown-item {
        text-align: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 1.2rem 1rem;
        min-width: 85px;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .countdown-number {
        font-size: 2.5rem;
        font-weight: 900;
        color: #ffffff;
        display: block;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .countdown-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: rgba(255,255,255,0.9);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .countdown-separator {
        font-size: 2.5rem;
        font-weight: 900;
        color: #667eea;
        align-self: center;
        opacity: 0.7;
    }

    .offer-image {
        text-align: center;
        position: relative;
        z-index: 1;
        margin: 2rem 0;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .offer-image img {
        max-width: 100%;
        max-height: 280px;
        object-fit: contain;
        filter: drop-shadow(0 15px 35px rgba(255,255,255,0.2));
        transition: transform 0.3s ease;
    }

    .offer-image:hover img {
        transform: scale(1.05) rotate(-2deg);
    }

    .offer-cta {
        text-align: center;
        margin-top: auto;
    }

    .offer-btn {
        background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
        color: #fff;
        padding: 1.2rem 3rem;
        border: none;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        box-shadow: 0 8px 25px rgba(255, 71, 87, 0.4);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        overflow: hidden;
    }

    .offer-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .offer-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .offer-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 35px rgba(255, 71, 87, 0.5);
    }

    .offer-btn i {
        font-size: 1.2rem;
    }

    .offer-features {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    .offer-feature {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255,255,255,0.9);
        font-size: 0.95rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }

    .offer-feature i {
        color: #FFD700;
        font-size: 1.1rem;
    }

    /* Featured Products Section */
    .featured-section {
        background: #f8f9fa;
        padding: 3rem 0;
        margin-bottom: 3rem;
        width: 100%;
    }

    .featured-section .container {
        max-width: 1500px; /* give more room */
        margin: 0 auto;
        padding: 0 1rem; /* reduce side margins */
    }

    .featured-section .product-grid {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 1rem; /* reduce side margins */
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        align-items: stretch;
    }

    /* Promo card that spans two product rows */
    .promo-featured-card {
        grid-row: span 2;
        border: 1.5px solid rgba(15,23,42,.2); /* subtle dark line similar to site */
        border-radius: 18px;
        background: #ffffff; /* no colored background */
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 1rem;
    }
    .promo-featured-card .badge-save {
        position: absolute;
        top: 12px;
        {{ is_rtl() ? 'left' : 'right' }}: 12px;
        background: #f59e0b; /* keep a small warm badge */
        color: #fff;
        font-weight: 800;
        padding: .4rem .8rem;
        border-radius: 999px;
        font-size: .9rem;
        box-shadow: 0 6px 14px rgba(245,158,11,.35);
    }
    .promo-featured-card .promo-media {
        background: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        flex: 1 1 auto;
        border: 1px solid rgba(15,23,42,.06); /* light inner frame around image */
    }
    .promo-featured-card .promo-media img {
        width: 100%;
        height: 240px;
        object-fit: contain;
    }
    .promo-featured-card .promo-body { color: #1f2937; margin-top: 1rem; display: flex; flex-direction: column; gap: .75rem; }
    .promo-featured-card .promo-title { font-weight: 800; font-size: 1.1rem; margin-bottom: .25rem; text-align:center; }
    .promo-featured-card .promo-product-name { color:#6b7280; font-weight:600; font-size:.95rem; text-align:center; }
    .promo-featured-card .promo-prices { display: flex; align-items: baseline; justify-content:center; gap: .6rem; text-align:center; }
    .promo-featured-card .promo-prices .orig { text-decoration: line-through; opacity: .6; color: #6b7280; }
    .promo-featured-card .promo-prices .sale { font-size: 1.8rem; font-weight: 900; color: #e11d48; }
    .promo-featured-card .promo-cta { margin-top: .4rem; }
    .promo-featured-card .promo-cta a { display:block; text-align:center; padding:.8rem 1rem; background:#111827; color:#fff; border-radius:10px; font-weight:800; text-decoration:none; border: 1px solid #111827; }
    .promo-featured-card .promo-cta a:hover { background:#1f2937; border-color:#1f2937; }

    /* Countdown in promo card */
    .promo-countdown { margin-top: .25rem; }
    .promo-countdown .label { color:#6b7280; font-weight:600; font-size:.9rem; margin-bottom:.4rem; text-align:center; }
    .promo-countdown .boxes { display:flex; justify-content:center; gap:.5rem; }
    .promo-countdown .box { background:#f3f4f6; border:1px solid rgba(15,23,42,.12); border-radius:10px; padding:.6rem .8rem; min-width:60px; text-align:center; }
    .promo-countdown .num { font-size:1.2rem; font-weight:800; color:#111827; display:block; }
    .promo-countdown .unit { font-size:.7rem; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; display:block; margin-top:.15rem; }

    /* Mobile Responsive */
    @media (max-width: 968px) {
        .special-offer-section {
            padding: 2rem 0;
        }
        
        .special-offer-card {
            padding: 0 1rem;
        }

        .featured-section .product-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
        
        .offer-card {
            grid-template-columns: 1fr;
            border-width: 3px;
        }

        .offer-right-section {
            border-radius: 0 0 21px 21px;
        }
        
        .offer-left-section,
        .offer-right-section {
            padding: 2rem;
        }
        
        .offer-title {
            font-size: 2rem;
        }
        
        .current-price {
            font-size: 2.5rem;
        }
        
        .countdown-item {
            min-width: 70px;
            padding: 1rem 0.8rem;
        }
        
        .countdown-number {
            font-size: 2rem;
        }
        
        .offer-btn {
            padding: 1rem 2.5rem;
            font-size: 1rem;
        }

        .offer-image img {
            max-height: 250px;
        }
    }

    @media (max-width: 768px) {
        .special-offer-section {
            padding: 1.5rem 0;
        }
        
        .special-offer-card {
            padding: 0 1rem;
        }
        
        .offer-card {
            border-radius: 16px;
            border-width: 2px;
        }
        
        .offer-left-section,
        .offer-right-section {
            padding: 1.5rem;
        }
        
        .offer-title {
            font-size: 1.6rem;
        }
        
        .current-price {
            font-size: 2rem;
        }
        
        .original-price {
            font-size: 1.3rem;
        }
        
        .countdown-timer {
            gap: 0.5rem;
        }
        
        .countdown-item {
            min-width: 60px;
            padding: 0.6rem;
        }
        
        .countdown-number {
            font-size: 1.5rem;
        }
        
        .countdown-label {
            font-size: 0.65rem;
        }
        
        .countdown-separator {
            font-size: 1.8rem;
        }
        
        .offer-btn {
            padding: 1rem 2rem;
            font-size: 1rem;
        }
        
        .discount-badge {
            font-size: 1rem;
            padding: 0.7rem 1.2rem;
        }

        .featured-section .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
            padding: 0 .75rem;
        }
    }

    /* Scroll Animation - Bottom to Top */
    .scroll-animate {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .scroll-animate.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<!-- Hero Section - Slider -->
<div class="hero-section">
    <div class="hero-slider">
        <!-- Slide 1 - Banner.jpg -->
        <div class="hero-slide active" style="background-image: url('{{ asset('images/assets/Banner.jpg') }}');"></div>

        <!-- Slide 2 - wallpaper.png -->
        <div class="hero-slide" style="background-image: url('{{ asset('images/assets/wallpaper.png') }}');"></div>

        <!-- Slide 3 - wallpaper2.png -->
        <div class="hero-slide" style="background-image: url('{{ asset('images/assets/wallpaper2.png') }}');"></div>

        <!-- Navigation Arrows -->
        <div class="slider-arrow prev" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="slider-arrow next" onclick="changeSlide(1)">
            <i class="fas fa-chevron-right"></i>
        </div>

        <!-- Navigation Dots -->
        <div class="slider-dots">
            <div class="slider-dot active" onclick="goToSlide(0)"></div>
            <div class="slider-dot" onclick="goToSlide(1)"></div>
            <div class="slider-dot" onclick="goToSlide(2)"></div>
        </div>
    </div>
</div>

<!-- Explore Our Products Section - Carousel Design -->
<div class="explore-products-section">
    <div class="container">
        <!-- Section Header with Decorative Elements -->
        <div class="discover-header">
            <div class="discover-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#e74c3c" stroke="#e74c3c" stroke-width="2"/>
                </svg>
            </div>
            <h2 class="discover-title">{{ __t('messages.explore_our_products') }}</h2>
            <div class="discover-underline">
                <span class="underline-bar"></span>
                <span class="underline-dot"></span>
                <span class="underline-bar"></span>
            </div>
        </div>

        <!-- Category Carousel -->
        <div class="category-carousel-wrapper">
            <!-- Navigation Arrow - Left -->
            <button class="carousel-nav-btn carousel-prev" onclick="slideCategoryCarousel(-1)" aria-label="Previous categories">
                <i class="fas fa-chevron-left"></i>
            </button>

            <!-- Carousel Track Container -->
            <div class="category-carousel-container">
                <div class="category-carousel-track" id="categoryCarouselTrack">
                    @foreach($categories as $category)
                    <a href="{{ route('products', ['category' => $category->slug]) }}" class="category-carousel-card">
                        <div class="category-carousel-image">
                            @if($category->image)
                                @if(str_starts_with($category->image, 'http'))
                                    <img src="{{ $category->image }}" alt="{{ $category->name }}" loading="lazy">
                                @else
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" loading="lazy">
                                @endif
                            @else
                                <img src="https://via.placeholder.com/300x300/f3f4f6/9ca3af?text={{ urlencode($category->name) }}" alt="{{ $category->name }}" loading="lazy">
                            @endif
                        </div>
                        <div class="category-carousel-name">{{ $category->name }}</div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Navigation Arrow - Right -->
            <button class="carousel-nav-btn carousel-next" onclick="slideCategoryCarousel(1)" aria-label="Next categories">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Pagination Dots -->
        <div class="category-carousel-dots" id="categoryCarouselDots">
            <!-- Dots will be generated by JavaScript -->
        </div>
    </div>
</div>

<!-- Category Carousel JavaScript -->
<script>
(function() {
    let currentCategorySlide = 0;
    const track = document.getElementById('categoryCarouselTrack');
    const dotsContainer = document.getElementById('categoryCarouselDots');
    const cards = track.querySelectorAll('.category-carousel-card');
    const totalCards = cards.length;

    // Responsive slides per view
    function getSlidesPerView() {
        const width = window.innerWidth;
        if (width >= 1024) return 4; // Desktop
        if (width >= 768) return 3;  // Tablet
        if (width >= 480) return 2;  // Small tablet
        return 1;                     // Mobile
    }

    let slidesPerView = getSlidesPerView();
    let totalSlides = Math.ceil(totalCards / slidesPerView);

    // Initialize dots
    function initDots() {
        dotsContainer.innerHTML = '';
        totalSlides = Math.ceil(totalCards / slidesPerView);
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('div');
            dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
            dot.onclick = () => goToCategorySlide(i);
            dotsContainer.appendChild(dot);
        }
    }

    // Update carousel position
    function updateCarousel() {
        const cardWidth = cards[0].offsetWidth;
        const gap = 24; // 1.5rem gap
        const offset = -(currentCategorySlide * slidesPerView * (cardWidth + gap));
        track.style.transform = `translateX(${offset}px)`;

        // Update dots
        const dots = dotsContainer.querySelectorAll('.carousel-dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentCategorySlide);
        });
    }

    // Slide function
    window.slideCategoryCarousel = function(direction) {
        currentCategorySlide += direction;

        // Loop around
        if (currentCategorySlide < 0) {
            currentCategorySlide = totalSlides - 1;
        } else if (currentCategorySlide >= totalSlides) {
            currentCategorySlide = 0;
        }

        updateCarousel();
    };

    // Go to specific slide
    window.goToCategorySlide = function(index) {
        currentCategorySlide = index;
        updateCarousel();
    };

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const newSlidesPerView = getSlidesPerView();
            if (newSlidesPerView !== slidesPerView) {
                slidesPerView = newSlidesPerView;
                currentCategorySlide = 0;
                initDots();
                updateCarousel();
            }
        }, 250);
    });

    // Touch/Swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                slideCategoryCarousel(1); // Swipe left - next
            } else {
                slideCategoryCarousel(-1); // Swipe right - prev
            }
        }
    }

    // Auto-scroll (optional - uncomment to enable)
    // let autoScrollInterval = setInterval(() => {
    //     slideCategoryCarousel(1);
    // }, 5000);

    // Pause auto-scroll on hover
    // track.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
    // track.addEventListener('mouseleave', () => {
    //     autoScrollInterval = setInterval(() => slideCategoryCarousel(1), 5000);
    // });

    // Initialize
    initDots();
    updateCarousel();
})();
</script>

<style>
.promo-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #ff6b6b;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 10px rgba(255,107,107,0.4);
    z-index: 2;
}

[dir="rtl"] .promo-badge { right: auto; left: 20px; }

.promo-image {
    background: white;
    border-radius: 15px;
    padding: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.promo-image img {
    width: 100%;
    max-height: 220px;
    object-fit: contain;
    display: block;
}

.promo-content { color: white; }

.promo-header { margin-bottom: .5rem; }

.promo-product-name {
    font-size: .95rem;
    opacity: .9;
}

.promo-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
}

.promo-features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem 0;
}

.promo-features li {
    padding: 0.4rem 0;
    font-size: 0.95rem;
    opacity: 0.95;
}

.promo-features i {
    color: #4ade80;
    margin-inline-end: 0.5rem;
}

.promo-price {
    background: rgba(255,255,255,0.2);
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    backdrop-filter: blur(10px);
}

.price-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.original-price {
    font-size: 1.1rem;
    text-decoration: line-through;
    opacity: 0.8;
}

.sale-price {
    font-size: 1.8rem;
    font-weight: 800;
    color: #4ade80;
}

.savings {
    font-size: 0.9rem;
    color: #fbbf24;
    font-weight: 600;
}

.promo-btn {
    display: block;
    width: 100%;
    background: #ffffff;
    color: #3b82f6;
    text-align: center;
    padding: 1rem;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

/* removed old promo section styles */
</style>

<!-- Removed top categories section - keeping only the middle one -->

<!-- Featured Products Section -->
<div class="featured-section">
    <div class="container">
        <div class="product-grid" id="featuredProducts">
                @if(isset($promotionalOffers) && $promotionalOffers->count() > 0)
                    @php $promo = $promotionalOffers->first(); @endphp
                    <div class="promo-featured-card">
                        <div class="badge-save">{{ is_rtl() ? 'وفر' : 'Save' }} {{ round($promo->discount_percentage) }}%</div>
                        <div class="promo-media">
                            @php
                                $img = null;
                                if ($promo->product && $promo->product->main_image) {
                                    $path = $promo->product->main_image;
                                    if (str_starts_with($path, 'http')) {
                                        $img = $path;
                                    } elseif (str_starts_with($path, 'storage/') || str_starts_with($path, 'images/')) {
                                        $img = asset($path);
                                    } else {
                                        $img = asset('storage/' . $path);
                                    }
                                }
                            @endphp
                            <img src="{{ $img ?? asset('images/placeholder.png') }}" alt="{{ $promo->title }}">
                        </div>
                        <div class="promo-body">
                            <div class="promo-title">{{ $promo->title }}</div>
                            @if($promo->product)
                                <div class="promo-product-name">{{ $promo->product->name }}</div>
                            @endif
                            <div class="promo-prices">
                                <span class="orig">₪{{ number_format($promo->original_price, 0) }}</span>
                                <span class="sale">₪{{ number_format($promo->sale_price, 0) }}</span>
                            </div>
                            @if($promo->end_date)
                            <div class="promo-countdown" data-end="{{ optional($promo->end_date)->format('c') }}">
                                <div class="label">{{ is_rtl() ? 'العرض ينتهي خلال:' : 'Hurry up! Offer ends in:' }}</div>
                                <div class="boxes">
                                    <div class="box"><span class="num cd-hours">00</span><span class="unit">{{ is_rtl() ? 'ساعات' : 'HRS' }}</span></div>
                                    <div class="box"><span class="num cd-mins">00</span><span class="unit">{{ is_rtl() ? 'دقائق' : 'MINS' }}</span></div>
                                    <div class="box"><span class="num cd-secs">00</span><span class="unit">{{ is_rtl() ? 'ثواني' : 'SECS' }}</span></div>
                                </div>
                            </div>
                            @endif
                            @if($promo->product)
                            <div class="promo-cta">
                                <a href="{{ route('product.detail', $promo->product->slug) }}">
                                    @if(is_rtl())
                                        {{ 'اطلب الآن' }} <i class="fas fa-shopping-cart"></i>
                                    @else
                                        <i class="fas fa-shopping-cart"></i> {{ 'Order Now' }}
                                    @endif
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                @endif
                @foreach($featuredProducts->take(6) as $product)
                <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
                    <div class="product-image">
                        @if($product->is_new)
                        <div class="product-badge">{{ __t('messages.new') }}</div>
                        @elseif($product->sale_price && $product->sale_price < $product->price)
                        <div class="product-badge">{{ __t('messages.sale') }}</div>
                        @elseif($product->is_featured)
                        <div class="product-badge">{{ __t('messages.hot') }}</div>
                        @endif
                        <div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </div>
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-description">{{ Str::limit($product->short_description, 60) }}</div>
                        <div class="product-footer">
                            <div class="product-price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="original-price">₪ {{ number_format($product->price, 0) }}</span>
                                    <span>₪ {{ number_format($product->sale_price, 0) }}</span>
                                @else
                                    <span class="original-price" style="visibility: hidden;">₪ 0</span>
                                    <span>₪ {{ number_format($product->price, 0) }}</span>
                                @endif
                            </div>
                            @if($product->stock_status === 'out_of_stock')
                            <button class="add-to-cart out-of-stock"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    onclick="event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                                @if(is_rtl())
                                    {{ __t('messages.request_product') }} <i class="fas fa-bell"></i>
                                @else
                                    <i class="fas fa-bell"></i> {{ __t('messages.request_product') }}
                                @endif
                            </button>
                            @else
                            <button class="add-to-cart {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    data-original-text="{{ __t('messages.add_to_cart') }}"
                                    data-added-text="{{ __t('messages.in_cart') }}"
                                    onclick="event.stopPropagation(); addToCart({{ $product->id }}, this);">
                                @if(in_array($product->id, $cartProductIds))
                                    @if(is_rtl())
                                        {{ __t('messages.in_cart') }} <i class="fas fa-check"></i>
                                    @else
                                        <i class="fas fa-check"></i> {{ __t('messages.in_cart') }}
                                    @endif
                                @else
                                    @if(is_rtl())
                                        {{ __t('messages.add_to_cart') }} <i class="fas fa-shopping-cart"></i>
                                    @else
                                        <i class="fas fa-shopping-cart"></i> {{ __t('messages.add_to_cart') }}
                                    @endif
                                @endif
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Main Content Container -->
<div class="container">
    <!-- Featured Products - HORIZONTAL SCROLLER -->
    @if($featuredProducts->count() > 0)
    <x-horizontal-product-scroller 
        :products="$featuredProducts" 
        title="{{ __t('messages.featured_products') }}"
        :viewMoreUrl="route('products')"
        :autoScroll="true"
        :autoScrollInterval="4000"
        containerId="featured-products-scroller"
    />
    @endif



    <!-- New Arrivals - HORIZONTAL SCROLLER -->
    @if($newProducts->count() > 0)
    <x-horizontal-product-scroller 
        :products="$newProducts" 
        title="{{ __t('messages.new_arrivals') }}"
        :viewMoreUrl="route('products')"
        :autoScroll="true"
        :autoScrollInterval="5000"
        :cardsToScroll="2"
        containerId="new-arrivals-scroller"
    />
    @endif

    <!-- Bestsellers - HORIZONTAL SCROLLER -->
    @if($bestsellerProducts->count() > 0)
    <x-horizontal-product-scroller 
        :products="$bestsellerProducts" 
        title="{{ __t('messages.best_sellers') }}"
        :viewMoreUrl="route('products', ['filter' => 'bestseller'])"
        :autoScroll="true"
        :autoScrollInterval="6000"
        containerId="bestsellers-scroller"
    />
    @endif

    <!-- On Sale Products - HORIZONTAL SCROLLER -->
    @if($onSaleProducts->count() > 0)
    <x-horizontal-product-scroller 
        :products="$onSaleProducts" 
        title="{{ __t('messages.on_sale') }}"
        :viewMoreUrl="route('products', ['filter' => 'sale'])"
        :autoScroll="true"
        :autoScrollInterval="5000"
        :cardsToScroll="1"
        containerId="on-sale-scroller"
    />
    @endif
</div>
<!-- End Main Content Container -->

<script>
    // Store cart product IDs from server
    window.cartProductIds = @json($cartProductIds);
    
    document.addEventListener('DOMContentLoaded', function() {
        // Hide page loader when everything is ready
        const pageLoader = document.getElementById('page-loader');
        if (pageLoader) {
            // Wait a bit to ensure all resources are loaded
            setTimeout(() => {
                pageLoader.style.opacity = '0';
                pageLoader.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    pageLoader.style.display = 'none';
                }, 300);
            }, 100);
        }
        // Hero Slider Functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const totalSlides = slides.length;
        let slideInterval;

        // Function to change slide
        window.changeSlide = function(direction) {
            clearInterval(slideInterval);
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            updateSlider();
            startAutoSlide();
        }

        // Function to go to specific slide
        window.goToSlide = function(slideIndex) {
            clearInterval(slideInterval);
            currentSlide = slideIndex;
            updateSlider();
            startAutoSlide();
        }

        // Function to update slider display
        function updateSlider() {
            slides.forEach((slide, index) => {
                slide.classList.remove('active');
                dots[index].classList.remove('active');
            });
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        // Function to start auto sliding
        function startAutoSlide() {
            clearInterval(slideInterval);
            slideInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
            }, 5000); // Change slide every 5 seconds
        }

        // Start auto sliding
        startAutoSlide();

        // Pause auto sliding when mouse is over the slider
        const heroSection = document.querySelector('.hero-section');
        heroSection.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });

        // Resume auto sliding when mouse leaves the slider
        heroSection.addEventListener('mouseleave', () => {
            startAutoSlide();
        });

        // Scroll Animation - Bottom to Top
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observe all cards
        const animateElements = document.querySelectorAll('.product-card, .special-card');
        animateElements.forEach(el => {
            el.classList.add('scroll-animate');
            observer.observe(el);
        });

        // Add additional event listener for wishlist buttons to force color change
        document.addEventListener('DOMContentLoaded', function() {
            // Observer to watch for class changes on wishlist buttons
            const observeWishlistButtons = () => {
                const wishlistButtons = document.querySelectorAll('.wishlist-btn');
                wishlistButtons.forEach(button => {
                    // Create a MutationObserver for each button
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.attributeName === 'class') {
                                const icon = button.querySelector('i');
                                if (button.classList.contains('active')) {
                                    // Force red color when active
                                    if (icon) {
                                        icon.style.color = '#ff0000';
                                    }
                                } else {
                                    // Reset to gray when not active
                                    if (icon) {
                                        icon.style.color = '#666';
                                    }
                                }
                            }
                        });
                    });
                    
                    // Start observing
                    observer.observe(button, { attributes: true });
                });
            };
            
            // Initial observation
            setTimeout(observeWishlistButtons, 500);
        });

        // Countdown Timer Functionality
        function startCountdown() {
            const hoursElement = document.getElementById('hours');
            const minutesElement = document.getElementById('minutes');
            const secondsElement = document.getElementById('seconds');
            
            if (!hoursElement || !minutesElement || !secondsElement) return;
            
            // Set initial time (8 hours, 19 minutes, 36 seconds)
            let totalSeconds = 8 * 3600 + 19 * 60 + 36;
            
            function updateCountdown() {
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                
                hoursElement.textContent = hours.toString().padStart(2, '0');
                minutesElement.textContent = minutes.toString().padStart(2, '0');
                secondsElement.textContent = seconds.toString().padStart(2, '0');
                
                if (totalSeconds <= 0) {
                    // Reset timer when it reaches zero
                    totalSeconds = 8 * 3600 + 19 * 60 + 36;
                } else {
                    totalSeconds--;
                }
            }
            
            // Update immediately
            updateCountdown();
            
            // Update every second
            setInterval(updateCountdown, 1000);
        }

        // Initialize countdown when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();
            // Promo Featured countdown (boxed H/M/S under image)
            (function initPromoFeaturedCountdown(){
                const blocks = document.querySelectorAll('.promo-featured-card .promo-countdown[data-end]');
                if (!blocks.length) return;
                const update = () => {
                    blocks.forEach(block => {
                        const end = new Date(block.getAttribute('data-end')).getTime();
                        if (!end) return;
                        const now = Date.now();
                        let diff = Math.max(0, end - now);
                        const hours = Math.floor(diff / 3_600_000);
                        diff %= 3_600_000;
                        const mins = Math.floor(diff / 60_000);
                        const secs = Math.floor((diff % 60_000) / 1000);
                        const hEl = block.querySelector('.cd-hours');
                        const mEl = block.querySelector('.cd-mins');
                        const sEl = block.querySelector('.cd-secs');
                        if (hEl) hEl.textContent = String(hours).padStart(2,'0');
                        if (mEl) mEl.textContent = String(mins).padStart(2,'0');
                        if (sEl) sEl.textContent = String(secs).padStart(2,'0');
                    });
                };
                update();
                setInterval(update, 1000);
            })();
        });
    });
</script>

@endsection
