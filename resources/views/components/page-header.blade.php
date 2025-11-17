{{-- Page Header Component --}}
{{-- Usage: <x-page-header title="Page Title" :breadcrumbs="$breadcrumbs" icon="fas fa-icon" /> --}}

@props([
    'title' => '',
    'subtitle' => '',
    'breadcrumbs' => [],
    'icon' => '',
    'background' => 'default',
    'actions' => null
])

<div class="page-header {{ $background === 'gradient' ? 'page-header-gradient' : '' }}">
    <div class="container">
        <div class="page-header-content">
            {{-- Left Side: Title and Breadcrumbs --}}
            <div class="page-header-main">
                {{-- Breadcrumbs --}}
                @if(!empty($breadcrumbs))
                    <nav class="breadcrumbs" aria-label="Breadcrumb">
                        <ol class="breadcrumb-list">
                            @foreach($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                    @if(!$loop->last && isset($breadcrumb['url']))
                                        <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-link">
                                            @if(isset($breadcrumb['icon']))
                                                <i class="{{ $breadcrumb['icon'] }}"></i>
                                            @endif
                                            {{ $breadcrumb['title'] }}
                                        </a>
                                    @else
                                        @if(isset($breadcrumb['icon']))
                                            <i class="{{ $breadcrumb['icon'] }}"></i>
                                        @endif
                                        {{ $breadcrumb['title'] }}
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                @endif

                {{-- Title Section --}}
                <div class="page-title-section">
                    @if($icon)
                        <div class="page-icon">
                            <i class="{{ $icon }}"></i>
                        </div>
                    @endif
                    
                    <div class="page-title-content">
                        <h1 class="page-title">{{ $title }}</h1>
                        @if($subtitle)
                            <p class="page-subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Side: Actions --}}
            @if($actions)
                <div class="page-header-actions">
                    {{ $actions }}
                </div>
            @endif
        </div>

        {{-- Additional Content Slot --}}
        @if(isset($slot) && !empty(trim($slot)))
            <div class="page-header-extra">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>

<style>
    .page-header {
        background: var(--bg-card);
        border-bottom: 1px solid #e2e8f0;
        padding: var(--space-8) 0;
        margin-bottom: var(--space-8);
    }

    .page-header-gradient {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        color: var(--text-white);
    }

    .page-header-gradient .page-title,
    .page-header-gradient .page-subtitle,
    .page-header-gradient .breadcrumb-link {
        color: var(--text-white);
    }

    .page-header-gradient .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.8);
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: var(--space-8);
    }

    .page-header-main {
        flex: 1;
    }

    /* Breadcrumbs */
    .breadcrumbs {
        margin-bottom: var(--space-4);
    }

    .breadcrumb-list {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: var(--text-sm);
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
        color: var(--text-secondary);
    }

    .breadcrumb-item:not(:last-child)::after {
        content: '/';
        margin: 0 var(--space-2);
        color: var(--text-muted);
        font-weight: 300;
    }

    .breadcrumb-item.active {
        color: var(--text-primary);
        font-weight: 500;
    }

    .breadcrumb-link {
        color: var(--text-secondary);
        text-decoration: none;
        transition: color var(--transition-normal);
        display: flex;
        align-items: center;
        gap: var(--space-1);
    }

    .breadcrumb-link:hover {
        color: var(--primary-blue);
    }

    /* Title Section */
    .page-title-section {
        display: flex;
        align-items: center;
        gap: var(--space-4);
    }

    .page-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light-blue) 100%);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-white);
        font-size: var(--text-2xl);
        box-shadow: var(--shadow-md);
        flex-shrink: 0;
    }

    .page-header-gradient .page-icon {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .page-title-content {
        flex: 1;
    }

    .page-title {
        font-size: var(--text-4xl);
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 var(--space-2) 0;
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: var(--text-lg);
        color: var(--text-secondary);
        margin: 0;
        line-height: 1.4;
    }

    /* Actions */
    .page-header-actions {
        display: flex;
        align-items: center;
        gap: var(--space-3);
        flex-shrink: 0;
    }

    /* Extra Content */
    .page-header-extra {
        margin-top: var(--space-6);
        padding-top: var(--space-6);
        border-top: 1px solid #e2e8f0;
    }

    .page-header-gradient .page-header-extra {
        border-top-color: rgba(255, 255, 255, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: var(--space-6) 0;
            margin-bottom: var(--space-6);
        }

        .page-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-4);
        }

        .page-title-section {
            gap: var(--space-3);
        }

        .page-icon {
            width: 50px;
            height: 50px;
            font-size: var(--text-xl);
        }

        .page-title {
            font-size: var(--text-3xl);
        }

        .page-subtitle {
            font-size: var(--text-base);
        }

        .breadcrumb-list {
            flex-wrap: wrap;
        }

        .page-header-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            padding: var(--space-4) 0;
        }

        .page-title-section {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-3);
        }

        .page-title {
            font-size: var(--text-2xl);
        }

        .page-icon {
            width: 45px;
            height: 45px;
            font-size: var(--text-lg);
        }
    }

    /* RTL Support */
    @if(is_rtl())
    .breadcrumb-item:not(:last-child)::after {
        content: '\\';
        transform: scaleX(-1);
    }

    .page-title-section {
        flex-direction: row-reverse;
    }

    .page-header-content {
        flex-direction: row-reverse;
    }
    @endif

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .page-header {
            background: #1f2937;
            border-bottom-color: #374151;
        }

        .page-title {
            color: #f9fafb;
        }

        .page-subtitle {
            color: #d1d5db;
        }

        .breadcrumb-item {
            color: #9ca3af;
        }

        .breadcrumb-item.active {
            color: #f3f4f6;
        }

        .breadcrumb-link {
            color: #9ca3af;
        }

        .breadcrumb-link:hover {
            color: #60a5fa;
        }
    }

    /* Print styles */
    @media print {
        .page-header {
            background: none !important;
            box-shadow: none !important;
            border-bottom: 2px solid #000 !important;
            padding: var(--space-4) 0 !important;
        }

        .page-header-actions {
            display: none !important;
        }

        .page-icon {
            display: none !important;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .page-header {
            border-bottom: 2px solid currentColor;
        }

        .page-icon {
            border: 2px solid currentColor;
        }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .breadcrumb-link,
        .page-icon {
            transition: none;
        }
    }
</style>
