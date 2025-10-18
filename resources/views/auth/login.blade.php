@extends('layouts.app')

@section('title', __t('messages.login') . ' - IT Center')

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
        max-width: 1000px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .auth-left {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .auth-left::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 15s infinite linear;
    }

    @keyframes float {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .auth-left-content {
        position: relative;
        z-index: 2;
    }

    .auth-left h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .auth-left p {
        font-size: 1.1rem;
        opacity: 0.95;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .auth-left-icon {
        font-size: 5rem;
        margin-bottom: 2rem;
        opacity: 0.9;
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
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control.error {
        border-color: #ff4757;
    }

    .error-message {
        color: #ff4757;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .remember-me label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: #666;
        font-size: 0.9rem;
    }

    .forgot-password {
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .forgot-password:hover {
        color: #764ba2;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .auth-divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
    }

    .auth-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e0e0e0;
    }

    .auth-divider span {
        background: #fff;
        padding: 0 1rem;
        color: #999;
        font-size: 0.9rem;
        position: relative;
        z-index: 1;
    }

    .social-login {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .social-btn {
        flex: 1;
        padding: 0.8rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .social-btn:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .social-btn i {
        font-size: 1.2rem;
    }

    .social-btn.google i { color: #DB4437; }
    .social-btn.facebook i { color: #4267B2; }

    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e0e0e0;
        color: #666;
        font-size: 0.95rem;
    }

    .auth-footer a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .auth-footer a:hover {
        color: #764ba2;
    }

    @media (max-width: 768px) {
        .auth-card {
            grid-template-columns: 1fr;
        }

        .auth-left {
            padding: 2rem;
            min-height: 250px;
        }

        .auth-left h2 {
            font-size: 2rem;
        }

        .auth-left-icon {
            font-size: 3rem;
        }

        .auth-right {
            padding: 2rem;
        }

        .form-options {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-left">
            <div class="auth-left-content">
                <div class="auth-left-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2>{{ __t('messages.welcome_back') }}</h2>
                <p>{{ __t('messages.login_description') }}</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h3>{{ __t('messages.login') }}</h3>
                <p>{{ __t('messages.enter_credentials') }}</p>
            </div>

            @if(session('error'))
            <div class="alert alert-error" style="background: #ffe6e6; color: #ff4757; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: center;">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success" style="background: #e6ffe6; color: #4CAF50; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: center;">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email">{{ __t('messages.email') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope form-input-icon"></i>
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="{{ __t('messages.email_placeholder') }}"
                               value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                    <div class="error-message show">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __t('messages.password') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="{{ __t('messages.password_placeholder') }}" required>
                    </div>
                    @error('password')
                    <div class="error-message show">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">{{ __t('messages.remember_me') }}</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">{{ __t('messages.forgot_password') }}</a>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> {{ __t('messages.login') }}
                </button>
            </form>

            <div class="auth-divider">
                <span>{{ __t('messages.or_continue_with') }}</span>
            </div>

            <div class="social-login">
                <button class="social-btn google">
                    <i class="fab fa-google"></i> Google
                </button>
                <button class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i> Facebook
                </button>
            </div>

            <div class="auth-footer">
                {{ __t('messages.dont_have_account') }}
                <a href="{{ route('register') }}">{{ __t('messages.register_now') }}</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const inputs = form.querySelectorAll('.form-control');

        // Add focus/blur effects
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('error');
                    isValid = false;
                } else {
                    input.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>

@endsection
