{{-- Button Component --}}
{{-- Usage: <x-button variant="primary" size="md" icon="fas fa-plus">Button Text</x-button> --}}

@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => '',
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'href' => '',
    'type' => 'button',
    'rounded' => false,
    'outline' => false,
    'block' => false
])

@php
    $tag = $href ? 'a' : 'button';
    $classes = [
        'btn',
        'btn-' . $variant,
        'btn-' . $size,
        $rounded ? 'btn-rounded' : '',
        $outline ? 'btn-outline' : '',
        $block ? 'btn-block' : '',
        $loading ? 'btn-loading' : '',
        $disabled ? 'btn-disabled' : ''
    ];
    $classes = array_filter($classes);
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if(!$href) type="{{ $type }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => implode(' ', $classes)]) }}
    @if($loading) aria-busy="true" @endif
>
    @if($loading)
        <span class="btn-spinner">
            <i class="fas fa-spinner fa-spin"></i>
        </span>
    @endif

    @if($icon && $iconPosition === 'left' && !$loading)
        <i class="{{ $icon }} btn-icon btn-icon-left"></i>
    @endif

    <span class="btn-text">{{ $slot }}</span>

    @if($icon && $iconPosition === 'right' && !$loading)
        <i class="{{ $icon }} btn-icon btn-icon-right"></i>
    @endif
</{{ $tag }}>

<style>
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
        font-weight: 600;
        text-decoration: none;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all var(--transition-bounce);
        white-space: nowrap;
        position: relative;
        overflow: hidden;
        font-family: inherit;
        line-height: 1;
        user-select: none;
        vertical-align: middle;
    }

    .btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn:disabled,
    .btn.btn-disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Button Variants */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-gray) 100%);
        color: var(--text-white);
        border-color: var(--primary-dark);
    }

    .btn-primary:hover:not(:disabled):not(.btn-disabled) {
        background: linear-gradient(135deg, var(--primary-gray) 0%, #000000 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(31, 41, 55, 0.3);
    }

    .btn-secondary {
        background: var(--bg-card);
        color: var(--text-primary);
        border-color: #e2e8f0;
    }

    .btn-secondary:hover:not(:disabled):not(.btn-disabled) {
        background: #f8fafc;
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        transform: translateY(-1px);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--secondary-green) 0%, #059669 100%);
        color: var(--text-white);
        border-color: var(--secondary-green);
    }

    .btn-success:hover:not(:disabled):not(.btn-disabled) {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--secondary-red) 0%, #dc2626 100%);
        color: var(--text-white);
        border-color: var(--secondary-red);
    }

    .btn-danger:hover:not(:disabled):not(.btn-disabled) {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--secondary-yellow) 0%, #d97706 100%);
        color: var(--text-white);
        border-color: var(--secondary-yellow);
    }

    .btn-warning:hover:not(:disabled):not(.btn-disabled) {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    }

    .btn-info {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light-blue) 100%);
        color: var(--text-white);
        border-color: var(--primary-blue);
    }

    .btn-info:hover:not(:disabled):not(.btn-disabled) {
        background: linear-gradient(135deg, var(--primary-light-blue) 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
    }

    .btn-light {
        background: #f8fafc;
        color: var(--text-primary);
        border-color: #e2e8f0;
    }

    .btn-light:hover:not(:disabled):not(.btn-disabled) {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .btn-dark {
        background: var(--primary-dark);
        color: var(--text-white);
        border-color: var(--primary-dark);
    }

    .btn-dark:hover:not(:disabled):not(.btn-disabled) {
        background: var(--primary-gray);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(31, 41, 55, 0.3);
    }

    /* Outline Variants */
    .btn-outline.btn-primary {
        background: transparent;
        color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .btn-outline.btn-primary:hover:not(:disabled):not(.btn-disabled) {
        background: var(--primary-dark);
        color: var(--text-white);
    }

    .btn-outline.btn-secondary {
        background: transparent;
        color: var(--text-secondary);
        border-color: #cbd5e1;
    }

    .btn-outline.btn-secondary:hover:not(:disabled):not(.btn-disabled) {
        background: #f8fafc;
        color: var(--text-primary);
        border-color: var(--primary-blue);
    }

    .btn-outline.btn-success {
        background: transparent;
        color: var(--secondary-green);
        border-color: var(--secondary-green);
    }

    .btn-outline.btn-success:hover:not(:disabled):not(.btn-disabled) {
        background: var(--secondary-green);
        color: var(--text-white);
    }

    .btn-outline.btn-danger {
        background: transparent;
        color: var(--secondary-red);
        border-color: var(--secondary-red);
    }

    .btn-outline.btn-danger:hover:not(:disabled):not(.btn-disabled) {
        background: var(--secondary-red);
        color: var(--text-white);
    }

    .btn-outline.btn-warning {
        background: transparent;
        color: var(--secondary-yellow);
        border-color: var(--secondary-yellow);
    }

    .btn-outline.btn-warning:hover:not(:disabled):not(.btn-disabled) {
        background: var(--secondary-yellow);
        color: var(--text-white);
    }

    .btn-outline.btn-info {
        background: transparent;
        color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .btn-outline.btn-info:hover:not(:disabled):not(.btn-disabled) {
        background: var(--primary-blue);
        color: var(--text-white);
    }

    /* Button Sizes */
    .btn-xs {
        padding: var(--space-1) var(--space-3);
        font-size: var(--text-xs);
        border-radius: var(--radius-sm);
    }

    .btn-sm {
        padding: var(--space-2) var(--space-4);
        font-size: var(--text-sm);
        border-radius: var(--radius-md);
    }

    .btn-md {
        padding: var(--space-3) var(--space-6);
        font-size: var(--text-base);
        border-radius: var(--radius-md);
    }

    .btn-lg {
        padding: var(--space-4) var(--space-8);
        font-size: var(--text-lg);
        border-radius: var(--radius-lg);
    }

    .btn-xl {
        padding: var(--space-5) var(--space-10);
        font-size: var(--text-xl);
        border-radius: var(--radius-lg);
    }

    /* Button Shapes */
    .btn-rounded {
        border-radius: var(--radius-full);
    }

    /* Block Button */
    .btn-block {
        width: 100%;
        justify-content: center;
    }

    /* Loading State */
    .btn-loading {
        pointer-events: none;
    }

    .btn-spinner {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .btn-loading .btn-text,
    .btn-loading .btn-icon {
        opacity: 0;
    }

    /* Icons */
    .btn-icon {
        font-size: 0.9em;
        line-height: 1;
    }

    .btn-icon-left {
        margin-{{ is_rtl() ? 'left' : 'right' }}: var(--space-1);
        margin-{{ is_rtl() ? 'right' : 'left' }}: calc(var(--space-1) * -1);
    }

    .btn-icon-right {
        margin-{{ is_rtl() ? 'right' : 'left' }}: var(--space-1);
        margin-{{ is_rtl() ? 'left' : 'right' }}: calc(var(--space-1) * -1);
    }

    /* Icon-only buttons */
    .btn:not(:has(.btn-text:not(:empty))) {
        aspect-ratio: 1;
        padding: var(--space-3);
    }

    .btn-sm:not(:has(.btn-text:not(:empty))) {
        padding: var(--space-2);
    }

    .btn-lg:not(:has(.btn-text:not(:empty))) {
        padding: var(--space-4);
    }

    .btn-xl:not(:has(.btn-text:not(:empty))) {
        padding: var(--space-5);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .btn-lg {
            padding: var(--space-3) var(--space-6);
            font-size: var(--text-base);
        }

        .btn-xl {
            padding: var(--space-4) var(--space-8);
            font-size: var(--text-lg);
        }

        .btn-block {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .btn {
            min-height: 44px; /* Minimum touch target */
        }

        .btn-sm {
            min-height: 40px;
        }

        .btn-xs {
            min-height: 36px;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .btn {
            border-width: 2px;
            border-style: solid;
        }

        .btn:focus {
            outline: 3px solid currentColor;
            outline-offset: 2px;
        }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .btn {
            transition: none;
        }

        .btn:hover {
            transform: none;
        }

        .btn-spinner i {
            animation: none;
        }
    }

    /* Print styles */
    @media print {
        .btn {
            background: none !important;
            color: #000 !important;
            border: 2px solid #000 !important;
            box-shadow: none !important;
            transform: none !important;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .btn-secondary {
            background: #374151;
            color: #f9fafb;
            border-color: #4b5563;
        }

        .btn-secondary:hover:not(:disabled):not(.btn-disabled) {
            background: #4b5563;
            border-color: #60a5fa;
            color: #60a5fa;
        }

        .btn-light {
            background: #374151;
            color: #f9fafb;
            border-color: #4b5563;
        }

        .btn-light:hover:not(:disabled):not(.btn-disabled) {
            background: #4b5563;
            border-color: #6b7280;
        }
    }
</style>
