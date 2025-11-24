<?php $__env->startSection('title', __('messages.edit_brand')); ?>

<?php $__env->startSection('content'); ?>
<div class="top-bar">
    <h1><?php echo e(__('messages.edit_brand')); ?>: <?php echo e($brand->name); ?></h1>
    <a href="<?php echo e(route('admin.brands.index')); ?>" class="btn btn-primary">← <?php echo e(__('messages.back_to_brands')); ?></a>
</div>

<div class="content-box">
    <form action="<?php echo e(route('admin.brands.update', $brand)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="form-group">
            <label for="name_en"><?php echo e(__('messages.brand_name_english')); ?> *</label>
            <input type="text" id="name_en" name="name_en" class="form-control" value="<?php echo e(old('name_en', $brand->name_en)); ?>" required>
            <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="name_ar"><?php echo e(__('messages.brand_name_arabic')); ?> *</label>
            <input type="text" id="name_ar" name="name_ar" class="form-control" value="<?php echo e(old('name_ar', $brand->name_ar)); ?>" required dir="rtl">
            <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="logo"><?php echo e(__('messages.logo_url')); ?></label>
            <input type="url" id="logo" name="logo" class="form-control" value="<?php echo e(old('logo', $brand->logo)); ?>">
            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php if($brand->logo): ?>
                <img src="<?php echo e($brand->logo); ?>" alt="<?php echo e(__('messages.current_logo')); ?>" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="website"><?php echo e(__('messages.website_url')); ?></label>
            <input type="url" id="website" name="website" class="form-control" value="<?php echo e(old('website', $brand->website)); ?>">
            <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="description_en"><?php echo e(__('messages.brand_description_english')); ?></label>
            <textarea id="description_en" name="description_en" class="form-control"><?php echo e(old('description_en', $brand->description_en)); ?></textarea>
            <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="description_ar"><?php echo e(__('messages.brand_description_arabic')); ?></label>
            <textarea id="description_ar" name="description_ar" class="form-control" dir="rtl"><?php echo e(old('description_ar', $brand->description_ar)); ?></textarea>
            <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="color: red;"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', $brand->is_active) ? 'checked' : ''); ?>>
            <label for="is_active"><?php echo e(__('messages.active')); ?></label>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo e(old('is_featured', $brand->is_featured) ? 'checked' : ''); ?>>
            <label for="is_featured"><?php echo e(__('messages.featured')); ?></label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success"><?php echo e(__('messages.update_brand')); ?></button>
            <a href="<?php echo e(route('admin.brands.index')); ?>" class="btn" style="background: #95a5a6; color: white;"><?php echo e(__('messages.cancel')); ?></a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\brands\edit.blade.php ENDPATH**/ ?>