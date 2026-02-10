<?php $__env->startSection('title', __('messages.create_category')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Category Create Page Specific Styles */
    .category-form-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    .image-preview {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
        margin-top: 8px;
        display: none;
    }

    .image-preview.visible {
        display: block;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><?php echo e(__('messages.create_new_category')); ?></h1>
        <p><?php echo e(__('messages.add_category_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_categories')); ?>

        </a>
    </div>
</div>

<form action="<?php echo e(route('admin.categories.store')); ?>" method="POST" class="category-form-grid">
    <?php echo csrf_field(); ?>

    <!-- Main Form Content -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-folder-plus"></i> <?php echo e(__('messages.category_information')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            <?php echo e(__('messages.category_name_english')); ?>

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
                            value="<?php echo e(old('name_en')); ?>" 
                            placeholder="e.g., Electronics, Clothing, Food"
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
                            <?php echo e(__('messages.category_name_arabic')); ?>

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
                            value="<?php echo e(old('name_ar')); ?>" 
                            placeholder="أدخل اسم الفئة بالعربية"
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

                <div class="form-group">
                    <label for="parent_id" class="form-label">
                        <?php echo e(__('messages.parent_category_optional')); ?>

                        <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.for_subcategories')); ?></span>
                    </label>
                    <select id="parent_id" name="parent_id" class="form-control <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value=""><?php echo e(__('messages.root_category_no_parent')); ?></option>
                        <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($parent->id); ?>" <?php echo e(old('parent_id') == $parent->id ? 'selected' : ''); ?>>
                                <?php echo e($parent->name_en ?? $parent->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.parent_category_help')); ?>

                    </p>
                    <?php $__errorArgs = ['parent_id'];
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

                <div class="form-row" id="iconPositionRow">
                    <div class="form-group" id="iconGroup">
                        <label for="icon" class="form-label">
                            <?php echo e(__('messages.category_icon')); ?>

                            <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                        </label>
                        <input 
                            type="text" 
                            id="icon" 
                            name="icon" 
                            class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('icon')); ?>" 
                            placeholder="e.g., fas fa-laptop, fas fa-tshirt">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.icon_help_text')); ?>

                        </p>
                        <?php $__errorArgs = ['icon'];
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
                        <label for="position" class="form-label">
                            <?php echo e(__('messages.display_position')); ?>

                            <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                        </label>
                        <input 
                            type="number" 
                            id="position" 
                            name="position" 
                            class="form-control <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('position', 0)); ?>" 
                            placeholder="0"
                            min="0">
                        <p class="form-text">
                            <i class="fas fa-sort-numeric-down"></i> <?php echo e(__('messages.lower_numbers_first')); ?>

                        </p>
                        <?php $__errorArgs = ['position'];
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

                <div class="form-group">
                    <label for="display_mode" class="form-label">
                        <?php echo e(__('messages.display_mode')); ?>

                        <span class="required">*</span>
                    </label>
                    <select id="display_mode" name="display_mode" class="form-control <?php $__errorArgs = ['display_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="carousel" <?php echo e(old('display_mode', 'carousel') == 'carousel' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.carousel')); ?> - <?php echo e(__('messages.carousel_description')); ?>

                        </option>
                        <option value="nav" <?php echo e(old('display_mode') == 'nav' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.nav_bar')); ?> - <?php echo e(__('messages.nav_bar_description')); ?>

                        </option>
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.display_mode_help')); ?>

                    </p>
                    <?php $__errorArgs = ['display_mode'];
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

                <!-- Nav Type Selection (Parent/Child) - Only for Nav mode -->
                <div class="form-group" id="navTypeGroup" style="display: none;">
                    <label for="nav_type" class="form-label">
                        <?php echo e(__('messages.nav_type')); ?>

                        <span class="required">*</span>
                    </label>
                    <select id="nav_type" name="nav_type" class="form-control <?php $__errorArgs = ['nav_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="parent" <?php echo e(old('nav_type', 'parent') == 'parent' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.nav_parent')); ?> - <?php echo e(__('messages.nav_parent_description')); ?>

                        </option>
                        <option value="child" <?php echo e(old('nav_type') == 'child' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.nav_child')); ?> - <?php echo e(__('messages.nav_child_description')); ?>

                        </option>
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.nav_type_help')); ?>

                    </p>
                    <?php $__errorArgs = ['nav_type'];
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

                <!-- Nav Parent Selection - Only for Nav Child type -->
                <div class="form-group" id="navParentGroup" style="display: none;">
                    <label for="nav_parent_id" class="form-label">
                        <?php echo e(__('messages.select_nav_parent')); ?>

                        <span class="required">*</span>
                    </label>
                    <select id="nav_parent_id" name="nav_parent_id" class="form-control <?php $__errorArgs = ['nav_parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value=""><?php echo e(__('messages.choose_parent_category')); ?></option>
                        <?php $__currentLoopData = $navParentCategories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navParent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($navParent->id); ?>" <?php echo e(old('nav_parent_id') == $navParent->id ? 'selected' : ''); ?>>
                                <?php echo e($navParent->name_en ?? $navParent->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.nav_parent_select_help')); ?>

                    </p>
                    <?php $__errorArgs = ['nav_parent_id'];
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

        <!-- Category Image Card (Only for Carousel mode) -->
        <div class="card" id="imageCard">
            <div class="card-header">
                <h2><i class="fas fa-image"></i> <?php echo e(__('messages.category_image')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="image" class="form-label">
                        <?php echo e(__('messages.category_image_url')); ?>

                        <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                    </label>
                    <input 
                        type="url" 
                        id="image" 
                        name="image" 
                        class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        value="<?php echo e(old('image')); ?>" 
                        placeholder="<?php echo e(__('messages.image_url_placeholder')); ?>"
                        oninput="previewImage(this.value)">
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> <?php echo __('messages.image_services_tip'); ?>

                    </p>
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <img id="imagePreview" class="image-preview" alt="Category preview">
                </div>
            </div>
        </div>

        <!-- Descriptions Card (Only for Carousel mode) -->
        <div class="card" id="descriptionsCard">
            <div class="card-header">
                <h2><i class="fas fa-align-left"></i> <?php echo e(__('messages.descriptions')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            <?php echo e(__('messages.description_english')); ?>

                            <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="<?php echo e(__('messages.description_placeholder')); ?>"
                            style="min-height: 100px;"><?php echo e(old('description_en')); ?></textarea>
                        <?php $__errorArgs = ['description_en'];
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
                        <label for="description_ar" class="form-label">
                            <?php echo e(__('messages.description_arabic')); ?>

                            <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            dir="rtl"
                            placeholder="<?php echo e(__('messages.description_placeholder_ar')); ?>"
                            style="min-height: 100px;"><?php echo e(old('description_ar')); ?></textarea>
                        <?php $__errorArgs = ['description_ar'];
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
        </div>

        <!-- SEO Card (Only for Carousel mode) -->
        <div class="card" id="seoCard">
            <div class="card-header">
                <h2><i class="fas fa-search"></i> <?php echo e(__('messages.seo_settings')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">
                            <?php echo e(__('messages.meta_title')); ?>

                            <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                        </label>
                        <input 
                            type="text" 
                            id="meta_title" 
                            name="meta_title" 
                            class="form-control <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('meta_title')); ?>" 
                            placeholder="<?php echo e(__('messages.meta_title_placeholder')); ?>"
                            maxlength="60">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.meta_title_tip')); ?>

                        </p>
                        <?php $__errorArgs = ['meta_title'];
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
                        <label for="meta_keywords" class="form-label">
                            <?php echo e(__('messages.meta_keywords')); ?>

                            <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                        </label>
                        <input 
                            type="text" 
                            id="meta_keywords" 
                            name="meta_keywords" 
                            class="form-control <?php $__errorArgs = ['meta_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('meta_keywords')); ?>" 
                            placeholder="<?php echo e(__('messages.meta_keywords_placeholder')); ?>">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.meta_keywords_tip')); ?>

                        </p>
                        <?php $__errorArgs = ['meta_keywords'];
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

                <div class="form-group">
                    <label for="meta_description" class="form-label">
                        <?php echo e(__('messages.meta_description')); ?>

                        <span style="color: #64748b; font-size: 12px;"><?php echo e(__('messages.optional')); ?></span>
                    </label>
                    <textarea 
                        id="meta_description" 
                        name="meta_description" 
                        class="form-control <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="<?php echo e(__('messages.meta_description_placeholder')); ?>"
                        style="min-height: 80px;"><?php echo e(old('meta_description')); ?></textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.meta_description_tip')); ?>

                    </p>
                    <?php $__errorArgs = ['meta_description'];
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

        <!-- Category Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> <?php echo e(__('messages.category_settings')); ?></h2>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-eye"></i> <?php echo e(__('messages.active_label')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.display_category_in_store')); ?></p>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?php echo e(__('messages.create_category_button')); ?>

            </button>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

            </a>
        </div>
    </div>


</form>

<script>
    function previewImage(url) {
        const preview = document.getElementById('imagePreview');
        if (url) {
            preview.src = url;
            preview.classList.add('visible');
            preview.onerror = function() {
                preview.classList.remove('visible');
            };
        } else {
            preview.classList.remove('visible');
        }
    }

    function toggleDisplayModeFields() {
        const displayMode = document.getElementById('display_mode').value;
        const imageCard = document.getElementById('imageCard');
        const descriptionsCard = document.getElementById('descriptionsCard');
        const seoCard = document.getElementById('seoCard');
        const iconGroup = document.getElementById('iconGroup');
        const navTypeGroup = document.getElementById('navTypeGroup');
        const parentIdGroup = document.getElementById('parent_id').closest('.form-group');
        
        if (displayMode === 'nav') {
            // Hide cards not needed for nav mode
            imageCard.style.display = 'none';
            descriptionsCard.style.display = 'none';
            seoCard.style.display = 'none';
            iconGroup.style.display = 'none';
            navTypeGroup.style.display = 'block';
            parentIdGroup.style.display = 'none'; // Hide regular parent dropdown for nav mode
            
            // Toggle nav type fields
            toggleNavTypeFields();
        } else {
            // Show all cards for carousel mode
            imageCard.style.display = 'block';
            descriptionsCard.style.display = 'block';
            seoCard.style.display = 'block';
            iconGroup.style.display = 'block';
            navTypeGroup.style.display = 'none';
            parentIdGroup.style.display = 'block'; // Show regular parent dropdown for carousel mode
            
            // Hide nav parent selection
            document.getElementById('navParentGroup').style.display = 'none';
        }
    }

    function toggleNavTypeFields() {
        const navType = document.getElementById('nav_type').value;
        const navParentGroup = document.getElementById('navParentGroup');
        const navParentSelect = document.getElementById('nav_parent_id');
        
        if (navType === 'child') {
            navParentGroup.style.display = 'block';
            navParentSelect.setAttribute('required', 'required');
        } else {
            navParentGroup.style.display = 'none';
            navParentSelect.removeAttribute('required');
        }
    }

    // Preview on load if image exists
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        if (imageInput.value) {
            previewImage(imageInput.value);
        }
        
        // Toggle fields based on display mode
        toggleDisplayModeFields();
        
        // Listen for display mode changes
        document.getElementById('display_mode').addEventListener('change', toggleDisplayModeFields);
        
        // Listen for nav type changes
        document.getElementById('nav_type').addEventListener('change', toggleNavTypeFields);
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/categories/create.blade.php ENDPATH**/ ?>