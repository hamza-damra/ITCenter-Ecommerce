@extends('layouts.app')

@section('title', 'Our Products - IT Center')

@section('content')
<!-- noUiSlider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

    .products-container {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }

    /* Filter Sidebar Styles */
    .filter-sidebar {
        width: 280px;
        min-width: 280px;
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .filter-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .filter-sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .filter-sidebar::-webkit-scrollbar-thumb {
        background: #2762f3;
        border-radius: 10px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .filter-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .clear-filters-btn {
        background: transparent;
        color: #2762f3;
        border: none;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .clear-filters-btn:hover {
        background: rgba(39, 98, 243, 0.1);
    }

    .filter-section {
        margin-bottom: 1.75rem;
    }

    .filter-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-section-title i {
        color: #2762f3;
        font-size: 0.9rem;
    }

    /* Price Range Filter */
    .price-range-labels {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 0 0.25rem;
        gap: 0.75rem;
    }

    .price-label-min,
    .price-label-max {
        font-size: 1rem;
        font-weight: 700;
        color: #2762f3;
        background: linear-gradient(135deg, rgba(39, 98, 243, 0.15) 0%, rgba(39, 98, 243, 0.08) 100%);
        padding: 0.6rem 1.1rem;
        border-radius: 12px;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.1);
        border: 1px solid rgba(39, 98, 243, 0.2);
        min-width: 90px;
        text-align: center;
    }

    .price-label-min:hover,
    .price-label-max:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.2);
        background: linear-gradient(135deg, rgba(39, 98, 243, 0.2) 0%, rgba(39, 98, 243, 0.12) 100%);
    }

    .price-range-slider {
        margin: 1.5rem 0 1.5rem 0;
        padding: 0 0.5rem;
    }

    /* noUiSlider Custom Styles */
    .noUi-target {
        background: #e2e8f0;
        border-radius: 8px;
        border: none;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        height: 8px;
    }

    .noUi-connect {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
    }

    .noUi-handle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #2762f3;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.3), 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: grab;
        transition: all 0.3s ease;
        top: -10px;
        outline: none;
    }

    .noUi-handle:active {
        cursor: grabbing;
    }

    .noUi-handle:before,
    .noUi-handle:after {
        display: none;
    }

    .noUi-handle:hover {
        transform: scale(1.1);
        box-shadow: 0 3px 12px rgba(39, 98, 243, 0.4);
    }

    .noUi-handle:active {
        transform: scale(1.15);
        box-shadow: 0 4px 16px rgba(39, 98, 243, 0.5);
        cursor: grabbing;
    }

    .noUi-handle-lower {
        right: -10px;
    }

    .noUi-handle-upper {
        right: -10px;
    }

    .noUi-tooltip {
        display: none !important;
    }

    .noUi-marker-horizontal.noUi-marker-large {
        height: 10px;
    }

    .noUi-value {
        font-size: 0.75rem;
        color: #64748b;
    }



    /* Category Checkboxes */
    .category-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .category-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.6rem;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .category-checkbox:hover {
        background: rgba(39, 98, 243, 0.05);
    }

    .category-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2762f3;
    }

    .category-checkbox label {
        flex: 1;
        cursor: pointer;
        font-size: 0.9rem;
        color: #334155;
        font-weight: 500;
        margin: 0;
    }

    .category-checkbox input[type="checkbox"]:checked + label {
        color: #2762f3;
        font-weight: 600;
    }

    /* Filter Accordion Styles - Top Level Pattern */
    .filter-accordion {
        border-top: 1px solid #f0f0f0;
        padding-top: 0;
    }

    .filter-accordion:first-of-type {
        border-top: none;
    }

    .filter-accordion-button {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
        border: none;
        padding: 1rem 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 0;
    }

    .filter-accordion-button:hover {
        background: rgba(39, 98, 243, 0.05);
    }

    .filter-accordion-button[aria-expanded="true"] {
        background: rgba(39, 98, 243, 0.08);
    }

    .filter-accordion-button:focus {
        outline: none;
        background: rgba(39, 98, 243, 0.08);
    }

    .filter-accordion-button:focus:not(:focus-visible) {
        outline: none;
    }

    .filter-accordion-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-accordion-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .filter-accordion-header i {
        color: #2762f3;
        font-size: 0.95rem;
    }

    .filter-accordion-icon {
        color: #64748b;
        font-size: 0.9rem;
        transition: transform 0.3s ease;
    }

    .filter-accordion-button[aria-expanded="true"] .filter-accordion-icon {
        transform: rotate(180deg);
    }

    .filter-accordion-content {
        animation: slideDown 0.3s ease-out;
        padding: 1rem 0.75rem 1.5rem 0.75rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }
        to {
            opacity: 1;
            max-height: 2000px;
        }
    }

    .filter-accordion-content[hidden] {
        display: none;
    }

    .filter-accordion-fieldset {
        border: none;
        padding: 0;
        margin: 0;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }

    .filter-checkbox-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .filter-checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.6rem;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .filter-checkbox-item:hover {
        background: rgba(39, 98, 243, 0.05);
    }

    .filter-checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2762f3;
    }

    .filter-checkbox-item label {
        flex: 1;
        cursor: pointer;
        font-size: 0.9rem;
        color: #334155;
        font-weight: 500;
        margin: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-checkbox-item input[type="checkbox"]:checked + label {
        color: #2762f3;
        font-weight: 600;
    }

    .item-count {
        font-size: 0.75rem;
        color: #94a3b8;
        background: #f1f5f9;
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        font-weight: 600;
        min-width: 28px;
        text-align: center;
    }

    .filter-checkbox-item input[type="checkbox"]:checked + label .item-count {
        background: rgba(39, 98, 243, 0.15);
        color: #2762f3;
    }

    .view-more-btn {
        width: 100%;
        padding: 0.6rem;
        background: transparent;
        color: #2762f3;
        border: 1px solid rgba(39, 98, 243, 0.3);
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .view-more-btn:hover {
        background: rgba(39, 98, 243, 0.1);
        border-color: #2762f3;
    }

    .view-more-btn:focus {
        outline: 2px solid #2762f3;
        outline-offset: 2px;
    }

    .view-more-btn i {
        font-size: 0.75rem;
    }

    /* Active Filters Display */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        background: rgba(39, 98, 243, 0.1);
        color: #2762f3;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .filter-tag i {
        cursor: pointer;
        font-size: 0.75rem;
    }

    .filter-tag i:hover {
        color: #1a4dbf;
    }

    .products-content {
        flex: 1;
        min-width: 0;
    }

    /* Mobile Filter Toggle */
    .mobile-filter-toggle {
        display: none;
        width: 100%;
        padding: 1rem;
        background: white;
        border: 2px solid #2762f3;
        color: #2762f3;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .mobile-filter-toggle:hover {
        background: #2762f3;
        color: white;
    }

    .mobile-filter-toggle i {
        margin-right: 0.5rem;
    }

    /* Loading Indicator - Products Area */
    .products-loading-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        min-height: 400px;
        border-radius: 20px;
    }

    .products-loading-container.active {
        display: flex;
    }

    .products-loading-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.5rem;
        padding: 2rem;
    }

    .products-loading-spinner {
        width: 60px;
        height: 60px;
        border: 5px solid #e2e8f0;
        border-top-color: #2762f3;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .products-loading-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: #334155;
        text-align: center;
    }

    .products-loading-subtext {
        font-size: 0.9rem;
        color: #64748b;
        text-align: center;
    }

    .products-content {
        position: relative;
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
        align-items: flex-start;
        text-align: start;
    }

    .search-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
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
        text-align: start;
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
        opacity: 0;
    }

    .product-card:hover::after {
        opacity: 0;
    }

    .product-image {
        width: 100%;
        height: 240px;
        background: transparent;
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
        box-shadow: none;
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
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-top: auto;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }

    .product-price {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
        text-align: start;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
        flex: 1;
    }

    .product-price .original-price {
        text-decoration: line-through;
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 500;
        order: -1;
    }

    .product-price .current-price {
        color: #2762f3;
        font-weight: 700;
        font-size: 1.35rem;
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

    /* Icon-Only Add to Cart Button */
    .add-to-cart-icon {
        background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
        color: #ffffff;
        border: none;
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.25);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        z-index: 10;
    }

    .add-to-cart-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1a4dbf 0%, #0f3a8f 100%);
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
        box-shadow: 0 6px 20px rgba(39, 98, 243, 0.4), 0 2px 8px rgba(39, 98, 243, 0.2);
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

    .product-card a {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .wishlist-btn,
    .add-to-cart,
    .add-to-cart-icon {
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
            flex-direction: row;
            gap: 0.75rem;
        }

        .add-to-cart {
            width: 100%;
            min-width: unset;
        }

        .add-to-cart-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 1rem;
        }

        .product-price {
            flex: 1;
        }

        .product-price .current-price {
            font-size: 1.2rem;
        }

        .product-price .original-price {
            font-size: 0.8rem;
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

        .add-to-cart-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
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
        .products-container {
            flex-direction: column;
        }

        .filter-sidebar {
            display: none;
            width: 100%;
            max-width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
            max-height: 100vh;
            border-radius: 0;
            padding: 2rem;
        }

        .filter-sidebar.active {
            display: block;
        }

        .mobile-filter-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-results-info-box {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }

        .search-query-display {
            align-items: center;
            text-align: center;
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
            text-align: center;
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
        <!-- Mobile Filter Toggle Button -->
        <button class="mobile-filter-toggle" onclick="toggleMobileFilters()">
            <i class="fas fa-filter"></i>
            {{ is_rtl() ? 'تصفية المنتجات' : 'Filter Products' }}
        </button>

        <div class="products-container">
            <!-- Filter Sidebar -->
            <aside class="filter-sidebar" id="filterSidebar">
                <div class="filter-header">
                    <h3>{{ is_rtl() ? 'تصفية' : 'Filters' }}</h3>
                    <button class="clear-filters-btn" id="clearFiltersBtn" type="button">
                        {{ is_rtl() ? 'مسح الكل' : 'Clear All' }}
                    </button>
                </div>

                <form id="filterForm">
                    <!-- Preserve search query if exists -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    
                    <!-- Note: Form submission is handled by JavaScript, not by form action -->

                    <!-- Price Range Filter - Always Visible -->
                    <div class="filter-section">
                        <div class="filter-section-title">
                            <i class="fas fa-dollar-sign"></i>
                            {{ is_rtl() ? 'نطاق السعر' : 'Price Range' }}
                        </div>

                        <!-- Live Price Labels Above Slider -->
                        <div class="price-range-labels">
                            <span class="price-label-min" id="minPriceLabel">₪ {{ number_format(request('min_price', $priceRange['min']), 0) }}</span>
                            <span class="price-label-max" id="maxPriceLabel">₪ {{ number_format(request('max_price', $priceRange['max']), 0) }}</span>
                        </div>

                        <!-- Dual-Handle Range Slider -->
                        <div class="price-range-slider">
                            <div id="priceSlider"></div>
                        </div>

                        <!-- Hidden Input Fields for Form Submission -->
                        <input type="hidden"
                               name="min_price"
                               id="minPrice"
                               value="{{ request('min_price', $priceRange['min']) }}">
                        <input type="hidden"
                               name="max_price"
                               id="maxPrice"
                               value="{{ request('max_price', $priceRange['max']) }}">
                    </div>

                    <!-- Brands Accordion - Top Level -->
                    <div class="filter-accordion" id="brandsAccordion">
                        <button type="button"
                                class="filter-accordion-button"
                                id="brandsAccordionToggle"
                                aria-expanded="false"
                                aria-controls="brandsAccordionContent"
                                data-accordion="brands"
                                onclick="toggleAccordion('brands')">
                            <span class="filter-accordion-header">
                                <i class="fas fa-tags"></i>
                                <span class="filter-accordion-title">{{ is_rtl() ? 'العلامات التجارية' : 'Brands' }}</span>
                            </span>
                            <i class="fas fa-chevron-down filter-accordion-icon" aria-hidden="true"></i>
                        </button>

                        <div class="filter-accordion-content" id="brandsAccordionContent" hidden>
                            <fieldset class="filter-accordion-fieldset">
                                <legend class="sr-only">{{ is_rtl() ? 'تصفية حسب العلامة التجارية' : 'Filter by brand' }}</legend>

                                <div class="filter-checkbox-list" id="brandList">
                                    @php
                                        // Parse comma-separated brand parameter
                                        $selectedBrands = [];
                                        if (request('brand')) {
                                            $selectedBrands = explode(',', request('brand'));
                                        }
                                    @endphp

                                    @foreach($brands as $index => $brand)
                                    @php
                                        $isChecked = in_array($brand->slug, $selectedBrands);
                                    @endphp
                                    <div class="filter-checkbox-item brand-item" data-brand-index="{{ $index }}" style="display: none;">
                                        <input type="checkbox"
                                               name="brand"
                                               value="{{ $brand->slug }}"
                                               id="brand-{{ $brand->slug }}"
                                               data-checkbox-group="brands"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label for="brand-{{ $brand->slug }}">
                                            {{ $brand->name }}
                                            <span class="item-count">{{ $brand->products_count }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>

                                @if($brands->count() > 10)
                                <button type="button"
                                        class="view-more-btn"
                                        id="brandViewMoreBtn"
                                        onclick="loadMoreBrands()"
                                        style="display: none;"
                                        aria-label="{{ is_rtl() ? 'عرض المزيد من العلامات التجارية' : 'View more brands' }}">
                                    <span id="brandViewMoreText">{{ is_rtl() ? 'عرض المزيد' : 'View more' }}</span>
                                    <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                </button>
                                @endif
                            </fieldset>
                        </div>
                    </div>

                    <!-- Categories Accordion - Top Level -->
                    <div class="filter-accordion" id="categoriesAccordion">
                        <button type="button"
                                class="filter-accordion-button"
                                id="categoriesAccordionToggle"
                                aria-expanded="false"
                                aria-controls="categoriesAccordionContent"
                                data-accordion="categories"
                                onclick="toggleAccordion('categories')">
                            <span class="filter-accordion-header">
                                <i class="fas fa-th-large"></i>
                                <span class="filter-accordion-title">{{ is_rtl() ? 'الفئات' : 'Categories' }}</span>
                            </span>
                            <i class="fas fa-chevron-down filter-accordion-icon" aria-hidden="true"></i>
                        </button>

                        <div class="filter-accordion-content" id="categoriesAccordionContent" hidden>
                            <fieldset class="filter-accordion-fieldset">
                                <legend class="sr-only">{{ is_rtl() ? 'تصفية حسب الفئة' : 'Filter by category' }}</legend>

                                <div class="filter-checkbox-list">
                                    @foreach($categories as $category)
                                    @php
                                        // Check if this category is selected (support both 'category' and 'categories[]')
                                        $selectedCategories = (array)request('categories', []);
                                        if (request('category') && !in_array(request('category'), $selectedCategories)) {
                                            $selectedCategories[] = request('category');
                                        }
                                        $isChecked = in_array($category->slug, $selectedCategories);
                                    @endphp
                                    <div class="filter-checkbox-item">
                                        <input type="checkbox"
                                               name="categories[]"
                                               value="{{ $category->slug }}"
                                               id="category-{{ $category->slug }}"
                                               data-checkbox-group="categories"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label for="category-{{ $category->slug }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </aside>

            <!-- Products Content -->
            <div class="products-content" id="productsContent">
                <!-- Loading Indicator -->
                <div class="products-loading-container" id="productsLoading">
                    <div class="products-loading-content">
                        <div class="products-loading-spinner"></div>
                        <div class="products-loading-text">{{ is_rtl() ? 'جاري التحميل...' : 'Loading...' }}</div>
                        <div class="products-loading-subtext">{{ is_rtl() ? 'يرجى الانتظار' : 'Please wait' }}</div>
                    </div>
                </div>
                
        @if(request('search'))
        <!-- Search Results Info Box -->
        <div class="search-results-info-box">
            <div class="search-query-display">
                <div class="search-label">
                    @if(is_rtl())
                    <span>{{ 'نتائج البحث عن' }}</span>
                    <i class="fas fa-search"></i>
                    @else
                    <i class="fas fa-search"></i>
                    <span>{{ 'Search results for' }}</span>
                    @endif
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
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                </div>
                <div class="product-info">
                    <div class="product-title">{{ $product->name }}</div>
                    <div class="product-description">{{ Str::limit($product->short_description, 60) }}</div>
                    <div class="product-footer">
                        <div class="product-price">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="original-price">₪ {{ number_format($product->price, 0) }}</span>
                                <span class="current-price">₪ {{ number_format($product->sale_price, 0) }}</span>
                            @else
                                <span class="current-price">₪ {{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                        @if($product->stock_status === 'out_of_stock')
                        <button class="add-to-cart-icon out-of-stock"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                title="{{ __t('messages.request_product') }}"
                                aria-label="{{ __t('messages.request_product') }}"
                                onclick="event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                            <i class="fas fa-bell"></i>
                        </button>
                        @else
                        <button class="add-to-cart-icon {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                data-product-id="{{ $product->id }}"
                                title="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                aria-label="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                onclick="event.stopPropagation(); addToCart({{ $product->id }}, this);">
                            <i class="fas {{ in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
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
            </div><!-- End products-content -->
        </div><!-- End products-container -->
    </div><!-- End container -->
</div><!-- End products-section -->

<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
(function() {
    'use strict';
    console.log('🚀 Products Filter System Initialized');
    
    // Filter state
    let priceSlider = null;
    let debounceTimer = null;
    let isFiltering = false;
    
    const FILTER_CONFIG = {
        minPrice: {{ $priceRange['min'] }},
        maxPrice: {{ $priceRange['max'] }},
        isRTL: {{ is_rtl() ? 'true' : 'false' }},
        productsRoute: '{{ route("products") }}'
    };

    // Loading indicator functions
    function showLoading() {
        const loading = document.getElementById('productsLoading');
        if (loading) loading.classList.add('active');
        isFiltering = true;
    }

    function hideLoading() {
        const loading = document.getElementById('productsLoading');
        if (loading) loading.classList.remove('active');
        isFiltering = false;
    }

    // Mobile filter toggle
    window.toggleMobileFilters = function() {
        const sidebar = document.getElementById('filterSidebar');
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
    };

    // Clear all filters
    function clearAllFilters() {
        console.log('🗑️ Clearing all filters');
        
        // Uncheck all category checkboxes
        document.querySelectorAll('input[name="categories[]"]').forEach(checkbox => {
            checkbox.checked = false;
            const label = checkbox.parentElement;
            if (label) label.style.backgroundColor = '';
        });

        // Reset price slider
        if (priceSlider) {
            priceSlider.set([FILTER_CONFIG.minPrice, FILTER_CONFIG.maxPrice]);
        }

        // Redirect to products page (preserving search if exists)
        const form = document.getElementById('filterForm');
        const searchInput = form ? form.querySelector('input[name="search"]') : null;
        const searchValue = searchInput ? searchInput.value : '';

        const url = searchValue 
            ? FILTER_CONFIG.productsRoute + '?search=' + encodeURIComponent(searchValue)
            : FILTER_CONFIG.productsRoute;
        
        window.location.href = url;
    }

    // Apply filters function
    function applyFilters() {
        if (isFiltering) {
            console.log('⏳ Already filtering, skipping...');
            return;
        }

        console.log('🔍 Applying filters...');
        showLoading();

        const form = document.getElementById('filterForm');
        if (!form) {
            console.error('❌ Filter form not found');
            hideLoading();
            return;
        }

        // Build URL parameters
        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value && String(value).trim() !== '') {
                params.append(key, value);
            }
        }

        const url = FILTER_CONFIG.productsRoute + '?' + params.toString();
        console.log('📍 Filter URL:', url);

        // Update browser URL
        history.pushState({}, '', url);

        // Fetch filtered products
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('productsContent');
            const currentContent = document.getElementById('productsContent');

            if (newContent && currentContent) {
                // Get the loading indicator before replacing content
                const loadingIndicator = document.getElementById('productsLoading');
                
                // Update content
                currentContent.innerHTML = newContent.innerHTML;
                
                // Re-add the loading indicator at the beginning
                if (loadingIndicator) {
                    currentContent.insertBefore(loadingIndicator, currentContent.firstChild);
                }
                
                currentContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
                console.log('✅ Products updated');
            } else {
                console.error('❌ Content elements not found');
            }

            hideLoading();
        })
        .catch(error => {
            console.error('❌ Filter error:', error);
            hideLoading();
            window.location.href = url;
        });
    }

    // Debounced filter
    function debouncedApplyFilters(delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, delay || 500);
    }

    // Initialize everything
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ Initializing filter system...');

        const form = document.getElementById('filterForm');
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        const minPriceLabel = document.getElementById('minPriceLabel');
        const maxPriceLabel = document.getElementById('maxPriceLabel');
        const sliderElement = document.getElementById('priceSlider');

        // Prevent form submission (we handle it with AJAX)
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('⛔ Form submission prevented - using AJAX instead');
                applyFilters();
                return false;
            });
            
            // Also prevent on form reset
            form.addEventListener('reset', function(e) {
                e.preventDefault();
                console.log('⛔ Form reset prevented');
                return false;
            });
        } else {
            console.error('❌ Filter form not found!');
        }

        const currentMin = parseFloat(minPriceInput.value) || FILTER_CONFIG.minPrice;
        const currentMax = parseFloat(maxPriceInput.value) || FILTER_CONFIG.maxPrice;

        // Initialize noUiSlider with error handling
        if (sliderElement) {
            try {
                console.log('Initializing slider...');

                // Destroy existing slider if it exists
                if (sliderElement.noUiSlider) {
                    console.log('Destroying existing slider...');
                    sliderElement.noUiSlider.destroy();
                }

                priceSlider = noUiSlider.create(sliderElement, {
                    start: [currentMin, currentMax],
                    connect: true,
                    direction: FILTER_CONFIG.isRTL ? 'rtl' : 'ltr',
                    range: {
                        'min': FILTER_CONFIG.minPrice,
                        'max': FILTER_CONFIG.maxPrice
                    },
                    step: 1,
                    format: {
                        to: function(value) {
                            return Math.round(value);
                        },
                        from: function(value) {
                            return Number(value);
                        }
                    }
                });

                console.log('Slider initialized successfully');

                // Update hidden inputs and labels when slider changes
                priceSlider.on('update', function(values, handle) {
                    const value = Math.round(values[handle]);
                    const formattedValue = '₪ ' + value.toLocaleString();

                    if (handle === 0) {
                        minPriceInput.value = value;
                        minPriceLabel.textContent = formattedValue;
                    } else {
                        maxPriceInput.value = value;
                        maxPriceLabel.textContent = formattedValue;
                    }
                });

                // Apply filters when slider is released (change event)
                priceSlider.on('change', function(values, handle) {
                    console.log('Slider changed:', values);
                    debouncedApplyFilters(500);
                });
            } catch (error) {
                console.error('Error initializing price slider:', error);
                // Continue with checkbox initialization even if slider fails
            }
        }

        // Setup category checkboxes
        const checkboxes = document.querySelectorAll('input[name="categories[]"]');
        console.log('📦 Found ' + checkboxes.length + ' category checkboxes');

        checkboxes.forEach(function(checkbox) {
            // Initial visual feedback
            if (checkbox.checked) {
                const label = checkbox.parentElement;
                if (label) label.style.backgroundColor = 'rgba(39, 98, 243, 0.08)';
            }
            
            // Add change listener  
            checkbox.addEventListener('change', function(e) {
                // Don't prevent default - we want the checkbox to work!
                console.log('✔️ Checkbox changed:', e.target.value, e.target.checked);
                
                // Visual feedback
                const label = e.target.parentElement;
                if (label) {
                    label.style.backgroundColor = e.target.checked 
                        ? 'rgba(39, 98, 243, 0.08)' 
                        : '';
                }
                
                // Apply filters via AJAX
                debouncedApplyFilters(300);
            });
        });
        
        // Setup clear filters button
        const clearBtn = document.getElementById('clearFiltersBtn');
        if (clearBtn) {
            clearBtn.addEventListener('click', clearAllFilters);
        }
        
        console.log('✅ Filter system ready');
    });

    // ============================================
    // Accordion System - Mutual Exclusivity
    // ============================================

    let currentOpenAccordion = null;
    let brandPaginationState = {
        currentlyShowing: 0,
        itemsPerPage: 10
    };

    /**
     * Toggle accordion with mutual exclusivity
     * Only one accordion can be open at a time
     */
    window.toggleAccordion = function(accordionName) {
        const button = document.getElementById(accordionName + 'AccordionToggle');
        const content = document.getElementById(accordionName + 'AccordionContent');

        if (!button || !content) {
            console.error('Accordion elements not found for:', accordionName);
            return;
        }

        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        // If clicking the currently open accordion, just close it
        if (isExpanded) {
            closeAccordion(accordionName);
            currentOpenAccordion = null;
            saveAccordionState(null);
            return;
        }

        // Close any currently open accordion
        if (currentOpenAccordion && currentOpenAccordion !== accordionName) {
            closeAccordion(currentOpenAccordion);
        }

        // Open the clicked accordion
        openAccordion(accordionName);
        currentOpenAccordion = accordionName;
        saveAccordionState(accordionName);
    };

    function openAccordion(name) {
        const button = document.getElementById(name + 'AccordionToggle');
        const content = document.getElementById(name + 'AccordionContent');

        button.setAttribute('aria-expanded', 'true');
        content.hidden = false;

        // Special handling for brands: show first batch
        if (name === 'brands') {
            showInitialBrands();
        }

        console.log('📂 Opened accordion:', name);
    }

    function closeAccordion(name) {
        const button = document.getElementById(name + 'AccordionToggle');
        const content = document.getElementById(name + 'AccordionContent');

        if (button && content) {
            button.setAttribute('aria-expanded', 'false');
            content.hidden = true;
            console.log('📁 Closed accordion:', name);
        }
    }

    /**
     * Load more brands - Progressive disclosure (10 at a time)
     */
    window.loadMoreBrands = function() {
        const brandItems = document.querySelectorAll('.brand-item');
        const viewMoreBtn = document.getElementById('brandViewMoreBtn');
        const viewMoreText = document.getElementById('brandViewMoreText');
        const isRTL = {{ is_rtl() ? 'true' : 'false' }};

        if (!viewMoreBtn) return;

        const totalBrands = brandItems.length;
        const currentlyShowing = brandPaginationState.currentlyShowing;
        const nextShow = Math.min(currentlyShowing + 10, totalBrands);

        // Show all brands up to nextShow
        let actuallyShown = 0;
        brandItems.forEach((item) => {
            const brandIndex = parseInt(item.getAttribute('data-brand-index'));
            if (brandIndex < nextShow) {
                if (item.style.display !== 'flex') {
                    actuallyShown++;
                }
                item.style.display = 'flex';
            }
        });

        brandPaginationState.currentlyShowing = nextShow;

        console.log('📄 View more clicked. Showing', nextShow, 'of', totalBrands, 'brands. Newly revealed:', actuallyShown);

        // Check if all items are now shown
        if (nextShow >= totalBrands) {
            // Change button to "View less"
            viewMoreText.textContent = isRTL ? 'عرض أقل' : 'View less';
            viewMoreBtn.onclick = collapseAllBrands;
            console.log('📄 All brands shown. Button changed to "View less"');
        }

        // Save state
        saveBrandPaginationState(nextShow);
    };

    /**
     * Collapse brands back to first 10
     */
    function collapseAllBrands() {
        const brandItems = document.querySelectorAll('.brand-item');
        const viewMoreBtn = document.getElementById('brandViewMoreBtn');
        const viewMoreText = document.getElementById('brandViewMoreText');
        const isRTL = {{ is_rtl() ? 'true' : 'false' }};

        // Show only first 10 brands
        brandItems.forEach((item) => {
            const brandIndex = parseInt(item.getAttribute('data-brand-index'));
            if (brandIndex < 10) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        brandPaginationState.currentlyShowing = 10;

        // Reset button text to "View more"
        viewMoreText.textContent = isRTL ? 'عرض المزيد' : 'View more';
        viewMoreBtn.onclick = loadMoreBrands;

        // Save state
        saveBrandPaginationState(10);

        console.log('📄 Collapsed brands back to first 10');
    }

    /**
     * Show initial brands (first 10, or restore saved state)
     */
    function showInitialBrands() {
        const brandItems = document.querySelectorAll('.brand-item');
        const viewMoreBtn = document.getElementById('brandViewMoreBtn');
        const totalBrands = brandItems.length;

        // Check for saved pagination state
        const savedShowing = getSavedBrandPaginationState();
        const initialShow = (savedShowing > 0 && savedShowing <= totalBrands) ? savedShowing : 10;

        // Show brands up to initialShow
        brandItems.forEach((item) => {
            const brandIndex = parseInt(item.getAttribute('data-brand-index'));
            if (brandIndex < initialShow) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        brandPaginationState.currentlyShowing = initialShow;

        // Show "View more" button if there are more than 10 brands
        if (viewMoreBtn && totalBrands > 10) {
            viewMoreBtn.style.display = 'flex';

            // Set correct button state and text
            const isRTL = {{ is_rtl() ? 'true' : 'false' }};
            const viewMoreText = document.getElementById('brandViewMoreText');

            if (initialShow >= totalBrands) {
                viewMoreText.textContent = isRTL ? 'عرض أقل' : 'View less';
                viewMoreBtn.onclick = collapseAllBrands;
            } else {
                viewMoreText.textContent = isRTL ? 'عرض المزيد' : 'View more';
                viewMoreBtn.onclick = loadMoreBrands;
            }
        }

        console.log('📄 Showing initial brands:', initialShow, 'of', totalBrands);
    }

    /**
     * State Persistence using sessionStorage
     */
    function saveAccordionState(accordionName) {
        sessionStorage.setItem('openAccordion', accordionName || '');
    }

    function getSavedAccordionState() {
        return sessionStorage.getItem('openAccordion') || null;
    }

    function saveBrandPaginationState(count) {
        sessionStorage.setItem('brandPaginationCount', count);
    }

    function getSavedBrandPaginationState() {
        return parseInt(sessionStorage.getItem('brandPaginationCount')) || 0;
    }

    /**
     * Setup accordion system and checkboxes
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Restore saved accordion state
        const savedAccordion = getSavedAccordionState();
        if (savedAccordion) {
            // Check if any items are selected in this accordion
            const hasSelection = document.querySelectorAll(`input[data-checkbox-group="${savedAccordion}"]:checked`).length > 0;
            if (hasSelection) {
                toggleAccordion(savedAccordion);
            }
        }

        // Auto-expand accordion if items are selected
        if (!savedAccordion) {
            // Check brands
            const selectedBrands = document.querySelectorAll('input[data-checkbox-group="brands"]:checked');
            if (selectedBrands.length > 0) {
                toggleAccordion('brands');
            } else {
                // Check categories
                const selectedCategories = document.querySelectorAll('input[data-checkbox-group="categories"]:checked');
                if (selectedCategories.length > 0) {
                    toggleAccordion('categories');
                }
            }
        }

        // Setup brand checkboxes
        const brandCheckboxes = document.querySelectorAll('input[data-checkbox-group="brands"]');
        console.log('🏷️ Found ' + brandCheckboxes.length + ' brand checkboxes');

        brandCheckboxes.forEach(function(checkbox) {
            // Initial visual feedback
            if (checkbox.checked) {
                const item = checkbox.closest('.filter-checkbox-item');
                if (item) item.style.backgroundColor = 'rgba(39, 98, 243, 0.08)';
            }

            // Add change listener
            checkbox.addEventListener('change', function(e) {
                console.log('✔️ Brand checkbox changed:', e.target.value, e.target.checked);

                // Visual feedback
                const item = e.target.closest('.filter-checkbox-item');
                if (item) {
                    item.style.backgroundColor = e.target.checked
                        ? 'rgba(39, 98, 243, 0.08)'
                        : '';
                }

                // Update URL format (comma-separated)
                updateBrandFilters();

                // Apply filters via AJAX
                debouncedApplyFilters(300);
            });
        });

        // Setup keyboard support for all accordion buttons
        document.querySelectorAll('.filter-accordion-button').forEach(function(button) {
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const accordionName = button.getAttribute('data-accordion');
                    toggleAccordion(accordionName);
                }
            });
        });

        console.log('✅ Accordion system initialized');
    });

    /**
     * Update brand filters with comma-separated format
     */
    function updateBrandFilters() {
        const checkedBrands = Array.from(document.querySelectorAll('input[data-checkbox-group="brands"]:checked'))
            .map(cb => cb.value);

        // Update all brand checkboxes with the comma-separated value
        const brandCheckboxes = document.querySelectorAll('input[data-checkbox-group="brands"]');
        brandCheckboxes.forEach(cb => {
            // Remove old name attribute
            cb.removeAttribute('name');
        });

        // Add hidden input with comma-separated values
        const form = document.getElementById('filterForm');
        let brandInput = form.querySelector('input[name="brand"][type="hidden"]');

        if (checkedBrands.length > 0) {
            if (!brandInput) {
                brandInput = document.createElement('input');
                brandInput.type = 'hidden';
                brandInput.name = 'brand';
                form.appendChild(brandInput);
            }
            brandInput.value = checkedBrands.join(',');
        } else {
            if (brandInput) {
                brandInput.remove();
            }
        }

        console.log('🏷️ Brand filter updated:', checkedBrands.join(','));
    }
})();
</script>

@endsection
