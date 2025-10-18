@extends('layouts.app')

@section('title', __('messages.about') . ' - IT Center')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        font-size: 1.1rem;
        opacity: 0.95;
    }

    .page-container {
        direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
        text-align: {{ is_rtl() ? 'right' : 'left' }};
        padding: 2rem 0 4rem 0;
    }

    .content-section {
        max-width: 800px;
        margin: 0 auto;
    }

    .content-section h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .content-section h2:first-child {
        margin-top: 0;
    }

    .content-section p {
        margin: 1rem 0;
        line-height: 1.8;
        color: #555;
        font-size: 1rem;
    }

    .content-section ul {
        margin: 1rem 0;
        line-height: 1.8;
        color: #555;
        list-style-position: {{ is_rtl() ? 'inside' : 'outside' }};
        padding-{{ is_rtl() ? 'right' : 'left' }}: {{ is_rtl() ? '0' : '2rem' }};
    }

    .content-section ul li {
        margin-bottom: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .stat-card {
        text-align: center;
        padding: 1.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.3s;
        background: #fff;
    }

    .stat-card:hover {
        border-color: #4169E1;
        box-shadow: 0 4px 15px rgba(65, 105, 225, 0.2);
        transform: translateY(-5px);
    }

    .stat-card h3 {
        color: #4169E1;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #666;
        font-size: 1rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2rem;
        }

        .content-section h2 {
            font-size: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <div class="container">
        <h1>{{ __('messages.about_us_title') }}</h1>
        <p>{{ __('messages.about_us_subtitle') }}</p>
    </div>
</div>

<div class="container page-container">
    <div class="content-section">
        <h2>{{ __('messages.who_we_are') }}</h2>
        <p>
            {{ __('messages.who_we_are_text') }}
        </p>

        <h2>{{ __('messages.our_mission') }}</h2>
        <p>
            {{ __('messages.our_mission_text') }}
        </p>

        <h2>{{ __('messages.what_we_offer') }}</h2>
        <ul>
            <li>{{ __('messages.offer_computers') }}</li>
            <li>{{ __('messages.offer_components') }}</li>
            <li>{{ __('messages.offer_networking') }}</li>
            <li>{{ __('messages.offer_software') }}</li>
            <li>{{ __('messages.offer_support') }}</li>
            <li>{{ __('messages.offer_custom_pc') }}</li>
        </ul>

        <h2>{{ __('messages.why_choose_us') }}</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>{{ __('messages.years_experience') }}</h3>
                <p>{{ __('messages.experience') }}</p>
            </div>
            <div class="stat-card">
                <h3>{{ __('messages.happy_customers_count') }}</h3>
                <p>{{ __('messages.happy_customers') }}</p>
            </div>
            <div class="stat-card">
                <h3>{{ __('messages.support_24_7') }}</h3>
                <p>{{ __('messages.support') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
