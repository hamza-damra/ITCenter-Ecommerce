<?php $__env->startSection('hideHeader', true); ?>

<?php $__env->startSection('title', __t('password_reset.reset_password') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        <?php echo e(is_rtl() ? 'right: 15px;' : 'left: 15px;'); ?>

        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    .password-toggle {
        position: absolute;
        <?php echo e(is_rtl() ? 'left: 15px;' : 'right: 15px;'); ?>

        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #999;
        font-size: 1rem;
    }

    .password-toggle:hover {
        color: #007bff;
    }

    .form-control {
        width: 100%;
        padding: 0.9rem 1rem;
        <?php echo e(is_rtl() ? 'padding-right: 45px; padding-left: 45px;' : 'padding-left: 45px; padding-right: 45px;'); ?>

        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s;
        direction: <?php echo e(is_rtl() ? 'rtl' : 'ltr'); ?>;
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

    .password-requirements {
        background-color: #f8f9fa;
        border-left: 4px solid #28a745;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }

    .password-requirements ul {
        margin: 0.5rem 0 0 0;
        padding-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 1.5rem;
    }

    .password-requirements li {
        margin-bottom: 0.25rem;
        color: #666;
    }

    .email-display {
        background-color: #f8f9fa;
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        color: #007bff;
        margin-bottom: 1.5rem;
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-right">
            <div class="auth-header">
                <h3><?php echo e(__t('password_reset.reset_password')); ?></h3>
                <p><?php echo e(__t('password_reset.enter_new_password')); ?></p>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div class="email-display">
                <i class="fas fa-envelope"></i> <?php echo e($email); ?>

            </div>

            <div class="password-requirements">
                <strong><?php echo e(__t('password_reset.password_requirements_title')); ?></strong>
                <ul>
                    <li><?php echo e(__t('password_reset.min_8_characters')); ?></li>
                    <li><?php echo e(__t('password_reset.must_match')); ?></li>
                </ul>
            </div>

            <form method="POST" action="<?php echo e(route('password.reset.post')); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="email" value="<?php echo e($email); ?>">

                <div class="form-group">
                    <label for="password"><?php echo e(__t('password_reset.new_password')); ?></label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            required 
                            autofocus
                            minlength="8"
                            placeholder="<?php echo e(__t('password_reset.password_placeholder')); ?>"
                        >
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small style="color: #dc3545; display: block; margin-top: 0.25rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="password_confirmation"><?php echo e(__t('password_reset.confirm_password')); ?></label>
                    <div class="form-input-wrapper">
                        <i class="fas fa-lock form-input-icon"></i>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            required 
                            minlength="8"
                            placeholder="<?php echo e(__t('password_reset.confirm_password_placeholder')); ?>"
                        >
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation')"></i>
                    </div>
                    <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small style="color: #dc3545; display: block; margin-top: 0.25rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn-primary">
                    <?php echo e(__t('password_reset.reset_password_button')); ?>

                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.parentElement.querySelector('.password-toggle');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>