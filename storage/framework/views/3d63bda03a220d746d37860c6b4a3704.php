<?php $__env->startSection('hideHeader', true); ?>

<?php $__env->startSection('title', __t('messages.register') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<!-- Import shared components CSS -->
<link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">

<style>
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
        max-width: 600px;
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
        <?php echo e(is_rtl() ? 'right: 15px;' : 'left: 15px;'); ?>

        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 0.9rem 1rem;
        <?php echo e(is_rtl() ? 'padding-right: 45px;' : 'padding-left: 45px;'); ?>

        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>;
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
                <h3><?php echo e(__t('messages.create_account')); ?></h3>
                <p><?php echo e(__t('messages.fill_details')); ?></p>
            </div>

            <?php if(session('error')): ?>
            <div class="alert alert-error" style="background: #ffe6e6; color: #ff4757; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: center;">
                <?php echo e(session('error')); ?>

            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('register.post')); ?>" method="POST" id="registerForm">
                <?php echo csrf_field(); ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name"><?php echo e(__t('messages.first_name')); ?></label>
                        <div class="form-input-wrapper">
                            <i class="fas fa-user form-input-icon"></i>
                            <input type="text" id="first_name" name="first_name" class="form-control"
                                   placeholder="<?php echo e(__t('messages.first_name_placeholder')); ?>"
                                   value="<?php echo e(old('first_name')); ?>" required>
                        </div>
                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message show"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="last_name"><?php echo e(__t('messages.last_name')); ?></label>
                        <div class="form-input-wrapper">
                            <i class="fas fa-user form-input-icon"></i>
                            <input type="text" id="last_name" name="last_name" class="form-control"
                                   placeholder="<?php echo e(__t('messages.last_name_placeholder')); ?>"
                                   value="<?php echo e(old('last_name')); ?>" required>
                        </div>
                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message show"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email"><?php echo e(__t('messages.email')); ?></label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-envelope form-input-icon"></i>
                        <input type="email" id="email" name="email" class="form-control"
                               placeholder="<?php echo e(__t('messages.email_placeholder')); ?>"
                               value="<?php echo e(old('email')); ?>" required>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message show"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="phone"><?php echo e(__t('messages.phone')); ?></label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-phone form-input-icon"></i>
                        <input type="tel" id="phone" name="phone" class="form-control"
                               placeholder="<?php echo e(__t('messages.phone_placeholder')); ?>"
                               value="<?php echo e(old('phone')); ?>">
                    </div>
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message show"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="password"><?php echo e(__t('messages.password')); ?></label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="<?php echo e(__t('messages.password_placeholder')); ?>" required>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message show"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="password_confirmation"><?php echo e(__t('messages.confirm_password')); ?></label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                               placeholder="<?php echo e(__t('messages.confirm_password_placeholder')); ?>" required>
                    </div>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        <?php echo e(__t('messages.agree_to')); ?>

                        <a href="#"><?php echo e(__t('messages.terms_conditions')); ?></a>
                        <?php echo e(__t('messages.and')); ?>

                        <a href="#"><?php echo e(__t('messages.privacy_policy')); ?></a>
                    </label>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-user-plus"></i> <?php echo e(__t('messages.create_account')); ?>

                </button>
            </form>

            <div class="auth-footer">
                <?php echo e(__t('messages.already_have_account')); ?>

                <a href="<?php echo e(route('login')); ?>"><?php echo e(__t('messages.login_here')); ?></a>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\auth\register.blade.php ENDPATH**/ ?>