<?php $__env->startSection('title', __('messages.site_settings')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .settings-tabs {
        display: flex;
        gap: 0;
        background: white;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        border-bottom: 2px solid var(--border);
    }

    .settings-tab {
        padding: 16px 28px;
        font-size: 15px;
        font-weight: 600;
        color: var(--secondary);
        cursor: pointer;
        border: none;
        background: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        position: relative;
        font-family: inherit;
    }

    .settings-tab:hover {
        color: var(--primary);
        background: #f8fafc;
    }

    .settings-tab.active {
        color: var(--primary);
        background: #eff6ff;
    }

    .settings-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary);
        border-radius: 3px 3px 0 0;
    }

    .settings-tab i {
        font-size: 16px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .settings-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .setting-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .setting-item .form-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
    }

    .setting-item .form-hint {
        font-size: 12px;
        color: var(--secondary);
        margin-top: 2px;
    }

    .setting-item .input-with-suffix {
        display: flex;
        align-items: stretch;
    }

    .setting-item .input-with-suffix .form-control {
        border-radius: 8px 0 0 8px;
        border-right: none;
    }

    [dir="rtl"] .setting-item .input-with-suffix .form-control {
        border-radius: 0 8px 8px 0;
        border-right: 2px solid var(--border);
        border-left: none;
    }

    .setting-item .input-suffix {
        display: flex;
        align-items: center;
        padding: 0 14px;
        background: #f1f5f9;
        border: 2px solid var(--border);
        border-left: none;
        border-radius: 0 8px 8px 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--secondary);
        white-space: nowrap;
    }

    [dir="rtl"] .setting-item .input-suffix {
        border-radius: 8px 0 0 8px;
        border-left: 2px solid var(--border);
        border-right: none;
    }

    .password-requirements {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
        margin-top: 8px;
    }

    .password-requirements h4 {
        font-size: 13px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .password-requirements ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .password-requirements ul li {
        font-size: 13px;
        color: var(--secondary);
        padding: 3px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .password-requirements ul li i {
        font-size: 11px;
        color: var(--primary);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 26px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + .toggle-slider {
        background-color: var(--primary);
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(22px);
    }

    [dir="rtl"] .toggle-slider:before {
        left: auto;
        right: 3px;
    }

    [dir="rtl"] .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(-22px);
    }

    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .settings-info-box {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .settings-info-box i {
        color: var(--primary);
        font-size: 18px;
        margin-top: 2px;
    }

    .settings-info-box p {
        font-size: 14px;
        color: #1e40af;
        line-height: 1.5;
    }

    .toggle-password-btn {
        position: absolute;
        top: 50%;
        right: 12px;
        left: auto;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--secondary);
        padding: 4px;
    }

    [dir="rtl"] .toggle-password-btn {
        right: auto;
        left: 12px;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-cog"></i> <?php echo e(__('messages.site_settings')); ?></h1>
        <p><?php echo e(__('messages.manage_site_configuration')); ?></p>
    </div>
</div>

<div class="card" style="overflow: visible;">
    <!-- Tabs -->
    <div class="settings-tabs">
        <button class="settings-tab <?php echo e(request('tab', 'images') === 'images' ? 'active' : ''); ?>" onclick="switchTab('images')" type="button">
            <i class="fas fa-image"></i> <?php echo e(__('messages.image_settings')); ?>

        </button>
        <button class="settings-tab <?php echo e(request('tab') === 'password' || session('tab') === 'password' ? 'active' : ''); ?>" onclick="switchTab('password')" type="button">
            <i class="fas fa-lock"></i> <?php echo e(__('messages.change_password')); ?>

        </button>
    </div>

    <!-- Image Settings Tab -->
    <div class="tab-content <?php echo e(request('tab', 'images') === 'images' && session('tab') !== 'password' ? 'active' : ''); ?>" id="tab-images">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-info-circle"></i>
                <p><?php echo e(__('messages.image_settings_description')); ?></p>
            </div>

            <form action="<?php echo e(route('admin.site-settings.update-images')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="settings-form-grid">
                    <!-- Max Image Size -->
                    <div class="setting-item">
                        <label for="max_image_size_kb" class="form-label">
                            <?php echo e(__('messages.max_image_size')); ?>

                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_image_size_kb"
                                   name="max_image_size_kb"
                                   class="form-control <?php $__errorArgs = ['max_image_size_kb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('max_image_size_kb', $imageSettings['max_image_size_kb'])); ?>"
                                   min="256" max="20480" step="256">
                            <span class="input-suffix">KB</span>
                        </div>
                        <span class="form-hint">
                            <?php echo e(__('messages.current_value')); ?>: <?php echo e(round($imageSettings['max_image_size_kb'] / 1024, 1)); ?> MB
                            (<?php echo e(__('messages.range')); ?>: 256 KB - 20 MB)
                        </span>
                        <?php $__errorArgs = ['max_image_size_kb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Allowed Formats -->
                    <div class="setting-item">
                        <label for="allowed_image_formats" class="form-label">
                            <?php echo e(__('messages.allowed_formats')); ?>

                        </label>
                        <input type="text"
                               id="allowed_image_formats"
                               name="allowed_image_formats"
                               class="form-control <?php $__errorArgs = ['allowed_image_formats'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('allowed_image_formats', $imageSettings['allowed_image_formats'])); ?>"
                               placeholder="jpg,jpeg,png,webp">
                        <span class="form-hint"><?php echo e(__('messages.comma_separated_formats')); ?></span>
                        <?php $__errorArgs = ['allowed_image_formats'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Max Additional Images -->
                    <div class="setting-item">
                        <label for="max_additional_images" class="form-label">
                            <?php echo e(__('messages.max_additional_images_setting')); ?>

                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_additional_images"
                                   name="max_additional_images"
                                   class="form-control <?php $__errorArgs = ['max_additional_images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('max_additional_images', $imageSettings['max_additional_images'])); ?>"
                                   min="1" max="50">
                            <span class="input-suffix"><?php echo e(__('messages.files')); ?></span>
                        </div>
                        <?php $__errorArgs = ['max_additional_images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Image Quality -->
                    <div class="setting-item">
                        <label for="image_quality" class="form-label">
                            <?php echo e(__('messages.image_quality')); ?>

                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="image_quality"
                                   name="image_quality"
                                   class="form-control <?php $__errorArgs = ['image_quality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('image_quality', $imageSettings['image_quality'])); ?>"
                                   min="10" max="100">
                            <span class="input-suffix">%</span>
                        </div>
                        <span class="form-hint"><?php echo e(__('messages.quality_hint')); ?></span>
                        <?php $__errorArgs = ['image_quality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Max Width -->
                    <div class="setting-item">
                        <label for="max_image_width" class="form-label">
                            <?php echo e(__('messages.max_image_width')); ?>

                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_image_width"
                                   name="max_image_width"
                                   class="form-control <?php $__errorArgs = ['max_image_width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('max_image_width', $imageSettings['max_image_width'])); ?>"
                                   min="320" max="7680">
                            <span class="input-suffix">px</span>
                        </div>
                        <?php $__errorArgs = ['max_image_width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Max Height -->
                    <div class="setting-item">
                        <label for="max_image_height" class="form-label">
                            <?php echo e(__('messages.max_image_height')); ?>

                        </label>
                        <div class="input-with-suffix">
                            <input type="number"
                                   id="max_image_height"
                                   name="max_image_height"
                                   class="form-control <?php $__errorArgs = ['max_image_height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('max_image_height', $imageSettings['max_image_height'])); ?>"
                                   min="320" max="4320">
                            <span class="input-suffix">px</span>
                        </div>
                        <?php $__errorArgs = ['max_image_height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-message"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Convert to WebP Toggle -->
                <div style="margin-top: 20px;">
                    <div class="toggle-row">
                        <div>
                            <strong style="font-size: 14px;"><?php echo e(__('messages.convert_to_webp')); ?></strong>
                            <p style="font-size: 12px; color: var(--secondary); margin-top: 2px;"><?php echo e(__('messages.webp_description')); ?></p>
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="convert_to_webp" value="0">
                            <input type="checkbox" name="convert_to_webp" value="1" <?php echo e(old('convert_to_webp', $imageSettings['convert_to_webp']) ? 'checked' : ''); ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo e(__('messages.save_settings')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Tab -->
    <div class="tab-content <?php echo e(request('tab') === 'password' || session('tab') === 'password' ? 'active' : ''); ?>" id="tab-password">
        <div class="card-body">
            <div class="settings-info-box">
                <i class="fas fa-shield-alt"></i>
                <p><?php echo e(__('messages.password_change_description')); ?></p>
            </div>

            <form action="<?php echo e(route('admin.site-settings.change-password')); ?>" method="POST" style="max-width: 500px;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="current_password" class="form-label">
                        <?php echo e(__('messages.current_password')); ?>

                        <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required
                               autocomplete="off">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('current_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="new_password" class="form-label">
                        <?php echo e(__('messages.new_password')); ?>

                        <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password"
                               id="new_password"
                               name="new_password"
                               class="form-control <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required
                               autocomplete="off">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="new_password_confirmation" class="form-label">
                        <?php echo e(__('messages.confirm_new_password')); ?>

                        <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password"
                               id="new_password_confirmation"
                               name="new_password_confirmation"
                               class="form-control"
                               required
                               autocomplete="off">
                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password_confirmation', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="password-requirements">
                    <h4><i class="fas fa-info-circle"></i> <?php echo e(__('messages.password_requirements')); ?></h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> <?php echo e(__('messages.password_min_8')); ?></li>
                        <li><i class="fas fa-check-circle"></i> <?php echo e(__('messages.password_mixed_case')); ?></li>
                        <li><i class="fas fa-check-circle"></i> <?php echo e(__('messages.password_numbers')); ?></li>
                    </ul>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> <?php echo e(__('messages.update_password')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    // Update tab buttons
    document.querySelectorAll('.settings-tab').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');

    // Update URL without reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.replaceState({}, '', url);
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Restore active tab from URL or session
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || '<?php echo e(session("tab", "images")); ?>';
    if (tab && tab !== 'images') {
        const tabBtn = document.querySelector(`.settings-tab:nth-child(${tab === 'password' ? 2 : 1})`);
        if (tabBtn) tabBtn.click();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/site-settings/index.blade.php ENDPATH**/ ?>