@extends('layouts.app')

@section('title', __t('password_reset.forgot_password') . ' - IT Center')

@section('content')
<style>
    .auth-container {
        min-height: calc(100vh - 400px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
    }

    .auth-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
    }

    .auth-right {
        padding: 3rem;
    }

    .auth-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .auth-header h3 {
        font-size: 2rem;
        color: #333;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .auth-header p {
        color: #666;
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-input-wrapper {
        position: relative;
    }

    .form-input-icon {
        position: absolute;
        {{ is_rtl() ? 'right: 15px;' : 'left: 15px;' }}
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 0.9rem 1rem;
        {{ is_rtl() ? 'padding-right: 45px;' : 'padding-left: 45px;' }}
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s;
        direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .btn-primary {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    .text-center {
        text-align: center;
    }

    .mt-3 {
        margin-top: 1.5rem;
    }

    .auth-link {
        color: #007bff;
        text-decoration: none;
        font-weight: 600;
    }

    .auth-link:hover {
        text-decoration: underline;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .info-box {
        background-color: #e7f3ff;
        border-left: 4px solid #007bff;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .info-box p {
        margin: 0;
        color: #004085;
        font-size: 0.9rem;
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-right">
            <div class="auth-header">
                <h3>{{ __t('password_reset.forgot_password') }}</h3>
                <p>{{ __t('password_reset.enter_email_instruction') }}</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="info-box">
                <p>{{ __t('password_reset.code_will_be_sent') }}</p>
            </div>

            <form method="POST" action="{{ route('password.request.post') }}">
                @csrf

                <div class="form-group">
                    <label for="email">{{ __t('password_reset.email_address') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope form-input-icon"></i>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            placeholder="{{ __t('password_reset.email_placeholder') }}"
                        >
                    </div>
                    @error('email')
                        <small style="color: #dc3545; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    {{ __t('password_reset.send_code') }}
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="auth-link">
                        {{ __t('password_reset.back_to_login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
