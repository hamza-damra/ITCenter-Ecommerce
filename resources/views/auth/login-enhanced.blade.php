@extends('layouts.app')

@section('hideHeader', true)

@section('title', __('messages.login') . ' - IT Center')

@section('content')
<!-- Import shared components CSS -->
<link rel="stylesheet" href="{{ asset('css/components.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-right">
            <div class="auth-header">
                <h3>{{ __('messages.welcome_back') }}</h3>
                <p>{{ __('messages.sign_in_to_continue') }}</p>
            </div>

            {{-- Alert Messages --}}
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

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf

                {{-- Email Field --}}
                <div class="form-group">
                    <label for="email">{{ __('messages.email_address') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope form-input-icon"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control @error('email') error @enderror"
                               placeholder="{{ __('messages.enter_email') }}"
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               autofocus>
                    </div>
                    @error('email')
                        <div class="error-message show">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="form-group">
                    <label for="password">{{ __('messages.password') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control @error('password') error @enderror"
                               placeholder="{{ __('messages.enter_password') }}" 
                               required 
                               autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message show">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Form Options --}}
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">{{ __('messages.remember_me') }}</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">
                        {{ __('messages.forgot_password') }}
                    </a>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>{{ __('messages.sign_in') }}</span>
                </button>
            </form>

            {{-- Social Login (Optional) --}}
            @if(config('services.google.client_id') || config('services.facebook.client_id'))
                <div class="social-login">
                    <div class="social-divider">{{ __('messages.or_continue_with') }}</div>
                    
                    @if(config('services.google.client_id'))
                        <a href="{{ route('auth.google') }}" class="social-btn google">
                            <i class="fab fa-google"></i>
                            {{ __('messages.continue_with_google') }}
                        </a>
                    @endif
                    
                    @if(config('services.facebook.client_id'))
                        <a href="{{ route('auth.facebook') }}" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                            {{ __('messages.continue_with_facebook') }}
                        </a>
                    @endif
                </div>
            @endif

            {{-- Footer --}}
            <div class="auth-footer">
                {{ __('messages.dont_have_account') }}
                <a href="{{ route('register') }}">{{ __('messages.create_account') }}</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Password Toggle Button */
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0;
        font-size: var(--text-base);
        transition: color var(--transition-normal);
        z-index: 2;
    }

    [dir="rtl"] .password-toggle {
        left: 15px;
        right: auto;
    }

    .password-toggle:hover {
        color: var(--primary-blue);
    }

    .form-input-wrapper:has(.password-toggle) .form-control {
        padding-right: 45px;
    }

    [dir="rtl"] .form-input-wrapper:has(.password-toggle) .form-control {
        padding-left: 45px;
        padding-right: var(--space-4);
    }

    /* Enhanced Form Animations */
    .form-group {
        position: relative;
    }

    .form-control:focus + .form-input-icon {
        color: var(--primary-blue);
        transform: translateY(-50%) scale(1.1);
    }

    /* Loading Animation for Submit Button */
    .btn-submit.loading span {
        opacity: 0;
    }

    .btn-submit.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Enhanced Alert Animations */
    .alert {
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Focus Ring for Better Accessibility */
    .form-control:focus,
    .btn-submit:focus,
    .remember-me input:focus,
    .forgot-password:focus,
    .auth-footer a:focus {
        outline: 2px solid var(--primary-blue);
        outline-offset: 2px;
    }

    /* High Contrast Mode Support */
    @media (prefers-contrast: high) {
        .auth-card {
            border: 2px solid currentColor;
        }
        
        .form-control {
            border-width: 2px;
        }
        
        .btn-submit {
            border: 2px solid currentColor;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const inputs = form.querySelectorAll('.form-control');
    const submitBtn = document.getElementById('submitBtn');

    // Enhanced form validation
    function validateForm() {
        let isValid = true;
        
        inputs.forEach(input => {
            const errorMsg = input.parentElement.parentElement.querySelector('.error-message');
            
            if (!input.value.trim()) {
                input.classList.add('error');
                if (errorMsg && !errorMsg.classList.contains('show')) {
                    errorMsg.textContent = `${input.previousElementSibling.textContent} is required`;
                    errorMsg.classList.add('show');
                }
                isValid = false;
            } else {
                input.classList.remove('error');
                if (errorMsg && errorMsg.classList.contains('show') && !errorMsg.textContent.includes('{{')) {
                    errorMsg.classList.remove('show');
                }
            }
        });

        // Email validation
        const emailInput = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailInput.value && !emailRegex.test(emailInput.value)) {
            emailInput.classList.add('error');
            const errorMsg = emailInput.parentElement.parentElement.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.textContent = 'Please enter a valid email address';
                errorMsg.classList.add('show');
            }
            isValid = false;
        }

        return isValid;
    }

    // Real-time validation
    inputs.forEach(input => {
        input.addEventListener('blur', validateForm);
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateForm();
            }
        });

        // Enhanced focus effects
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return;
        }

        // Add loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        
        // Re-enable after 10 seconds as fallback
        setTimeout(() => {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }, 10000);
    });

    // Enter key navigation
    inputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                
                const nextIndex = index + 1;
                if (nextIndex >= inputs.length) {
                    if (validateForm()) {
                        form.submit();
                    }
                } else {
                    inputs[nextIndex].focus();
                }
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Password visibility toggle
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

// Keyboard accessibility
document.addEventListener('keydown', function(e) {
    // ESC key to close alerts
    if (e.key === 'Escape') {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        });
    }
});
</script>

@endsection
