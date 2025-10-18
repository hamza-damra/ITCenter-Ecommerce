@extends('layouts.app')

@section('title', __t('messages.register') . ' - IT Center')

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
        max-width: 600px;
        width: 100%;
    }

    .auth-right {
        padding: 3rem;
        max-height: 90vh;
        overflow-y: auto;
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
        border-color: #06beb6;
        box-shadow: 0 0 0 3px rgba(6, 190, 182, 0.1);
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .password-strength {
        height: 4px;
        background: #e0e0e0;
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

    .password-strength-bar.weak { width: 33%; background: #ff4757; }
    .password-strength-bar.medium { width: 66%; background: #ffa502; }
    .password-strength-bar.strong { width: 100%; background: #4CAF50; }

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
    }

    .terms-checkbox label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .terms-checkbox label a {
        color: #06beb6;
        text-decoration: none;
        font-weight: 600;
    }

    .terms-checkbox label a:hover {
        color: #48b1bf;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(6, 190, 182, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 190, 182, 0.4);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e0e0e0;
        color: #666;
        font-size: 0.95rem;
    }

    .auth-footer a {
        color: #06beb6;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .auth-footer a:hover {
        color: #48b1bf;
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

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-right">
            <div class="auth-header">
                <h3>{{ __t('messages.create_account') }}</h3>
                <p>{{ __t('messages.fill_details') }}</p>
            </div>

            @if(session('error'))
            <div class="alert alert-error" style="background: #ffe6e6; color: #ff4757; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: center;">
                {{ session('error') }}
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
                        <a href="#">{{ __t('messages.terms_conditions') }}</a>
                        {{ __t('messages.and') }}
                        <a href="#">{{ __t('messages.privacy_policy') }}</a>
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
        const inputs = form.querySelectorAll('.form-control');
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
