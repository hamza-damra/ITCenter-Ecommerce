@extends('layouts.app')

@section('title', __('messages.privacy_policy_page_title') . ' - IT Center')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    body, 
    body *:not(.fa):not(.fas):not(.far):not(.fab):not(.fal):not(.fad):not([class*="fa-"]) {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    .fa, .fas, .far, .fab, .fal, .fad, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
    }

    .page-header {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: white;
        padding: 4rem 2rem;
        text-align: center;
        margin: 1.5rem 1.5rem 3rem 1.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .page-header p {
        font-size: 1.125rem;
        opacity: 0.95;
    }

    .page-container {
        direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
        text-align: {{ is_rtl() ? 'right' : 'left' }};
        padding: 2rem 0 4rem 0;
        background: #f5f5f5;
    }

    .content-section {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 2rem;
        background: #fff;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .content-section h2 {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .content-section h2:first-child {
        margin-top: 0;
    }

    .content-section h2 i {
        color: #1f2937;
        font-size: 1.5rem;
    }

    .content-section h3 {
        font-size: 1.375rem;
        font-weight: 600;
        color: #374151;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }

    .content-section p {
        margin: 1rem 0;
        line-height: 1.8;
        color: #4b5563;
        font-size: 1.0625rem;
    }

    .content-section ul, .content-section ol {
        margin: 1rem 0;
        line-height: 1.8;
        color: #4b5563;
        list-style-position: {{ is_rtl() ? 'inside' : 'outside' }};
        padding-{{ is_rtl() ? 'right' : 'left' }}: {{ is_rtl() ? '0' : '2rem' }};
    }

    .content-section ul li, .content-section ol li {
        margin-bottom: 0.75rem;
        padding-{{ is_rtl() ? 'right' : 'left' }}: 0.5rem;
    }

    .content-section ul li::marker {
        color: #1f2937;
    }

    .empty-policy {
        text-align: center;
        padding: 3rem 2rem;
        color: #9ca3af;
    }

    .empty-policy i {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .empty-policy p {
        font-size: 1.125rem;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 3rem 1.5rem;
            margin: 1rem 1rem 2rem 1rem;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .content-section {
            padding: 2rem 1.5rem;
        }

        .content-section h2 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-shield-alt"></i> {{ __('messages.privacy_policy_page_title') }}</h1>
        <p>{{ __('messages.privacy_policy_subtitle') }}</p>
    </div>
</div>

<div class="container page-container">
    <div class="content-section">
        @if(!empty($content))
            {!! $content !!}
        @else
            <div class="empty-policy">
                <i class="fas fa-file-alt"></i>
                <p>{{ __('messages.policy_content_coming_soon') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
