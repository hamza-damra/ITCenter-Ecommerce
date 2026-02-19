@extends('layouts.app')

@section('hideHeader', true)

@section('title', __t('messages.register') . ' - IT Center')

@section('content')
<!-- Import shared components CSS -->
<link rel="stylesheet" href="{{ asset('css/components.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<style>
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
        background: linear-gradient(180deg, #e8f0fe 0%, #d4e4fc 30%, #bdd4f8 60%, #a8c5f4 100%);
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
        background: 
            radial-gradient(ellipse 80% 50% at 20% 40%, rgba(147, 197, 253, 0.5) 0%, transparent 60%),
            radial-gradient(ellipse 60% 40% at 80% 20%, rgba(191, 219, 254, 0.6) 0%, transparent 50%),
            radial-gradient(ellipse 70% 50% at 50% 80%, rgba(165, 180, 252, 0.3) 0%, transparent 60%);
        pointer-events: none;
    }

    .auth-container::after {
        content: '';
        position: absolute;
        top: -10%;
        right: -5%;
        width: 40%;
        height: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .auth-card {
        background: var(--bg-card);
        border-radius: var(--radius-2xl);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        max-width: 600px;
        width: 100%;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all var(--transition-bounce);
    }

    .auth-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .auth-right {
        padding: var(--space-12);
        max-height: 90vh;
        overflow-y: auto;
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
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }

    .form-control:focus + .form-input-icon {
        color: var(--primary-blue);
    }

    .form-control.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .error-message {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: none;
        font-weight: 500;
    }

    .error-message.show {
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .password-strength {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        margin-top: 0.5rem;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0;
        transition: all 0.3s;
        border-radius: 2px;
    }

    .password-strength-bar.weak { width: 33%; background: #ef4444; }
    .password-strength-bar.medium { width: 66%; background: #f59e0b; }
    .password-strength-bar.strong { width: 100%; background: #10b981; }

    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .terms-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-top: 2px;
        cursor: pointer;
        flex-shrink: 0;
        accent-color: var(--primary-blue);
    }

    .terms-checkbox label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .terms-checkbox label a {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
    }

    .terms-checkbox label a:hover {
        color: var(--primary-light-blue);
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
        color: #fff;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-bounce);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
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
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
    }

    .btn-submit:hover::before {
        opacity: 1;
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-submit i,
    .btn-submit span {
        position: relative;
        z-index: 1;
    }

    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        color: var(--text-secondary);
        font-size: 0.95rem;
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
    }

    .auth-footer a:hover::after {
        width: 100%;
    }

    @media (max-width: 768px) {
        .auth-right {
            padding: 2rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="auth-container" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
    <div class="auth-card">
        <div class="auth-right">
            <div class="auth-header">
                <h3>{{ __t('messages.create_account') }}</h3>
                <p>{{ __t('messages.fill_details') }}</p>
            </div>

            @if(session('error'))
            <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; text-align: center; border: 1px solid rgba(239, 68, 68, 0.2); display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; text-align: center; border: 1px solid rgba(16, 185, 129, 0.2); display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" id="registerForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">{{ __t('messages.first_name') }}</label>
                        <div class="form-input-wrapper">
                            <i class="fas fa-user form-input-icon"></i>
                            <input type="text" id="first_name" name="first_name" class="form-control"
                                   placeholder="{{ __t('messages.first_name_placeholder') }}"
                                   value="{{ old('first_name') }}" required>
                        </div>
                        @error('first_name')
                        <div class="error-message show">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name">{{ __t('messages.last_name') }}</label>
                        <div class="form-input-wrapper">
                            <i class="fas fa-user form-input-icon"></i>
                            <input type="text" id="last_name" name="last_name" class="form-control"
                                   placeholder="{{ __t('messages.last_name_placeholder') }}"
                                   value="{{ old('last_name') }}" required>
                        </div>
                        @error('last_name')
                        <div class="error-message show">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

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
                    <label for="phone">{{ __t('messages.phone') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-phone form-input-icon"></i>
                        <input type="tel" id="phone" name="phone" class="form-control"
                               placeholder="{{ __t('messages.phone_placeholder') }}"
                               value="{{ old('phone') }}">
                    </div>
                    @error('phone')
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
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    @error('password')
                    <div class="error-message show">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">{{ __t('messages.confirm_password') }}</label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                               placeholder="{{ __t('messages.confirm_password_placeholder') }}" required>
                    </div>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        {{ __t('messages.agree_to') }}
                        <a href="{{ route('refund-policy') }}" target="_blank">{{ __t('messages.refund_policy_tab') }}</a>
                        {{ __t('messages.and') }}
                        <a href="{{ route('privacy-policy') }}" target="_blank">{{ __t('messages.privacy_policy') }}</a>
                    </label>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-user-plus"></i> {{ __t('messages.create_account') }}
                </button>
            </form>

            <div class="auth-footer">
                {{ __t('messages.already_have_account') }}
                <a href="{{ route('login') }}">{{ __t('messages.login_here') }}</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const strengthBar = document.getElementById('passwordStrengthBar');
        const submitBtn = document.getElementById('submitBtn');
        const termsCheckbox = document.getElementById('terms');

        // Get all input fields (excluding checkbox)
        const inputs = form.querySelectorAll('.form-control');
        const inputArray = Array.from(inputs);

        // Handle Enter key to move to next field
        inputs.forEach((input, index) => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    
                    const nextIndex = index + 1;
                    
                    // If this is the last input field, focus on terms checkbox
                    if (nextIndex >= inputArray.length) {
                        termsCheckbox.focus();
                    } else {
                        // Move to next input field
                        inputArray[nextIndex].focus();
                    }
                }
            });
        });

        // Handle Enter key on terms checkbox to submit
        termsCheckbox.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.checked && !submitBtn.disabled) {
                    form.submit();
                } else if (!this.checked) {
                    this.checked = true;
                    this.dispatchEvent(new Event('change'));
                }
            }
        });

        // Password strength checker
        password.addEventListener('input', function() {
            const value = this.value;
            let strength = 0;

            if (value.length >= 8) strength++;
            if (value.match(/[a-z]/) && value.match(/[A-Z]/)) strength++;
            if (value.match(/[0-9]/)) strength++;
            if (value.match(/[^a-zA-Z0-9]/)) strength++;

            strengthBar.className = 'password-strength-bar';
            if (strength === 0) {
                strengthBar.style.width = '0';
            } else if (strength <= 2) {
                strengthBar.classList.add('weak');
            } else if (strength === 3) {
                strengthBar.classList.add('medium');
            } else {
                strengthBar.classList.add('strong');
            }
        });

        // Password match checker
        passwordConfirmation.addEventListener('input', function() {
            if (this.value !== password.value) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Check if passwords match
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.classList.add('error');
                isValid = false;
            }

            // Check terms acceptance
            if (!termsCheckbox.checked) {
                termsCheckbox.parentElement.style.color = '#ff4757';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Enable/disable submit button based on terms
        termsCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
            if (this.checked) {
                this.parentElement.style.color = '';
            }
        });

        // Add focus/blur effects
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    });
</script>

@endsection
