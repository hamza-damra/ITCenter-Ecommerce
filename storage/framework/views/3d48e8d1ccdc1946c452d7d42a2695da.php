<?php $__env->startSection('title', __t('messages.home') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
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
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.1) 100%);
        z-index: 1;
    }
    
    /* Hero Slide Content */
    .hero-slide-content {
        position: absolute;
        top: 50%;
        <?php echo e(is_rtl() ? 'right' : 'left'); ?>: 5%;
        transform: translateY(-50%);
        z-index: 2;
        max-width: 550px;
        color: white;
        padding: 2rem;
    }
    
    .hero-slide-content h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
    }
    
    .hero-slide-content p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.95;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
    }
    
    .hero-cta-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .hero-cta-btn {
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .hero-cta-btn.primary {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: white;
        border: 2px solid transparent;
    }
    
    .hero-cta-btn.primary:hover {
        background: linear-gradient(135deg, #111827 0%, #000000 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }
    
    .hero-cta-btn.secondary {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(10px);
    }
    
    .hero-cta-btn.secondary:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.8);
        transform: translateY(-2px);
    }
    
    /* Progress Bar */
    .slider-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        z-index: 10;
    }
    
    .slider-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #1f2937 0%, #4b5563 100%);
        width: 0%;
        transition: width 0.1s linear;
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
        margin-bottom: 1.5rem;
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
        font-size: 1.75rem;
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
        background: linear-gradient(90deg, transparent, #2563eb, transparent);
    }

    .underline-dot {
        width: 8px;
        height: 8px;
        background: #2563eb;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(37, 99, 235, 0.5);
    }

    /* Category Carousel Wrapper */
    .category-carousel-wrapper {
        position: relative;
        padding: 0 60px;
        margin-bottom: 1rem;
    }

    /* Navigation Buttons */
    .carousel-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 48px;
        height: 48px;
        border-radius: 0;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #1f2937;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .carousel-nav-btn:hover {
        background: #1f2937;
        border-color: #1f2937;
        color: #ffffff;
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
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
        gap: 0;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Category Carousel Card */
    .category-carousel-card {
        flex: 0 0 20%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        padding: 1.5rem 1rem;
        border-radius: 0;
        background: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .category-carousel-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #1f2937 0%, #111827 100%);
        transform: scaleX(0);
        transition: transform 0.35s ease;
    }

    .category-carousel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border-color: rgba(0, 0, 0, 0.12);
    }

    .category-carousel-card:hover::before {
        transform: scaleX(1);
    }

    .category-carousel-image {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
        border-radius: 0;
        background: transparent;
        position: relative;
    }

    .category-carousel-image img {
        width: 75%;
        height: 75%;
        object-fit: contain;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        filter: none;
    }

    .category-carousel-card:hover .category-carousel-image img {
        transform: scale(1.08);
    }

    .category-carousel-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
        text-align: center;
        transition: all 0.3s ease;
        margin-top: 0;
        letter-spacing: 0.3px;
    }

    .category-carousel-card:hover .category-carousel-name {
        color: #1f2937;
        font-weight: 700;
    }

    /* Pagination Dots */
    .category-carousel-dots {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1rem;
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
        background: #1f2937;
        width: 30px;
        border-radius: 5px;
    }

    /* Responsive Design for Category Carousel */
    @media (max-width: 1023px) {
        .category-carousel-card {
            flex: 0 0 25%;
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
            flex: 0 0 33.333%;
        }

        .explore-products-section {
            padding: 2rem 0;
        }

        .discover-title {
            font-size: 1.5rem;
        }

        .discover-header {
            margin-bottom: 1.25rem;
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
            flex: 0 0 50%;
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
        background: #f8f9fa;
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        border-radius: 0;
        overflow: hidden;
        box-shadow: none;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    /* Border between cards - not full height */
    .product-card::after {
        content: '';
        position: absolute;
        right: 0;
        top: 15%;
        height: 70%;
        width: 1px;
        background: rgba(0, 0, 0, 0.08);
        transition: opacity 0.3s ease;
    }
    
    /* Remove border from last card in each row */
    .product-card:nth-child(4n)::after {
        display: none;
    }
    
    .product-card:hover::after {
        opacity: 0;
    }

    /* Remove gradient overlay on hover */
    .product-card::before {
        display: none;
    }

    .product-card:hover {
        transform: none;
        box-shadow: none;
        background: #ffffff;
    }

    /* Quick View & Compare Buttons */
    .product-actions {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 15;
    }

    .product-card:hover .product-actions {
        opacity: 1;
    }

    .quick-view-btn, .compare-btn {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .quick-view-btn:hover, .compare-btn:hover {
        background: #1f2937;
        color: white;
        transform: scale(1.1);
    }

    .quick-view-btn i, .compare-btn i {
        font-size: 1rem;
        color: #1f2937;
        transition: color 0.3s ease;
    }

    .quick-view-btn:hover i, .compare-btn:hover i {
        color: white;
    }

    /* Product Rating */
    .product-rating {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
    }

    .product-rating .stars {
        display: flex;
        gap: 0.15rem;
        color: #fbbf24;
    }

    .product-rating .rating-count {
        color: #6b7280;
        font-size: 0.8rem;
    }

    .product-image {
        width: 100%;
        height: 200px;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 0.8rem;
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
        box-shadow: none;
    }

    .wishlist-btn {
        position: absolute !important;
        top: 10px !important;
        bottom: auto !important;
        <?php if(is_rtl()): ?>
        left: 10px !important;
        right: auto !important;
        <?php else: ?>
        right: 10px !important;
        left: auto !important;
        <?php endif; ?>
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        width: 32px;
        height: 32px;
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
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.18);
        border-color: rgba(37, 99, 235, 0.2);
    }

    .wishlist-btn:hover i {
        color: #1f2937 !important;
    }

    .wishlist-btn.active {
        background: rgba(31, 41, 55, 0.1) !important;
        border-color: #1f2937 !important;
    }

    .wishlist-btn.active i {
        color: #1f2937 !important;
    }

    .wishlist-btn i {
        font-size: 0.9rem;
        color: #64748b;
        transition: all 0.3s ease;
    }

    /* Solid heart icon should be black */
    .wishlist-btn i.fas.fa-heart {
        color: #1f2937 !important;
    }

    .product-badge {
        position: absolute !important;
        top: 10px !important;
        bottom: auto !important;
        <?php if(is_rtl()): ?>
        right: 10px !important;
        left: auto !important;
        <?php else: ?>
        left: 10px !important;
        right: auto !important;
        <?php endif; ?>
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #fff;
        padding: 0.3rem 0.65rem;
        border-radius: 10px;
        font-size: 0.65rem;
        font-weight: 700;
        z-index: 5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .product-badge.discount-badge {
        background: linear-gradient(135deg, #ff4757 0%, #d63447 100%);
        box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
        font-size: 0.75rem;
        padding: 0.4rem 0.85rem;
        font-weight: 800;
        letter-spacing: 0.3px;
    }

    .product-info {
        padding: 1rem 1rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        background: transparent;
    }

    .product-title {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #1e293b;
        text-align: start;
        line-height: 1.3;
        transition: color 0.3s ease;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.4rem;
    }

    .product-card:hover .product-title {
        color: #1f2937;
    }

    .product-description {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.7rem;
        line-height: 1.3;
        text-align: start;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-footer {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-top: auto;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        text-align: start;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.2rem;
        flex: 1;
    }

    .product-price .original-price {
        text-decoration: line-through;
        color: #94a3b8;
        font-size: 0.875rem;
        font-weight: 500;
        order: -1;
    }

    .product-price .current-price {
        color: #1f2937;
        font-weight: 700;
        font-size: 1.1rem;
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
        color: #2563eb;
        padding: 0.65rem 1.25rem;
        border-radius: 50px;
        border: 1.5px solid #2563eb;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        white-space: nowrap;
        font-size: 0.875rem;
        box-shadow: 0 0 0 rgba(37, 99, 235, 0);
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
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
        z-index: -1;
    }

    .add-to-cart:hover {
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 20px rgba(37, 99, 235, 0.4), 0 4px 12px rgba(37, 99, 235, 0.2);
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

    /* Icon-Only Add to Cart Button */
    .add-to-cart-icon {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #ffffff;
        border: none;
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .add-to-cart-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #111827 0%, #000000 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
        z-index: 0;
    }

    .add-to-cart-icon i {
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .add-to-cart-icon:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4), 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .add-to-cart-icon:hover::before {
        opacity: 1;
    }

    .add-to-cart-icon:hover i {
        transform: scale(1.1);
    }

    .add-to-cart-icon:active {
        transform: translateY(0) scale(1);
    }

    /* Success state - green with check icon */
    .add-to-cart-icon.in-cart {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
    }

    .add-to-cart-icon.in-cart::before {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .add-to-cart-icon.in-cart:hover {
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4), 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .add-to-cart-icon.in-cart i {
        animation: cartBounce 0.5s ease;
    }

    /* Out of stock state - orange with bell icon */
    .add-to-cart-icon.out-of-stock {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.25);
        cursor: pointer;
    }

    .add-to-cart-icon.out-of-stock::before {
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
    }

    .add-to-cart-icon.out-of-stock:hover {
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4), 0 2px 8px rgba(249, 115, 22, 0.2);
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
            <?php if(is_rtl()): ?>
            right: 10px;
            <?php else: ?>
            left: 10px;
            <?php endif; ?>
        }
        
        .wishlist-btn {
            width: 32px;
            height: 32px;
            top: 10px;
            <?php if(is_rtl()): ?>
            left: 10px;
            <?php else: ?>
            right: 10px;
            <?php endif; ?>
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
            flex-direction: row;
            gap: 0.75rem;
        }

        .product-price {
            font-size: 1.2rem;
            flex: 1;
        }

        .product-price .original-price {
            font-size: 0.8rem;
        }

        .product-price .current-price {
            font-size: 1.2rem;
        }

        .add-to-cart {
            width: 100%;
            padding: 0.7rem 1rem;
            font-size: 0.85rem;
            min-width: unset;
        }

        .add-to-cart-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 1rem;
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
        <?php if(is_rtl()): ?>
        right: 30px;
        <?php else: ?>
        left: 30px;
        <?php endif; ?>
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
        color: #2563eb;
        margin: 0;
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
        padding: 2.5rem 0;
        margin-bottom: 0;
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
        padding: 0;
        display: grid;
        grid-template-columns: 1.2fr 1fr 1fr 1fr 1fr; /* 1 side card + 4 product cards */
        grid-template-rows: 1fr 1fr;
        gap: 0;
        align-items: stretch;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    /* Promo card that spans two product rows - positioned on the left for LTR, right for RTL */
    .promo-featured-card {
        grid-column: <?php echo e(is_rtl() ? '5' : '1'); ?>;
        grid-row: 1 / 3;
        border: none;
        border-radius: 0;
        background: #ffffff;
        box-shadow: none;
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 1.2rem;
        transition: all 0.3s ease;
        <?php echo e(is_rtl() ? 'margin-left' : 'margin-right'); ?>: 0;
        <?php echo e(is_rtl() ? 'border-left' : 'border-right'); ?>: 1px solid rgba(0, 0, 0, 0.08);
    }
    .promo-featured-card:hover {
        box-shadow: none;
        transform: none;
        background: #f8f9fa;
    }
    .promo-featured-card .special-offer-header {
        text-align: center;
        font-size: 1.15rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.8rem;
        letter-spacing: 0.3px;
    }
    .promo-featured-card .badge-save {
        position: absolute;
        top: 12px;
        <?php echo e(is_rtl() ? 'left' : 'right'); ?>: 12px;
        background: #fbbf24;
        color: #111827;
        font-weight: 900;
        padding: 0.6rem 0.8rem;
        border-radius: 50%;
        font-size: 0.75rem;
        box-shadow: 0 6px 16px rgba(251, 191, 36, 0.4);
        width: 65px;
        height: 65px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1.2;
    }
    .promo-featured-card .badge-save .save-label {
        font-size: 0.7rem;
        font-weight: 700;
    }
    .promo-featured-card .badge-save .save-amount {
        font-size: 1.1rem;
        font-weight: 900;
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
        height: 200px;
        object-fit: contain;
    }
    .promo-featured-card .promo-body { color: #1f2937; margin-top: 1rem; display: flex; flex-direction: column; gap: .75rem; }
    .promo-featured-card .promo-title { font-weight: 700; font-size: 0.95rem; margin-bottom: .2rem; text-align: center; color: #1e293b; }
    .promo-featured-card .promo-product-name { color:#6b7280; font-weight:600; font-size:.85rem; text-align: center; }
    .promo-featured-card .promo-prices { display: flex; align-items: baseline; justify-content: center; gap: .5rem; }
    .promo-featured-card .promo-prices .orig { text-decoration: line-through; opacity: .6; color: #6b7280; }
    .promo-featured-card .promo-prices .sale { font-size: 1.5rem; font-weight: 900; color: #e11d48; }
    .promo-featured-card .promo-cta { margin-top: .4rem; }
    .promo-featured-card .promo-cta a { display:block; text-align:center; padding:.8rem 1rem; background:#111827; color:#fff; border-radius:10px; font-weight:800; text-decoration:none; border: 1px solid #111827; }
    .promo-featured-card .promo-cta a:hover { background:#1f2937; border-color:#1f2937; }

    /* Countdown in promo card */
    .promo-countdown { margin-top: 1rem; padding-top: 1rem; border-top: 2px solid #f3f4f6; }
    .promo-countdown .label { color:#111827; font-weight:700; font-size:0.85rem; margin-bottom:.6rem; text-align: center; }
    .promo-countdown .boxes { display:flex; justify-content: center; gap:.5rem; }
    .promo-countdown .box {
        background:#ffffff;
        border:2px solid #fbbf24;
        border-radius:8px;
        padding:.5rem .6rem;
        min-width:55px;
        text-align:center;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.15);
        position: relative;
    }
    .promo-countdown .num { font-size:1.4rem; font-weight:900; color:#111827; display:block; line-height:1; margin-bottom: 0.25rem; }
    .promo-countdown .unit { font-size:.65rem; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; font-weight:700; display:block; }
    .promo-countdown .boxes .box:not(:last-child)::after {
        content: ':';
        position: absolute;
        right: -0.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.3rem;
        font-weight: 700;
        color: #111827;
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
            grid-template-columns: 1.2fr 1fr 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 0;
        }
        
        .promo-featured-card {
            grid-column: <?php echo e(is_rtl() ? '4' : '1'); ?>;
            grid-row: 1 / 3;
            <?php echo e(is_rtl() ? 'margin-left' : 'margin-right'); ?>: 0.3rem;
            <?php echo e(is_rtl() ? 'margin-right' : 'margin-left'); ?>: 0;
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
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto;
            gap: 0.5rem;
            padding: 0 .75rem;
        }
        
        .promo-featured-card {
            grid-column: 1 / -1;
            grid-row: auto;
            margin-left: 0;
            margin-bottom: 1rem;
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
    
    /* Gift Ideas Section CSS */
    .home-section.gift-ideas-section {
        padding: 4rem 0;
        background: #ffffff;
        width: 100%;
    }
    
    .home-section.gift-ideas-section .container {
        /* Match featured products section width */
        max-width: 1500px;
        width: 100%;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    /* Gift Ideas Grid Layout */
    .gift-ideas-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-top: 0;
        align-items: stretch; /* ensure items are equal height per row */
    }

    .gift-ideas-item {
        min-height: auto;
    }

    /* Product items take 1 column each */
    .gift-product-item {
        grid-column: span 1;
    }

    /* Banner item takes 2 columns */
    .gift-banner-item {
        grid-column: span 2;
    }

    /* Control banner position based on direction using CSS Grid order */
    /* RTL: Banner appears first (on the right) */
    body[dir="rtl"] .gift-banner-item,
    html[dir="rtl"] .gift-banner-item,
    [dir="rtl"] .gift-banner-item {
        order: -1;
    }

    /* LTR: Banner appears last (on the left) */
    body[dir="ltr"] .gift-banner-item,
    html[dir="ltr"] .gift-banner-item,
    [dir="ltr"] .gift-banner-item {
        order: 1;
    }

    body[dir="ltr"] .gift-product-item,
    html[dir="ltr"] .gift-product-item,
    [dir="ltr"] .gift-product-item {
        order: 2;
    }

    /* Strong Offers Section - Position control */
    /* Arabic (RTL): Products on RIGHT, Banner on LEFT */
    body[dir="rtl"] .strong-offers-section .strong-offers-banner,
    html[dir="rtl"] .strong-offers-section .strong-offers-banner,
    [dir="rtl"] .strong-offers-section .strong-offers-banner {
        order: 3 !important; /* In RTL, higher order = left side */
    }

    body[dir="rtl"] .strong-offers-section .strong-offers-product,
    html[dir="rtl"] .strong-offers-section .strong-offers-product,
    [dir="rtl"] .strong-offers-section .strong-offers-product {
        order: 1 !important; /* In RTL, lower order = right side */
    }

    /* English (LTR): Products on LEFT, Banner on RIGHT */
    body[dir="ltr"][lang="en"] .strong-offers-section .strong-offers-banner,
    html[dir="ltr"][lang="en"] .strong-offers-section .strong-offers-banner,
    [dir="ltr"][lang="en"] .strong-offers-section .strong-offers-banner {
        order: 3 !important; /* In LTR, higher order = right side */
    }

    body[dir="ltr"][lang="en"] .strong-offers-section .strong-offers-product,
    html[dir="ltr"][lang="en"] .strong-offers-section .strong-offers-product,
    [dir="ltr"][lang="en"] .strong-offers-section .strong-offers-product {
        order: 1 !important; /* In LTR, lower order = left side */
    }

    /* Hebrew (LTR): Products on RIGHT, Banner on LEFT (same as Arabic visually) */
    body[dir="ltr"][lang="he"] .strong-offers-section .strong-offers-banner,
    html[dir="ltr"][lang="he"] .strong-offers-section .strong-offers-banner,
    [dir="ltr"][lang="he"] .strong-offers-section .strong-offers-banner {
        order: 1 !important; /* In LTR, lower order = left side */
    }

    body[dir="ltr"][lang="he"] .strong-offers-section .strong-offers-product,
    html[dir="ltr"][lang="he"] .strong-offers-section .strong-offers-product,
    [dir="ltr"][lang="he"] .strong-offers-section .strong-offers-product {
        order: 3 !important; /* In LTR, higher order = right side */
    }
    
    /* Product Card Styling */
    .home-section.gift-ideas-section .product-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border-radius: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .home-section.gift-ideas-section .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    
    /* Gift Banner Styling - Ubuy Style */
    .product-item-section.gift-idea-banner {
        width: 100%;
        margin: 0 auto;
        border-radius: 18px;
        padding: 40px 40px 50px;
        position: relative;
        background-repeat: no-repeat;
        background-size: cover !important;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 40px;
    }

    /* Content group (title + text + button) */
    .gift-banner-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Title styling */
    .gift-banner-title {
        font-size: 28px;
        font-weight: 700;
        line-height: 1.2;
        margin: 0 0 12px 0;
        color: #0B265A;
    }

    /* Description styling */
    .product-item-section.gift-idea-banner p {
        font-size: 16px;
        line-height: 1.6;
        color: #274C7E;
        margin: 0 0 8px 0;
    }

    /* CTA button - round and compact */
    .product-item-section.gift-idea-banner .gift-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #0A3766;
        color: #fff;
        padding: 16px 36px;
        border-radius: 35px;
        font-weight: 700;
        font-size: 17px;
        text-decoration: none;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        white-space: nowrap;
        transition: all 0.3s ease;
        margin-top: 8px;
        width: fit-content;
    }

    .product-item-section.gift-idea-banner .gift-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.16);
    }

    /* Image container */
    .gift-banner-image {
        flex-shrink: 0;
    }

    /* Gift art image sizing */
    .product-item-section.gift-idea-banner .gift-art {
        width: 280px;
        height: auto;
        object-fit: contain;
        display: block;
    }

    /* Flex wrapper - removed, now using banner direct flex */
    .gift-banner-flex-wrapper {
        display: contents;
    }

    /* LTR Layout - English: Content left, image right */
    body[dir="ltr"] .product-item-section.gift-idea-banner,
    html[dir="ltr"] .product-item-section.gift-idea-banner,
    [dir="ltr"] .product-item-section.gift-idea-banner {
        flex-direction: row;
    }

    body[dir="ltr"] .gift-banner-title,
    html[dir="ltr"] .gift-banner-title,
    [dir="ltr"] .gift-banner-title,
    body[dir="ltr"] .gift-banner-content,
    html[dir="ltr"] .gift-banner-content,
    [dir="ltr"] .gift-banner-content,
    body[dir="ltr"] .gift-banner-content p,
    html[dir="ltr"] .gift-banner-content p,
    [dir="ltr"] .gift-banner-content p {
        text-align: left;
    }

    body[dir="ltr"] .gift-banner-content .gift-cta,
    html[dir="ltr"] .gift-banner-content .gift-cta,
    [dir="ltr"] .gift-banner-content .gift-cta {
        margin-right: auto;
        margin-left: 0;
    }

    /* RTL Layout - Arabic: Content right, image left */
    body[dir="rtl"] .product-item-section.gift-idea-banner,
    html[dir="rtl"] .product-item-section.gift-idea-banner,
    [dir="rtl"] .product-item-section.gift-idea-banner {
        flex-direction: row-reverse;
    }

    body[dir="rtl"] .gift-banner-title,
    html[dir="rtl"] .gift-banner-title,
    [dir="rtl"] .gift-banner-title,
    body[dir="rtl"] .gift-banner-content,
    html[dir="rtl"] .gift-banner-content,
    [dir="rtl"] .gift-banner-content,
    body[dir="rtl"] .gift-banner-content p,
    html[dir="rtl"] .gift-banner-content p,
    [dir="rtl"] .gift-banner-content p {
        text-align: right;
    }

    body[dir="rtl"] .gift-banner-content .gift-cta,
    html[dir="rtl"] .gift-banner-content .gift-cta,
    [dir="rtl"] .gift-banner-content .gift-cta {
        margin-left: auto;
        margin-right: 0;
    }
    
    /* Tablet Layout - 2 columns */
    @media (max-width: 991px) {
        .home-section.gift-ideas-section {
            padding: 3rem 0;
        }

        .gift-ideas-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .gift-product-item {
            grid-column: span 1;
        }

        .gift-banner-item {
            grid-column: span 2;
        }

        .product-item-section.gift-idea-banner { 
            padding: 35px; 
            height: 100%;
            gap: 30px;
        }
        
        .gift-banner-title {
            font-size: 24px;
            margin-bottom: 12px;
        }

        .product-item-section.gift-idea-banner .gift-cta {
            padding: 14px 30px;
            font-size: 16px;
        }
        
        .product-item-section.gift-idea-banner .gift-art {
            width: 240px;
        }
    }
    
    /* Mobile Layout - 1 column, vertical banner */
    @media (max-width: 768px) {
        .home-section.gift-ideas-section {
            padding: 2.5rem 0;
        }

        .home-section.gift-ideas-section .container {
            padding: 0 1rem;
        }

        .gift-ideas-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .gift-product-item,
        .gift-banner-item {
            grid-column: span 1;
        }

        .gift-ideas-item {
            min-height: auto;
        }

        .product-item-section.gift-idea-banner {
            padding: 30px 20px;
            height: auto;
            min-height: 280px;
            flex-direction: column !important;
            gap: 25px;
        }

        .gift-banner-title {
            font-size: 22px;
            margin-bottom: 10px;
            text-align: center !important;
        }
        
        .product-item-section.gift-idea-banner p {
            font-size: 14px;
        }

        /* Mobile: stack vertically, center content */
        .gift-banner-content {
            align-items: center;
            text-align: center !important;
        }

        body[dir="rtl"] .gift-banner-content,
        html[dir="rtl"] .gift-banner-content,
        [dir="rtl"] .gift-banner-content,
        body[dir="ltr"] .gift-banner-content,
        html[dir="ltr"] .gift-banner-content,
        [dir="ltr"] .gift-banner-content,
        body[dir="rtl"] .gift-banner-content p,
        html[dir="rtl"] .gift-banner-content p,
        [dir="rtl"] .gift-banner-content p,
        body[dir="ltr"] .gift-banner-content p,
        html[dir="ltr"] .gift-banner-content p,
        [dir="ltr"] .gift-banner-content p {
            text-align: center !important;
        }

        body[dir="rtl"] .gift-banner-content .gift-cta,
        html[dir="rtl"] .gift-banner-content .gift-cta,
        [dir="rtl"] .gift-banner-content .gift-cta,
        body[dir="ltr"] .gift-banner-content .gift-cta,
        html[dir="ltr"] .gift-banner-content .gift-cta,
        [dir="ltr"] .gift-banner-content .gift-cta {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .product-item-section.gift-idea-banner .gift-art {
            width: 210px;
        }

        .product-item-section.gift-idea-banner .gift-cta {
            width: auto;
            padding: 14px 28px;
        }
    }

    /* Small Mobile - 2 product columns */
    @media (max-width: 576px) {
        .home-section.gift-ideas-section {
            padding: 2rem 0;
        }

        .gift-ideas-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .gift-product-item {
            grid-column: span 1;
        }

        .gift-banner-item {
            grid-column: span 2;
        }

        .product-item-section.gift-idea-banner {
            padding: 25px 15px;
        }

        .gift-banner-title {
            font-size: 18px;
        }

        .product-item-section.gift-idea-banner p {
            font-size: 13px;
        }

        .product-item-section.gift-idea-banner .gift-cta {
            padding: 10px 20px;
            font-size: 13px;
        }

        .product-item-section.gift-idea-banner .gift-art {
            width: 160px;
        }
    }
</style>

<!-- Hero Section - Slider -->
<div class="hero-section">
    <div class="hero-slider">
        <!-- Slide 1 - Banner.jpg -->
        <div class="hero-slide active" style="background-image: url('<?php echo e(asset('images/assets/Banner.jpg')); ?>');">
            <div class="hero-slide-content">
                <h1><?php echo e(is_rtl() ? 'أحدث التقنيات' : 'Latest Technology'); ?></h1>
                <p><?php echo e(is_rtl() ? 'اكتشف أفضل الأجهزة الإلكترونية والإكسسوارات بأسعار لا تقبل المنافسة' : 'Discover the best electronics and accessories at unbeatable prices'); ?></p>
                <div class="hero-cta-buttons">
                    <a href="<?php echo e(route('products')); ?>" class="hero-cta-btn primary">
                        <i class="fas fa-shopping-bag"></i>
                        <?php echo e(is_rtl() ? 'تسوق الآن' : 'Shop Now'); ?>

                    </a>
                    <a href="<?php echo e(route('products', ['filter' => 'sale'])); ?>" class="hero-cta-btn secondary">
                        <i class="fas fa-tags"></i>
                        <?php echo e(is_rtl() ? 'العروض الخاصة' : 'Special Offers'); ?>

                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 2 - wallpaper.png -->
        <div class="hero-slide" style="background-image: url('<?php echo e(asset('images/assets/wallpaper.png')); ?>');">
            <div class="hero-slide-content">
                <h1><?php echo e(is_rtl() ? 'عروض حصرية' : 'Exclusive Deals'); ?></h1>
                <p><?php echo e(is_rtl() ? 'خصومات تصل إلى 50% على منتجات مختارة' : 'Up to 50% off on selected products'); ?></p>
                <div class="hero-cta-buttons">
                    <a href="<?php echo e(route('products', ['filter' => 'sale'])); ?>" class="hero-cta-btn primary">
                        <i class="fas fa-fire"></i>
                        <?php echo e(is_rtl() ? 'اكتشف العروض' : 'Discover Deals'); ?>

                    </a>
                    <a href="<?php echo e(route('products', ['filter' => 'bestseller'])); ?>" class="hero-cta-btn secondary">
                        <i class="fas fa-star"></i>
                        <?php echo e(is_rtl() ? 'الأكثر مبيعاً' : 'Best Sellers'); ?>

                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 3 - wallpaper2.png -->
        <div class="hero-slide" style="background-image: url('<?php echo e(asset('images/assets/wallpaper2.png')); ?>');">
            <div class="hero-slide-content">
                <h1><?php echo e(is_rtl() ? 'شحن مجاني' : 'Free Shipping'); ?></h1>
                <p><?php echo e(is_rtl() ? 'شحن مجاني على جميع الطلبات فوق 200 شيكل' : 'Free shipping on all orders over ₪200'); ?></p>
                <div class="hero-cta-buttons">
                    <a href="<?php echo e(route('products')); ?>" class="hero-cta-btn primary">
                        <i class="fas fa-truck"></i>
                        <?php echo e(is_rtl() ? 'ابدأ التسوق' : 'Start Shopping'); ?>

                    </a>
                    <a href="<?php echo e(url('/about')); ?>" class="hero-cta-btn secondary">
                        <i class="fas fa-info-circle"></i>
                        <?php echo e(is_rtl() ? 'المزيد' : 'Learn More'); ?>

                    </a>
                </div>
            </div>
        </div>

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
        
        <!-- Progress Bar -->
        <div class="slider-progress">
            <div class="slider-progress-bar" id="sliderProgressBar"></div>
        </div>
    </div>
</div>

<!-- Explore Our Products Section - Carousel Design -->
<div class="explore-products-section">
    <div class="container">
        <!-- Section Header with Decorative Elements -->
       <!-- <div class="discover-header">
            <div class="discover-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#2563eb" stroke="#2563eb" stroke-width="2"/>
                </svg>
            </div>
            <h2 class="discover-title"><?php echo e(__t('messages.explore_our_products')); ?></h2>
            <div class="discover-underline">
                <span class="underline-bar"></span>
                <span class="underline-dot"></span>
                <span class="underline-bar"></span>
            </div>
        </div>-->

        <!-- Category Carousel -->
        <div class="category-carousel-wrapper">
            <!-- Navigation Arrow - Left -->
            <button class="carousel-nav-btn carousel-prev" onclick="slideCategoryCarousel(-1)" aria-label="Previous categories">
                <i class="fas fa-chevron-left"></i>
            </button>

            <!-- Carousel Track Container -->
            <div class="category-carousel-container">
                <div class="category-carousel-track" id="categoryCarouselTrack">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('products', ['category' => $category->slug])); ?>" class="category-carousel-card">
                        <div class="category-carousel-image">
                            <?php if($category->image): ?>
                                <?php if(str_starts_with($category->image, 'http')): ?>
                                    <img src="<?php echo e($category->image); ?>" alt="<?php echo e($category->name); ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="<?php echo e(asset($category->image)); ?>" alt="<?php echo e($category->name); ?>" loading="lazy">
                                <?php endif; ?>
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x300/f3f4f6/9ca3af?text=<?php echo e(urlencode($category->name)); ?>" alt="<?php echo e($category->name); ?>" loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div class="category-carousel-name"><?php echo e($category->name); ?></div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    let currentPosition = 0;
    const track = document.getElementById('categoryCarouselTrack');
    const dotsContainer = document.getElementById('categoryCarouselDots');
    const originalCards = track.querySelectorAll('.category-carousel-card');
    const totalCards = originalCards.length;
    let isTransitioning = false;

    // Responsive slides per view
    function getSlidesPerView() {
        const width = window.innerWidth;
        if (width >= 1024) return 5; // Desktop - 5 cards
        if (width >= 768) return 4;  // Tablet - 4 cards
        if (width >= 480) return 3;  // Small tablet - 3 cards
        return 2;                     // Mobile - 2 cards
    }

    let slidesPerView = getSlidesPerView();

    // Create infinite loop by duplicating cards
    function createInfiniteLoop() {
        // Store original cards HTML
        const originalHTML = track.innerHTML;
        
        // Create seamless loop by duplicating all cards
        track.innerHTML = originalHTML + originalHTML + originalHTML;
        
        // Set initial position to middle set (original cards)
        currentPosition = totalCards;
        updateCarouselPosition(false);
    }

    // Initialize dots (only for original cards)
    function initDots() {
        dotsContainer.innerHTML = '';
        for (let i = 0; i < totalCards; i++) {
            const dot = document.createElement('div');
            dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
            dot.onclick = () => goToCategorySlide(i);
            dotsContainer.appendChild(dot);
        }
    }

    // Update carousel position
    function updateCarouselPosition(animate = true) {
        const allCards = track.querySelectorAll('.category-carousel-card');
        if (allCards.length === 0) return;
        
        const cardWidth = allCards[0].offsetWidth;
        const offset = -(currentPosition * cardWidth);
        
        if (animate) {
            track.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        } else {
            track.style.transition = 'none';
        }
        
        track.style.transform = `translateX(${offset}px)`;

        // Update dots (map to original cards)
        const dots = dotsContainer.querySelectorAll('.carousel-dot');
        const activeIndex = (currentPosition - totalCards + totalCards) % totalCards;
        
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === activeIndex);
        });
    }

    // Slide function with seamless loop
    window.slideCategoryCarousel = function(direction) {
        if (isTransitioning) return;
        
        isTransitioning = true;
        currentPosition += direction;
        updateCarouselPosition(true);
        
        // Handle seamless loop
        setTimeout(() => {
            if (currentPosition <= 0) {
                // Jump to end of middle set
                currentPosition = totalCards * 2;
                updateCarouselPosition(false);
            } else if (currentPosition >= totalCards * 2) {
                // Jump to start of middle set
                currentPosition = totalCards;
                updateCarouselPosition(false);
            }
            isTransitioning = false;
        }, 300);
    };

    // Go to specific slide
    window.goToCategorySlide = function(index) {
        if (isTransitioning) return;
        
        currentPosition = totalCards + index;
        updateCarouselPosition(true);
    };

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const newSlidesPerView = getSlidesPerView();
            if (newSlidesPerView !== slidesPerView) {
                slidesPerView = newSlidesPerView;
                createInfiniteLoop();
                initDots();
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
    if (totalCards > 0) {
        createInfiniteLoop();
        initDots();
    }
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
                <?php if(isset($specialOfferProduct) && $specialOfferProduct): ?>
                    <div class="promo-featured-card">
                        <div class="special-offer-header"><?php echo e(is_rtl() ? 'عرض خاص' : 'Special Offer'); ?></div>
                        <?php if($specialOfferProduct->sale_price && $specialOfferProduct->sale_price < $specialOfferProduct->price): ?>
                        <div class="badge-save">
                            <span class="save-label"><?php echo e(is_rtl() ? 'وفر' : 'Save'); ?></span>
                            <span class="save-amount">₪<?php echo e(number_format($specialOfferProduct->price - $specialOfferProduct->sale_price, 0)); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="promo-media">
                            <img src="<?php echo e($specialOfferProduct->main_image); ?>" alt="<?php echo e($specialOfferProduct->name); ?>">
                        </div>
                        <div class="promo-body">
                            <div class="promo-product-name"><?php echo e($specialOfferProduct->name); ?></div>
                            <div class="promo-prices">
                                <?php if($specialOfferProduct->sale_price && $specialOfferProduct->sale_price < $specialOfferProduct->price): ?>
                                    <span class="orig">₪<?php echo e(number_format($specialOfferProduct->price, 0)); ?></span>
                                    <span class="sale">₪<?php echo e(number_format($specialOfferProduct->sale_price, 0)); ?></span>
                                <?php else: ?>
                                    <span class="sale">₪<?php echo e(number_format($specialOfferProduct->price, 0)); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="promo-cta">
                                <a href="<?php echo e(route('product.detail', $specialOfferProduct->slug)); ?>"><?php echo e(is_rtl() ? 'تسوق الآن' : 'Shop Now'); ?></a>
                            </div>
                        </div>
                    </div>
                <?php elseif(isset($promotionalOffers) && $promotionalOffers->count() > 0): ?>
                    <?php $promo = $promotionalOffers->first(); ?>
                    <div class="promo-featured-card">
                        <div class="special-offer-header"><?php echo e(is_rtl() ? 'عرض خاص' : 'Special Offer'); ?></div>
                        <div class="badge-save">
                            <span class="save-label"><?php echo e(is_rtl() ? 'وفر' : 'Save'); ?></span>
                            <span class="save-amount">₪<?php echo e(number_format($promo->original_price - $promo->sale_price, 0)); ?></span>
                        </div>
                        <div class="promo-media">
                            <?php
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
                            ?>
                            <img src="<?php echo e($img ?? asset('images/placeholder.png')); ?>" alt="<?php echo e($promo->title); ?>">
                        </div>
                        <div class="promo-body">
                            <div class="promo-title"><?php echo e($promo->title); ?></div>
                            <?php if($promo->product): ?>
                                <div class="promo-product-name"><?php echo e($promo->product->name); ?></div>
                            <?php endif; ?>
                            <div class="promo-prices">
                                <span class="orig">₪<?php echo e(number_format($promo->original_price, 0)); ?></span>
                                <span class="sale">₪<?php echo e(number_format($promo->sale_price, 0)); ?></span>
                            </div>
                            <?php if($promo->end_date): ?>
                            <div class="promo-countdown" data-end="<?php echo e(optional($promo->end_date)->format('c')); ?>">
                                <div class="label"><?php echo e(is_rtl() ? 'العرض ينتهي خلال:' : 'Hurry up! Offer ends in:'); ?></div>
                                <div class="boxes">
                                    <div class="box"><span class="num cd-hours">00</span><span class="unit"><?php echo e(is_rtl() ? 'ساعات' : 'HRS'); ?></span></div>
                                    <div class="box"><span class="num cd-mins">00</span><span class="unit"><?php echo e(is_rtl() ? 'دقائق' : 'MINS'); ?></span></div>
                                    <div class="box"><span class="num cd-secs">00</span><span class="unit"><?php echo e(is_rtl() ? 'ثواني' : 'SECS'); ?></span></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($promo->product): ?>
                            <div class="promo-cta">
                                <a href="<?php echo e(route('product.detail', $promo->product->slug)); ?>">
                                    <?php if(is_rtl()): ?>
                                        <?php echo e('اطلب الآن'); ?> <i class="fas fa-shopping-cart"></i>
                                    <?php else: ?>
                                        <i class="fas fa-shopping-cart"></i> <?php echo e('Order Now'); ?>

                                    <?php endif; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php $__currentLoopData = $featuredProducts->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="product-card" onclick="window.location.href='<?php echo e(route('product.detail', $product->slug)); ?>'">
                    <div class="product-image">
                        <?php if($product->is_new): ?>
                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                        <?php elseif($product->is_featured): ?>
                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                        <?php endif; ?>
                        <div class="wishlist-btn" data-product-id="<?php echo e($product->id); ?>" onclick="event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </div>
                        
                        
                        <div class="product-actions">
                            <button class="quick-view-btn" onclick="event.stopPropagation(); quickView(<?php echo e($product->id); ?>)" title="<?php echo e(is_rtl() ? 'معاينة سريعة' : 'Quick View'); ?>">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="compare-btn" onclick="event.stopPropagation(); addToCompare(<?php echo e($product->id); ?>)" title="<?php echo e(is_rtl() ? 'مقارنة' : 'Compare'); ?>">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </div>
                        
                        <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>" loading="lazy" decoding="async">
                    </div>
                    <div class="product-info">
                        
                        <div class="product-rating">
                            <div class="stars">
                                <?php
                                    $rating = $product->average_rating ?? 4.5;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                ?>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $fullStars): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-count">(<?php echo e($product->reviews_count ?? rand(10, 150)); ?>)</span>
                        </div>
                        
                        <div class="product-title"><?php echo e($product->name); ?></div>
                        <div class="product-description"><?php echo e(Str::limit($product->short_description, 60)); ?></div>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                    <span class="original-price">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                    <span class="current-price">₪ <?php echo e(number_format($product->sale_price, 0)); ?></span>
                                <?php else: ?>
                                    <span class="current-price">₪ <?php echo e(number_format($product->price, 0)); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($product->stock_status === 'out_of_stock'): ?>
                            <button class="add-to-cart-icon out-of-stock"
                                    data-product-id="<?php echo e($product->id); ?>"
                                    data-product-name="<?php echo e($product->name); ?>"
                                    title="<?php echo e(__t('messages.request_product')); ?>"
                                    aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                    onclick="event.stopPropagation(); requestProduct(<?php echo e($product->id); ?>, '<?php echo e($product->name); ?>');">
                                <i class="fas fa-bell"></i>
                            </button>
                            <?php else: ?>
                            <button class="add-to-cart-icon <?php echo e(in_array($product->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                    data-product-id="<?php echo e($product->id); ?>"
                                    title="<?php echo e(in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    aria-label="<?php echo e(in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    onclick="event.stopPropagation(); addToCart(<?php echo e($product->id); ?>, this);">
                                <i class="fas <?php echo e(in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>


<section class="home-section gift-ideas-section strong-offers-section" dir="<?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>">
    <div class="container">
        <div class="gift-ideas-grid">
            
            
            <div class="gift-ideas-item gift-banner-item strong-offers-banner">
                <div class="product-item-section gift-idea-banner" style="background-image: url(https://d2ati23fc66y9j.cloudfront.net/ubuycom/home_v5/gift-ideas/international-gifting-store.jpg); cursor: pointer;" onclick="window.location.href='<?php echo e(route('products', ['strong_offers' => 1])); ?>'">
                    
                    <div class="gift-banner-content">
                        <h3 class="gift-banner-title"><?php echo e(__t('messages.strong_offers.headline')); ?></h3>
                        <p>
                            <?php echo e(__t('messages.strong_offers.desc')); ?><br>
                            <?php if(app()->getLocale() === 'ar'): ?>
                                <?php echo e(__t('messages.strong_offers.discount')); ?><br>
                                <?php echo e(__t('messages.strong_offers.code')); ?>

                            <?php else: ?>
                                <?php echo e(__t('messages.strong_offers.discount')); ?><br>
                                <?php echo e(__t('messages.strong_offers.code')); ?>

                            <?php endif; ?>
                        </p>
                        <a class="gift-cta" href="<?php echo e(route('products', ['strong_offers' => 1])); ?>" onclick="event.stopPropagation();"><?php echo e(__t('messages.strong_offers.cta')); ?></a>
                    </div>
                    
                    
                    <div class="gift-banner-image">
                        <img class="gift-art" src="https://d2ati23fc66y9j.cloudfront.net/ubuycom/home_v5/daily-deal/daily-deals.png.webp?v=1.0" alt="<?php echo e(__t('messages.strong_offers.headline')); ?>" loading="lazy">
                    </div>
                </div>
            </div>

            
            <?php if(isset($featuredProducts[6])): ?>
            <div class="gift-ideas-item gift-product-item strong-offers-product">
                <div class="product-card h-100" onclick="window.location.href='<?php echo e(route('product.detail', $featuredProducts[6]->slug)); ?>'">
                    <div class="product-image">
                        <?php if($featuredProducts[6]->is_new): ?>
                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                        <?php elseif($featuredProducts[6]->is_featured): ?>
                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                        <?php endif; ?>
                        <div class="wishlist-btn" data-product-id="<?php echo e($featuredProducts[6]->id); ?>" onclick="event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </div>
                        <img src="<?php echo e($featuredProducts[6]->main_image); ?>" alt="<?php echo e($featuredProducts[6]->name); ?>" loading="lazy">
                    </div>
                    <div class="product-info">
                        <div class="product-title"><?php echo e($featuredProducts[6]->name); ?></div>
                        <div class="product-description"><?php echo e(Str::limit($featuredProducts[6]->short_description, 60)); ?></div>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if($featuredProducts[6]->sale_price && $featuredProducts[6]->sale_price < $featuredProducts[6]->price): ?>
                                    <span class="original-price">₪ <?php echo e(number_format($featuredProducts[6]->price, 0)); ?></span>
                                    <span class="current-price">₪ <?php echo e(number_format($featuredProducts[6]->sale_price, 0)); ?></span>
                                <?php else: ?>
                                    <span class="current-price">₪ <?php echo e(number_format($featuredProducts[6]->price, 0)); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($featuredProducts[6]->stock_status === 'out_of_stock'): ?>
                            <button class="add-to-cart-icon out-of-stock"
                                    data-product-id="<?php echo e($featuredProducts[6]->id); ?>"
                                    data-product-name="<?php echo e($featuredProducts[6]->name); ?>"
                                    title="<?php echo e(__t('messages.request_product')); ?>"
                                    aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                    onclick="event.stopPropagation(); requestProduct(<?php echo e($featuredProducts[6]->id); ?>, '<?php echo e($featuredProducts[6]->name); ?>');">
                                <i class="fas fa-bell"></i>
                            </button>
                            <?php else: ?>
                            <button class="add-to-cart-icon <?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                    data-product-id="<?php echo e($featuredProducts[6]->id); ?>"
                                    title="<?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    aria-label="<?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    onclick="event.stopPropagation(); addToCart(<?php echo e($featuredProducts[6]->id); ?>, this);">
                                <i class="fas <?php echo e(in_array($featuredProducts[6]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(isset($featuredProducts[7])): ?>
            <div class="gift-ideas-item gift-product-item strong-offers-product">
                <div class="product-card h-100" onclick="window.location.href='<?php echo e(route('product.detail', $featuredProducts[7]->slug)); ?>'">
                    <div class="product-image">
                        <?php if($featuredProducts[7]->is_new): ?>
                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                        <?php elseif($featuredProducts[7]->is_featured): ?>
                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                        <?php endif; ?>
                        <div class="wishlist-btn" data-product-id="<?php echo e($featuredProducts[7]->id); ?>" onclick="event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </div>
                        <img src="<?php echo e($featuredProducts[7]->main_image); ?>" alt="<?php echo e($featuredProducts[7]->name); ?>" loading="lazy">
                    </div>
                    <div class="product-info">
                        <div class="product-title"><?php echo e($featuredProducts[7]->name); ?></div>
                        <div class="product-description"><?php echo e(Str::limit($featuredProducts[7]->short_description, 60)); ?></div>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if($featuredProducts[7]->sale_price && $featuredProducts[7]->sale_price < $featuredProducts[7]->price): ?>
                                    <span class="original-price">₪ <?php echo e(number_format($featuredProducts[7]->price, 0)); ?></span>
                                    <span class="current-price">₪ <?php echo e(number_format($featuredProducts[7]->sale_price, 0)); ?></span>
                                <?php else: ?>
                                    <span class="current-price">₪ <?php echo e(number_format($featuredProducts[7]->price, 0)); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($featuredProducts[7]->stock_status === 'out_of_stock'): ?>
                            <button class="add-to-cart-icon out-of-stock"
                                    data-product-id="<?php echo e($featuredProducts[7]->id); ?>"
                                    data-product-name="<?php echo e($featuredProducts[7]->name); ?>"
                                    title="<?php echo e(__t('messages.request_product')); ?>"
                                    aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                    onclick="event.stopPropagation(); requestProduct(<?php echo e($featuredProducts[7]->id); ?>, '<?php echo e($featuredProducts[7]->name); ?>');">
                                <i class="fas fa-bell"></i>
                            </button>
                            <?php else: ?>
                            <button class="add-to-cart-icon <?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                    data-product-id="<?php echo e($featuredProducts[7]->id); ?>"
                                    title="<?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    aria-label="<?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    onclick="event.stopPropagation(); addToCart(<?php echo e($featuredProducts[7]->id); ?>, this);">
                                <i class="fas <?php echo e(in_array($featuredProducts[7]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Content Container -->
<div class="container">
    <!-- Special Discounts & Offers - HORIZONTAL SCROLLER -->
    <?php if($specialDiscounts->count() > 0): ?>
    <?php if (isset($component)) { $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-product-scroller','data' => ['products' => $specialDiscounts,'title' => ''.e(__t('messages.special_discounts')).'','viewMoreUrl' => route('products', ['filter' => 'sale']),'autoScroll' => true,'autoScrollInterval' => 4500,'cardsToScroll' => 1,'cartProductIds' => $cartProductIds,'showDiscountPercentage' => true,'containerId' => 'special-discounts-scroller']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-product-scroller'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($specialDiscounts),'title' => ''.e(__t('messages.special_discounts')).'','viewMoreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('products', ['filter' => 'sale'])),'autoScroll' => true,'autoScrollInterval' => 4500,'cardsToScroll' => 1,'cartProductIds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cartProductIds),'showDiscountPercentage' => true,'containerId' => 'special-discounts-scroller']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $attributes = $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $component = $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
    <?php endif; ?>

    <!-- New Arrivals - HORIZONTAL SCROLLER -->
    <?php if($newProducts->count() > 0): ?>
    <?php if (isset($component)) { $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-product-scroller','data' => ['products' => $newProducts,'title' => ''.e(__t('messages.new_arrivals')).'','viewMoreUrl' => route('products'),'autoScroll' => true,'autoScrollInterval' => 5000,'cardsToScroll' => 2,'cartProductIds' => $cartProductIds,'hideSaleBadge' => true,'containerId' => 'new-arrivals-scroller']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-product-scroller'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($newProducts),'title' => ''.e(__t('messages.new_arrivals')).'','viewMoreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('products')),'autoScroll' => true,'autoScrollInterval' => 5000,'cardsToScroll' => 2,'cartProductIds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cartProductIds),'hideSaleBadge' => true,'containerId' => 'new-arrivals-scroller']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $attributes = $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $component = $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
    <?php endif; ?>

    <!-- Bestsellers - HORIZONTAL SCROLLER -->
    <?php if($bestsellerProducts->count() > 0): ?>
    <?php if (isset($component)) { $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-product-scroller','data' => ['products' => $bestsellerProducts,'title' => ''.e(__t('messages.best_sellers')).'','viewMoreUrl' => route('products', ['filter' => 'bestseller']),'autoScroll' => true,'autoScrollInterval' => 6000,'cartProductIds' => $cartProductIds,'hideSaleBadge' => true,'containerId' => 'bestsellers-scroller']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-product-scroller'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($bestsellerProducts),'title' => ''.e(__t('messages.best_sellers')).'','viewMoreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('products', ['filter' => 'bestseller'])),'autoScroll' => true,'autoScrollInterval' => 6000,'cartProductIds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cartProductIds),'hideSaleBadge' => true,'containerId' => 'bestsellers-scroller']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $attributes = $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $component = $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<!-- End Main Content Container -->


<section class="home-section gift-ideas-section" dir="<?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>">
    <div class="container">
        <div class="gift-ideas-grid">
            
            <?php if(is_rtl()): ?>
                
                <div class="gift-ideas-item gift-banner-item">
                    <div class="product-item-section gift-idea-banner" style="background-image: url(https://d2ati23fc66y9j.cloudfront.net/ubuycom/home_v5/gift-ideas/international-gifting-store.jpg); cursor: pointer;" onclick="window.location.href='<?php echo e(route('products', ['filter' => 'gifts'])); ?>'">
                        
                        <div class="gift-banner-content">
                            <h3 class="gift-banner-title"><?php echo e(__t('messages.gift_ideas.headline')); ?></h3>
                            <p><?php echo e(__t('messages.gift_ideas.desc')); ?></p>
                            <a class="gift-cta" href="<?php echo e(route('products', ['filter' => 'gifts'])); ?>" onclick="event.stopPropagation();"><?php echo e(__t('messages.gift_ideas.cta')); ?></a>
                        </div>
                        
                        
                        <div class="gift-banner-image">
                            <img class="gift-art" src="https://d2ati23fc66y9j.cloudfront.net/ubuycom/home_v5/gift-ideas/international-gifting-store.png.webp?v=1.0" alt="<?php echo e(__t('messages.gift_ideas.headline')); ?>" loading="lazy">
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if(isset($giftIdeas[0])): ?>
            <div class="gift-ideas-item gift-product-item">
                <div class="product-card h-100" onclick="window.location.href='<?php echo e(route('product.detail', $giftIdeas[0]->slug)); ?>'">
                    <div class="product-image">
                        <?php if($giftIdeas[0]->is_new): ?>
                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                        <?php elseif($giftIdeas[0]->is_featured): ?>
                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                        <?php endif; ?>
                        <div class="wishlist-btn" data-product-id="<?php echo e($giftIdeas[0]->id); ?>" onclick="event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </div>
                        <img src="<?php echo e($giftIdeas[0]->main_image); ?>" alt="<?php echo e($giftIdeas[0]->name); ?>" loading="lazy">
                    </div>
                    <div class="product-info">
                        <div class="product-title"><?php echo e($giftIdeas[0]->name); ?></div>
                        <div class="product-description"><?php echo e(Str::limit($giftIdeas[0]->short_description, 60)); ?></div>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if($giftIdeas[0]->sale_price && $giftIdeas[0]->sale_price < $giftIdeas[0]->price): ?>
                                    <span class="original-price">₪ <?php echo e(number_format($giftIdeas[0]->price, 0)); ?></span>
                                    <span class="current-price">₪ <?php echo e(number_format($giftIdeas[0]->sale_price, 0)); ?></span>
                                <?php else: ?>
                                    <span class="current-price">₪ <?php echo e(number_format($giftIdeas[0]->price, 0)); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($giftIdeas[0]->stock_status === 'out_of_stock'): ?>
                            <button class="add-to-cart-icon out-of-stock"
                                    data-product-id="<?php echo e($giftIdeas[0]->id); ?>"
                                    data-product-name="<?php echo e($giftIdeas[0]->name); ?>"
                                    title="<?php echo e(__t('messages.request_product')); ?>"
                                    aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                    onclick="event.stopPropagation(); requestProduct(<?php echo e($giftIdeas[0]->id); ?>, '<?php echo e($giftIdeas[0]->name); ?>');">
                                <i class="fas fa-bell"></i>
                            </button>
                            <?php else: ?>
                            <button class="add-to-cart-icon <?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                    data-product-id="<?php echo e($giftIdeas[0]->id); ?>"
                                    title="<?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    aria-label="<?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    onclick="event.stopPropagation(); addToCart(<?php echo e($giftIdeas[0]->id); ?>, this);">
                                <i class="fas <?php echo e(in_array($giftIdeas[0]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(isset($giftIdeas[1])): ?>
            <div class="gift-ideas-item gift-product-item">
                <div class="product-card h-100" onclick="window.location.href='<?php echo e(route('product.detail', $giftIdeas[1]->slug)); ?>'">
                    <div class="product-image">
                        <?php if($giftIdeas[1]->is_new): ?>
                        <div class="product-badge"><?php echo e(__t('messages.new')); ?></div>
                        <?php elseif($giftIdeas[1]->is_featured): ?>
                        <div class="product-badge"><?php echo e(__t('messages.hot')); ?></div>
                        <?php endif; ?>
                        <div class="wishlist-btn" data-product-id="<?php echo e($giftIdeas[1]->id); ?>" onclick="event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </div>
                        <img src="<?php echo e($giftIdeas[1]->main_image); ?>" alt="<?php echo e($giftIdeas[1]->name); ?>" loading="lazy">
                    </div>
                    <div class="product-info">
                        <div class="product-title"><?php echo e($giftIdeas[1]->name); ?></div>
                        <div class="product-description"><?php echo e(Str::limit($giftIdeas[1]->short_description, 60)); ?></div>
                        <div class="product-footer">
                            <div class="product-price">
                                <?php if($giftIdeas[1]->sale_price && $giftIdeas[1]->sale_price < $giftIdeas[1]->price): ?>
                                    <span class="original-price">₪ <?php echo e(number_format($giftIdeas[1]->price, 0)); ?></span>
                                    <span class="current-price">₪ <?php echo e(number_format($giftIdeas[1]->sale_price, 0)); ?></span>
                                <?php else: ?>
                                    <span class="current-price">₪ <?php echo e(number_format($giftIdeas[1]->price, 0)); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($giftIdeas[1]->stock_status === 'out_of_stock'): ?>
                            <button class="add-to-cart-icon out-of-stock"
                                    data-product-id="<?php echo e($giftIdeas[1]->id); ?>"
                                    data-product-name="<?php echo e($giftIdeas[1]->name); ?>"
                                    title="<?php echo e(__t('messages.request_product')); ?>"
                                    aria-label="<?php echo e(__t('messages.request_product')); ?>"
                                    onclick="event.stopPropagation(); requestProduct(<?php echo e($giftIdeas[1]->id); ?>, '<?php echo e($giftIdeas[1]->name); ?>');">
                                <i class="fas fa-bell"></i>
                            </button>
                            <?php else: ?>
                            <button class="add-to-cart-icon <?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? 'in-cart' : ''); ?>"
                                    data-product-id="<?php echo e($giftIdeas[1]->id); ?>"
                                    title="<?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    aria-label="<?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart')); ?>"
                                    onclick="event.stopPropagation(); addToCart(<?php echo e($giftIdeas[1]->id); ?>, this);">
                                <i class="fas <?php echo e(in_array($giftIdeas[1]->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart'); ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if(!is_rtl()): ?>
                
                <div class="gift-ideas-item gift-banner-item">
                    <div class="product-item-section gift-idea-banner" style="background-image: url(https://d2ati23fc66y9j.cloudfront.net/ubuycom/home_v5/gift-ideas/international-gifting-store.jpg); cursor: pointer;" onclick="window.location.href='<?php echo e(route('products', ['filter' => 'gifts'])); ?>'">
                        
                        <div class="gift-banner-content">
                            <h3 class="gift-banner-title"><?php echo e(__t('messages.gift_ideas.headline')); ?></h3>
                            <p><?php echo e(__t('messages.gift_ideas.desc')); ?></p>
                            <a class="gift-cta" href="<?php echo e(route('products', ['filter' => 'gifts'])); ?>" onclick="event.stopPropagation();"><?php echo e(__t('messages.gift_ideas.cta')); ?></a>
                        </div>
                        
                        
                        <div class="gift-banner-image">
                            <img class="gift-art" src="https://d2ati23fc66y9j.cloudfront.net/ubuycom/home_v5/gift-ideas/international-gifting-store.png.webp?v=1.0" alt="<?php echo e(__t('messages.gift_ideas.headline')); ?>" loading="lazy">
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Continue Main Content Container -->
<div class="container">
    <!-- On Sale Products - HORIZONTAL SCROLLER -->
    <?php if($onSaleProducts->count() > 0): ?>
    <?php if (isset($component)) { $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.horizontal-product-scroller','data' => ['products' => $onSaleProducts,'title' => ''.e(__t('messages.on_sale')).'','viewMoreUrl' => route('products', ['filter' => 'sale']),'autoScroll' => true,'autoScrollInterval' => 5000,'cardsToScroll' => 1,'cartProductIds' => $cartProductIds,'hideSaleBadge' => true,'containerId' => 'on-sale-scroller']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('horizontal-product-scroller'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['products' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($onSaleProducts),'title' => ''.e(__t('messages.on_sale')).'','viewMoreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('products', ['filter' => 'sale'])),'autoScroll' => true,'autoScrollInterval' => 5000,'cardsToScroll' => 1,'cartProductIds' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cartProductIds),'hideSaleBadge' => true,'containerId' => 'on-sale-scroller']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $attributes = $__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__attributesOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce)): ?>
<?php $component = $__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce; ?>
<?php unset($__componentOriginal87d4e907aef0f5d4d0507d4d54c177ce); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<!-- End Main Content Container -->

<script>
    // Store cart product IDs from server
    window.cartProductIds = <?php echo json_encode($cartProductIds, 15, 512) ?>;
    
    // Quick View Function
    window.quickView = function(productId) {
        // Show loading
        Swal.fire({
            title: '<?php echo e(is_rtl() ? "جاري التحميل..." : "Loading..."); ?>',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Fetch product details and show in modal
        fetch(`/api/products/${productId}/quick-view`)
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    html: `
                        <div class="quick-view-modal" style="text-align: <?php echo e(is_rtl() ? 'right' : 'left'); ?>;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
                                <div>
                                    <img src="${data.main_image}" alt="${data.name}" style="width: 100%; border-radius: 8px;">
                                </div>
                                <div>
                                    <h2 style="margin-bottom: 1rem; font-size: 1.5rem;">${data.name}</h2>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                        <div style="color: #fbbf24;">★★★★☆</div>
                                        <span style="color: #6b7280; font-size: 0.9rem;">(${data.reviews_count || 0} <?php echo e(is_rtl() ? 'تقييم' : 'reviews'); ?>)</span>
                                    </div>
                                    <p style="color: #6b7280; margin-bottom: 1.5rem;">${data.short_description}</p>
                                    <div style="font-size: 1.8rem; font-weight: bold; color: #1f2937; margin-bottom: 1.5rem;">
                                        ₪${data.sale_price || data.price}
                                        ${data.sale_price ? `<span style="text-decoration: line-through; color: #9ca3af; font-size: 1.2rem; margin-left: 0.5rem;">₪${data.price}</span>` : ''}
                                    </div>
                                    <a href="/products/${data.slug}" class="swal2-confirm swal2-styled" style="margin-top: 1rem;">
                                        <?php echo e(is_rtl() ? 'عرض التفاصيل الكاملة' : 'View Full Details'); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true,
                    width: '800px',
                    customClass: {
                        container: 'quick-view-container'
                    }
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo e(is_rtl() ? "خطأ" : "Error"); ?>',
                    text: '<?php echo e(is_rtl() ? "حدث خطأ أثناء تحميل المنتج" : "Error loading product"); ?>'
                });
            });
    };
    
    // Compare Function
    let compareList = JSON.parse(localStorage.getItem('compareList') || '[]');
    
    window.addToCompare = function(productId) {
        if (compareList.includes(productId)) {
            Swal.fire({
                icon: 'info',
                title: '<?php echo e(is_rtl() ? "موجود مسبقاً" : "Already Added"); ?>',
                text: '<?php echo e(is_rtl() ? "هذا المنتج موجود في قائمة المقارنة" : "This product is already in compare list"); ?>',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
        
        if (compareList.length >= 4) {
            Swal.fire({
                icon: 'warning',
                title: '<?php echo e(is_rtl() ? "القائمة ممتلئة" : "List Full"); ?>',
                text: '<?php echo e(is_rtl() ? "يمكنك مقارنة 4 منتجات كحد أقصى" : "You can compare maximum 4 products"); ?>',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
        
        compareList.push(productId);
        localStorage.setItem('compareList', JSON.stringify(compareList));
        
        Swal.fire({
            icon: 'success',
            title: '<?php echo e(is_rtl() ? "تمت الإضافة!" : "Added!"); ?>',
            text: '<?php echo e(is_rtl() ? "تمت إضافة المنتج لقائمة المقارنة" : "Product added to compare list"); ?>',
            timer: 1500,
            showConfirmButton: false
        });
    };
    
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
        const progressBar = document.getElementById('sliderProgressBar');
        const totalSlides = slides.length;
        let slideInterval;
        let progressInterval;
        const slideDuration = 5000; // 5 seconds

        // Function to change slide
        window.changeSlide = function(direction) {
            clearInterval(slideInterval);
            clearInterval(progressInterval);
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            updateSlider();
            startAutoSlide();
        }

        // Function to go to specific slide
        window.goToSlide = function(slideIndex) {
            clearInterval(slideInterval);
            clearInterval(progressInterval);
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
            
            // Reset progress bar
            if (progressBar) {
                progressBar.style.width = '0%';
            }
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        // Function to animate progress bar
        function animateProgressBar() {
            if (!progressBar) return;
            
            clearInterval(progressInterval);
            let progress = 0;
            const increment = 100 / (slideDuration / 50); // Update every 50ms
            
            progressInterval = setInterval(() => {
                progress += increment;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(progressInterval);
                }
                progressBar.style.width = progress + '%';
            }, 50);
        }

        // Function to start auto sliding
        function startAutoSlide() {
            clearInterval(slideInterval);
            clearInterval(progressInterval);
            animateProgressBar();
            
            slideInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlider();
                animateProgressBar();
            }, slideDuration);
        }

        // Start auto sliding
        startAutoSlide();

        // Pause auto sliding when mouse is over the slider
        const heroSection = document.querySelector('.hero-section');
        heroSection.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
            clearInterval(progressInterval);
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/home.blade.php ENDPATH**/ ?>