{{-- Mobile Filter Toggle Button Component --}}
{{-- Usage: <x-mobile-filter-toggle :count="$activeFilterCount" /> --}}

@props([
    'count' => 0,
])

@php
    $isRtl = is_rtl();
@endphp

<button class="mobile-filter-toggle" onclick="toggleMobileFilters()" aria-label="{{ $isRtl ? 'فتح التصفية' : 'Open filters' }}">
    <span class="mobile-filter-toggle-content">
        <i class="fas fa-filter"></i>
        <span class="mobile-filter-toggle-text">{{ $isRtl ? 'تصفية المنتجات' : 'Filter Products' }}</span>
        @if($count > 0)
            <span class="mobile-filter-badge" aria-label="{{ $isRtl ? "$count مرشحات نشطة" : "$count active filters" }}">
                {{ $count }}
            </span>
        @endif
    </span>
</button>

<style>
    .mobile-filter-toggle {
        display: none;
        width: 100%;
        padding: 1rem 1.5rem;
        background: white;
        border: 2px solid #2762f3;
        color: #2762f3;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 2px 8px rgba(39, 98, 243, 0.1);
    }

    .mobile-filter-toggle:hover {
        background: #2762f3;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.2);
    }

    .mobile-filter-toggle:active {
        transform: translateY(0);
    }

    .mobile-filter-toggle-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        position: relative;
    }

    .mobile-filter-toggle i {
        font-size: 1.1rem;
    }

    .mobile-filter-toggle-text {
        font-weight: 600;
    }

    .mobile-filter-badge {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 22px;
        height: 22px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.4rem;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        animation: badgePulse 2s ease-in-out infinite;
        flex-shrink: 0;
    }

    @keyframes badgePulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .mobile-filter-toggle:hover .mobile-filter-badge {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    @media (max-width: 1024px) {
        .mobile-filter-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .mobile-filter-toggle {
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
        }

        .mobile-filter-toggle i {
            font-size: 1rem;
        }

        .mobile-filter-badge {
            min-width: 20px;
            height: 20px;
            font-size: 0.7rem;
        }
    }
</style>
