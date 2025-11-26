

<?php $__env->startSection('title', 'Edit Attribute'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-header-content">
        <h1>Edit Attribute</h1>
        <p>Update attribute information</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.attributes.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Attributes
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-edit"></i> Attribute Information</h2>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.attributes.update', $attribute)); ?>" method="POST" class="form-layout">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Multi-language Names Section -->
            <div class="form-section">
                <h3 class="section-title">Attribute Names (Multi-language)</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            Name (English)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('name_en', $attribute->name_en)); ?>" 
                            placeholder="e.g., Refresh Rate"
                            required>
                        <?php $__errorArgs = ['name_en'];
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

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            Name (Arabic)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('name_ar', $attribute->name_ar)); ?>" 
                            placeholder="مثال: معدل التحديث"
                            required 
                            dir="rtl">
                        <?php $__errorArgs = ['name_ar'];
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="name_he" class="form-label">
                            Name (Hebrew)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_he" 
                            name="name_he" 
                            class="form-control <?php $__errorArgs = ['name_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('name_he', $attribute->name_he)); ?>" 
                            placeholder="לדוגמה: קצב רענון"
                            required 
                            dir="rtl">
                        <?php $__errorArgs = ['name_he'];
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

                    <div class="form-group">
                        <label for="slug" class="form-label">
                            Slug
                        </label>
                        <input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('slug', $attribute->slug)); ?>" 
                            placeholder="Auto-generated from English name">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Leave empty to auto-generate from English name
                        </p>
                        <?php $__errorArgs = ['slug'];
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
            </div>

            <!-- Attribute Configuration Section -->
            <div class="form-section">
                <h3 class="section-title">Attribute Configuration</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="type" class="form-label">
                            Type
                            <span class="required">*</span>
                        </label>
                        <select 
                            id="type" 
                            name="type" 
                            class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                            <option value="">Select Type</option>
                            <option value="select" <?php echo e(old('type', $attribute->type) == 'select' ? 'selected' : ''); ?>>Select (Single Choice)</option>
                            <option value="multi_select" <?php echo e(old('type', $attribute->type) == 'multi_select' ? 'selected' : ''); ?>>Multi-Select (Multiple Choices)</option>
                            <option value="range" <?php echo e(old('type', $attribute->type) == 'range' ? 'selected' : ''); ?>>Range (Min-Max)</option>
                            <option value="color" <?php echo e(old('type', $attribute->type) == 'color' ? 'selected' : ''); ?>>Color</option>
                        </select>
                        <?php $__errorArgs = ['type'];
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

                    <div class="form-group">
                        <label for="unit" class="form-label">
                            Unit
                        </label>
                        <input 
                            type="text" 
                            id="unit" 
                            name="unit" 
                            class="form-control <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('unit', $attribute->unit)); ?>" 
                            placeholder="e.g., Hz, GB, inches">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Optional unit of measurement (e.g., Hz, GB, inches)
                        </p>
                        <?php $__errorArgs = ['unit'];
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="order" class="form-label">
                            Display Order
                        </label>
                        <input 
                            type="number" 
                            id="order" 
                            name="order" 
                            class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('order', $attribute->order)); ?>" 
                            min="0"
                            placeholder="0">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Lower numbers appear first
                        </p>
                        <?php $__errorArgs = ['order'];
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
            </div>

            <!-- Settings Section -->
            <div class="form-section">
                <h3 class="section-title">Settings</h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_filterable" 
                            name="is_filterable" 
                            value="1" 
                            <?php echo e(old('is_filterable', $attribute->is_filterable) ? 'checked' : ''); ?>>
                        <span>
                            <strong>Filterable</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Show this attribute in filter sidebars</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            <?php echo e(old('is_active', $attribute->is_active) ? 'checked' : ''); ?>>
                        <span>
                            <strong>Active</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Make this attribute available for use</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Attribute Values Info -->
            <?php if($attribute->values->count() > 0): ?>
                <div class="form-section">
                    <h3 class="section-title">Attribute Values (<?php echo e($attribute->values->count()); ?>)</h3>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <p style="margin: 0 0 12px 0; color: #64748b; font-size: 14px;">
                            <i class="fas fa-info-circle"></i> This attribute has <?php echo e($attribute->values->count()); ?> value(s). Manage values separately.
                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php $__currentLoopData = $attribute->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span style="background: white; padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; color: #475569;">
                                    <?php echo e($value->value_en); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e2e8f0; margin-top: 24px;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Attribute
                </button>
                <a href="<?php echo e(route('admin.attributes.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\attributes\edit.blade.php ENDPATH**/ ?>