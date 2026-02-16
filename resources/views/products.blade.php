@extends('layouts.app')

@section('title', 'Our Products - IT Center')

@section('content')
<!-- Import shared components CSS -->
<link rel="stylesheet" href="{{ asset('css/components.css') }}">

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
        padding: var(--space-12) 0;
        background: var(--bg-primary);
        min-height: 100vh;
    }

    .products-container {
        display: flex;
        gap: var(--space-8);
        align-items: flex-start;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 var(--space-8);
    }

    /* Filter Sidebar Styles */
    .filter-sidebar {
        width: 280px;
        min-width: 280px;
        background: var(--bg-card);
        border-radius: var(--radius-xl);
        padding: var(--space-6);
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        overflow-x: clip; /* Prevent overflow-y:auto from clipping slider handles */
        transition: all var(--transition-bounce);
    }

    .filter-sidebar:hover {
        box-shadow: var(--shadow-lg);
    }

    .filter-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .filter-sidebar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: var(--radius-md);
    }

    .filter-sidebar::-webkit-scrollbar-thumb {
        background: var(--primary-blue);
        border-radius: var(--radius-md);
    }

    .filter-sidebar::-webkit-scrollbar-thumb:hover {
        background: var(--primary-light-blue);
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-6);
        padding-bottom: var(--space-4);
        border-bottom: 2px solid #e2e8f0;
    }

    .filter-header h3 {
        font-size: var(--text-xl);
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .clear-filters-btn {
        background: transparent;
        color: var(--primary-blue);
        border: 1px solid var(--primary-blue);
        font-size: var(--text-xs);
        font-weight: 600;
        cursor: pointer;
        padding: var(--space-2) var(--space-3);
        border-radius: var(--radius-md);
        transition: all var(--transition-bounce);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .clear-filters-btn:hover {
        background: var(--primary-blue);
        color: var(--text-white);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
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
        outline: none;
    }

    .filter-section-title:focus {
        outline: none;
    }

    .filter-section-title i {
        color: #2762f3;
        font-size: 0.9rem;
    }

    /* Price Range Filter */
    .price-input-container {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding: 0 0.25rem;
    }

    .price-input-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .price-input-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .price-input-group {
        position: relative;
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }

    .price-input-group:focus-within {
        border-color: #2762f3;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(39, 98, 243, 0.1);
    }

    .price-currency {
        font-size: 0.9rem;
        font-weight: 600;
        color: #64748b;
        margin-right: 0.5rem;
        flex-shrink: 0;
    }

    .price-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        outline: none;
        padding: 0;
        -moz-appearance: textfield;
    }

    .price-input::-webkit-outer-spin-button,
    .price-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .price-input-separator {
        font-size: 1.25rem;
        font-weight: 600;
        color: #94a3b8;
        padding-bottom: 0.5rem;
        flex-shrink: 0;
    }

    /* RTL Support for Price Input */
    @if(is_rtl())
    .price-input-container {
        flex-direction: row-reverse;
    }

    .price-currency {
        margin-right: 0;
        margin-left: 0.5rem;
    }
    @endif

    /* ========== Custom Dual-Range Price Slider ========== */
    .price-range-slider {
        margin: 1rem 0 0.5rem;
        padding: 0.75rem 0;
    }

    .dual-range-wrapper {
        position: relative;
        height: 8px;
        margin: 18px 0;
        /* Always LTR so left=min, right=max regardless of page direction */
        direction: ltr !important;
    }

    /* The gray background track */
    .dual-range-track {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #e2e8f0;
        border-radius: 8px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.06);
    }

    /* The blue highlighted range between the two thumbs */
    .dual-range-highlight {
        position: absolute;
        top: 0;
        height: 100%;
        background: linear-gradient(90deg, #2762f3 0%, #3b82f6 100%);
        border-radius: 8px;
        z-index: 1;
        pointer-events: none;
    }

    /* Both native range inputs stacked on top of each other */
    .dual-range-wrapper input[type="range"] {
        position: absolute;
        top: -10px;
        left: 0;
        width: 100%;
        height: 28px;
        margin: 0;
        padding: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: transparent;
        pointer-events: none; /* Disable track clicks, only thumbs respond */
        z-index: 3;
        outline: none;
        /* Force LTR so the native range always works left-to-right */
        direction: ltr !important;
    }

    /* Webkit (Chrome, Safari, Edge) thumb */
    .dual-range-wrapper input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #2762f3;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.3);
        cursor: grab;
        pointer-events: auto; /* Re-enable pointer events on thumbs only */
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        position: relative;
        z-index: 4;
    }

    .dual-range-wrapper input[type="range"]::-webkit-slider-thumb:hover {
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.4), 0 0 0 4px rgba(39, 98, 243, 0.1);
        border-color: #1a4dbf;
    }

    .dual-range-wrapper input[type="range"]::-webkit-slider-thumb:active {
        cursor: grabbing;
        box-shadow: 0 2px 6px rgba(39, 98, 243, 0.5), 0 0 0 6px rgba(39, 98, 243, 0.15);
    }

    /* Firefox thumb */
    .dual-range-wrapper input[type="range"]::-moz-range-thumb {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #2762f3;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.3);
        cursor: grab;
        pointer-events: auto;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .dual-range-wrapper input[type="range"]::-moz-range-thumb:hover {
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.4), 0 0 0 4px rgba(39, 98, 243, 0.1);
        border-color: #1a4dbf;
    }

    .dual-range-wrapper input[type="range"]::-moz-range-thumb:active {
        cursor: grabbing;
        box-shadow: 0 2px 6px rgba(39, 98, 243, 0.5), 0 0 0 6px rgba(39, 98, 243, 0.15);
    }

    /* Hide native track in Firefox */
    .dual-range-wrapper input[type="range"]::-moz-range-track {
        background: transparent;
        border: none;
        height: 0;
    }

    /* Webkit track - full height so thumb centers on input center */
    .dual-range-wrapper input[type="range"]::-webkit-slider-runnable-track {
        width: 100%;
        height: 100%;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    /* Touch-friendly handles on mobile */
    @media (max-width: 768px) {
        .dual-range-wrapper input[type="range"]::-webkit-slider-thumb {
            width: 32px;
            height: 32px;
        }
        .dual-range-wrapper input[type="range"]::-moz-range-thumb {
            width: 26px;
            height: 26px;
        }
        .dual-range-wrapper input[type="range"] {
            top: -12px;
            height: 32px;
        }
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

    /* Brand Filter Styles - Collapsible Disclosure Pattern */
    /* Brand Accordion - Matches Categories Style */
    .filter-accordion {
        margin-bottom: 0;
    }

    .filter-accordion-button {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 0.75rem;
        font-family: inherit;
    }

    .filter-accordion-button:hover {
        background: rgba(39, 98, 243, 0.03);
        border-color: #cbd5e1;
    }

    .filter-accordion-button[aria-expanded="true"] {
        background: rgba(39, 98, 243, 0.05);
        border-color: #2762f3;
    }

    .filter-accordion-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(39, 98, 243, 0.1);
    }

    .filter-accordion-button:hover{
        color: #2762f3;
    }
    .filter-accordion-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-accordion-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .filter-accordion-button[aria-expanded="true"] .filter-accordion-title {
        color: #2762f3;
    }

    .filter-accordion-header fa-tags {
        color: #000000ff;
        font-size: 1rem;
    }



    .filter-accordion-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }

    .filter-accordion-button[aria-expanded="true"] .filter-accordion-icon {
        background: #2762f3;
        color: white;
        transform: rotate(45deg);
    }

    .filter-accordion-content {
        border: none;
        padding: 0 0.5rem 1rem 0.5rem;
        margin: 0;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-accordion-content[hidden] {
        display: none;
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

    .brand-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .brand-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.6rem;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .brand-checkbox:hover {
        background: rgba(39, 98, 243, 0.05);
    }

    /* Disabled brand styles (no products) */
    .brand-checkbox.brand-disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .brand-checkbox.brand-disabled:hover {
        background: transparent;
    }

    .brand-checkbox.brand-disabled label {
        cursor: not-allowed;
        color: #94a3b8;
    }

    .brand-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2762f3;
    }

    .brand-checkbox input[type="checkbox"]:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }

    .brand-checkbox label {
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

    .brand-checkbox input[type="checkbox"]:checked + label {
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

    .item-count.count-zero {
        color: #cbd5e1;
        background: #f8fafc;
    }
        min-width: 28px;
        text-align: center;
    }

    .brand-checkbox input[type="checkbox"]:checked + label .item-count {
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
        margin-top: 0.5rem;
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
        transition: transform 0.3s ease;
    }

    .view-more-btn.expanded i {
        transform: rotate(180deg);
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

    /* Active Tag Info Box */
    .active-tag-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, rgba(var(--tag-color-rgb, 59, 130, 246), 0.1) 0%, rgba(var(--tag-color-rgb, 37, 99, 235), 0.05) 100%);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(var(--tag-color-rgb, 59, 130, 246), 0.2);
        animation: fadeInUp 0.4s ease-out;
    }
    
    .active-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--tag-color, #3b82f6);
        color: white;
        border-radius: 25px;
        font-size: 0.95rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .active-tag-badge .tag-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: white;
    }
    
    .active-tag-count {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 500;
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

    /* Product Card Loading Animation - Lightweight & Smooth */
    .product-grid.loading .product-card {
        opacity: 0 !important;
        transform: translateY(15px) !important;
        animation: cardSlideIn 0.4s ease-out forwards !important;
        will-change: opacity, transform;
    }

    .product-grid.loading .product-card:nth-child(1) { animation-delay: 0.05s !important; }
    .product-grid.loading .product-card:nth-child(2) { animation-delay: 0.1s !important; }
    .product-grid.loading .product-card:nth-child(3) { animation-delay: 0.15s !important; }
    .product-grid.loading .product-card:nth-child(4) { animation-delay: 0.2s !important; }
    .product-grid.loading .product-card:nth-child(5) { animation-delay: 0.25s !important; }
    .product-grid.loading .product-card:nth-child(6) { animation-delay: 0.3s !important; }
    .product-grid.loading .product-card:nth-child(7) { animation-delay: 0.35s !important; }
    .product-grid.loading .product-card:nth-child(8) { animation-delay: 0.4s !important; }
    .product-grid.loading .product-card:nth-child(9) { animation-delay: 0.45s !important; }
    .product-grid.loading .product-card:nth-child(10) { animation-delay: 0.5s !important; }
    .product-grid.loading .product-card:nth-child(11) { animation-delay: 0.55s !important; }
    .product-grid.loading .product-card:nth-child(12) { animation-delay: 0.6s !important; }

    @keyframes cardSlideIn {
        0% {
            opacity: 0;
            transform: translateY(15px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-card-link {
        display: block;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .product-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        overflow: visible;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid rgba(0, 0, 0, 0.06);
        display: flex;
        flex-direction: column;
        position: relative;
        height: 100%;
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

    .product-card-link:hover .product-card {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 20px 40px rgba(39, 98, 243, 0.08), 0 8px 16px rgba(0, 0, 0, 0.06);
        border-color: rgba(39, 98, 243, 0.15);
    }

    .product-card-link:hover .product-card::before {
        opacity: 0;
    }

    .product-card-link:hover .product-card::after {
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

    .product-card-link:hover .product-image img {
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
        padding: 1.25rem 1.25rem 1.75rem 1.25rem;
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

    .product-card-link:hover .product-title {
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
        position: relative;
    }
    
    /* Keep add-to-cart button inline with price */
    .product-footer .add-to-cart-icon {
        position: static;
        flex-shrink: 0;
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

    /* Product Grid Loading Animation - Lightweight & Simple */
    .product-grid.loading .product-card {
        opacity: 0;
        transform: translateY(15px);
        animation: cardFadeIn 0.4s ease-out forwards;
    }

    .product-grid.loading .product-card:nth-child(1) { animation-delay: 0.05s; }
    .product-grid.loading .product-card:nth-child(2) { animation-delay: 0.1s; }
    .product-grid.loading .product-card:nth-child(3) { animation-delay: 0.15s; }
    .product-grid.loading .product-card:nth-child(4) { animation-delay: 0.2s; }
    .product-grid.loading .product-card:nth-child(5) { animation-delay: 0.25s; }
    .product-grid.loading .product-card:nth-child(6) { animation-delay: 0.3s; }
    .product-grid.loading .product-card:nth-child(7) { animation-delay: 0.35s; }
    .product-grid.loading .product-card:nth-child(8) { animation-delay: 0.4s; }
    .product-grid.loading .product-card:nth-child(9) { animation-delay: 0.45s; }
    .product-grid.loading .product-card:nth-child(10) { animation-delay: 0.5s; }
    .product-grid.loading .product-card:nth-child(11) { animation-delay: 0.55s; }
    .product-grid.loading .product-card:nth-child(12) { animation-delay: 0.6s; }

    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .products-container {
            flex-direction: column;
            padding: 0 var(--space-4);
            gap: var(--space-4);
        }

        .products-content {
            width: 100%;
        }

        /* Filter sidebar is handled by filter-sidebar component */
        .filter-sidebar {
            display: block;
        }

        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.25rem;
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

        .section-header h2 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .products-section {
            padding: 1.5rem 0.75rem;
        }

        .products-container {
            padding: 0 0.5rem;
        }

        .search-results-info-box {
            padding: 1.25rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
        }

        .search-query {
            font-size: 1.1rem;
            text-align: center;
        }

        .count-number {
            font-size: 1.5rem;
        }

        .no-results {
            padding: 2.5rem 1.25rem;
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

        .no-results p {
            font-size: 0.95rem;
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
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .product-card {
            border-radius: 12px;
        }

        .product-info {
            padding: 0.75rem;
        }

        .product-title {
            font-size: 0.875rem;
            -webkit-line-clamp: 2;
        }

        .product-description {
            display: none;
        }

        .product-price .current-price {
            font-size: 1rem;
        }

        .product-price .original-price {
            font-size: 0.75rem;
        }

        .add-to-cart-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
        }

        .section-header {
            margin-bottom: 1rem;
        }

        .section-header h2 {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 480px) {
        .products-section {
            padding: 1rem 0.5rem;
        }

        .products-container {
            padding: 0;
        }

        .search-query {
            font-size: 1rem;
        }

        .search-results-info-box {
            padding: 1rem;
            margin-bottom: 1.25rem;
            border-radius: 12px;
        }

        .search-label {
            font-size: 0.8rem;
        }

        .count-number {
            font-size: 1.25rem;
        }

        .no-results-icon {
            width: 80px;
            height: 80px;
        }

        .no-results-icon i {
            font-size: 2rem;
        }

        .no-results h3 {
            font-size: 1.25rem;
        }

        .no-results p {
            font-size: 0.875rem;
        }

        .btn-primary-action,
        .btn-secondary-action {
            padding: 0.875rem 1.5rem;
            font-size: 0.875rem;
        }

        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .product-card {
            border-radius: 10px;
        }

        .product-image {
            height: 140px;
        }

        .product-info {
            padding: 0.625rem;
        }

        .product-title {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .product-footer {
            gap: 0.5rem;
        }

        .product-price .current-price {
            font-size: 0.9rem;
        }

        .add-to-cart-icon {
            width: 32px;
            height: 32px;
            min-width: 32px;
            font-size: 0.85rem;
        }

        .product-badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
        }

        .wishlist-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .section-header h2 {
            font-size: 1.125rem;
        }
    }
    
</style>

<div class="products-section">
    <div class="container">
        <div class="products-container">
            <!-- Filter Sidebar Component (includes mobile toggle button) -->
            <x-filter-sidebar 
                :filters="$availableFilters" 
                :current="request()->all()"
            />

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
            <a href="{{ route('product.detail', $product) }}" class="product-card-link">
                <div class="product-card">
                    <div class="product-image">
                        @if($product->is_new)
                        <div class="product-badge">{{ __t('messages.new') }}</div>
                        @elseif($product->sale_price && $product->sale_price < $product->price)
                        <div class="product-badge">{{ __t('messages.sale') }}</div>
                        @elseif($product->is_featured)
                        <div class="product-badge">{{ __t('messages.hot') }}</div>
                        @endif
                        <div class="wishlist-btn" data-product-id="{{ $product->id }}" onclick="event.preventDefault(); event.stopPropagation();">
                            <i class="far fa-heart"></i>
                        </div>
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}" decoding="async">
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
                                    onclick="event.preventDefault(); event.stopPropagation(); requestProduct({{ $product->id }}, '{{ $product->name }}');">
                                <i class="fas fa-bell"></i>
                            </button>
                            @else
                            <button class="add-to-cart-icon {{ in_array($product->id, $cartProductIds) ? 'in-cart' : '' }}"
                                    data-product-id="{{ $product->id }}"
                                    title="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                    aria-label="{{ in_array($product->id, $cartProductIds) ? __t('messages.in_cart') : __t('messages.add_to_cart') }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); addToCart({{ $product->id }}, this);">
                                <i class="fas {{ in_array($product->id, $cartProductIds) ? 'fa-check' : 'fa-shopping-cart' }}"></i>
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

<script>
(function() {
    'use strict';
    console.log('🚀 Products Filter System Initialized');
    
    // Filter state
    let debounceTimer = null;
    let isFiltering = false;
    
    const FILTER_CONFIG = {
        minPrice: 0,
        maxPrice: 5000,
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

    // Clear all filters (exposed globally for filter-sidebar component)
    window.clearAllFilters = function() {
        console.log('🗑️ Clearing all filters');
        
        // Uncheck all checkboxes
        document.querySelectorAll('input[name="categories[]"], input[name="brands[]"], input[name^="attr["]').forEach(checkbox => {
            checkbox.checked = false;
            const label = checkbox.parentElement;
            if (label) label.style.backgroundColor = '';
        });

        // Uncheck all radio buttons
        document.querySelectorAll('input[name="stock"], input[name="tag"]').forEach(radio => {
            radio.checked = false;
        });

        // Uncheck strong offers
        const strongOffersCheckbox = document.getElementById('strong-offers-checkbox');
        if (strongOffersCheckbox) {
            strongOffersCheckbox.checked = false;
        }

        // Reset price slider and inputs
        const rMin = document.getElementById('rangeMin');
        const rMax = document.getElementById('rangeMax');
        if (rMin) rMin.value = FILTER_CONFIG.minPrice;
        if (rMax) rMax.value = FILTER_CONFIG.maxPrice;
        if (minPriceInput) minPriceInput.value = FILTER_CONFIG.minPrice;
        if (maxPriceInput) maxPriceInput.value = FILTER_CONFIG.maxPrice;

        // Update hidden inputs
        const minPriceHidden = document.getElementById('minPrice');
        const maxPriceHidden = document.getElementById('maxPrice');
        if (minPriceHidden) minPriceHidden.value = FILTER_CONFIG.minPrice;
        if (maxPriceHidden) maxPriceHidden.value = FILTER_CONFIG.maxPrice;

        // Update slider highlight
        const hl = document.querySelector('.dual-range-highlight');
        if (hl) { hl.style.left = '0%'; hl.style.width = '100%'; }

        // Redirect to products page using AJAX (preserving search if exists)
        const form = document.getElementById('filterForm');
        const searchInput = form ? form.querySelector('input[name="search"]') : null;
        const searchValue = searchInput ? searchInput.value : '';

        const url = searchValue 
            ? FILTER_CONFIG.productsRoute + '?search=' + encodeURIComponent(searchValue)
            : FILTER_CONFIG.productsRoute;
        
        // Update URL without reload
        window.history.pushState({ path: url }, '', url);
        
        // Apply filters using AJAX
        if (typeof window.applyFilters === 'function') {
            window.applyFilters();
        } else {
            // Fallback: try to fetch directly without reload
            console.warn('⚠️ applyFilters function not found, trying direct fetch');
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('.product-grid');
                const newPagination = doc.querySelector('.pagination-wrapper');
                const currentGrid = document.querySelector('.product-grid');
                const currentPagination = document.querySelector('.pagination-wrapper');
                
                if (newGrid && currentGrid) {
                    currentGrid.innerHTML = newGrid.innerHTML;
                }
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                }
            })
            .catch(err => {
                console.error('Fallback fetch failed:', err);
                alert('Error loading products. Please refresh the page.');
            });
        }
    }

    // Apply filters function (exposed globally for filter-sidebar component)
    window.applyFilters = function() {
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

        // Add all form fields
        for (const [key, value] of formData.entries()) {
            if (value && String(value).trim() !== '') {
                // Handle array values (like categories[], brands[])
                if (key.endsWith('[]')) {
                    params.append(key, value);
                } else {
                    params.append(key, value);
                }
            }
        }

        // Preserve search query if exists
        const searchParam = new URLSearchParams(window.location.search).get('search');
        if (searchParam) {
            params.set('search', searchParam);
        }

        const url = FILTER_CONFIG.productsRoute + (params.toString() ? '?' + params.toString() : '');
        console.log('📍 Filter URL:', url);

        // Update browser URL without reload
        window.history.pushState({ path: url, filters: params.toString() }, '', url);

        // Fetch filtered products using AJAX
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
                'Cache-Control': 'no-cache'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.text();
        })
        .then(html => {
            try {
                console.log('📦 Received HTML response, length:', html.length);
                
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Check for errors in parsing
                const parserErrors = doc.querySelectorAll('parsererror');
                if (parserErrors.length > 0) {
                    console.error('❌ HTML parsing errors:', parserErrors);
                }
                
                // Get only the product grid and pagination from new content
                const newProductGrid = doc.querySelector('.product-grid');
                const newNoResults = doc.querySelector('.no-results');
                const newPagination = doc.querySelector('.pagination-wrapper');
                
                // Get current elements
                const currentProductGrid = document.querySelector('.product-grid');
                const currentNoResults = document.querySelector('.no-results');
                const currentPagination = document.querySelector('.pagination-wrapper');
                
                console.log('🔍 Content check:', {
                    newProductGridFound: !!newProductGrid,
                    newNoResultsFound: !!newNoResults,
                    newPaginationFound: !!newPagination,
                    currentProductGridFound: !!currentProductGrid,
                    currentNoResultsFound: !!currentNoResults,
                    currentPaginationFound: !!currentPagination
                });

                // Update product grid or no-results message
                if (newProductGrid && currentProductGrid) {
                    // Hide current grid with fade
                    currentProductGrid.style.opacity = '0';
                    currentProductGrid.style.transition = 'opacity 0.15s ease';
                    
                    setTimeout(() => {
                        // Replace product grid content
                        currentProductGrid.innerHTML = newProductGrid.innerHTML;
                        
                        // Reset opacity and remove transition for animation
                        currentProductGrid.style.opacity = '';
                        currentProductGrid.style.transition = '';
                        
                        // Add loading class for animation
                        currentProductGrid.classList.add('loading');
                        
                        // Force reflow to trigger animation
                        void currentProductGrid.offsetHeight;
                        
                        // Re-initialize wishlist and cart buttons
                        if (typeof initializeWishlistButtons === 'function') {
                            initializeWishlistButtons();
                        }
                        if (typeof initializeCartButtons === 'function') {
                            initializeCartButtons();
                        }
                        
                        // Remove loading class after animation completes (350ms + max delay 360ms = ~700ms)
                        setTimeout(() => {
                            currentProductGrid.classList.remove('loading');
                            console.log('✅ Animation completed');
                        }, 750);
                        
                        console.log('✅ Product grid updated with animation');
                    }, 150);
                } else if (newNoResults) {
                    // Handle no results case
                    if (currentProductGrid) {
                        currentProductGrid.style.opacity = '0';
                        setTimeout(() => {
                            currentProductGrid.style.display = 'none';
                        }, 200);
                    }
                    
                    if (currentNoResults) {
                        currentNoResults.style.opacity = '0';
                        setTimeout(() => {
                            currentNoResults.innerHTML = newNoResults.innerHTML;
                            currentNoResults.style.opacity = '1';
                        }, 200);
                    } else {
                        // Insert no-results if it doesn't exist
                        const productsContent = document.getElementById('productsContent');
                        if (productsContent) {
                            const noResultsHTML = newNoResults.outerHTML;
                            if (currentProductGrid) {
                                currentProductGrid.insertAdjacentHTML('afterend', noResultsHTML);
                            } else {
                                productsContent.insertAdjacentHTML('beforeend', noResultsHTML);
                            }
                        }
                    }
                }
                
                // Update pagination
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                    handlePaginationLinks();
                    console.log('✅ Pagination updated');
                } else if (newPagination && !currentPagination) {
                    // Add pagination if it doesn't exist
                    const productsContent = document.getElementById('productsContent');
                    if (productsContent) {
                        productsContent.insertAdjacentHTML('beforeend', newPagination.outerHTML);
                        handlePaginationLinks();
                    }
                } else if (!newPagination && currentPagination) {
                    // Remove pagination if no longer needed
                    currentPagination.remove();
                }
                
                // Smooth scroll to products section and hide loading
                setTimeout(() => {
                    const productsSection = document.querySelector('.products-section');
                    if (productsSection) {
                        productsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                    hideLoading();
                    console.log('✅ Products updated successfully');
                }, 300);
            } catch (parseError) {
                console.error('❌ Error parsing response:', parseError);
                console.error('Response HTML:', html.substring(0, 500));
                hideLoading();
                alert('Error parsing response. Please try again.');
            }
        })
        .catch(error => {
            console.error('❌ Filter error:', error);
            hideLoading();
            // Don't reload - show error message instead
            alert('Error loading products. Please check your connection and try again.');
            console.error('Full error:', error);
        });
    }

    // Debounced filter (exposed globally for filter-sidebar component)
    window.debouncedApplyFilters = function(delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(window.applyFilters, delay || 300);
    }

    // Handle pagination links with AJAX
    function handlePaginationLinks() {
        const paginationLinks = document.querySelectorAll('.pagination a, .pagination-wrapper a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const href = this.getAttribute('href');
                if (!href || href === '#' || href === 'javascript:void(0)') {
                    return;
                }
                
                console.log('📄 Pagination link clicked:', href);
                
                // Update URL
                window.history.pushState({ path: href }, '', href);
                
                // Fetch new page
                fetch(href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                        'Cache-Control': 'no-cache'
                    }
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
                        showLoading();
                        currentContent.style.opacity = '0.5';
                        
                        setTimeout(() => {
                            currentContent.innerHTML = newContent.innerHTML;
                            currentContent.style.opacity = '1';
                            
                            // Re-initialize pagination links
                            handlePaginationLinks();
                            
                            // Scroll to top
                            const productsSection = document.querySelector('.products-section');
                            if (productsSection) {
                                productsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                            
                            hideLoading();
                            console.log('✅ Pagination updated');
                        }, 200);
                    } else {
                        console.error('❌ Pagination content not found');
                        hideLoading();
                        alert('Error loading page. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('❌ Pagination error:', error);
                    hideLoading();
                    // Don't reload - show error instead
                    console.warn('⚠️ Pagination error, showing alert instead of reloading');
                    alert('Error loading page. Please try again.');
                });
                
                return false;
            });
        });
    }

    // Initialize everything
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ Initializing filter system...');
        
        // Initialize pagination links
        handlePaginationLinks();
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            console.log('🔙 Browser navigation detected');
            applyFilters();
        });

        const form = document.getElementById('filterForm');
        const minPriceInput = document.getElementById('minPriceInput');
        const maxPriceInput = document.getElementById('maxPriceInput');
        const minPriceHidden = document.getElementById('minPrice');
        const maxPriceHidden = document.getElementById('maxPrice');

        // Prevent form submission (we handle it with AJAX)
        if (form) {
            // Remove any existing submit handlers
            form.onsubmit = null;
            
            // Add multiple layers of protection
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('⛔ Form submission prevented - using AJAX instead');
                applyFilters();
                return false;
            }, true); // Use capture phase
            
            // Also prevent on form reset
            form.addEventListener('reset', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('⛔ Form reset prevented');
                return false;
            }, true);
            
            // Prevent any button clicks that might submit
            form.addEventListener('click', function(e) {
                const target = e.target;
                if (target.tagName === 'BUTTON' && (target.type === 'submit' || !target.type)) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    console.log('⛔ Button click prevented in form');
                }
            }, true);
            
            // Prevent form action navigation - multiple ways
            form.setAttribute('onsubmit', 'return false;');
            form.setAttribute('data-ajax', 'true');
            form.action = 'javascript:void(0);';
            
            // Override form submit method
            const originalSubmit = form.submit;
            form.submit = function() {
                console.log('⛔ Form.submit() called - preventing');
                if (typeof window.applyFilters === 'function') {
                    window.applyFilters();
                }
                return false;
            };
        } else {
            console.error('❌ Filter form not found!');
        }

        // ============================================
        // Custom Dual-Range Price Slider (pure JS, no library)
        // ============================================
        const rangeMin = document.getElementById('rangeMin');
        const rangeMax = document.getElementById('rangeMax');
        const highlight = document.querySelector('.dual-range-highlight');

        function updateSliderHighlight() {
            if (!rangeMin || !rangeMax || !highlight) return;
            const min = parseInt(rangeMin.value);
            const max = parseInt(rangeMax.value);
            const rangeTotal = parseInt(rangeMin.max) - parseInt(rangeMin.min);
            if (rangeTotal <= 0) return;
            const minPercent = ((min - parseInt(rangeMin.min)) / rangeTotal) * 100;
            const maxPercent = ((max - parseInt(rangeMin.min)) / rangeTotal) * 100;
            highlight.style.left = minPercent + '%';
            highlight.style.width = (maxPercent - minPercent) + '%';
        }

        function syncSliderToInputs() {
            if (!rangeMin || !rangeMax) return;
            const min = parseInt(rangeMin.value);
            const max = parseInt(rangeMax.value);
            if (minPriceInput) minPriceInput.value = min;
            if (maxPriceInput) maxPriceInput.value = max;
            if (minPriceHidden) minPriceHidden.value = min;
            if (maxPriceHidden) maxPriceHidden.value = max;
            updateSliderHighlight();
        }

        if (rangeMin && rangeMax) {
            // Min thumb dragged
            rangeMin.addEventListener('input', function() {
                if (parseInt(rangeMin.value) > parseInt(rangeMax.value)) {
                    rangeMin.value = rangeMax.value;
                }
                syncSliderToInputs();
            });

            // Max thumb dragged
            rangeMax.addEventListener('input', function() {
                if (parseInt(rangeMax.value) < parseInt(rangeMin.value)) {
                    rangeMax.value = rangeMin.value;
                }
                syncSliderToInputs();
            });

            // Apply filter on release (not during drag)
            rangeMin.addEventListener('change', function() { debouncedApplyFilters(300); });
            rangeMax.addEventListener('change', function() { debouncedApplyFilters(300); });

            // Initialize highlight on load
            syncSliderToInputs();
            console.log('✅ Custom dual-range slider initialized');
        }

        // Handle manual number input changes
        if (minPriceInput) {
            minPriceInput.addEventListener('change', function() {
                let value = parseFloat(this.value) || FILTER_CONFIG.minPrice;
                const max = parseFloat(maxPriceInput?.value) || FILTER_CONFIG.maxPrice;
                value = Math.max(FILTER_CONFIG.minPrice, Math.min(value, max));
                this.value = Math.round(value);
                if (minPriceHidden) minPriceHidden.value = this.value;
                if (rangeMin) { rangeMin.value = this.value; updateSliderHighlight(); }
                debouncedApplyFilters(500);
            });

            minPriceInput.addEventListener('blur', function() {
                let value = parseFloat(this.value) || FILTER_CONFIG.minPrice;
                value = Math.max(FILTER_CONFIG.minPrice, Math.min(value, FILTER_CONFIG.maxPrice));
                this.value = Math.round(value);
            });
        }

        if (maxPriceInput) {
            maxPriceInput.addEventListener('change', function() {
                let value = parseFloat(this.value) || FILTER_CONFIG.maxPrice;
                const min = parseFloat(minPriceInput?.value) || FILTER_CONFIG.minPrice;
                value = Math.max(min, Math.min(value, FILTER_CONFIG.maxPrice));
                this.value = Math.round(value);
                if (maxPriceHidden) maxPriceHidden.value = this.value;
                if (rangeMax) { rangeMax.value = this.value; updateSliderHighlight(); }
                debouncedApplyFilters(500);
            });

            maxPriceInput.addEventListener('blur', function() {
                let value = parseFloat(this.value) || FILTER_CONFIG.maxPrice;
                value = Math.max(FILTER_CONFIG.minPrice, Math.min(value, FILTER_CONFIG.maxPrice));
                this.value = Math.round(value);
            });
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
    // Brand Pagination (View More/Less)
    // ============================================
    // Note: Accordion toggle is handled by filter-sidebar.blade.php

    /**
     * Toggle brand pagination (show more/less brands)
     * Shows/hides brands beyond the first 10
     */
    window.toggleBrandPagination = function() {
        const brandCheckboxes = document.querySelectorAll('.brand-checkbox');
        const viewMoreBtn = document.getElementById('brandViewMoreBtn');
        const viewMoreText = document.getElementById('brandViewMoreText');
        const viewMoreIcon = document.getElementById('brandViewMoreIcon');
        const isRTL = {{ is_rtl() ? 'true' : 'false' }};

        if (!viewMoreBtn) {
            console.error('View more button not found');
            return;
        }

        const isExpanded = viewMoreBtn.classList.contains('expanded');

        // Toggle visibility of brands after index 9
        let visibleCount = 0;
        brandCheckboxes.forEach((checkbox, index) => {
            const brandIndex = parseInt(checkbox.getAttribute('data-brand-index'));
            if (brandIndex >= 10) {
                checkbox.style.display = isExpanded ? 'none' : 'flex';
            }
            if (checkbox.style.display !== 'none') {
                visibleCount++;
            }
        });

        // Update button state
        viewMoreBtn.classList.toggle('expanded');
        viewMoreIcon.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';

        // Update button text
        if (isExpanded) {
            viewMoreText.textContent = isRTL ? 'عرض المزيد' : 'View more';
            viewMoreBtn.setAttribute('aria-label', isRTL ? 'عرض المزيد من العلامات التجارية' : 'View more brands');
        } else {
            viewMoreText.textContent = isRTL ? 'عرض أقل' : 'View less';
            viewMoreBtn.setAttribute('aria-label', isRTL ? 'عرض عدد أقل من العلامات التجارية' : 'View less brands');
        }

        console.log('📄 Brand pagination toggled - Visible: ' + visibleCount);
    };

    /**
     * Setup brand checkbox event listeners
     * Note: Accordion toggle is handled by filter-sidebar.blade.php
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Setup brand checkboxes
        const brandCheckboxes = document.querySelectorAll('input[name="brands[]"]');
        console.log('🏷️ Found ' + brandCheckboxes.length + ' brand checkboxes');

        brandCheckboxes.forEach(function(checkbox) {
            // Initial visual feedback
            if (checkbox.checked) {
                const label = checkbox.parentElement;
                if (label) label.style.backgroundColor = 'rgba(39, 98, 243, 0.08)';
            }

            // Add change listener
            checkbox.addEventListener('change', function(e) {
                console.log('✔️ Brand checkbox changed:', e.target.value, e.target.checked);

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

        // Keyboard support for disclosure button
        const brandToggle = document.getElementById('brandFilterToggle');
        if (brandToggle) {
            brandToggle.addEventListener('keydown', function(e) {
                // Support Enter and Space keys for activation (WAI-ARIA APG)
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleBrandFilter();
                }
            });
        }

        // Auto-expand brand filter if any brand is selected
        const selectedBrands = document.querySelectorAll('input[name="brands[]"]:checked');
        if (selectedBrands.length > 0) {
            console.log('🏷️ Auto-expanding brand filter (brands selected)');
            toggleBrandFilter();

            // Auto-expand pagination if any selected brand is beyond first 10
            selectedBrands.forEach(function(checkbox) {
                const brandCheckbox = checkbox.closest('.brand-checkbox');
                if (brandCheckbox) {
                    const index = parseInt(brandCheckbox.getAttribute('data-brand-index'));
                    if (index >= 10) {
                        const viewMoreBtn = document.getElementById('brandViewMoreBtn');
                        if (viewMoreBtn && !viewMoreBtn.classList.contains('expanded')) {
                            console.log('📄 Auto-expanding brand pagination (selected brand index: ' + index + ')');
                            toggleBrandPagination();
                        }
                    }
                }
            });
        }
    });
})();
</script>

@endsection
