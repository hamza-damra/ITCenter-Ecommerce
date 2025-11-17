@extends('layouts.app')

@section('hideHeader', true)

@section('title', __('messages.login') . ' - IT Center')

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

    /* CSS Variables */
    :root {
        --primary-dark: #1f2937;
        --primary-blue: #2563eb;
        --primary-light-blue: #3b82f6;
        --secondary-red: #ef4444;
        --secondary-green: #10b981;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --text-white: #ffffff;
        --space-1: 0.25rem;
        --space-2: 0.5rem;
        --space-3: 0.75rem;
        --space-4: 1rem;
        --space-6: 1.5rem;
        --space-8: 2rem;
        --space-12: 3rem;
        --radius-lg: 16px;
        --radius-xl: 20px;
        --radius-2xl: 24px;
        --radius-full: 50px;
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.12);
        --shadow-xl: 0 12px 32px rgba(0, 0, 0, 0.15);
        --transition-normal: 0.3s ease;
        --transition-bounce: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --text-xs: 0.75rem;
        --text-sm: 0.875rem;
        --text-base: 1rem;
        --text-lg: 1.125rem;
        --text-xl: 1.25rem;
        --text-2xl: 1.5rem;
        --text-3xl: 1.875rem;
        --text-4xl: 2.25rem;
    }

    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--space-12) var(--space-8);
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        position: relative;
        overflow: hidden;
    }

    .auth-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .auth-card {
        background: var(--bg-card);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all var(--transition-bounce);
    }

    .auth-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .auth-right {
        padding: var(--space-12);
    }

    .auth-header {
        margin-bottom: var(--space-8);
        text-align: center;
        position: relative;
    }

    .auth-header::before {
        content: '';
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--primary-light-blue));
        border-radius: var(--radius-full);
    }

    .auth-header h3 {
        font-size: var(--text-4xl);
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: var(--space-2);
        font-family: 'Poppins', sans-serif;
    }

    .auth-header p {
        color: var(--text-secondary);
        font-size: var(--text-lg);
        font-weight: 400;
    }

    .form-group {
        margin-bottom: var(--space-6);
    }

    .form-group label {
        display: block;
        margin-bottom: var(--space-2);
        color: var(--text-primary);
        font-weight: 600;
        font-size: var(--text-sm);
        font-family: 'Poppins', sans-serif;
    }

    .form-input-wrapper {
        position: relative;
    }

    .form-input-icon {
        position: absolute;
        {{ is_rtl() ? 'right: 15px;' : 'left: 15px;' }}
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: var(--text-base);
        transition: color var(--transition-normal);
    }

    .form-control {
        width: 100%;
        padding: var(--space-4) var(--space-4);
        {{ is_rtl() ? 'padding-right: 45px;' : 'padding-left: 45px;' }}
        border: 2px solid #e2e8f0;
        border-radius: var(--radius-lg);
        font-size: var(--text-base);
        transition: all var(--transition-bounce);
        direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
        background: var(--bg-card);
        color: var(--text-primary);
        font-family: 'Poppins', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }

    .form-control:focus + .form-input-icon {
        color: var(--primary-blue);
    }

    .form-control.error {
        border-color: var(--secondary-red);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .error-message {
        color: var(--secondary-red);
        font-size: var(--text-sm);
        margin-top: var(--space-2);
        display: none;
        font-weight: 500;
        align-items: center;
        gap: var(--space-1);
    }

    .error-message.show {
        display: flex;
    }

    .error-message::before {
        content: '⚠';
        font-size: var(--text-xs);
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-6);
        flex-wrap: wrap;
        gap: var(--space-2);
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary-blue);
    }

    .remember-me label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: var(--text-secondary);
        font-size: var(--text-sm);
        font-family: 'Poppins', sans-serif;
    }

    .forgot-password {
        color: var(--primary-blue);
        text-decoration: none;
        font-size: var(--text-sm);
        font-weight: 600;
        transition: all var(--transition-normal);
        font-family: 'Poppins', sans-serif;
    }

    .forgot-password:hover {
        color: var(--primary-light-blue);
        transform: translateY(-1px);
    }

    .btn-submit {
        width: 100%;
        padding: var(--space-4);
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        color: var(--text-white);
        border: none;
        border-radius: var(--radius-lg);
        font-size: var(--text-lg);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-bounce);
        box-shadow: var(--shadow-md);
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light-blue) 100%);
        opacity: 0;
        transition: opacity var(--transition-normal);
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(31, 41, 55, 0.4);
    }

    .btn-submit:hover::before {
        opacity: 1;
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    .btn-submit i,
    .btn-submit span {
        position: relative;
        z-index: 1;
    }

    .auth-footer {
        text-align: center;
        margin-top: var(--space-6);
        padding-top: var(--space-6);
        border-top: 1px solid #e2e8f0;
        color: var(--text-secondary);
        font-size: var(--text-base);
        font-family: 'Poppins', sans-serif;
    }

    .auth-footer a {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
        transition: all var(--transition-normal);
        position: relative;
    }

    .auth-footer a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary-blue);
        transition: width var(--transition-normal);
    }

    .auth-footer a:hover {
        color: var(--primary-light-blue);
        transform: translateY(-1px);
    }

    .auth-footer a:hover::after {
        width: 100%;
    }

    .alert {
        padding: var(--space-4);
        border-radius: var(--radius-lg);
        margin-bottom: var(--space-6);
        text-align: center;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2);
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        color: var(--secondary-red);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--secondary-green);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .auth-container {
            padding: var(--space-8) var(--space-4);
        }

        .auth-right {
            padding: var(--space-8);
        }

        .auth-header h3 {
            font-size: var(--text-3xl);
        }

        .form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: var(--space-3);
        }

        .auth-card {
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .auth-right {
            padding: var(--space-6);
        }

        .auth-header h3 {
            font-size: var(--text-2xl);
        }

        .btn-submit {
            font-size: var(--text-base);
        }
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-right">
            <div class="auth-header">
                <h3>{{ __('messages.login') }}</h3>
                <p>{{ __('messages.enter_credentials') }}</p>
            </div>

            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email">{{ __('messages.email') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope form-input-icon"></i>
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="{{ __('messages.email_placeholder') }}"
                               value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                    <div class="error-message show">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('messages.password') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="{{ __('messages.password_placeholder') }}" required>
                    </div>
                    @error('password')
                    <div class="error-message show">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">{{ __('messages.remember_me') }}</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">{{ __('messages.forgot_password') }}</a>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>{{ __('messages.login') }}</span>
                </button>
            </form>

            <div class="auth-footer">
                {{ __('messages.dont_have_account') }}
                <a href="{{ route('register') }}">{{ __('messages.register_now') }}</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const inputs = form.querySelectorAll('.form-control');
    const submitBtn = form.querySelector('.btn-submit');

    // Add focus/blur effects
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Handle Enter key to move to next field
    inputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                
                const nextIndex = index + 1;
                
                if (nextIndex >= inputs.length) {
                    form.submit();
                } else {
                    inputs[nextIndex].focus();
                }
            }
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
