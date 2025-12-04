<?php $__env->startSection('title', __t('messages.contact_us') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
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

    /* Contact Page Styles */
    .contact-page {
        background: #f5f5f5;
        min-height: calc(100vh - 200px);
        padding: 0;
    }
    
    .page-header {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        padding: 4rem 2rem;
        text-align: center;
        color: #fff;
        margin: 1.5rem 1.5rem 3rem 1.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    
    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 0.75rem 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    
    .page-header p {
        font-size: 1.125rem;
        margin: 0;
        opacity: 0.95;
    }
    
    .contact-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem 3rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    .contact-form-section,
    .contact-info-section {
        background: #fff;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }

    .contact-form-section:hover,
    .contact-info-section:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    }
    
    .contact-form-section h2,
    .contact-info-section h2 {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 1.5rem 0;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .contact-form-section h2 i,
    .contact-info-section h2 i {
        color: #1f2937;
    }
    
    /* Form Styles */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9375rem;
    }
    
    .form-group label .required {
        color: #1f2937;
        margin-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 0.25rem;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: inherit;
        box-sizing: border-box;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #1f2937;
        box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 150px;
    }
    
    .error-text {
        color: #1f2937;
        font-size: 0.8125rem;
        margin-top: 0.5rem;
        display: block;
        font-weight: 500;
    }
    
    /* Alert Messages */
    .alert {
        padding: 1rem 1.2rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideDown 0.3s ease;
        border: 2px solid;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success {
        background: #f0fdf4;
        border-color: #86efac;
        color: #166534;
    }
    
    .alert-error {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #991b1b;
    }
    
    .alert i {
        font-size: 1.2rem;
    }
    
    /* Submit Button */
    .submit-btn {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #fff;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
        letter-spacing: 0.3px;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(31, 41, 55, 0.4);
        background: linear-gradient(135deg, #111827 0%, #000000 100%);
    }
    
    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .submit-btn i {
        font-size: 1rem;
    }
    
    /* Contact Info Cards */
    .contact-info-card {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        border-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 4px solid #1f2937;
        background: #f9fafb;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .contact-info-card:hover {
        transform: translateX(<?php echo e(is_rtl() ? '-' : ''); ?>5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        background: #fff;
    }
    
    .contact-info-card h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.75rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .contact-info-card h3 i {
        font-size: 1.25rem;
        color: #1f2937;
    }
    
    .contact-info-card p {
        margin: 0;
        color: #6b7280;
        line-height: 1.8;
        font-size: 0.9375rem;
    }
    
    /* Responsive Design */
    @media (max-width: 968px) {
        .contact-container {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 0 1.5rem;
        }
        
        .page-header {
            padding: 2.5rem 1rem;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .page-header p {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 768px) {
        .contact-page {
            padding: 1.5rem 0;
        }
        
        .contact-container {
            padding: 0 1rem;
            gap: 1.5rem;
        }
        
        .page-header {
            padding: 2rem 1rem;
            margin-bottom: 2rem;
        }
        
        .page-header h1 {
            font-size: 1.8rem;
        }
        
        .page-header p {
            font-size: 1rem;
        }
        
        .contact-form-section,
        .contact-info-section {
            padding: 1.5rem;
            border-radius: 15px;
        }
        
        .contact-form-section h2,
        .contact-info-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1.2rem;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 0.8rem;
            font-size: 0.95rem;
        }
        
        .submit-btn {
            width: 100%;
            justify-content: center;
            padding: 0.9rem 2rem;
            font-size: 1rem;
        }
        
        .contact-info-card {
            padding: 1.2rem;
            margin-bottom: 1.2rem;
        }
        
        .contact-info-card h3 {
            font-size: 1.1rem;
        }
        
        .contact-info-card p {
            font-size: 0.95rem;
        }
    }
    
    @media (max-width: 480px) {
        .contact-container {
            padding: 0 0.8rem;
        }
        
        .page-header {
            padding: 1.5rem 0.8rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .page-header p {
            font-size: 0.95rem;
        }
        
        .contact-form-section,
        .contact-info-section {
            padding: 1.2rem;
            border-radius: 12px;
        }
        
        .contact-form-section h2,
        .contact-info-section h2 {
            font-size: 1.3rem;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 0.7rem;
            font-size: 0.9rem;
        }
        
        .submit-btn {
            padding: 0.8rem 1.5rem;
            font-size: 0.95rem;
        }
        
        .contact-info-card {
            padding: 1rem;
        }
        
        .contact-info-card h3 {
            font-size: 1rem;
        }
        
        .contact-info-card h3 i {
            font-size: 1.2rem;
        }
        
        .contact-info-card p {
            font-size: 0.9rem;
        }
    }
</style>

<div class="contact-page">
    <div class="page-header">
        <div class="container">
            <h1><?php echo e(__t('messages.contact_us')); ?></h1>
            <p><?php echo e(__t('messages.get_in_touch')); ?></p>
        </div>
    </div>

    <div class="contact-container">
        <div class="contact-form-section">
            <h2>
                <i class="fas fa-envelope"></i>
                <?php echo e(__t('messages.send_us_message')); ?>

            </h2>
            
            <!-- Session Success Message -->
            <?php if(session('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
            <?php endif; ?>
            
            <!-- Session Error Message -->
            <?php if(session('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
            <?php endif; ?>
            
            <!-- Validation Errors -->
            <?php if($errors->any()): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <ul style="margin: 0; padding-<?php echo e(is_rtl() ? 'right' : 'left'); ?>: 1.5rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- AJAX Success Message -->
            <div id="success-message" class="alert alert-success" style="display: none;">
                <i class="fas fa-check-circle"></i>
                <span id="success-text"></span>
            </div>
            
            <!-- AJAX Error Message -->
            <div id="error-message" class="alert alert-error" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <span id="error-text"></span>
            </div>
            
            <form id="contact-form" action="<?php echo e(route('contact.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="name">
                        <?php echo e(__t('messages.your_name')); ?>

                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required>
                    <span id="name-error" class="error-text" style="display: none;"></span>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <?php echo e(__t('messages.your_email')); ?>

                        <span class="required">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required>
                    <span id="email-error" class="error-text" style="display: none;"></span>
                </div>
                
                <div class="form-group">
                    <label for="subject">
                        <?php echo e(__t('messages.subject')); ?>

                        <span class="required">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" value="<?php echo e(old('subject')); ?>" required>
                    <span id="subject-error" class="error-text" style="display: none;"></span>
                </div>
                
                <div class="form-group">
                    <label for="message">
                        <?php echo e(__t('messages.your_message')); ?>

                        <span class="required">*</span>
                    </label>
                    <textarea name="message" id="message" rows="5" required><?php echo e(old('message')); ?></textarea>
                    <span id="message-error" class="error-text" style="display: none;"></span>
                </div>
                
                <button type="submit" id="submit-btn" class="submit-btn">
                    <?php if(is_rtl()): ?>
                        <?php echo e(__t('messages.send_message')); ?>

                        <i class="fas fa-paper-plane"></i>
                    <?php else: ?>
                        <i class="fas fa-paper-plane"></i>
                        <?php echo e(__t('messages.send_message')); ?>

                    <?php endif; ?>
                </button>
            </form>
        </div>

        <div class="contact-info-section">
            <h2>
                <i class="fas fa-info-circle"></i>
                <?php echo e(__t('messages.contact_information')); ?>

            </h2>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo e(__t('messages.address')); ?>

                </h3>
                <p>123 Tech Street<br>Silicon Valley, CA 94025<br>United States</p>
            </div>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-phone"></i>
                    <?php echo e(__t('messages.phone')); ?>

                </h3>
                <p>+1 (555) 123-4567</p>
            </div>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-envelope"></i>
                    <?php echo e(__t('messages.email')); ?>

                </h3>
                <p>info@itcenter.com<br>support@itcenter.com</p>
            </div>
            
            <div class="contact-info-card">
                <h3>
                    <i class="fas fa-clock"></i>
                    <?php echo e(__t('messages.business_hours')); ?>

                </h3>
                <p>
                    <?php echo e(__t('messages.monday_friday')); ?><br>
                    <?php echo e(__t('messages.saturday_hours')); ?><br>
                    <?php echo e(__t('messages.sunday_closed')); ?>

                </p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const successMessage = document.getElementById('success-message');
    const successText = document.getElementById('success-text');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    const originalBtnContent = submitBtn.innerHTML;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.error-text').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';

        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__t("messages.sending") ?? "Sending..."); ?>';

        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            subject: formData.get('subject'),
            message: formData.get('message')
        };

        try {
            const response = await fetch('<?php echo e(route("api.contact.store")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                    'Accept-Language': '<?php echo e(app()->getLocale()); ?>'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                // Success
                successText.textContent = result.message;
                successMessage.style.display = 'flex';
                form.reset();
                
                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Hide success message after 5 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            } else {
                // Validation errors
                if (result.error && typeof result.error === 'object') {
                    Object.keys(result.error).forEach(key => {
                        const errorEl = document.getElementById(`${key}-error`);
                        if (errorEl && result.error[key][0]) {
                            errorEl.textContent = result.error[key][0];
                            errorEl.style.display = 'block';
                        }
                    });
                } else {
                    errorText.textContent = result.message || '<?php echo e(__t("messages.error_occurred") ?? "An error occurred"); ?>';
                    errorMessage.style.display = 'flex';
                    
                    // Scroll to error message
                    errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            errorText.textContent = '<?php echo e(__t("messages.error_occurred") ?? "An error occurred. Please try again."); ?>';
            errorMessage.style.display = 'flex';
            
            // Scroll to error message
            errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/contact.blade.php ENDPATH**/ ?>