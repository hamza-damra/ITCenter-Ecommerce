

<?php $__env->startSection('title', 'Add New Value - ' . $attribute->name_en); ?>

<?php $__env->startSection('content'); ?>
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        padding: 30px;
        max-width: 800px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    .form-group label .required {
        color: #dc2626;
        margin-left: 4px;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="color"] {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-group .help-text {
        font-size: 12px;
        color: var(--secondary);
        margin-top: 6px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: var(--secondary);
    }

    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb i {
        font-size: 10px;
    }

    .attribute-info {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid var(--border);
    }

    .attribute-info h2 {
        margin: 0 0 8px 0;
        color: var(--dark);
        font-size: 18px;
    }

    .attribute-info .meta {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: var(--secondary);
    }

    .color-preview-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .color-preview-box {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        border: 2px solid var(--border);
    }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="<?php echo e(route('admin.attributes.index')); ?>">
        <i class="fas fa-tags"></i> Attributes
    </a>
    <i class="fas fa-chevron-right"></i>
    <a href="<?php echo e(route('admin.attribute-values.index', $attribute)); ?>">
        <?php echo e($attribute->name_en); ?> Values
    </a>
    <i class="fas fa-chevron-right"></i>
    <span>Add New Value</span>
</div>

<!-- Attribute Info -->
<div class="attribute-info">
    <h2>Adding value to: <?php echo e($attribute->name_en); ?></h2>
    <div class="meta">
        <span><strong>Type:</strong> <?php echo e(str_replace('_', ' ', ucfirst($attribute->type))); ?></span>
        <?php if($attribute->unit): ?>
            <span><strong>Unit:</strong> <?php echo e($attribute->unit); ?></span>
        <?php endif; ?>
    </div>
</div>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1>Add New Value</h1>
        <p>Create a new value for <?php echo e($attribute->name_en); ?></p>
    </div>
</div>

<!-- Form -->
<div class="form-container">
    <form action="<?php echo e(route('admin.attribute-values.store', $attribute)); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <!-- English Value -->
        <div class="form-group">
            <label for="value_en">
                Value (English)
                <span class="required">*</span>
            </label>
            <input type="text" id="value_en" name="value_en" value="<?php echo e(old('value_en')); ?>" required>
            <?php $__errorArgs = ['value_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="help-text" style="color: #dc2626;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Arabic Value -->
        <div class="form-group">
            <label for="value_ar">
                Value (Arabic)
                <span class="required">*</span>
            </label>
            <input type="text" id="value_ar" name="value_ar" value="<?php echo e(old('value_ar')); ?>" required dir="rtl">
            <?php $__errorArgs = ['value_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="help-text" style="color: #dc2626;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Hebrew Value -->
        <div class="form-group">
            <label for="value_he">
                Value (Hebrew)
                <span class="required">*</span>
            </label>
            <input type="text" id="value_he" name="value_he" value="<?php echo e(old('value_he')); ?>" required dir="rtl">
            <?php $__errorArgs = ['value_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="help-text" style="color: #dc2626;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Slug -->
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="<?php echo e(old('slug')); ?>">
            <div class="help-text">Leave empty to auto-generate from English value</div>
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="help-text" style="color: #dc2626;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Color Code (only for color type attributes) -->
        <?php if($attribute->type === 'color'): ?>
            <div class="form-group">
                <label for="color_code">Color Code</label>
                <div class="color-preview-container">
                    <input type="color" id="color_code" name="color_code" value="<?php echo e(old('color_code', '#000000')); ?>" style="width: 80px; height: 50px; cursor: pointer;">
                    <input type="text" id="color_code_text" value="<?php echo e(old('color_code', '#000000')); ?>" style="width: 120px;" readonly>
                </div>
                <div class="help-text">Select a color for this value</div>
                <?php $__errorArgs = ['color_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="help-text" style="color: #dc2626;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        <?php endif; ?>

        <!-- Order -->
        <div class="form-group">
            <label for="order">Display Order</label>
            <input type="number" id="order" name="order" value="<?php echo e(old('order', 0)); ?>" min="0">
            <div class="help-text">Lower numbers appear first</div>
            <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="help-text" style="color: #dc2626;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Is Active -->
        <div class="form-group">
            <div class="checkbox-group">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                <label for="is_active">Active</label>
            </div>
            <div class="help-text">Inactive values won't appear in filters</div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Create Value
            </button>
            <a href="<?php echo e(route('admin.attribute-values.index', $attribute)); ?>" class="btn" style="background: #e5e7eb; color: #374151;">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<?php if($attribute->type === 'color'): ?>
<script>
    // Sync color picker with text input
    const colorPicker = document.getElementById('color_code');
    const colorText = document.getElementById('color_code_text');

    colorPicker.addEventListener('input', function() {
        colorText.value = this.value;
    });

    colorText.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            colorPicker.value = this.value;
        }
    });
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\attribute-values\create.blade.php ENDPATH**/ ?>