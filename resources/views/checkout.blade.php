@extends('layouts.app')

@section('title', __('messages.checkout') . ' - IT Center')

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

    /* Checkout Container */
    .checkout-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 3rem 2rem;
        min-height: calc(100vh - 200px);
        background: #f5f5f5;
    }

    /* Progress Steps */
    .checkout-progress {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        position: relative;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .checkout-progress::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, #1f2937 50%, #e2e8f0 50%);
        z-index: 0;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 1;
    }

    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #999;
        margin-bottom: 0.5rem;
        transition: all 0.3s;
    }

    .progress-step.active .step-circle {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        border-color: #1f2937;
        color: #fff;
        box-shadow: 0 4px 15px rgba(31, 41, 55, 0.4);
    }

    .progress-step.completed .step-circle {
        background: #4CAF50;
        border-color: #4CAF50;
        color: #fff;
    }

    .step-label {
        font-size: 0.9rem;
        color: #999;
        font-weight: 500;
        text-align: center;
    }

    .progress-step.active .step-label {
        color: #1f2937;
        font-weight: 600;
    }

    /* Main Content Grid */
    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 3rem;
        align-items: start;
    }

    /* Checkout Form */
    .checkout-form-section {
        background: #fff;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: #4169E1;
        font-size: 1.3rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-grid.full {
        grid-template-columns: 1fr;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-group label .required {
        color: #ff4757;
        margin-left: 3px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.9rem 1.2rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #fafafa;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4169E1;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(65, 105, 225, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    /* Order Summary Sidebar */
    .order-summary-sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: #fff;
        position: sticky;
        top: 100px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }

    .summary-header {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .summary-items {
        margin-bottom: 2rem;
    }

    .summary-item-link {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.2s ease;
    }

    .summary-item-link:hover {
        transform: translateX(-3px);
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .summary-item-link:hover .summary-item {
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .summary-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        flex-shrink: 0;
    }

    .summary-item-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 5px;
    }

    .summary-item-details {
        flex: 1;
    }

    .summary-item-name {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .summary-item-qty {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .summary-item-price {
        font-size: 1.1rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* Summary Totals */
    .summary-totals {
        padding: 1.5rem 0;
        border-top: 2px solid rgba(255, 255, 255, 0.2);
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-row .label {
        opacity: 0.9;
    }

    .summary-row .value {
        font-weight: 600;
    }

    .summary-row.total {
        font-size: 1.4rem;
        font-weight: 700;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid rgba(255, 255, 255, 0.2);
    }

    /* Place Order Button */
    .place-order-btn {
        width: 100%;
        background: #fff;
        color: #667eea;
        padding: 1.2rem;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .place-order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        background: #f8f9fa;
    }

    .place-order-btn i {
        font-size: 1rem;
    }

    /* Secure Badge */
    .secure-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .secure-badge i {
        font-size: 1.2rem;
    }

    /* Payment Method Section */
    .payment-methods {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f0f0f0;
    }

    .payment-option {
        padding: 1.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fafafa;
    }

    .payment-option:hover {
        border-color: #4169E1;
        background: #fff;
    }

    .payment-option input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #4169E1;
    }

    .payment-option-label {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .payment-icon {
        font-size: 1.8rem;
        color: #4169E1;
    }

    .payment-info h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.3rem;
    }

    .payment-info p {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }

    /* RTL Support */
    [dir="rtl"] .checkout-content {
        direction: rtl;
    }

    [dir="rtl"] .summary-row {
        direction: rtl;
    }

    [dir="rtl"] .form-group label .required {
        margin-right: 3px;
        margin-left: 0;
    }

    /* ========== Global Mobile Touch & iOS Fixes ========== */
    /* Prevent iOS zoom on focus (16px minimum) */
    @supports (-webkit-touch-callout: none) {
        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 16px !important;
        }
    }

    /* Remove iOS input styling */
    .form-group input,
    .form-group select,
    .form-group textarea {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .form-group select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    [dir="rtl"] .form-group select {
        background-position: left 1rem center;
        padding-right: 1.2rem;
        padding-left: 2.5rem;
    }

    /* ========== Collapsible Summary Toggle (mobile only) ========== */
    .summary-toggle-btn {
        display: none;
    }

    /* Desktop: summary content always visible */
    .summary-collapsible {
        max-height: none;
        overflow: visible;
    }

    /* ========== Mobile Sticky Place Order Bar ========== */
    .mobile-sticky-order-bar {
        display: none;
    }

    /* ========== Mobile Responsive - Tablet (â‰¤968px) ========== */
    @media (max-width: 968px) {
        .checkout-container {
            padding: 2rem 1rem;
        }

        .checkout-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .checkout-progress {
            margin-bottom: 2rem;
        }

        .progress-step {
            font-size: 0.85rem;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 0.95rem;
        }

        .step-label {
            font-size: 0.8rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .checkout-form-section {
            padding: 1.5rem;
            border-radius: 12px;
        }

        .order-summary-sidebar {
            position: static;
            padding: 1.5rem;
            border-radius: 12px;
            order: -1;
        }

        .summary-header {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
        }
    }

    /* ========== Mobile Responsive - Phone (â‰¤768px) ========== */
    @media (max-width: 768px) {
        .checkout-container {
            padding: 1rem 0.75rem;
            padding-bottom: calc(1rem + 80px + env(safe-area-inset-bottom, 0px));
        }

        /* Progress Steps Mobile */
        .checkout-progress {
            margin-bottom: 1.25rem;
            max-width: 100%;
            padding: 0 0.25rem;
        }

        .checkout-progress::before {
            top: 18px;
            height: 2px;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            font-size: 0.85rem;
            border-width: 2px;
        }

        .step-label {
            font-size: 0.72rem;
            margin-top: 0.25rem;
        }

        .progress-step.active .step-circle {
            box-shadow: 0 2px 10px rgba(31, 41, 55, 0.3);
        }

        /* Form Section Mobile */
        .checkout-form-section {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .section-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            gap: 0.5rem;
        }

        .section-title i {
            font-size: 1.1rem;
        }

        .form-grid {
            gap: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }

        /* Touch-friendly inputs: min 44px height */
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem 1rem;
            font-size: 16px;
            border-radius: 10px;
            min-height: 48px;
        }

        .form-group textarea {
            min-height: 80px;
        }

        /* Payment Methods Mobile */
        .payment-methods {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }

        .payment-option {
            padding: 1rem;
            border-radius: 10px;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            min-height: 60px;
        }

        .payment-option input[type="radio"] {
            width: 22px;
            height: 22px;
            min-width: 22px;
        }

        .payment-icon {
            font-size: 1.5rem;
        }

        .payment-info h4 {
            font-size: 0.95rem;
        }

        .payment-info p {
            font-size: 0.8rem;
        }

        /* ---- Collapsible Order Summary ---- */
        .order-summary-sidebar {
            padding: 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .summary-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 1.1rem 1.25rem;
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-family: inherit;
        }

        .summary-toggle-btn .summary-toggle-left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .summary-toggle-btn .summary-toggle-left i {
            font-size: 1rem;
        }

        .summary-toggle-btn .summary-toggle-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-toggle-btn .summary-toggle-total {
            font-size: 1.05rem;
            font-weight: 700;
            background: rgba(255,255,255,0.2);
            padding: 0.2rem 0.65rem;
            border-radius: 8px;
        }

        .summary-toggle-btn .summary-toggle-chevron {
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .summary-toggle-btn .summary-toggle-chevron.open {
            transform: rotate(180deg);
        }

        .summary-collapsible {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }

        .summary-collapsible.expanded {
            max-height: 2000px;
        }

        .summary-collapsible-inner {
            padding: 0 1.25rem 1.25rem;
        }

        .summary-header {
            display: none;
        }

        .summary-item {
            padding: 0.75rem;
            border-radius: 10px;
            gap: 0.75rem;
            margin-bottom: 0.65rem;
        }

        .summary-item-image {
            width: 48px;
            height: 48px;
            border-radius: 8px;
        }

        .summary-item-name {
            font-size: 0.85rem;
        }

        .summary-item-qty {
            font-size: 0.75rem;
        }

        .summary-item-price {
            font-size: 0.95rem;
        }

        .summary-items {
            max-height: 240px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        .summary-items::-webkit-scrollbar {
            width: 4px;
        }

        .summary-items::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
        }

        .summary-items::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 4px;
        }

        .summary-totals {
            padding: 1rem 0;
            margin-bottom: 0.5rem;
        }

        .summary-row {
            font-size: 0.9rem;
            margin-bottom: 0.6rem;
        }

        .summary-row.total {
            font-size: 1.15rem;
            margin-top: 0.6rem;
            padding-top: 0.6rem;
        }

        /* Hide place-order-btn inside sidebar on mobile, show sticky bar instead */
        .order-summary-sidebar .place-order-btn {
            display: none;
        }

        .order-summary-sidebar .secure-badge {
            display: none;
        }

        /* ---- Sticky Place Order Bar ---- */
        .mobile-sticky-order-bar {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0.75rem 1rem;
            padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));
            box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .mobile-sticky-order-bar .sticky-total {
            color: #fff;
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .mobile-sticky-order-bar .sticky-total-label {
            font-size: 0.72rem;
            opacity: 0.85;
            font-weight: 500;
        }

        .mobile-sticky-order-bar .sticky-total-value {
            font-size: 1.2rem;
            font-weight: 800;
        }

        .mobile-sticky-order-bar .sticky-order-btn {
            background: #fff;
            color: #667eea;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            transition: all 0.2s;
            min-height: 48px;
            font-family: inherit;
        }

        .mobile-sticky-order-bar .sticky-order-btn:active {
            transform: scale(0.97);
        }

        .mobile-sticky-order-bar .sticky-order-btn i {
            font-size: 0.85rem;
        }
    }

    /* ========== Mobile Responsive - Small Phone (â‰¤480px) ========== */
    @media (max-width: 480px) {
        .checkout-container {
            padding: 0.75rem 0.5rem;
            padding-bottom: calc(0.75rem + 80px + env(safe-area-inset-bottom, 0px));
        }

        .checkout-content {
            gap: 1rem;
        }

        /* Progress Steps Small Phone */
        .checkout-progress {
            margin-bottom: 1rem;
            padding: 0;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            font-size: 0.7rem;
        }

        .step-label {
            font-size: 0.6rem;
        }

        .checkout-progress::before {
            top: 15px;
        }

        /* Form Section Small Phone */
        .checkout-form-section {
            padding: 1rem;
            border-radius: 10px;
        }

        .section-title {
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .section-title i {
            font-size: 0.95rem;
        }

        .form-grid {
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-size: 0.85rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.7rem 0.875rem;
            font-size: 16px;
            min-height: 46px;
        }

        /* Payment Methods Small Phone */
        .payment-option {
            padding: 0.875rem;
            flex-wrap: wrap;
        }

        .payment-option-label {
            flex: 1;
            min-width: 0;
        }

        .payment-icon {
            font-size: 1.3rem;
        }

        .payment-info h4 {
            font-size: 0.9rem;
        }

        .payment-info p {
            font-size: 0.75rem;
            line-height: 1.3;
        }

        /* Order Summary Small Phone */
        .summary-collapsible-inner {
            padding: 0 1rem 1rem;
        }

        .summary-toggle-btn {
            padding: 1rem;
        }

        .summary-toggle-btn .summary-toggle-left {
            font-size: 1rem;
        }

        .summary-toggle-btn .summary-toggle-total {
            font-size: 0.95rem;
        }

        .summary-items {
            max-height: 200px;
        }

        .summary-item {
            padding: 0.5rem;
            gap: 0.5rem;
        }

        .summary-item-image {
            width: 42px;
            height: 42px;
        }

        .summary-item-name {
            font-size: 0.8rem;
            -webkit-line-clamp: 1;
        }

        .summary-item-qty {
            font-size: 0.7rem;
        }

        .summary-item-price {
            font-size: 0.85rem;
        }

        .summary-row {
            font-size: 0.85rem;
        }

        .summary-row.total {
            font-size: 1.05rem;
        }

        /* Sticky bar small phone */
        .mobile-sticky-order-bar {
            padding: 0.65rem 0.75rem;
            padding-bottom: calc(0.65rem + env(safe-area-inset-bottom, 0px));
        }

        .mobile-sticky-order-bar .sticky-total-value {
            font-size: 1.1rem;
        }

        .mobile-sticky-order-bar .sticky-order-btn {
            padding: 0.65rem 1.1rem;
            font-size: 0.9rem;
        }
    }

    /* ========== Ultra-small phones (â‰¤360px) ========== */
    @media (max-width: 360px) {
        .checkout-container {
            padding: 0.5rem 0.35rem;
            padding-bottom: calc(0.5rem + 80px + env(safe-area-inset-bottom, 0px));
        }

        .step-circle {
            width: 26px;
            height: 26px;
            font-size: 0.6rem;
        }

        .step-label {
            font-size: 0.55rem;
        }

        .checkout-form-section {
            padding: 0.75rem;
        }

        .section-title {
            font-size: 0.95rem;
        }

        .payment-option {
            padding: 0.75rem;
        }

        .payment-option-label {
            gap: 0.5rem;
        }

        .payment-icon {
            font-size: 1.1rem;
        }

        .payment-info h4 {
            font-size: 0.85rem;
        }

        .payment-info p {
            font-size: 0.7rem;
        }

        .mobile-sticky-order-bar .sticky-total-label {
            font-size: 0.65rem;
        }

        .mobile-sticky-order-bar .sticky-total-value {
            font-size: 1rem;
        }

        .mobile-sticky-order-bar .sticky-order-btn {
            padding: 0.6rem 0.9rem;
            font-size: 0.85rem;
        }
    }

    /* ========== RTL Mobile Adjustments ========== */
    @media (max-width: 768px) {
        [dir="rtl"] .payment-option-label {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .payment-info {
            text-align: right;
        }

        [dir="rtl"] .summary-item {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .summary-item-details {
            text-align: right;
        }

        [dir="rtl"] .summary-toggle-btn {
            direction: rtl;
        }

        [dir="rtl"] .mobile-sticky-order-bar {
            direction: rtl;
        }
    }

    /* ========== Palestine Shipping Validation Styles ========== */

    /* Postal Code Input with P Prefix */
    .postal-code-wrapper {
        position: relative;
        display: flex;
        align-items: stretch;
    }

    .postal-prefix {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 1rem;
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        border: 2px solid #1f2937;
        border-right: none;
        border-radius: 10px 0 0 10px;
        min-width: 48px;
        user-select: none;
        letter-spacing: 1px;
    }

    [dir="rtl"] .postal-prefix {
        border-radius: 0 10px 10px 0;
        border-right: 2px solid #1f2937;
        border-left: none;
    }

    .postal-city-prefix {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.6rem;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 1rem;
        font-family: 'Courier New', monospace;
        border: 2px solid #e2e8f0;
        border-left: none;
        border-right: none;
        min-width: 42px;
        letter-spacing: 2px;
        user-select: none;
        transition: all 0.3s;
    }

    .postal-city-prefix.has-value {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }

    [dir="rtl"] .postal-city-prefix {
        border-left: none;
        border-right: none;
    }

    .postal-code-wrapper input {
        border-radius: 0 10px 10px 0 !important;
        flex: 1;
    }

    [dir="rtl"] .postal-code-wrapper input {
        border-radius: 10px 0 0 10px !important;
    }

    /* Postal Code Lookup Link */
    .postal-lookup-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 6px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #0369a1;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .postal-lookup-link:hover {
        background: #e0f2fe;
        color: #0c4a6e;
        border-color: #7dd3fc;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(3, 105, 161, 0.15);
    }

    /* Country Fixed Badge */
    .country-fixed {
        background: #f0fdf4;
        border: 2px solid #86efac;
        color: #166534;
        font-weight: 600;
        cursor: not-allowed;
        opacity: 1 !important;
    }

    .country-fixed-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        color: #166534;
        background: #dcfce7;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        margin-top: 0.35rem;
        font-weight: 500;
    }

    .country-fixed-badge i {
        font-size: 0.75rem;
    }

    /* Validation States */
    .form-group.has-success input,
    .form-group.has-success select {
        border-color: #22c55e !important;
        background: #f0fdf4 !important;
    }

    .form-group.has-success input:focus,
    .form-group.has-success select:focus {
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15) !important;
    }

    .form-group.has-error input,
    .form-group.has-error select {
        border-color: #ef4444 !important;
        background: #fef2f2 !important;
    }

    .form-group.has-error input:focus,
    .form-group.has-error select:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
    }

    .form-group.has-success .postal-prefix {
        background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
        border-color: #22c55e;
    }

    .form-group.has-error .postal-prefix {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        border-color: #ef4444;
    }

    /* Validation Message */
    .validation-msg {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.82rem;
        margin-top: 0.35rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .validation-msg.success {
        color: #16a34a;
    }

    .validation-msg.error {
        color: #dc2626;
    }

    .validation-msg i {
        font-size: 0.8rem;
    }

    /* Postal Range Hint */
    .postal-hint {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.82rem;
        color: #6b7280;
        margin-top: 0.35rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .postal-hint i {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .postal-hint .range-badge {
        display: inline-block;
        background: #f3f4f6;
        color: #374151;
        padding: 0.1rem 0.5rem;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* City Dropdown optgroup */
    .form-group select optgroup {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1f2937;
        padding: 0.5rem 0;
    }

    .form-group select option {
        font-weight: 400;
        padding: 0.4rem 0.5rem;
    }

    /* Shipping Restriction Notice */
    .shipping-notice {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        color: #1e40af;
        line-height: 1.5;
    }

    .shipping-notice i {
        font-size: 1.1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
        color: #3b82f6;
    }

    /* Server-side validation errors */
    .checkout-errors {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .checkout-errors ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .checkout-errors li {
        color: #dc2626;
        font-size: 0.9rem;
        padding: 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkout-errors li::before {
        content: '\f06a';
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .postal-prefix {
            padding: 0 0.6rem;
            font-size: 0.95rem;
            min-width: 38px;
        }

        .postal-city-prefix {
            padding: 0 0.4rem;
            font-size: 0.9rem;
            min-width: 36px;
        }

        .postal-code-wrapper input {
            min-width: 0;
            flex: 1;
        }

        .postal-lookup-link {
            font-size: 0.78rem;
            padding: 5px 10px;
        }

        .shipping-notice {
            font-size: 0.82rem;
            padding: 0.875rem 1rem;
        }

        .checkout-errors {
            padding: 0.875rem 1rem;
        }

        .checkout-errors li {
            font-size: 0.82rem;
        }

        .country-fixed-badge {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .postal-prefix {
            padding: 0 0.5rem;
            font-size: 0.9rem;
            min-width: 32px;
        }

        .postal-city-prefix {
            padding: 0 0.3rem;
            font-size: 0.85rem;
            min-width: 30px;
            letter-spacing: 1px;
        }

        .postal-hint {
            font-size: 0.75rem;
        }

        .postal-hint .range-badge {
            font-size: 0.78rem;
            padding: 0.05rem 0.35rem;
        }

        .validation-msg {
            font-size: 0.78rem;
        }

        .shipping-notice {
            font-size: 0.78rem;
            padding: 0.75rem 0.875rem;
            gap: 0.5rem;
        }

        .shipping-notice i {
            font-size: 1rem;
        }
    }

    @media (max-width: 360px) {
        .postal-prefix {
            padding: 0 0.4rem;
            font-size: 0.85rem;
            min-width: 28px;
        }

        .postal-city-prefix {
            padding: 0 0.25rem;
            font-size: 0.8rem;
            min-width: 26px;
        }
    }
</style>

<div class="checkout-container">
    <!-- Progress Steps -->
    <div class="checkout-progress">
        <div class="progress-step completed">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">{{ __('messages.cart') }}</div>
        </div>
        <div class="progress-step active">
            <div class="step-circle">2</div>
            <div class="step-label">{{ __('messages.checkout') }}</div>
        </div>
        <div class="progress-step">
            <div class="step-circle">3</div>
            <div class="step-label">{{ __('messages.confirmation') }}</div>
        </div>
    </div>

    <div class="checkout-content">
        <!-- Checkout Form -->
        <div class="checkout-form-section">
            <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
                @csrf
                <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                
                <!-- Contact Information -->
                <div class="section-title">
                    <i class="fas fa-user"></i>
                    {{ __('messages.contact_information') }}
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name">
                            {{ __('messages.first_name') }}
                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name', $user->first_name ?? '') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">
                            {{ __('messages.last_name') }}
                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name', $user->last_name ?? '') }}"
                               required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="email">
                            {{ __('messages.email') }}
                            <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email ?? '') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            {{ __('messages.phone') }}
                            <span class="required">*</span>
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone ?? '') }}"
                               required>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="section-title" style="margin-top: 2rem;">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ __('messages.shipping_address') }}
                </div>

                <!-- Shipping Restriction Notice -->
                <div class="shipping-notice">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ __('messages.shipping_palestine_only') }}</span>
                </div>

                <!-- Server-side Validation Errors -->
                @if ($errors->any())
                    <div class="checkout-errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-grid full">
                    <div class="form-group">
                        <label for="address">
                            {{ __('messages.street_address') }}
                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="{{ old('address') }}"
                               placeholder="{{ __('messages.address_placeholder') }}"
                               required>
                    </div>
                </div>

                @php $locale = app()->getLocale(); @endphp

                <div class="form-grid">
                    <!-- City Dropdown (grouped by region) -->
                    <div class="form-group" id="city-group">
                        <label for="city">
                            {{ __('messages.city') }}
                            <span class="required">*</span>
                        </label>
                        <select id="city" name="city" required>
                            <option value="">{{ __('messages.select_city') }}</option>
                            @foreach ($shippingRegions as $region)
                                <optgroup label="{{ $region->{'name_' . $locale} ?? $region->name_en }}">
                                    @foreach ($region->activeCities as $city)
                                        <option value="{{ $city->key }}"
                                                data-postal-min="{{ $city->postal_code_min }}"
                                                data-postal-max="{{ $city->postal_code_max }}"
                                                data-governorate="{{ $city->{'governorate_' . $locale} ?? $city->governorate_en }}"
                                                {{ old('city') == $city->key ? 'selected' : '' }}>
                                            {{ $city->{'name_' . $locale} ?? $city->name_en }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div id="city-validation" class="validation-msg" style="display:none;"></div>
                    </div>

                    <!-- Governorate (auto-populated, read-only) -->
                    <div class="form-group">
                        <label for="governorate_display">{{ __('messages.governorate') }}</label>
                        <input type="text" 
                               id="governorate_display" 
                               value="{{ old('governorate', '') }}"
                               readonly
                               style="background: #f3f4f6; cursor: not-allowed;">
                        <input type="hidden" id="governorate" name="governorate" value="{{ old('governorate', '') }}">
                    </div>
                </div>

                <div class="form-grid">
                    <!-- Postal Code with P Prefix -->
                    <div class="form-group" id="postal-group">
                        <label for="postal_code_input">
                            {{ __('messages.postal_code') }}
                            <span class="required">*</span>
                        </label>
                        @php $suffixDigits = $postalCodeDigits - 3; @endphp
                        <div class="postal-code-wrapper">
                            <span class="postal-prefix">P</span>
                            <span class="postal-city-prefix" id="postal_city_prefix">---</span>
                            <input type="text" 
                                   id="postal_code_input"
                                   inputmode="numeric"
                                   pattern="[0-9]{{ '{' . $suffixDigits . '}' }}"
                                   maxlength="{{ $suffixDigits }}"
                                   placeholder="{{ str_repeat('0', $suffixDigits) }}"
                                   value="{{ old('postal_code') ? substr(ltrim(strtoupper(old('postal_code')), 'P'), 3) : '' }}"
                                   disabled
                                   required>
                        </div>
                        <input type="hidden" id="postal_code" name="postal_code" value="{{ old('postal_code', '') }}">
                        <input type="hidden" id="postal_code_digits" value="{{ $postalCodeDigits }}">
                        <input type="hidden" id="postal_suffix_digits" value="{{ $suffixDigits }}">
                        <div id="postal-hint" class="postal-hint" style="display:none;">
                            <i class="fas fa-info-circle"></i>
                            <span></span>
                        </div>
                        <div id="postal-validation" class="validation-msg" style="display:none;"></div>
                        <a href="https://postcode.palestine.ps/" target="_blank" rel="noopener noreferrer" class="postal-lookup-link">
                            <i class="fas fa-search-location"></i>
                            {{ __('messages.find_your_postal_code') }}
                            <i class="fas fa-external-link-alt" style="font-size: 0.7em; opacity: 0.7;"></i>
                        </a>
                    </div>

                    <!-- Country (Fixed to Palestine) -->
                    <div class="form-group">
                        <label for="country_display">
                            {{ __('messages.country') }}
                            <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="country_display"
                               value="{{ __('messages.palestine') }}"
                               class="country-fixed"
                               disabled
                               readonly>
                        <input type="hidden" id="country" name="country" value="{{ $shippingCountry }}">
                        <div class="country-fixed-badge">
                            <i class="fas fa-lock"></i>
                            {{ __('messages.shipping_country_locked') }}
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="payment-methods">
                    <div class="section-title">
                        <i class="fas fa-credit-card"></i>
                        {{ __('messages.payment_method') }}
                    </div>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                        <div class="payment-option-label">
                            <i class="fas fa-money-bill-wave payment-icon"></i>
                            <div class="payment-info">
                                <h4>{{ __('messages.cash_on_delivery') }}</h4>
                                <p>{{ __('messages.cod_description') }}</p>
                            </div>
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bank_transfer">
                        <div class="payment-option-label">
                            <i class="fas fa-university payment-icon"></i>
                            <div class="payment-info">
                                <h4>{{ __('messages.bank_transfer') }}</h4>
                                <p>{{ __('messages.bank_transfer_description') }}</p>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Order Notes -->
                <div class="form-grid full" style="margin-top: 2rem;">
                    <div class="form-group">
                        <label for="notes">{{ __('messages.order_notes') }}</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  placeholder="{{ __('messages.order_notes_placeholder') }}">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-sidebar">
            <!-- Mobile: Collapsible toggle -->
            <button type="button" class="summary-toggle-btn" id="summaryToggleBtn" aria-expanded="false" aria-controls="summaryCollapsible">
                <span class="summary-toggle-left">
                    <i class="fas fa-receipt"></i>
                    {{ __('messages.order_summary') }}
                    <span style="font-size:0.8rem; opacity:0.8; font-weight:400;">({{ $cartItems->sum('quantity') }})</span>
                </span>
                <span class="summary-toggle-right">
                    <span class="summary-toggle-total">â‚ª{{ number_format($total, 2) }}</span>
                    <i class="fas fa-chevron-down summary-toggle-chevron"></i>
                </span>
            </button>

            <!-- Collapsible wrapper for mobile -->
            <div class="summary-collapsible" id="summaryCollapsible">
            <div class="summary-collapsible-inner">

            <h2 class="summary-header">{{ __('messages.order_summary') }}</h2>

            <div class="summary-items">
                @foreach($cartItems as $item)
                    @if($item->product)
                        <a href="{{ route('product.detail', $item->product) }}" class="summary-item-link">
                            <div class="summary-item">
                                <div class="summary-item-image">
                                    @php
                                        // Get raw main_image value from database
                                        $mainImage = $item->product->getAttributes()['main_image'] ?? null;
                                        $imageSrc = \App\Helpers\ImageHelper::assetUrl('images/products/default.png'); // default
                                        
                                        if ($mainImage) {
                                            if (str_starts_with($mainImage, 'http')) {
                                                $imageSrc = $mainImage;
                                            } elseif (str_starts_with($mainImage, 'images/')) {
                                                $imageSrc = asset($mainImage);
                                            } else {
                                                $imageSrc = asset('media/' . $mainImage);
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $imageSrc }}" 
                                         alt="{{ $item->product->name }}"
                                         onerror="this.src='{{ \App\Helpers\ImageHelper::assetUrl('images/products/default.png') }}'">
                                </div>
                                <div class="summary-item-details">
                                    <div class="summary-item-name">
                                        {{ $item->product->{'name_' . current_locale()} }}
                                    </div>
                                    <div class="summary-item-qty">
                                        {{ __('messages.quantity') }}: {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="summary-item-price">
                                    â‚ª{{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="summary-totals">
                <div class="summary-row">
                    <span class="label">{{ __('messages.subtotal') }}</span>
                    <span class="value">â‚ª{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span class="label">{{ __('messages.total') }}</span>
                    <span class="value">â‚ª{{ number_format($total, 2) }}</span>
                </div>
            </div>

            <button type="submit" form="checkout-form" class="place-order-btn">
                <i class="fas fa-check-circle"></i>
                {{ __('messages.place_order') }}
            </button>

            <div class="secure-badge">
                <i class="fas fa-lock"></i>
                {{ __('messages.secure_checkout') }}
            </div>

            </div><!-- /.summary-collapsible-inner -->
            </div><!-- /.summary-collapsible -->
        </div>
    </div>
</div>

<!-- Mobile Sticky Place Order Bar -->
<div class="mobile-sticky-order-bar" id="mobileStickyOrderBar">
    <div class="sticky-total">
        <span class="sticky-total-label">{{ __('messages.total') }}</span>
        <span class="sticky-total-value">â‚ª{{ number_format($total, 2) }}</span>
    </div>
    <button type="submit" form="checkout-form" class="sticky-order-btn" id="mobilePlaceOrderBtn">
        <i class="fas fa-check-circle"></i>
        {{ __('messages.place_order') }}
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const citySelect = document.getElementById('city');
    const governorateDisplay = document.getElementById('governorate_display');
    const governorateHidden = document.getElementById('governorate');
    const postalInput = document.getElementById('postal_code_input');
    const postalHidden = document.getElementById('postal_code');
    const postalHint = document.getElementById('postal-hint');
    const postalValidation = document.getElementById('postal-validation');
    const cityValidation = document.getElementById('city-validation');
    const postalGroup = document.getElementById('postal-group');
    const cityGroup = document.getElementById('city-group');
    const postalCityPrefix = document.getElementById('postal_city_prefix');
    const postalDigits = parseInt(document.getElementById('postal_code_digits').value) || 7;
    const suffixDigits = parseInt(document.getElementById('postal_suffix_digits').value) || 4;
    let currentCityPrefix = '';

    // Translation strings
    const translations = {
        postalMismatch: @json(__('messages.postal_code_mismatch', ['min' => ':min', 'max' => ':max'])),
        postalValid: @json(__('messages.postal_code_valid')),
        postalHintRange: @json(__('messages.postal_hint_range')),
        citySelected: @json(__('messages.city_selected_success')),
        selectCityFirst: @json(__('messages.select_city_first')),
        invalidPostalFormat: @json(__('messages.invalid_postal_format')),
    };

    let currentMin = null;
    let currentMax = null;

    // ==================== CITY CHANGE ====================
    citySelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        
        if (!this.value) {
            // Reset everything
            governorateDisplay.value = '';
            governorateHidden.value = '';
            postalHint.style.display = 'none';
            cityGroup.classList.remove('has-success', 'has-error');
            cityValidation.style.display = 'none';
            currentMin = null;
            currentMax = null;
            currentCityPrefix = '';
            postalCityPrefix.textContent = '---';
            postalCityPrefix.classList.remove('has-value');
            postalInput.value = '';
            postalInput.disabled = true;
            postalHidden.value = '';
            postalGroup.classList.remove('has-success', 'has-error');
            postalValidation.style.display = 'none';
            return;
        }

        const min = parseInt(selected.dataset.postalMin);
        const max = parseInt(selected.dataset.postalMax);
        const governorate = selected.dataset.governorate;

        currentMin = min;
        currentMax = max;

        // Auto-populate governorate
        governorateDisplay.value = governorate;
        governorateHidden.value = governorate;

        // Auto-fill postal code city prefix (first 3 digits from min)
        currentCityPrefix = String(min).padStart(3, '0');
        postalCityPrefix.textContent = currentCityPrefix;
        postalCityPrefix.classList.add('has-value');

        // Enable postal input and clear previous value
        postalInput.disabled = false;
        postalInput.value = '';
        postalInput.focus();

        // Show postal code range hint
        const minStr = 'P' + String(min).padStart(3, '0');
        const maxStr = 'P' + String(max).padStart(3, '0');
        postalHint.querySelector('span').innerHTML = translations.postalHintRange
            .replace(':min', '<span class="range-badge">' + minStr + '</span>')
            .replace(':max', '<span class="range-badge">' + maxStr + '</span>');
        postalHint.style.display = 'flex';

        // Mark city as valid (green border only, no text)
        cityGroup.classList.remove('has-error');
        cityGroup.classList.add('has-success');
        cityValidation.style.display = 'none';

        // Sync hidden field and re-validate
        syncPostalHidden();
        validatePostalCode();
    });

    // ==================== SYNC HIDDEN FIELD ====================
    function syncPostalHidden() {
        const suffix = postalInput.value.trim();
        if (currentCityPrefix && suffix) {
            postalHidden.value = 'P' + currentCityPrefix + suffix;
        } else if (currentCityPrefix) {
            postalHidden.value = '';
        } else {
            postalHidden.value = '';
        }
    }

    // ==================== POSTAL CODE INPUT ====================
    // Only allow numeric input (user types remaining 4 digits)
    postalInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > suffixDigits) {
            this.value = this.value.slice(0, suffixDigits);
        }
        syncPostalHidden();
        validatePostalCode();
    });

    postalInput.addEventListener('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter, arrows
        if ([8, 9, 27, 13, 46, 37, 38, 39, 40].indexOf(e.keyCode) !== -1) return;
        // Allow Ctrl+A/C/V/X
        if ((e.ctrlKey || e.metaKey) && [65, 67, 86, 88].indexOf(e.keyCode) !== -1) return;
        // Block non-numeric
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    // ==================== VALIDATION ====================
    function validatePostalCode() {
        const suffix = postalInput.value.trim();

        // No city selected yet
        if (!currentCityPrefix) {
            postalGroup.classList.remove('has-success', 'has-error');
            postalValidation.style.display = 'none';
            return;
        }

        // Nothing entered yet
        if (!suffix) {
            postalGroup.classList.remove('has-success', 'has-error');
            postalValidation.style.display = 'none';
            return;
        }

        if (!/^\d+$/.test(suffix)) {
            postalGroup.classList.remove('has-success');
            postalGroup.classList.add('has-error');
            postalValidation.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + translations.invalidPostalFormat;
            postalValidation.className = 'validation-msg error';
            postalValidation.style.display = 'flex';
            return;
        }

        // Check if full suffix length is entered
        if (suffix.length < suffixDigits) {
            postalGroup.classList.remove('has-success', 'has-error');
            postalValidation.style.display = 'none';
            return;
        }

        // Valid â€” city prefix auto-set + user entered all remaining digits
        postalGroup.classList.remove('has-error');
        postalGroup.classList.add('has-success');
        postalValidation.innerHTML = '<i class="fas fa-check-circle"></i> ' + translations.postalValid + ' (P' + currentCityPrefix + suffix + ')';
        postalValidation.className = 'validation-msg success';
        postalValidation.style.display = 'flex';
    }

    // ==================== FORM SUBMISSION ====================
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Sync postal code hidden field
        syncPostalHidden();

        // Frontend validation check
        if (!citySelect.value) {
            cityGroup.classList.add('has-error');
            cityValidation.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + translations.selectCityFirst;
            cityValidation.className = 'validation-msg error';
            cityValidation.style.display = 'flex';
            citySelect.focus();
            return;
        }

        if (!postalInput.value || postalGroup.classList.contains('has-error')) {
            postalInput.focus();
            validatePostalCode();
            return;
        }

        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
            return;
        }

        // Show loading state on both desktop and mobile buttons
        const submitBtn = document.querySelector('.place-order-btn');
        const mobileSubmitBtn = document.getElementById('mobilePlaceOrderBtn');
        const originalText = submitBtn ? submitBtn.innerHTML : '';
        const mobileOriginalText = mobileSubmitBtn ? mobileSubmitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("messages.processing_order") }}...';
        }
        if (mobileSubmitBtn) {
            mobileSubmitBtn.disabled = true;
            mobileSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("messages.processing_order") }}...';
        }

        // Submit form via AJAX
        const formData = new FormData(checkoutForm);
        fetch(checkoutForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else if (response.status === 422) {
                // Validation errors
                return response.json().then(data => { throw data; });
            } else if (response.status === 409) {
                // Duplicate submission / stale checkout token
                return response.json().then(data => {
                    if (data.success && data.redirect) {
                        // Recent order found â€” redirect to its confirmation
                        window.location.replace(data.redirect);
                        return null;
                    }
                    throw { tokenError: true, message: data.message || '{{ __("messages.checkout_token_invalid") }}' };
                });
            } else {
                throw { message: 'Server error' };
            }
        })
        .then(data => {
            if (data && data.success && data.redirect) {
                // Use location.replace() to REPLACE checkout in browser history
                // so back button won't return to checkout page
                window.location.replace(data.redirect);
            }
        })
        .catch(errData => {
            if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalText; }
            if (mobileSubmitBtn) { mobileSubmitBtn.disabled = false; mobileSubmitBtn.innerHTML = mobileOriginalText; }
            if (errData && errData.tokenError) {
                // Checkout token expired / duplicate submission
                let errorHtml = '<div class="checkout-errors"><ul><li>' + errData.message + '</li></ul></div>';
                const existingErrors = document.querySelector('.checkout-errors');
                if (existingErrors) existingErrors.remove();
                document.querySelector('.shipping-notice').insertAdjacentHTML('afterend', errorHtml);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (errData && errData.errors) {
                let errorHtml = '<div class="checkout-errors"><ul>';
                Object.values(errData.errors).forEach(errs => {
                    errs.forEach(err => { errorHtml += '<li>' + err + '</li>'; });
                });
                errorHtml += '</ul></div>';
                const existingErrors = document.querySelector('.checkout-errors');
                if (existingErrors) existingErrors.remove();
                document.querySelector('.shipping-notice').insertAdjacentHTML('afterend', errorHtml);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                console.error('Order error:', errData);
                alert('{{ __("messages.order_error") }}');
            }
        });
    });

    // ==================== INIT (handle old() values) ====================
    if (citySelect.value) {
        citySelect.dispatchEvent(new Event('change'));
    }
    if (postalInput.value) {
        syncPostalHidden();
        validatePostalCode();
    }
});

// ==================== MOBILE: COLLAPSIBLE SUMMARY ====================
(function() {
    const toggleBtn = document.getElementById('summaryToggleBtn');
    const collapsible = document.getElementById('summaryCollapsible');
    const chevron = toggleBtn ? toggleBtn.querySelector('.summary-toggle-chevron') : null;

    if (toggleBtn && collapsible) {
        toggleBtn.addEventListener('click', function() {
            const isExpanded = collapsible.classList.contains('expanded');
            collapsible.classList.toggle('expanded');
            if (chevron) chevron.classList.toggle('open');
            toggleBtn.setAttribute('aria-expanded', !isExpanded);
        });
    }
})();

// ==================== MOBILE: STICKY BAR SUBMIT ====================
(function() {
    const mobileBtn = document.getElementById('mobilePlaceOrderBtn');
    const stickyBar = document.getElementById('mobileStickyOrderBar');
    if (mobileBtn) {
        // The button already has form="checkout-form" and type="submit",
        // so it naturally submits the form via the existing AJAX handler.
        // We just need to sync the loading state.
        mobileBtn.addEventListener('click', function() {
            // Trigger the form submit event (handled by the AJAX handler above)
        });
    }

    // Show/hide sticky bar based on scroll (hide when sidebar place-order is visible)
    if (stickyBar) {
        const isMobile = window.matchMedia('(max-width: 768px)');
        function checkStickyVisibility() {
            if (!isMobile.matches) {
                stickyBar.style.display = 'none';
            } else {
                stickyBar.style.display = 'flex';
            }
        }
        checkStickyVisibility();
        window.addEventListener('resize', checkStickyVisibility);
    }
})();

// ==================== BACK-BUTTON / BFCACHE PROTECTION ====================
// Detect if page is loaded from browser back-forward cache and force reload
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page was served from bfcache (back button)
        // Force a server reload which will check if cart is empty and redirect
        window.location.reload();
    }
});
</script>
@endsection
