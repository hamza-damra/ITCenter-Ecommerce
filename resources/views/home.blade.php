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

    /* Categories Section */
    .categories-section {
        background: #fff;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

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
        color: #e69270ff;
    }

    /* RTL Support for arrows */
    [dir="rtl"] .view-more {
        flex-direction: row-reverse;
    }

    .categories-wrapper {
        position: relative;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 60px;
    }

    .categories-grid {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        padding: 1rem 0;
        /* Fix for RTL horizontal scrolling - force LTR for scrolling */
        direction: ltr !important;
    }
    
    /* Restore RTL for category items content */
    html[dir="rtl"] .categories-grid .category-item,
    [dir="rtl"] .categories-grid .category-item {
        direction: rtl;
    }
    
    /* Ensure proper text alignment in RTL */
    html[dir="rtl"] .category-name,
    [dir="rtl"] .category-name {
        text-align: center;
        direction: rtl;
    }

    .categories-grid::-webkit-scrollbar {
        display: none;
    }

    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s;
        min-width: 120px;
        flex-shrink: 0;
    }

    .category-item:hover {
        transform: translateY(-5px);
    }

    .category-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        border: 2px solid #e0e0e0;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .category-nav-btn:hover {
        background: #e69270ff;
        border-color: #e69270ff;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .category-nav-btn.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    .category-nav-btn.left {
        left: 0;
    }

    .category-nav-btn.right {
        right: 0;
    }

    .category-nav-btn i {
        font-size: 1.2rem;
        color: #333;
    }

    .category-nav-btn:hover i {
        color: #fff;
    }

    .category-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12), 0 2px 6px rgba(0,0,0,0.08);
        transition: box-shadow 0.3s, transform 0.3s;
    }

    .category-item:hover .category-icon {
        box-shadow: 0 8px 24px rgba(0,0,0,0.18), 0 4px 12px rgba(0,0,0,0.12);
        transform: scale(1.05);
    }

    .category-icon img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }

    .category-name {
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
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
    }

    .special-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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

    /* Builder Cards */
    .builder-cards {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .builder-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem 1.5rem;
        color: #fff;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15), 0 2px 8px rgba(0,0,0,0.1);
    }

    .builder-card:nth-child(1) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .builder-card:nth-child(2) { background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%); }
    .builder-card:nth-child(3) { background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%); }
    .builder-card:nth-child(4) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .builder-card:nth-child(5) { background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%); }

    .builder-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.25), 0 4px 16px rgba(0,0,0,0.15);
    }

    .builder-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .builder-card-link {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.9);
    }

    .builder-card-image {
        margin-top: 1rem;
        height: 100px;
    }

    .builder-card-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
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

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1), 0 2px 6px rgba(0,0,0,0.08);
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: #fafafaff;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }


    .product-image img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }

    .wishlist-btn {
        position: absolute !important;
        top: 12px !important;
        bottom: auto !important;
        @if(is_rtl())
        left: 12px !important;
        right: auto !important;
        @else
        right: 12px !important;
        left: auto !important;
        @endif
        background: rgba(255, 255, 255, 0.95);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 10;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
    }

    .wishlist-btn:hover {
        background: rgba(255, 255, 255, 1);
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .wishlist-btn:hover i {
        color: #ff0000 !important;
    }

    .wishlist-btn.active {
        background: rgba(255, 255, 255, 1) !important;
    }

    .wishlist-btn.active i {
        color: #ff0000 !important;
    }

    .wishlist-btn i {
        font-size: 1.1rem;
        color: #888;
        transition: all 0.3s;
    }

    /* Solid heart icon should be red */
    .wishlist-btn i.fas.fa-heart {
        color: #ff0000 !important;
    }

    .product-badge {
        position: absolute !important;
        top: 12px !important;
        bottom: auto !important;
        @if(is_rtl())
        right: 12px !important;
        left: auto !important;
        @else
        left: 12px !important;
        right: auto !important;
        @endif
        background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 2px 8px rgba(255, 71, 87, 0.3);
        letter-spacing: 0.3px;
    }

    .product-info {
        padding: 1.25rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-title {
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
        text-align: center;
        line-height: 1.4;
    }

    .product-description {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 1rem;
        line-height: 1.5;
        text-align: center;
        flex-grow: 1;
    }

    .product-footer {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
        margin-top: auto;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        text-align: start;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .product-price .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 1rem;
        font-weight: 500;
    }

    .add-to-cart {
        background: linear-gradient(135deg, #454546ff 0%, #000000ff 100%);
        color: #fff;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        white-space: nowrap;
        font-size: 0.95rem;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .add-to-cart:hover {
        background: linear-gradient(135deg, #7bace3ff 0%, #404249ff 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(78, 115, 223, 0.4);
    }

    .add-to-cart.in-cart {
        background: #4CAF50;
    }

    .add-to-cart.in-cart:hover {
        background: #45a049;
    }

    .add-to-cart.in-cart i {
        animation: cartBounce 0.5s ease;
    }

    .add-to-cart.out-of-stock {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .add-to-cart.out-of-stock:hover {
        background: linear-gradient(135deg, #f57c00 0%, #ef6c00 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 152, 0, 0.4);
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
        
        .builder-cards {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
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
        
        .categories-wrapper {
            padding: 0 50px;
        }
        
        .category-nav-btn {
            width: 40px;
            height: 40px;
        }
        
        .category-icon {
            width: 100px;
            height: 100px;
        }
        
        .section-header h2 {
            font-size: 1.8rem;
        }
        
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
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
        
        .builder-cards {
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
        
        .category-icon {
            width: 80px;
            height: 80px;
        }
        
        .category-item {
            min-width: 90px;
        }
        
        .category-name {
            font-size: 0.85rem;
        }
        
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .product-card {
            border-radius: 8px;
        }
        
        .product-image {
            height: 180px;
        }
        
        .product-badge {
            font-size: 0.65rem;
            padding: 0.3rem 0.8rem;
            top: 8px;
            @if(is_rtl())
            right: 8px;
            @else
            left: 8px;
            @endif
        }
        
        .wishlist-btn {
            width: 35px;
            height: 35px;
            top: 8px;
            @if(is_rtl())
            left: 8px;
            @else
            right: 8px;
            @endif
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
        
        .builder-cards {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .builder-card {
            padding: 1.5rem 1rem;
        }
        
        .builder-card-title {
            font-size: 1rem;
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
        
        .featured-tabs {
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .featured-tab {
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
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
        
        .category-icon {
            width: 70px;
            height: 70px;
        }
        
        .category-item {
            min-width: 80px;
        }
        
        .category-name {
            font-size: 0.8rem;
        }
        
        .product-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .product-card {
            max-width: 100%;
        }
        
        .product-image {
            height: 220px;
        }
        
        .product-title {
            font-size: 1rem;
        }
        
        .product-description {
            font-size: 0.85rem;
        }
        
        .product-price {
            font-size: 1.4rem;
        }
        
        .add-to-cart {
            padding: 0.8rem 1.2rem;
            font-size: 0.9rem;
        }
        
        .builder-cards {
            grid-template-columns: 1fr;
        }
        
        .builder-card-image {
            height: 80px;
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
        
        .category-icon {
            width: 60px;
            height: 60px;
        }
        
        .category-name {
            font-size: 0.75rem;
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

    .featured-tabs {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .featured-tab {
        background: transparent;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .featured-tab.active {
        color: #4169E1;
        border-bottom-color: #FFD700;
    }

    .featured-tab:hover {
        color: #4169E1;
    }

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

<!-- Categories Section -->
<div class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>{{ __t('messages.shop_by_category') }}</h2>
            <a href="{{ route('categories') }}" class="view-more">
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
            </a>
        </div>

        <div class="categories-wrapper">
            <button class="category-nav-btn left" id="categoryScrollLeft">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="categories-grid" id="categoriesGrid" style="direction: ltr !important;">
                @foreach($categories as $category)
                <div class="category-item" onclick="window.location.href='{{ route('products', ['category' => $category->slug]) }}'">
                    <div class="category-icon">
                        @if($category->image)
                            @if(str_starts_with($category->image, 'http'))
                                <img src="{{ $category->image }}" alt="{{ $category->name }}">
                            @else
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                            @endif
                        @else
                            <img src="{{ asset('images/products/default.png') }}" alt="{{ $category->name }}">
                        @endif
                    </div>
                    <div class="category-name">{{ $category->name }}</div>
                </div>
                @endforeach
            </div>

            <button class="category-nav-btn right" id="categoryScrollRight">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- Removed static special-offer demo card -->

<!-- Featured Products Section -->
<div class="featured-section">
    <div class="container">
        <div class="featured-tabs">
            <button class="featured-tab active" data-tab="featured">{{ __t('messages.featured') }}</button>
            <button class="featured-tab" data-tab="sale">{{ __t('messages.on_sale') }}</button>
                <button class="featured-tab" data-tab="rated">{{ __t('messages.top_rated') }}</button>
            </div>
            
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
    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <div class="section-header">
        <h2>{{ __t('messages.featured_products') }}</h2>
        <a href="{{ route('products') }}" class="view-more">
            {{ __t('messages.view_more') }} <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
        </a>
    </div>

    <div class="product-grid">
        @foreach($featuredProducts as $product)
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
    @endif

    <!-- Shop by Categories - Builder Cards -->
    @if($categories->count() >= 5)
    <div class="section-header">
        <h2>{{ __t('messages.shop_by_category') }}</h2>
    </div>

    <div class="builder-cards">
        @foreach($categories->take(5) as $index => $category)
        <div class="builder-card" onclick="window.location.href='{{ route('products', ['category' => $category->slug]) }}'">
            <div class="builder-card-title">{{ $category->name }}</div>
            <div class="builder-card-link">{{ __t('messages.explore_products', ['count' => $category->products_count]) }} ›</div>
            @if($category->image)
            <div class="builder-card-image">
                @if(str_starts_with($category->image, 'http'))
                    <img src="{{ $category->image }}" alt="{{ $category->name }}">
                @else
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- New Arrivals -->
    @if($newProducts->count() > 0)
    <div class="section-header">
        <h2>{{ __t('messages.new_arrivals') }}</h2>
        <a href="{{ route('products') }}" class="view-more">
            @if(app()->getLocale() === 'ar')
                <i class="fas fa-arrow-left"></i> {{ __t('messages.view_more') }}
            @else
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-right"></i>
            @endif
        </a>
    </div>

    <div class="product-grid">
        {{-- Debug: {{ $newProducts->count() }} products --}}
        @forelse($newProducts as $product)
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
        @empty
        <div style="width: 100%; text-align: center; padding: 40px; color: #999;">
            <p>لا توجد منتجات جديدة حالياً</p>
        </div>
        @endforelse
    </div>
    @endif

    <!-- Bestsellers -->
    @if($bestsellerProducts->count() > 0)
    <div class="section-header">
        <h2>{{ __t('messages.best_sellers') }}</h2>
        <a href="{{ route('products', ['filter' => 'bestseller']) }}" class="view-more">
            @if(app()->getLocale() === 'ar')
                <i class="fas fa-arrow-left"></i> {{ __t('messages.view_more') }}
            @else
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-right"></i>
            @endif
        </a>
    </div>

    <div class="product-grid">
        @foreach($bestsellerProducts as $product)
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
    @endif

    <!-- On Sale Products -->
    @if($onSaleProducts->count() > 0)
    <div class="section-header">
        <h2>{{ __t('messages.on_sale') }}</h2>
        <a href="{{ route('products', ['filter' => 'sale']) }}" class="view-more">
            @if(app()->getLocale() === 'ar')
                <i class="fas fa-arrow-left"></i> {{ __t('messages.view_more') }}
            @else
                {{ __t('messages.view_more') }} <i class="fas fa-arrow-right"></i>
            @endif
        </a>
    </div>

    <div class="product-grid">
        @foreach($onSaleProducts as $product)
        <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->slug) }}'">
            <div class="product-image">
                <div class="product-badge">{{ __t('messages.sale') }}</div>
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
                        <span class="original-price">₪ {{ number_format($product->price, 0) }}</span>
                        <span>₪ {{ number_format($product->sale_price, 0) }}</span>
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
        const animateElements = document.querySelectorAll('.product-card, .category-item, .builder-card, .special-card');
        animateElements.forEach(el => {
            el.classList.add('scroll-animate');
            observer.observe(el);
        });

        // Category Slider Navigation
        const categoriesGrid = document.getElementById('categoriesGrid');
        const scrollLeftBtn = document.getElementById('categoryScrollLeft');
        const scrollRightBtn = document.getElementById('categoryScrollRight');
        
        if (categoriesGrid && scrollLeftBtn && scrollRightBtn) {
            const scrollAmount = 300;
            
            // Check if RTL - check multiple sources
            const isRTL = document.documentElement.dir === 'rtl' || 
                         document.body.dir === 'rtl' || 
                         document.documentElement.getAttribute('dir') === 'rtl' ||
                         getComputedStyle(document.body).direction === 'rtl';
            
            console.log('Is RTL:', isRTL);
            
            // Since we force LTR on the container, scrolling works normally
            // But we need to reverse button behavior for RTL pages
            
            // Update button states
            function updateButtonStates() {
                const maxScroll = categoriesGrid.scrollWidth - categoriesGrid.clientWidth;
                let currentScroll = categoriesGrid.scrollLeft;
                
                // Container is LTR, so scroll works normally (0 to maxScroll)
                // But we keep button behavior consistent with visual direction
                
                if (currentScroll <= 0) {
                    // At the start (scroll position = 0)
                    // Can't scroll more to the left
                    scrollLeftBtn.classList.add('disabled');
                    scrollRightBtn.classList.remove('disabled');
                } else if (currentScroll >= maxScroll - 5) {
                    // At the end (scroll position = max)
                    // Can't scroll more to the right
                    scrollRightBtn.classList.add('disabled');
                    scrollLeftBtn.classList.remove('disabled');
                } else {
                    // In the middle - both enabled
                    scrollLeftBtn.classList.remove('disabled');
                    scrollRightBtn.classList.remove('disabled');
                }
            }
            
            // Scroll left button
            scrollLeftBtn.addEventListener('click', function() {
                // In RTL: left button (←) should scroll left (negative)
                // In LTR: left button (←) should scroll left (negative)
                const scrollValue = -scrollAmount;
                categoriesGrid.scrollBy({
                    left: scrollValue,
                    behavior: 'smooth'
                });
            });
            
            // Scroll right button
            scrollRightBtn.addEventListener('click', function() {
                // In RTL: right button (→) should scroll right (positive)
                // In LTR: right button (→) should scroll right (positive)
                const scrollValue = scrollAmount;
                categoriesGrid.scrollBy({
                    left: scrollValue,
                    behavior: 'smooth'
                });
            });
            
            // Update button states on scroll
            categoriesGrid.addEventListener('scroll', updateButtonStates);
            
            // Initial button state
            setTimeout(updateButtonStates, 100);
            
            // Update on window resize
            window.addEventListener('resize', updateButtonStates);
        }

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

    // Featured Products Tab Functionality
        function initFeaturedTabs() {
            const tabs = document.querySelectorAll('.featured-tab');
            const productsGrid = document.getElementById('featuredProducts');
            
            if (!tabs.length || !productsGrid) return;
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Get tab type
                    const tabType = this.dataset.tab;
                    
                    // Here you would typically fetch different products based on tab type
                    // For now, we'll just show a message
                    console.log('Switched to tab:', tabType);
                    
                    // You can add AJAX calls here to fetch different product sets
                    // Example: fetchProductsByType(tabType);
                });
            });
        }

        // Initialize countdown and tabs when DOM is loaded
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
            initFeaturedTabs();
        });
    });
</script>

@endsection
