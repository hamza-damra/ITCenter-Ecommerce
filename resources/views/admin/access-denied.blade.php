@extends('admin.layout')

@section('title', __('messages.access_denied'))

@section('content')
<style>
    .access-denied-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        text-align: center;
        padding: 2rem;
    }

    .access-denied-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-rose, #f43f5e) 0%, #e11d48 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        box-shadow: 0 15px 40px rgba(244, 63, 94, 0.3);
    }

    .access-denied-icon i {
        font-size: 3rem;
        color: white;
    }

    .access-denied-container h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }

    .access-denied-container p {
        font-size: 1.0625rem;
        color: var(--secondary);
        max-width: 480px;
        margin: 0 auto 2rem;
        line-height: 1.7;
    }

    .access-denied-container .btn {
        padding: 12px 28px;
        font-size: 15px;
    }
</style>

<div class="access-denied-container">
    <div class="access-denied-icon">
        <i class="fas fa-shield-alt"></i>
    </div>
    <h1>{{ __('messages.access_denied') }}</h1>
    <p>{{ __('messages.access_denied_description') }}</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_dashboard') }}
    </a>
</div>
@endsection
