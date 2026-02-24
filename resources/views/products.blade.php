@extends('layouts.app')

@section('title', 'Our Products - IT Center')

@section('content')
<!-- Import shared components CSS -->
<link rel="stylesheet" href="{{ asset('css/components.css') }}">
<link rel="stylesheet" href="{{ asset('css/filter-sidebar.css') }}?v={{ filemtime(public_path('css/filter-sidebar.css')) }}">

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

    /* ═══════════════════════════════════════════════════
       BREADCRUMBS
       ═══════════════════════════════════════════════════ */
    .breadcrumb-bar {
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.875rem 0;
    }

    .breadcrumb-bar .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .breadcrumb-list {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: 0.875rem;
    }

    .breadcrumb-list li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
    }

    .breadcrumb-list li a {
        color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-list li a:hover {
        color: #2563eb;
    }

    .breadcrumb-list li.active {
        color: #1e293b;
        font-weight: 600;
    }

    .breadcrumb-separator {
        color: #cbd5e1;
        font-size: 0.75rem;
    }

    /* Browse Categories in breadcrumb bar */
    .breadcrumb-bar .browse-categories-wrapper {
        margin: 0;
    }

    .breadcrumb-bar .browse-categories-btn {
        height: 34px;
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
    }

    .breadcrumb-bar .browse-categories-btn:hover,
    .breadcrumb-bar .browse-categories-btn.active {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
    }

    /* ═══════════════════════════════════════════════════
       MAIN PRODUCTS SECTION
       ═══════════════════════════════════════════════════ */
    .products-page {
        background: #f8fafc;
        min-height: 100vh;
        padding-bottom: 3rem;
    }

    .products-page .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .products-layout {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
        padding-top: 1.5rem;
    }

    .products-main {
        flex: 1;
        min-width: 0;
    }

    /* ═══════════════════════════════════════════════════
       TOOLBAR — Sort, View Toggle, Per Page
       ═══════════════════════════════════════════════════ */
    .products-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 0.625rem 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .toolbar-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .toolbar-count {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        white-space: nowrap;
    }

    .toolbar-count strong {
        color: #1e293b;
        font-weight: 700;
    }

    /* View Toggle */
    .view-toggle {
        display: flex;
        align-items: center;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .view-toggle-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 34px;
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .view-toggle-btn:not(:last-child) {
        border-right: 1px solid #e5e7eb;
    }

    .view-toggle-btn.active {
        background: #2563eb;
        color: #ffffff;
    }

    .view-toggle-btn:hover:not(.active) {
        background: #f1f5f9;
        color: #475569;
    }

    .toolbar-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .toolbar-control {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .toolbar-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        white-space: nowrap;
    }

    .toolbar-select {
        padding: 0.4rem 2rem 0.4rem 0.625rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.825rem;
        font-weight: 500;
        color: #334155;
        background: #ffffff;
        cursor: pointer;
        outline: none;
        transition: border-color 0.2s;
        font-family: inherit;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 12px;
    }

    [dir="rtl"] .toolbar-select {
        padding: 0.4rem 0.625rem 0.4rem 2rem;
        background-position: left 0.5rem center;
    }

    .toolbar-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    /* ═══════════════════════════════════════════════════
       PAGE HEADER
       ═══════════════════════════════════════════════════ */
    .page-title-section {
        margin-bottom: 0.25rem;
    }

    .page-title-section h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }

    /* ═══════════════════════════════════════════════════
       SEARCH RESULTS INFO
       ═══════════════════════════════════════════════════ */
    .search-info-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        gap: 1rem;
    }

    .search-info-left {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .search-info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .search-info-label i {
        color: #2563eb;
    }

    .search-info-query {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e40af;
    }

    .search-info-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.125rem;
        padding: 0.75rem 1.25rem;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
    }

    .search-count-num {
        font-size: 1.5rem;
        font-weight: 800;
        color: #2563eb;
        line-height: 1;
    }

    .search-count-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ═══════════════════════════════════════════════════
       ACTIVE TAG INFO
       ═══════════════════════════════════════════════════ */
    .active-tag-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.875rem 1.25rem;
        background: linear-gradient(135deg, rgba(var(--tag-color-rgb, 59, 130, 246), 0.08) 0%, rgba(var(--tag-color-rgb, 37, 99, 235), 0.03) 100%);
        border-radius: 10px;
        margin-bottom: 1.25rem;
        border: 1px solid rgba(var(--tag-color-rgb, 59, 130, 246), 0.15);
    }

    .active-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        background: var(--tag-color, #3b82f6);
        color: white;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .active-tag-badge .tag-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
    }

    .active-tag-count {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    /* ═══════════════════════════════════════════════════
       PRODUCT GRID
       ═══════════════════════════════════════════════════ */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .product-grid.list-view {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    /* Grid loading animation */
    .product-grid.loading .product-card {
        opacity: 0;
        transform: translateY(12px);
        animation: cardReveal 0.35s ease-out forwards;
    }

    .product-grid.loading .product-card:nth-child(1) { animation-delay: 0.04s; }
    .product-grid.loading .product-card:nth-child(2) { animation-delay: 0.08s; }
    .product-grid.loading .product-card:nth-child(3) { animation-delay: 0.12s; }
    .product-grid.loading .product-card:nth-child(4) { animation-delay: 0.16s; }
    .product-grid.loading .product-card:nth-child(5) { animation-delay: 0.20s; }
    .product-grid.loading .product-card:nth-child(6) { animation-delay: 0.24s; }
    .product-grid.loading .product-card:nth-child(7) { animation-delay: 0.28s; }
    .product-grid.loading .product-card:nth-child(8) { animation-delay: 0.32s; }
    .product-grid.loading .product-card:nth-child(9) { animation-delay: 0.36s; }
    .product-grid.loading .product-card:nth-child(10) { animation-delay: 0.40s; }
    .product-grid.loading .product-card:nth-child(11) { animation-delay: 0.44s; }
    .product-grid.loading .product-card:nth-child(12) { animation-delay: 0.48s; }

    @keyframes cardReveal {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ═══════════════════════════════════════════════════
       PRODUCT CARD
       ═══════════════════════════════════════════════════ */
    .product-card-link {
        display: block;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .product-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .product-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }

    /* Product Image */
    .product-image {
        position: relative;
        height: 220px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-bottom: 1px solid #f1f5f9;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 1rem;
        transition: transform 0.35s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-image .icon-placeholder {
        font-size: 3.5rem;
        color: #cbd5e1;
    }

    /* Badge */
    .product-badge {
        position: absolute;
        top: 10px;
        @if(is_rtl())
        right: 10px;
        @else
        left: 10px;
        @endif
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
    }

    /* Wishlist */
    .wishlist-btn {
        position: absolute;
        top: 10px;
        @if(is_rtl())
        left: 10px;
        @else
        right: 10px;
        @endif
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .wishlist-btn:hover {
        background: #fef2f2;
        border-color: #fca5a5;
    }

    .wishlist-btn i {
        font-size: 0.875rem;
        color: #94a3b8;
        transition: color 0.2s;
    }

    .wishlist-btn:hover i {
        color: #ef4444;
    }

    .wishlist-btn.active i,
    .wishlist-btn i.fas.fa-heart {
        color: #ef4444;
    }

    /* Product Info */
    .product-info {
        padding: 0.875rem 1rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 0.375rem;
    }

    .product-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
        margin: 0;
    }

    .product-card:hover .product-title {
        color: #2563eb;
    }

    .product-description {
        font-size: 0.775rem;
        color: #94a3b8;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }

    /* Product Footer */
    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 0.5rem;
    }

    .product-price {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .product-price .original-price {
        text-decoration: line-through;
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
    }

    .product-price .current-price {
        color: #2563eb;
        font-weight: 700;
        font-size: 1.125rem;
        line-height: 1.2;
    }

    /* Add to Cart Icon Button */
    .add-to-cart-icon {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        position: relative;
        z-index: 5;
        flex-shrink: 0;
    }

    .add-to-cart-icon:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    .add-to-cart-icon:active {
        transform: scale(1);
    }

    .add-to-cart-icon.in-cart {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
    }

    .add-to-cart-icon.in-cart:hover {
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    }

    .add-to-cart-icon.in-cart i {
        animation: cartBounce 0.5s ease;
    }

    .add-to-cart-icon.out-of-stock {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        box-shadow: 0 2px 6px rgba(249, 115, 22, 0.25);
        cursor: pointer;
    }

    .add-to-cart-icon.out-of-stock:hover {
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
    }

    @keyframes cartBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    /* Full-width Add to Cart (hidden by default, shown in list view) */
    .add-to-cart {
        background: transparent;
        color: #2563eb;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1.5px solid #2563eb;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.25s ease;
        display: none;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        white-space: nowrap;
        font-size: 0.825rem;
    }

    .add-to-cart:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .add-to-cart.in-cart {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
    }

    .add-to-cart.out-of-stock {
        background: transparent;
        color: #f97316;
        border-color: #f97316;
        cursor: not-allowed;
    }

    .add-to-cart.out-of-stock:hover {
        background: #f97316;
        color: #ffffff;
    }

    .wishlist-btn,
    .add-to-cart,
    .add-to-cart-icon {
        position: relative;
        z-index: 10;
    }

    /* ═══════════════════════════════════════════════════
       LIST VIEW CARD OVERRIDES
       ═══════════════════════════════════════════════════ */
    .product-grid.list-view .product-card {
        flex-direction: row;
        border-radius: 10px;
    }

    .product-grid.list-view .product-image {
        width: 200px;
        min-width: 200px;
        height: 180px;
        border-bottom: none;
        border-right: 1px solid #f1f5f9;
    }

    [dir="rtl"] .product-grid.list-view .product-image {
        border-right: none;
        border-left: 1px solid #f1f5f9;
    }

    .product-grid.list-view .product-info {
        padding: 1rem 1.25rem;
    }

    .product-grid.list-view .product-title {
        font-size: 1rem;
        -webkit-line-clamp: 1;
    }

    .product-grid.list-view .product-description {
        -webkit-line-clamp: 2;
        display: -webkit-box;
    }

    .product-grid.list-view .product-footer {
        flex-direction: row;
        align-items: center;
    }

    .product-grid.list-view .add-to-cart {
        display: inline-flex;
        width: auto;
    }

    .product-grid.list-view .add-to-cart-icon {
        display: none;
    }

    /* ═══════════════════════════════════════════════════
       PAGINATION
       ═══════════════════════════════════════════════════ */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 2rem 0 1rem 0;
        padding: 0 1rem;
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .pagination {
        display: flex !important;
        gap: 0.375rem !important;
        align-items: center !important;
        justify-content: center !important;
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .pagination li {
        list-style: none !important;
    }

    .pagination .page-link,
    .pagination a,
    .pagination span {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 38px !important;
        height: 38px !important;
        padding: 0 0.75rem !important;
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        color: #374151 !important;
        text-decoration: none !important;
        font-weight: 500 !important;
        font-size: 0.875rem !important;
        transition: all 0.2s !important;
    }

    .pagination .page-link:hover,
    .pagination a:hover {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
    }

    .pagination .page-item.active .page-link,
    .pagination .page-item.active span,
    .pagination .active span {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #2563eb !important;
    }

    .pagination .page-item.disabled .page-link,
    .pagination .page-item.disabled span,
    .pagination .disabled span {
        background: #f8fafc !important;
        color: #94a3b8 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }

    .pagination svg {
        width: 14px !important;
        height: 14px !important;
    }

    /* ═══════════════════════════════════════════════════
       NO RESULTS
       ═══════════════════════════════════════════════════ */
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        margin: 1rem 0;
    }

    .no-results-content {
        animation: fadeInUp 0.6s ease-out;
    }

    .no-results-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .no-results-icon i {
        font-size: 2.5rem;
        color: #2563eb;
    }

    .no-results h3 {
        font-size: 1.5rem;
        color: #1e293b;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }

    .no-results p {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 2rem;
        line-height: 1.7;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .no-results-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary-action {
        background: #2563eb;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        border: none;
    }

    .btn-primary-action:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        color: white;
    }

    .btn-secondary-action {
        background: #ffffff;
        color: #2563eb;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: 1.5px solid #2563eb;
        font-size: 0.9rem;
    }

    .btn-secondary-action:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-1px);
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ═══════════════════════════════════════════════════
       LOADING OVERLAY
       ═══════════════════════════════════════════════════ */
    .products-main {
        position: relative;
    }

    .products-loading-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(248, 250, 252, 0.92);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        min-height: 300px;
        border-radius: 12px;
    }

    .products-loading-container.active {
        display: flex;
    }

    .products-loading-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .products-loading-spinner {
        width: 44px;
        height: 44px;
        border: 4px solid #e2e8f0;
        border-top-color: #2563eb;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .products-loading-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
    }

    .products-loading-subtext {
        font-size: 0.825rem;
        color: #64748b;
    }

    /* ═══════════════════════════════════════════════════
       RESPONSIVE
       ═══════════════════════════════════════════════════ */
    @media (max-width: 1024px) {
        .products-layout {
            flex-direction: column;
            gap: 1rem;
        }

        .products-page .container {
            padding: 0 1rem;
        }

        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .product-grid.list-view .product-image {
            width: 160px;
            min-width: 160px;
            height: 150px;
        }

        .search-info-banner {
            flex-direction: column;
            text-align: center;
        }

        .search-info-left {
            align-items: center;
        }
    }

    @media (max-width: 768px) {
        .products-page {
            padding-bottom: 2rem;
        }

        .breadcrumb-bar .container {
            padding: 0 1rem;
        }

        .products-page .container {
            padding: 0 0.75rem;
        }

        .products-toolbar {
            padding: 0.5rem 0.75rem;
        }

        .view-toggle {
            display: none; /* Hide view toggle on mobile */
        }

        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.625rem;
        }

        .product-grid.list-view {
            grid-template-columns: 1fr;
        }

        .product-image {
            height: 170px;
        }

        .product-info {
            padding: 0.625rem 0.75rem;
        }

        .product-title {
            font-size: 0.8rem;
        }

        .product-description {
            display: none;
        }

        .product-price .current-price {
            font-size: 0.95rem;
        }

        .product-price .original-price {
            font-size: 0.7rem;
        }

        .add-to-cart-icon {
            width: 34px;
            height: 34px;
            min-width: 34px;
            font-size: 0.85rem;
        }

        .product-badge {
            font-size: 0.625rem;
            padding: 0.2rem 0.5rem;
        }

        .wishlist-btn {
            width: 30px;
            height: 30px;
        }

        .wishlist-btn i {
            font-size: 0.8rem;
        }

        .page-title-section h1 {
            font-size: 1.25rem;
        }

        .no-results {
            padding: 2.5rem 1.5rem;
        }

        .no-results h3 {
            font-size: 1.25rem;
        }

        .no-results p {
            font-size: 0.875rem;
        }

        .no-results-actions {
            flex-direction: column;
        }

        .btn-primary-action,
        .btn-secondary-action {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .breadcrumb-bar {
            padding: 0.625rem 0;
        }

        .breadcrumb-list {
            font-size: 0.775rem;
        }

        .products-page .container {
            padding: 0 0.5rem;
        }

        .products-toolbar {
            gap: 0.5rem;
        }

        .toolbar-right {
            flex-wrap: wrap;
        }

        .product-grid {
            gap: 0.5rem;
        }

        .product-image {
            height: 140px;
        }

        .product-card {
            border-radius: 8px;
        }

        .product-info {
            padding: 0.5rem 0.625rem;
        }

        .product-title {
            font-size: 0.75rem;
        }

        .product-price .current-price {
            font-size: 0.875rem;
        }

        .add-to-cart-icon {
            width: 30px;
            height: 30px;
            min-width: 30px;
            font-size: 0.8rem;
        }

        .product-badge {
            font-size: 0.6rem;
            padding: 0.15rem 0.4rem;
            top: 6px;
            @if(is_rtl())
            right: 6px;
            @else
            left: 6px;
            @endif
        }

        .wishlist-btn {
            width: 26px;
            height: 26px;
            top: 6px;
            @if(is_rtl())
            left: 6px;
            @else
            right: 6px;
            @endif
        }

        .wishlist-btn i {
            font-size: 0.7rem;
        }
    }

    /* RTL overrides */
    @if(is_rtl())
    .breadcrumb-separator i {
        transform: rotate(180deg);
    }
    @endif
</style>

<!-- Breadcrumb Bar -->
<div class="breadcrumb-bar">
    <div class="container">
        <ul class="breadcrumb-list">
            <li><a href="{{ route('home') }}"><i class="fas fa-home" style="font-size: 0.8rem;"></i></a></li>
            <li><span class="breadcrumb-separator"><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i></span></li>
            @if(request('search'))
            <li><a href="{{ route('products') }}">{{ __t('messages.products') }}</a></li>
            <li><span class="breadcrumb-separator"><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i></span></li>
            <li class="active">{{ is_rtl() ? 'نتائج البحث' : 'Search Results' }}</li>
            @else
            <li class="active">{{ __t('messages.products') }}</li>
            @endif
        </ul>

        {{-- Browse Categories dropdown in breadcrumb bar --}}
        @if(isset($availableFilters['category_tree']))
            <x-browse-categories :categories="$availableFilters['category_tree']" />
        @endif
    </div>
</div>

<div class="products-page">
    <div class="container">
        <div class="products-layout">
            <!-- Filter Sidebar Component (includes mobile toggle button) -->
            <x-filter-sidebar
                :filters="$availableFilters"
                :current="request()->all()"
            />

            <!-- Products Main Content -->
            <div class="products-main" id="productsContent">
                <!-- Loading Indicator -->
                <div class="products-loading-container" id="productsLoading">
                    <div class="products-loading-content">
                        <div class="products-loading-spinner"></div>
                        <div class="products-loading-text">{{ is_rtl() ? 'جاري التحميل...' : 'Loading...' }}</div>
                        <div class="products-loading-subtext">{{ is_rtl() ? 'يرجى الانتظار' : 'Please wait' }}</div>
                    </div>
                </div>

                <!-- Tags Carousel Filter -->
                @if(isset($tags) && count($tags) > 0)
                    <x-tags-carousel :tags="$tags" :activeTag="$activeTag ?? null" />
                @endif

                <!-- Active Tag Info -->
                @if(isset($activeTag) && $activeTag)
                <div class="active-tag-info">
                    <div class="active-tag-badge" style="--tag-color: {{ $activeTag->color }};">
                        @if($activeTag->icon)
                            <i class="{{ $activeTag->icon }}"></i>
                        @else
                            <span class="tag-dot" style="background: {{ $activeTag->color }};"></span>
                        @endif
                        <span>{{ $activeTag->name }}</span>
                    </div>
                    <span class="active-tag-count">{{ $products->total() }} {{ __('messages.products_found') ?? 'products found' }}</span>
                </div>
                @endif

                <!-- Sort / Per-Page Toolbar -->
                @php
                    $currentSort = request('sort', 'created_at');
                    $currentOrder = request('order', 'desc');
                    $currentSortValue = $currentSort . '|' . $currentOrder;
                    $currentPerPage = request('per_page', 12);
                @endphp

                <div class="products-toolbar">
                    <div class="toolbar-left">
                        <span class="toolbar-count"><strong>{{ $products->total() }}</strong> {{ is_rtl() ? 'منتج' : 'products' }}</span>
                        <div class="view-toggle">
                            <button type="button" class="view-toggle-btn active" data-view="grid" title="Grid view" aria-label="Grid view">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="view-toggle-btn" data-view="list" title="List view" aria-label="List view">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                    <div class="toolbar-right">
                        <div class="toolbar-control">
                            <label for="sortSelect" class="toolbar-label">{{ is_rtl() ? 'ترتيب' : 'Sort' }}</label>
                            <select id="sortSelect" class="toolbar-select">
                                <option value="created_at|desc" {{ $currentSortValue === 'created_at|desc' ? 'selected' : '' }}>{{ is_rtl() ? 'الأحدث' : 'Latest' }}</option>
                                <option value="price|asc" {{ $currentSortValue === 'price|asc' ? 'selected' : '' }}>{{ is_rtl() ? 'السعر: الأقل' : 'Price: Low→High' }}</option>
                                <option value="price|desc" {{ $currentSortValue === 'price|desc' ? 'selected' : '' }}>{{ is_rtl() ? 'السعر: الأعلى' : 'Price: High→Low' }}</option>
                                <option value="name_{{ app()->getLocale() }}|asc" {{ $currentSortValue === 'name_' . app()->getLocale() . '|asc' ? 'selected' : '' }}>{{ is_rtl() ? 'الاسم أ-ي' : 'Name A-Z' }}</option>
                                <option value="sales_count|desc" {{ $currentSortValue === 'sales_count|desc' ? 'selected' : '' }}>{{ is_rtl() ? 'الأكثر مبيعاً' : 'Best Selling' }}</option>
                                <option value="views_count|desc" {{ $currentSortValue === 'views_count|desc' ? 'selected' : '' }}>{{ is_rtl() ? 'الأكثر مشاهدة' : 'Most Viewed' }}</option>
                            </select>
                        </div>
                        <div class="toolbar-control">
                            <label for="perPageSelect" class="toolbar-label">{{ is_rtl() ? 'عرض' : 'Show' }}</label>
                            <select id="perPageSelect" class="toolbar-select">
                                <option value="12" {{ (int)$currentPerPage === 12 ? 'selected' : '' }}>12</option>
                                <option value="24" {{ (int)$currentPerPage === 24 ? 'selected' : '' }}>24</option>
                                <option value="36" {{ (int)$currentPerPage === 36 ? 'selected' : '' }}>36</option>
                                <option value="48" {{ (int)$currentPerPage === 48 ? 'selected' : '' }}>48</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if(request('search'))
                <!-- Search Results Info Banner -->
                <div class="search-info-banner">
                    <div class="search-info-left">
                        <div class="search-info-label">
                            @if(is_rtl())
                            <span>{{ 'نتائج البحث عن' }}</span>
                            <i class="fas fa-search"></i>
                            @else
                            <i class="fas fa-search"></i>
                            <span>{{ 'Search results for' }}</span>
                            @endif
                        </div>
                        <div class="search-info-query">"{{ request('search') }}"</div>
                    </div>
                    <div class="search-info-count">
                        <span class="search-count-num">{{ $products->total() }}</span>
                        <span class="search-count-label">{{ is_rtl() ? 'منتج' : 'products' }}</span>
                    </div>
                </div>
                @else
                <div class="page-title-section">
                    <h1>{{ __t('messages.all_products') }}</h1>
                </div>
                @endif

                @if($products->count() > 0)
                <div class="product-grid" id="productGrid">
                    @forelse($products as $product)
                    <a href="{{ route('product.detail', $product) }}" class="product-card-link">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->is_new)
                                <div class="product-badge">{{ __t('messages.new') }}</div>
                                @elseif($product->sale_price && $product->sale_price < $product->price)
                                <div class="product-badge" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 2px 6px rgba(239,68,68,0.3);">{{ __t('messages.sale') }}</div>
                                @elseif($product->is_featured)
                                <div class="product-badge" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 2px 6px rgba(245,158,11,0.3);">{{ __t('messages.hot') }}</div>
                                @endif
                                <div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.preventDefault(); event.stopPropagation();">
                                    <i class="far fa-heart"></i>
                                </div>
                                <img src="{{ $product->main_image }}" alt="{{ $product->name }}" decoding="async" loading="lazy">
                            </div>
                            <div class="product-info">
                                <div class="product-title">{{ $product->name }}</div>
                                <div class="product-description">{{ Str::limit($product->short_description, 60) }}</div>
                                <div class="product-footer">
                                    <div class="product-price">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="original-price">&#8362; {{ number_format($product->price, 0) }}</span>
                                            <span class="current-price">&#8362; {{ number_format($product->sale_price, 0) }}</span>
                                        @else
                                            <span class="current-price">&#8362; {{ number_format($product->price, 0) }}</span>
                                        @endif
                                    </div>
                                    @if($product->stock_status === 'out_of_stock')
                                    <button class="add-to-cart-icon out-of-stock"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            title="{{ __t('messages.request_product') }}"
                                            aria-label="{{ __t('messages.request_product') }}"
                                            onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    <button class="add-to-cart out-of-stock"
                                            data-product-id="{{ $product->id }}"
                                            onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                                        <i class="fas fa-bell"></i>
                                        <span>{{ __t('messages.request_product') }}</span>
                                    </button>
                                    @else
                                    <button class="add-to-cart-icon {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                            data-product-id="{{ $product->id }}"
                                            title="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                            aria-label="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                            onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, this);">
                                        <i class="fas {{ in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                    </button>
                                    <button class="add-to-cart {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                            data-product-id="{{ $product->id }}"
                                            onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, this);">
                                        <i class="fas {{ in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
                                        <span>{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}</span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
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
                                <i class="fas fa-th-large"></i>
                                <span>{{ is_rtl() ? 'عرض جميع المنتجات' : 'View All Products' }}</span>
                            </a>
                            @if(request('search'))
                            <a href="{{ route('home') }}" class="btn-secondary-action">
                                <i class="fas fa-home"></i>
                                <span>{{ is_rtl() ? 'العودة للرئيسية' : 'Back to Home' }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($products) && method_exists($products, 'hasPages') && $products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->links() }}
                </div>
                @endif
            </div><!-- End products-main -->
        </div><!-- End products-layout -->
    </div><!-- End container -->
</div><!-- End products-page -->

<!-- Include unified filter sidebar JavaScript -->
<script src="{{ asset('js/filter-sidebar.js') }}?v={{ filemtime(public_path('js/filter-sidebar.js')) }}"></script>

<!-- View Toggle Script -->
<script>
(function() {
    'use strict';

    // View Toggle (Grid / List)
    const viewBtns = document.querySelectorAll('.view-toggle-btn');
    const productGrid = document.getElementById('productGrid');

    if (viewBtns.length && productGrid) {
        // Restore saved view preference
        const savedView = localStorage.getItem('productViewMode') || 'grid';
        setView(savedView);

        viewBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                setView(view);
                localStorage.setItem('productViewMode', view);
            });
        });
    }

    function setView(view) {
        viewBtns.forEach(function(b) { b.classList.remove('active'); });
        const activeBtn = document.querySelector('.view-toggle-btn[data-view="' + view + '"]');
        if (activeBtn) activeBtn.classList.add('active');

        if (productGrid) {
            if (view === 'list') {
                productGrid.classList.add('list-view');
            } else {
                productGrid.classList.remove('list-view');
            }
        }
    }

    // Page-specific config
    window.FILTER_CONFIG = {
        isRTL: {{ is_rtl() ? 'true' : 'false' }},
        productsRoute: '{{ route("products") }}'
    };
})();
</script>

@endsection
