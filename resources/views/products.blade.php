@extends('layouts.app')

@section('title', 'Our Products - IT Center')

@section('content')
<style>
    /* Import Google Fonts - Poppins & Cairo for Arabic */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Cairo:wght@300;400;500;600;700;800;900&display=swap');

    /* Override font - exclude Font Awesome icons */
    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        @if(is_rtl())
        font-family: 'Cairo', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        @else
        font-family: 'Poppins', 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        @endif
    }

    /* Ensure Font Awesome icons keep their font */
    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    .products-section {
        padding: 3rem 2rem;
        background: #F9FAFB;
        min-height: 100vh;
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

    /* Search Results Info Box */
    .search-results-info-box {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 3rem;
        margin-top: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        animation: fadeInUp 0.6s ease-out;
        @if(is_rtl())
        flex-direction: row-reverse;
        @endif
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .search-query-display {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .search-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
        @if(is_rtl())
        flex-direction: row-reverse;
        @endif
    }

    .search-label i {
        color: #3B82F6;
    }

    .search-query {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .search-results-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .count-number {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
    }

    .count-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

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
        display: flex;
        flex-direction: column;
        position: relative;
    }

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
        transition: transform 0.4s ease-in-out, filter 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
        filter: brightness(1);
        will-change: transform;
    }

    .product-card:hover .product-image img {
        transform: scale(1.08);
        filter: brightness(1.05);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .product-image .icon-placeholder {
        font-size: 4rem;
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

    .product-card a {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .wishlist-btn,
    .add-to-cart {
        position: relative;
        z-index: 10;
    }

    /* Pagination Styling */
    .pagination {
        display: flex !important;
        gap: 0.5rem !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 1rem 0 !important;
        list-style: none !important;
        margin: 0 !important;
    }

    .pagination li {
        list-style: none !important;
        margin: 0 !important;
    }

    .pagination .page-item {
        list-style: none !important;
    }

    .pagination .page-link,
    .pagination a,
    .pagination span {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 40px !important;
        height: 40px !important;
        padding: 0.5rem 1rem !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        color: #333 !important;
        text-decoration: none !important;
        font-weight: 500 !important;
        transition: all 0.3s !important;
        font-size: 1rem !important;
        line-height: 1 !important;
    }

    .pagination .page-link:hover,
    .pagination a:hover {
        background: #4169E1 !important;
        color: #fff !important;
        border-color: #4169E1 !important;
        transform: translateY(-2px) !important;
    }

    .pagination .page-item.active .page-link,
    .pagination .page-item.active span,
    .pagination .active span {
        background: #4169E1 !important;
        color: #fff !important;
        border-color: #4169E1 !important;
    }

    .pagination .page-item.disabled .page-link,
    .pagination .page-item.disabled span,
    .pagination .disabled span {
        background: #f5f5f5 !important;
        color: #999 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }

    .pagination .page-link svg,
    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }

    /* Hide default nav wrapper styles */
    .pagination nav {
        width: 100% !important;
    }

    .pagination-wrapper {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
    }

    .pagination-wrapper nav {
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
    }

    .pagination-wrapper ul {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0.5rem !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-footer {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .add-to-cart {
            width: 100%;
            min-width: unset;
        }
        
        .product-price {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
        
        .add-to-cart {
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
        }
    }

    /* Search Results Indicator */
    .search-results-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
    }

    .search-results-info h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .search-results-info p {
        font-size: 1rem;
        opacity: 0.95;
        margin: 0;
    }

    .search-query-highlight {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
        margin: 0 0.3rem;
    }

    .clear-search-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid white;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .clear-search-btn:hover {
        background: white;
        color: #667eea;
    }

    /* RTL Support for Search Results */
    @if(is_rtl())
    .search-results-info h3,
    .search-results-info p {
        direction: rtl;
    }
    @endif

    .no-results {
        text-align: center;
        padding: 5rem 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .no-results::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(78, 115, 223, 0.05) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .no-results-content {
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.8s ease-out;
    }

    .no-results-icon {
        width: 160px;
        height: 160px;
        margin: 0 auto 2.5rem;
        background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 60px rgba(236, 72, 153, 0.3), 0 0 100px rgba(139, 92, 246, 0.2);
        animation: iconFloat 4s ease-in-out infinite;
        position: relative;
    }

    .no-results-icon::before {
        content: '';
        position: absolute;
        inset: -10px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.3) 0%, rgba(139, 92, 246, 0.3) 100%);
        filter: blur(20px);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes iconFloat {
        0%, 100% { 
            transform: translateY(0) scale(1); 
        }
        25% {
            transform: translateY(-15px) scale(1.02);
        }
        50% { 
            transform: translateY(0) scale(1); 
        }
        75% {
            transform: translateY(-8px) scale(0.98);
        }
    }

    .no-results-icon i {
        font-size: 4.5rem;
        color: white;
        margin: 0;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
    }

    .no-results h3 {
        font-size: 2.25rem;
        color: #1e293b;
        margin-bottom: 1.25rem;
        font-weight: 700;
        line-height: 1.3;
        @if(is_rtl())
        direction: rtl;
        @endif
    }

    .no-results p {
        font-size: 1.15rem;
        color: #64748b;
        margin-bottom: 3rem;
        line-height: 1.8;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        @if(is_rtl())
        direction: rtl;
        text-align: center;
        @endif
    }

    .no-results-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        @if(is_rtl())
        flex-direction: row-reverse;
        @endif
    }

    .btn-primary-action {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
        padding: 1.1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.35);
        font-size: 1.05rem;
        border: none;
        @if(is_rtl())
        flex-direction: row-reverse;
        @endif
    }

    .btn-primary-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(59, 130, 246, 0.45);
        color: white;
    }

    .btn-primary-action:active {
        transform: translateY(-1px);
    }

    .btn-secondary-action {
        background: white;
        color: #3B82F6;
        padding: 1.1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #3B82F6;
        font-size: 1.05rem;
        @if(is_rtl())
        flex-direction: row-reverse;
        @endif
    }

    .btn-secondary-action:hover {
        background: #3B82F6;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary-action:active {
        transform: translateY(-1px);
    }

    /* RTL Support for No Results */
    @if(is_rtl())
    .no-results h3,
    .no-results p {
        direction: rtl;
        text-align: center;
    }
    @endif

    /* Product Grid Fade In Animation */
    .product-card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .product-card:nth-child(1) { animation-delay: 0.1s; }
    .product-card:nth-child(2) { animation-delay: 0.15s; }
    .product-card:nth-child(3) { animation-delay: 0.2s; }
    .product-card:nth-child(4) { animation-delay: 0.25s; }
    .product-card:nth-child(5) { animation-delay: 0.3s; }
    .product-card:nth-child(6) { animation-delay: 0.35s; }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .search-results-info-box {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }

        .search-query-display {
            align-items: center;
        }

        .search-label {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .products-section {
            padding: 2rem 1rem;
        }

        .search-results-info-box {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .search-query {
            font-size: 1.25rem;
        }

        .count-number {
            font-size: 1.75rem;
        }

        .no-results {
            padding: 3rem 1.5rem;
        }

        .no-results-icon {
            width: 120px;
            height: 120px;
        }

        .no-results-icon i {
            font-size: 3rem;
        }

        .no-results h3 {
            font-size: 1.75rem;
        }

        .no-results p {
            font-size: 1rem;
        }

        .no-results-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-primary-action,
        .btn-secondary-action {
            width: 100%;
            justify-content: center;
        }

        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .search-query {
            font-size: 1.1rem;
        }

        .search-results-info-box {
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .no-results-icon {
            width: 100px;
            height: 100px;
        }

        .no-results-icon i {
            font-size: 2.5rem;
        }

        .no-results h3 {
            font-size: 1.5rem;
        }

        .btn-primary-action,
        .btn-secondary-action {
            padding: 1rem 2rem;
            font-size: 0.95rem;
        }
    }
</style>

<div class="products-section">
    <div class="container">
        @if(request('search'))
        <!-- Search Results Info Box -->
        <div class="search-results-info-box">
            <div class="search-query-display">
                <div class="search-label">
                    <i class="fas fa-search"></i>
                    <span>{{ is_rtl() ? 'نتائج البحث عن' : 'Search results for' }}</span>
                </div>
                <div class="search-query">"{{ request('search') }}"</div>
            </div>
            <div class="search-results-count">
                <span class="count-number">{{ $products->total() }}</span>
                <span class="count-label">{{ is_rtl() ? 'منتج' : 'products' }}</span>
            </div>
        </div>
        @else
        <div class="section-header">
            <h2>{{ __t('messages.all_products') }}</h2>
        </div>
        @endif

        @if($products->count() > 0)
        <div class="product-grid">
            @forelse($products as $product)
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
            @endforelse
        </div>
        @else
        <div class="no-results">
            <div class="no-results-content">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>
                    @if(is_rtl())
                        لم يتم العثور على نتائج
                    @else
                        No Results Found
                    @endif
                </h3>
                <p>
                    @if(request('search'))
                        @if(is_rtl())
                            لم يتم العثور على نتائج مطابقة لبحثك عن <strong>"{{ request('search') }}"</strong><br>
                            جرب استخدام كلمات مفتاحية مختلفة أو تصفح جميع المنتجات.
                        @else
                            No results found matching your search for <strong>"{{ request('search') }}"</strong><br>
                            Try using different keywords or browse all products.
                        @endif
                    @else
                        @if(is_rtl())
                            لا توجد منتجات متاحة في الوقت الحالي.
                        @else
                            No products are currently available.
                        @endif
                    @endif
                </p>
                <div class="no-results-actions">
                    <a href="{{ route('products') }}" class="btn-primary-action">
                        @if(is_rtl())
                            <span>عرض جميع المنتجات</span>
                            <i class="fas fa-th-large"></i>
                        @else
                            <i class="fas fa-th-large"></i>
                            <span>View All Products</span>
                        @endif
                    </a>
                    @if(request('search'))
                    <a href="{{ route('home') }}" class="btn-secondary-action">
                        @if(is_rtl())
                            <span>العودة للرئيسية</span>
                            <i class="fas fa-home"></i>
                        @else
                            <i class="fas fa-home"></i>
                            <span>Back to Home</span>
                        @endif
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if(isset($products) && method_exists($products, 'hasPages') && $products->hasPages())
        <div class="pagination-wrapper" style="display: flex; justify-content: center; margin: 3rem 0 2rem 0; padding: 0 1rem; width: 100%;">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    // Add event listener for wishlist buttons to force color change
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
</script>

@endsection
