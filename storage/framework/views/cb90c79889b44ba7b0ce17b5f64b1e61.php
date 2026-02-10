<?php $__env->startSection('title', __('messages.edit_product')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Product Edit Page Specific Styles */
    .product-form-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Character Counter Styles */
    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }
    
    .char-counter.warning {
        color: #f59e0b;
    }
    
    .char-counter.danger {
        color: #ef4444;
        font-weight: 600;
    }
    
    .char-counter-input.near-limit {
        border-color: #f59e0b !important;
    }
    
    .char-counter-input.at-limit {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }

    .section-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .current-image-container {
        margin-top: 12px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .current-image-container img {
        width: 100%;
        max-width: 300px;
        height: auto;
        border-radius: 8px;
        display: block;
    }

    .current-image-label {
        font-size: 12px;
        color: var(--secondary);
        margin-bottom: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .additional-images-preview {
        margin-top: 15px;
    }

    .additional-images-preview strong {
        font-size: 12px;
        color: var(--secondary);
        margin-bottom: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .images-grid img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid var(--border);
    }

    .delete-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 2px solid #fee2e2;
    }

    .danger-zone {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1px solid #fca5a5;
        border-radius: 8px;
        padding: 20px;
    }

    .danger-zone h3 {
        color: #dc2626;
        font-size: 16px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .danger-zone p {
        color: #7f1d1d;
        font-size: 13px;
        margin-bottom: 16px;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-edit"></i> <?php echo e(__('messages.edit_product')); ?></h1>
        <p><?php echo e(__('messages.update_product_info')); ?>: <strong><?php echo e($product->name); ?></strong></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_products')); ?>

        </a>
    </div>
</div>

<form action="<?php echo e(route('admin.products.update', $product)); ?>" method="POST" class="product-form-grid">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <!-- Main Form Content -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> <?php echo e(__('messages.basic_information')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            <?php echo e(__('messages.product_name_english')); ?>

                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control char-counter-input <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('name_en', $product->name_en)); ?>" 
                            placeholder="Enter product name in English"
                            maxlength="<?php echo e($inputLimits['name'] ?? 120); ?>"
                            data-max-length="<?php echo e($inputLimits['name'] ?? 120); ?>"
                            required>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max"><?php echo e($inputLimits['name'] ?? 120); ?></span>
                        </div>
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
                            اسم المنتج (عربي)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control char-counter-input <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('name_ar', $product->name_ar)); ?>" 
                            placeholder="أدخل اسم المنتج بالعربية"
                            maxlength="<?php echo e($inputLimits['name'] ?? 120); ?>"
                            data-max-length="<?php echo e($inputLimits['name'] ?? 120); ?>"
                            required 
                            dir="rtl">
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max"><?php echo e($inputLimits['name'] ?? 120); ?></span>
                        </div>
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

                    <div class="form-group">
                        <label for="name_he" class="form-label">
                            <?php echo e(__('messages.product_name_hebrew')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <input
                            type="text"
                            id="name_he"
                            name="name_he"
                            class="form-control char-counter-input <?php $__errorArgs = ['name_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            value="<?php echo e(old('name_he', $product->name_he)); ?>"
                            placeholder="<?php echo e(__('messages.enter_product_name_hebrew')); ?>"
                            maxlength="<?php echo e($inputLimits['name'] ?? 120); ?>"
                            data-max-length="<?php echo e($inputLimits['name'] ?? 120); ?>"
                            dir="rtl">
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max"><?php echo e($inputLimits['name'] ?? 120); ?></span>
                        </div>
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
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            <?php echo e(__('messages.category')); ?>

                            <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value=""><?php echo e(__('messages.select_category')); ?></option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['category_id'];
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
                        <label for="brand_id" class="form-label">
                            <?php echo e(__('messages.brand')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <select id="brand_id" name="brand_id" class="form-control <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value=""><?php echo e(__('messages.select_brand')); ?></option>
                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand_id', $product->brand_id) == $brand->id ? 'selected' : ''); ?>>
                                    <?php echo e($brand->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['brand_id'];
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

        <!-- Pricing & Inventory Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-dollar-sign"></i> <?php echo e(__('messages.pricing_inventory')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">
                            <?php echo e(__('messages.regular_price')); ?>

                            <span class="required">*</span>
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: var(--secondary); font-weight: 600;">$</span>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                step="0.01" 
                                value="<?php echo e(old('price', $product->price)); ?>" 
                                placeholder="0.00"
                                style="padding-left: 28px;"
                                required>
                        </div>
                        <?php $__errorArgs = ['price'];
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
                        <label for="sale_price" class="form-label">
                            <?php echo e(__('messages.sale_price')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: var(--secondary); font-weight: 600;">$</span>
                            <input 
                                type="number" 
                                id="sale_price" 
                                name="sale_price" 
                                class="form-control <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                step="0.01" 
                                value="<?php echo e(old('sale_price', $product->sale_price)); ?>"
                                placeholder="0.00"
                                style="padding-left: 28px;">
                        </div>
                        <?php $__errorArgs = ['sale_price'];
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
                        <label for="discount_percentage" class="form-label">
                            <?php echo e(__('messages.discount_percentage') ?? 'Discount Percentage'); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="number" 
                                id="discount_percentage" 
                                name="discount_percentage" 
                                class="form-control <?php $__errorArgs = ['discount_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                step="0.01" 
                                min="0"
                                max="100"
                                value="<?php echo e(old('discount_percentage', $product->discount_percentage)); ?>" 
                                placeholder="0.00"
                                style="padding-right: 32px;">
                            <span style="position: absolute; right: 12px; top: 12px; color: var(--secondary); font-weight: 600;">%</span>
                        </div>
                        <?php $__errorArgs = ['discount_percentage'];
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
                        <label for="stock_quantity" class="form-label">
                            <?php echo e(__('messages.stock_quantity')); ?>

                            <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            class="form-control <?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            value="<?php echo e(old('stock_quantity', $product->stock_quantity)); ?>" 
                            placeholder="0"
                            required>
                        <?php $__errorArgs = ['stock_quantity'];
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

        <!-- Images Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-images"></i> <?php echo e(__('messages.product_images')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="main_image" class="form-label">
                        <?php echo e(__('messages.main_product_image')); ?>

                        <span class="required">*</span>
                    </label>
                    <input 
                        type="url" 
                        id="main_image" 
                        name="main_image" 
                        class="form-control <?php $__errorArgs = ['main_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        value="<?php echo e(old('main_image', $product->main_image)); ?>" 
                        placeholder="https://picsum.photos/800/800"
                        required>
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> <?php echo e(__('messages.image_services_recommendation')); ?>

                    </p>
                    <?php $__errorArgs = ['main_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    
                    <?php if($product->main_image): ?>
                        <div class="current-image-container">
                            <div class="current-image-label">
                                <i class="fas fa-image"></i>
                                <?php echo e(__('messages.current_main_image')); ?>

                            </div>
                            <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="additional_images" class="form-label">
                        <?php echo e(__('messages.additional_images')); ?>

                        <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?> - <?php echo e(__('messages.one_url_per_line')); ?>)</span>
                    </label>
                    <textarea 
                        id="additional_images" 
                        name="additional_images" 
                        class="form-control <?php $__errorArgs = ['additional_images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                        rows="5" 
                        placeholder="https://picsum.photos/800/801&#10;https://picsum.photos/800/802&#10;https://picsum.photos/800/803"><?php echo e(old('additional_images', $product->images->where('is_primary', false)->pluck('image_path')->implode("\n"))); ?></textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.enter_image_url_per_line')); ?>

                    </p>
                    <?php $__errorArgs = ['additional_images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    
                    <?php if($product->images->where('is_primary', false)->count() > 0): ?>
                        <div class="additional-images-preview">
                            <strong>
                                <i class="fas fa-images"></i>
                                <?php echo e(__('messages.current_additional_images')); ?> (<?php echo e($product->images->where('is_primary', false)->count()); ?>)
                            </strong>
                            <div class="images-grid">
                                <?php $__currentLoopData = $product->images->where('is_primary', false); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <img src="<?php echo e($image->image_path); ?>" alt="Product Image">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Search Keywords Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-search"></i> <?php echo e(__('messages.search_optimization')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="search_keywords" class="form-label">
                        <?php echo e(__('messages.search_keywords')); ?>

                        <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                    </label>
                    <textarea
                        id="search_keywords"
                        name="search_keywords"
                        class="form-control <?php $__errorArgs = ['search_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="<?php echo e(__('messages.search_keywords_placeholder')); ?>"
                        style="min-height: 100px;"><?php echo e(old('search_keywords', $product->search_keywords)); ?></textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.search_keywords_help')); ?>

                    </p>
                    <?php $__errorArgs = ['search_keywords'];
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

        <!-- Descriptions Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-align-left"></i> <?php echo e(__('messages.descriptions')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="short_description_en" class="form-label">
                            <?php echo e(__('messages.short_description_english')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <textarea 
                            id="short_description_en" 
                            name="short_description_en" 
                            class="form-control <?php $__errorArgs = ['short_description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Brief description for product listings"
                            style="min-height: 80px;"><?php echo e(old('short_description_en', $product->short_description_en)); ?></textarea>
                        <?php $__errorArgs = ['short_description_en'];
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
                        <label for="short_description_ar" class="form-label">
                            وصف قصير (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري)</span>
                        </label>
                        <textarea 
                            id="short_description_ar" 
                            name="short_description_ar" 
                            class="form-control <?php $__errorArgs = ['short_description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            dir="rtl"
                            placeholder="وصف قصير للمنتج"
                            style="min-height: 80px;"><?php echo e(old('short_description_ar', $product->short_description_ar)); ?></textarea>
                        <?php $__errorArgs = ['short_description_ar'];
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
                        <label for="short_description_he" class="form-label">
                            <?php echo e(__('messages.short_description_hebrew')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <textarea
                            id="short_description_he"
                            name="short_description_he"
                            class="form-control <?php $__errorArgs = ['short_description_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            dir="rtl"
                            placeholder="<?php echo e(__('messages.brief_description_hebrew')); ?>"
                            style="min-height: 80px;"><?php echo e(old('short_description_he', $product->short_description_he)); ?></textarea>
                        <?php $__errorArgs = ['short_description_he'];
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
                        <label for="description_en" class="form-label">
                            <?php echo e(__('messages.full_description_english')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?> - <?php echo e($inputLimits['description'] ?? 3000); ?>)</span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control char-counter-input <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Complete product description with details"
                            maxlength="<?php echo e($inputLimits['description'] ?? 3000); ?>"
                            data-max-length="<?php echo e($inputLimits['description'] ?? 3000); ?>"
                            style="min-height: 150px;"><?php echo e(old('description_en', $product->description_en)); ?></textarea>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max"><?php echo e($inputLimits['description'] ?? 3000); ?></span>
                        </div>
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
                            وصف كامل (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري - <?php echo e($inputLimits['description'] ?? 3000); ?> حرف)</span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control char-counter-input <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            dir="rtl"
                            placeholder="وصف المنتج الكامل بالتفاصيل"
                            maxlength="<?php echo e($inputLimits['description'] ?? 3000); ?>"
                            data-max-length="<?php echo e($inputLimits['description'] ?? 3000); ?>"
                            style="min-height: 150px;"><?php echo e(old('description_ar', $product->description_ar)); ?></textarea>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max"><?php echo e($inputLimits['description'] ?? 3000); ?></span>
                        </div>
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

                    <div class="form-group">
                        <label for="description_he" class="form-label">
                            <?php echo e(__('messages.full_description_hebrew')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?> - <?php echo e($inputLimits['description'] ?? 3000); ?>)</span>
                        </label>
                        <textarea
                            id="description_he"
                            name="description_he"
                            class="form-control char-counter-input <?php $__errorArgs = ['description_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            dir="rtl"
                            placeholder="<?php echo e(__('messages.complete_description_hebrew')); ?>"
                            maxlength="<?php echo e($inputLimits['description'] ?? 3000); ?>"
                            data-max-length="<?php echo e($inputLimits['description'] ?? 3000); ?>"
                            style="min-height: 150px;"><?php echo e(old('description_he', $product->description_he)); ?></textarea>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max"><?php echo e($inputLimits['description'] ?? 3000); ?></span>
                        </div>
                        <?php $__errorArgs = ['description_he'];
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

        <!-- Product Tags Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-tags"></i> <?php echo e(__('messages.product_tags')); ?></h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('messages.select_tags')); ?></label>
                    
                    <!-- Tag Input with Autocomplete -->
                    <div class="tag-input-wrapper">
                        <div class="selected-tags" id="selectedTags">
                            <!-- Pre-populated tags will appear here -->
                        </div>
                        <div class="tag-input-container">
                            <input type="text" 
                                   id="tagSearchInput" 
                                   class="tag-search-input" 
                                   placeholder="<?php echo e(__('messages.type_to_search_or_add_tag')); ?>"
                                   autocomplete="off">
                            <div class="tag-suggestions" id="tagSuggestions"></div>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs container -->
                    <div id="tagHiddenInputs"></div>
                    
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.tag_input_help')); ?>

                    </p>
                </div>
            </div>
        </div>
        
        <style>
        .tag-input-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            background: #f9fafb;
            min-height: 50px;
        }
        .selected-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }
        .selected-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 13px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .selected-tag .tag-color {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .selected-tag .remove-tag {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            margin-left: 4px;
            font-size: 14px;
            line-height: 1;
        }
        .selected-tag .remove-tag:hover {
            color: #ef4444;
        }
        .selected-tag.new-tag {
            background: #eff6ff;
            border-color: #3b82f6;
        }
        .selected-tag.new-tag::after {
            content: '<?php echo e(__("messages.new")); ?>';
            font-size: 10px;
            background: #3b82f6;
            color: white;
            padding: 1px 5px;
            border-radius: 10px;
            margin-left: 4px;
        }
        .tag-input-container {
            position: relative;
        }
        .tag-search-input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 8px;
            font-size: 14px;
            outline: none;
        }
        .tag-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .tag-suggestion {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
        }
        .tag-suggestion:last-child {
            border-bottom: none;
        }
        .tag-suggestion:hover {
            background: #f9fafb;
        }
        .tag-suggestion .tag-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        .tag-suggestion.create-new {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 500;
        }
        .tag-suggestion.create-new:hover {
            background: #dbeafe;
        }
        .tag-suggestion.create-new i {
            color: #3b82f6;
        }
        </style>
        
        <?php
            $existingTags = $product->tags->map(function($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name_en,
                    'color' => $tag->color,
                    'icon' => $tag->icon,
                    'isNew' => false
                ];
            })->values();
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const availableTags = <?php echo json_encode($tags ?? [], 15, 512) ?>;
            // Pre-populate with existing product tags
            let selectedTags = <?php echo json_encode($existingTags, 15, 512) ?>;
            
            const searchInput = document.getElementById('tagSearchInput');
            const suggestionsDiv = document.getElementById('tagSuggestions');
            const selectedTagsDiv = document.getElementById('selectedTags');
            const hiddenInputsDiv = document.getElementById('tagHiddenInputs');
            
            // Initial render
            renderSelectedTags();
            
            // Search input handler
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                if (query.length === 0) {
                    suggestionsDiv.style.display = 'none';
                    return;
                }
                
                // Filter available tags
                const filtered = availableTags.filter(tag => 
                    !selectedTags.some(s => s.id === tag.id) &&
                    (tag.name_en.toLowerCase().includes(query) || 
                     tag.name_ar.toLowerCase().includes(query))
                );
                
                let html = '';
                
                // Show matching tags
                filtered.slice(0, 8).forEach(tag => {
                    const icon = tag.icon 
                        ? `<i class="${tag.icon}" style="color: ${tag.color}"></i>`
                        : `<span class="tag-color" style="background: ${tag.color}"></span>`;
                    html += `<div class="tag-suggestion" data-id="${tag.id}" data-name="${tag.name_en}" data-color="${tag.color}" data-icon="${tag.icon || ''}">
                        ${icon}
                        <span>${tag.name_en}</span>
                        <span style="color: #9ca3af; font-size: 12px;">(${tag.name_ar})</span>
                    </div>`;
                });
                
                // Show "Create new tag" option
                const exactMatch = availableTags.some(tag => 
                    tag.name_en.toLowerCase() === query || tag.name_ar.toLowerCase() === query
                );
                
                if (!exactMatch && query.length >= 2) {
                    html += `<div class="tag-suggestion create-new" data-new="true" data-name="${this.value.trim()}">
                        <i class="fas fa-plus"></i>
                        <span><?php echo e(__('messages.create_tag')); ?>: "${this.value.trim()}"</span>
                    </div>`;
                }
                
                if (html) {
                    suggestionsDiv.innerHTML = html;
                    suggestionsDiv.style.display = 'block';
                    
                    // Add click handlers
                    suggestionsDiv.querySelectorAll('.tag-suggestion').forEach(el => {
                        el.addEventListener('click', function() {
                            if (this.dataset.new === 'true') {
                                addNewTag(this.dataset.name);
                            } else {
                                addExistingTag(parseInt(this.dataset.id), this.dataset.name, this.dataset.color, this.dataset.icon);
                            }
                            searchInput.value = '';
                            suggestionsDiv.style.display = 'none';
                        });
                    });
                } else {
                    suggestionsDiv.style.display = 'none';
                }
            });
            
            // Handle Enter key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        // Check if exact match exists
                        const exactMatch = availableTags.find(tag => 
                            tag.name_en.toLowerCase() === query.toLowerCase() || 
                            tag.name_ar.toLowerCase() === query.toLowerCase()
                        );
                        
                        if (exactMatch && !selectedTags.some(s => s.id === exactMatch.id)) {
                            addExistingTag(exactMatch.id, exactMatch.name_en, exactMatch.color, exactMatch.icon);
                        } else if (!exactMatch) {
                            addNewTag(query);
                        }
                        this.value = '';
                        suggestionsDiv.style.display = 'none';
                    }
                }
            });
            
            // Hide suggestions on click outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                    suggestionsDiv.style.display = 'none';
                }
            });
            
            function addExistingTag(id, name, color, icon) {
                if (selectedTags.some(t => t.id === id)) return;
                
                selectedTags.push({ id, name, color, icon, isNew: false });
                renderSelectedTags();
            }
            
            function addNewTag(name) {
                if (selectedTags.some(t => t.name.toLowerCase() === name.toLowerCase())) return;
                
                const tempId = 'new_' + Date.now();
                selectedTags.push({ id: tempId, name, color: '#3b82f6', icon: '', isNew: true });
                renderSelectedTags();
            }
            
            function removeTag(id) {
                selectedTags = selectedTags.filter(t => t.id !== id);
                renderSelectedTags();
            }
            
            function renderSelectedTags() {
                // Render visual tags
                selectedTagsDiv.innerHTML = selectedTags.map(tag => {
                    const icon = tag.icon 
                        ? `<i class="${tag.icon}" style="color: ${tag.color}"></i>`
                        : `<span class="tag-color" style="background: ${tag.color}"></span>`;
                    return `<span class="selected-tag ${tag.isNew ? 'new-tag' : ''}" data-id="${tag.id}">
                        ${icon}
                        <span>${tag.name}</span>
                        <button type="button" class="remove-tag" onclick="window.removeTagById('${tag.id}')">&times;</button>
                    </span>`;
                }).join('');
                
                // Render hidden inputs
                let hiddenHtml = '';
                selectedTags.forEach(tag => {
                    if (tag.isNew) {
                        hiddenHtml += `<input type="hidden" name="new_tags_array[]" value="${tag.name}">`;
                    } else {
                        hiddenHtml += `<input type="hidden" name="tags[]" value="${tag.id}">`;
                    }
                });
                hiddenInputsDiv.innerHTML = hiddenHtml;
            }
            
            // Global function for remove button
            window.removeTagById = function(id) {
                if (typeof id === 'string' && id.startsWith('new_')) {
                    selectedTags = selectedTags.filter(t => t.id !== id);
                } else {
                    selectedTags = selectedTags.filter(t => t.id !== parseInt(id));
                }
                renderSelectedTags();
            };
        });
        </script>

        <!-- Product Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> <?php echo e(__('messages.product_settings')); ?></h2>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <!-- Hidden inputs to ensure unchecked values are sent -->
                    <input type="hidden" name="is_active" value="0">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="hidden" name="is_new" value="0">
                    <input type="hidden" name="is_bestseller" value="0">
                    <input type="hidden" name="is_special_offer" value="0">
                    <input type="hidden" name="is_strong_offer" value="0">
                    
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            <?php echo e(old('is_active', $product->is_active) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-eye"></i> <?php echo e(__('messages.active')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.display_product_in_store')); ?></p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_featured" 
                            name="is_featured" 
                            value="1" 
                            <?php echo e(old('is_featured', $product->is_featured) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-star"></i> <?php echo e(__('messages.featured')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.show_homepage_featured')); ?></p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_new" 
                            name="is_new" 
                            value="1" 
                            <?php echo e(old('is_new', $product->is_new) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-badge"></i> <?php echo e(__('messages.new_product')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.mark_new_highlight')); ?></p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_bestseller" 
                            name="is_bestseller" 
                            value="1" 
                            <?php echo e(old('is_bestseller', $product->is_bestseller) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-fire"></i> <?php echo e(__('messages.bestseller')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.mark_bestselling_product')); ?></p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_special_offer" 
                            name="is_special_offer" 
                            value="1" 
                            <?php echo e(old('is_special_offer', $product->is_special_offer ?? false) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-gift"></i> <?php echo e(__('messages.special_offer')); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.show_special_offer_homepage')); ?></p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_strong_offer" 
                            name="is_strong_offer" 
                            value="1" 
                            <?php echo e(old('is_strong_offer', $product->is_strong_offer ?? false) ? 'checked' : ''); ?>>
                        <span>
                            <strong><i class="fas fa-bolt"></i> <?php echo e(__('messages.strong_offer') ?? 'Strong Offer'); ?></strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e(__('messages.mark_as_strong_promotional_offer') ?? 'Mark as strong promotional offer for filtering'); ?></p>
                        </span>
                    </label>

                    <!-- Custom Home Sections -->
                    <?php if(isset($customSections) && $customSections->count() > 0): ?>
                        <?php $__currentLoopData = $customSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="checkbox-group">
                            <input 
                                type="checkbox" 
                                name="home_sections[]" 
                                value="<?php echo e($cs->id); ?>" 
                                <?php echo e(in_array($cs->id, old('home_sections', $selectedHomeSections ?? [])) ? 'checked' : ''); ?>>
                            <span>
                                <strong><i class="fas fa-th-list"></i> <?php echo e($cs->title); ?></strong>
                                <?php if($cs->subtitle): ?>
                                    <p style="color: #64748b; font-size: 12px; margin-top: 2px;"><?php echo e($cs->subtitle); ?></p>
                                <?php endif; ?>
                            </span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Product Attributes Card -->
        <div class="card" id="attributes-card" style="<?php echo e(!empty($categoryAttributes) && $categoryAttributes->count() > 0 ? '' : 'display: none;'); ?>">
            <div class="card-header">
                <h2><i class="fas fa-tags"></i> <?php echo e(__('messages.product_attributes')); ?></h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;"><?php echo e(__('messages.select_attributes_for_category')); ?></p>
            </div>
            <div class="card-body">
                <div id="attributes-container">
                    <?php if(!empty($categoryAttributes) && $categoryAttributes->count() > 0): ?>
                        <div style="display: flex; flex-direction: column; gap: 24px;">
                            <?php $__currentLoopData = $categoryAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        <strong><?php echo e($attribute->name); ?></strong>
                                        <?php if($attribute->unit): ?>
                                            <span style="color: #64748b; font-size: 12px;">(<?php echo e($attribute->unit); ?>)</span>
                                        <?php endif; ?>
                                    </label>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-top: 8px;">
                                        <?php $__currentLoopData = $attribute->values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="checkbox-group" style="margin: 0;">
                                                <input 
                                                    type="checkbox" 
                                                    id="attr_<?php echo e($attribute->id); ?>_<?php echo e($value->id); ?>" 
                                                    name="attribute_values[]" 
                                                    value="<?php echo e($value->id); ?>"
                                                    <?php echo e(in_array($value->id, old('attribute_values', $selectedAttributeValues)) ? 'checked' : ''); ?>>
                                                <span>
                                                    <?php if($value->color_code): ?>
                                                        <span style="display: inline-block; width: 16px; height: 16px; border-radius: 3px; background: <?php echo e($value->color_code); ?>; border: 1px solid #ddd; margin-right: 6px; vertical-align: middle;"></span>
                                                    <?php endif; ?>
                                                    <?php echo e($value->value); ?>

                                                </span>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #64748b; text-align: center; padding: 20px;">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.no_attributes_for_category')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Product Specifications Card -->
        <div class="card" id="specifications-card" style="<?php echo e(isset($specFields) && $specFields->count() > 0 ? '' : 'display: none;'); ?>">
            <div class="card-header">
                <h2><i class="fas fa-clipboard-list"></i> <?php echo e(__('messages.product_specifications')); ?></h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;"><?php echo e(__('messages.fill_specs_for_category') ?? 'Fill in the specifications for this product category'); ?></p>
            </div>
            <div class="card-body">
                <div id="specifications-container">
                    <?php if(isset($specFields) && $specFields->count() > 0): ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                            <?php $__currentLoopData = $specFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group">
                                    <label for="spec_<?php echo e($field->id); ?>" class="form-label">
                                        <?php echo e($field->label); ?>

                                        <?php if($field->is_required): ?>
                                            <span class="required">*</span>
                                        <?php endif; ?>
                                        <?php if($field->unit): ?>
                                            <span style="color: #64748b; font-size: 12px;">(<?php echo e($field->unit); ?>)</span>
                                        <?php endif; ?>
                                    </label>
                                    
                                    <?php if($field->type === 'text'): ?>
                                        <input 
                                            type="text" 
                                            id="spec_<?php echo e($field->id); ?>" 
                                            name="spec_values[<?php echo e($field->id); ?>]" 
                                            class="form-control <?php $__errorArgs = ['spec_values.'.$field->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('spec_values.'.$field->id, $specValues[$field->id] ?? '')); ?>"
                                            placeholder="<?php echo e($field->label); ?>"
                                            <?php echo e($field->is_required ? 'required' : ''); ?>>
                                    <?php elseif($field->type === 'number'): ?>
                                        <input 
                                            type="number" 
                                            id="spec_<?php echo e($field->id); ?>" 
                                            name="spec_values[<?php echo e($field->id); ?>]" 
                                            class="form-control <?php $__errorArgs = ['spec_values.'.$field->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e(old('spec_values.'.$field->id, $specValues[$field->id] ?? '')); ?>"
                                            placeholder="<?php echo e($field->label); ?>"
                                            step="any"
                                            <?php echo e($field->is_required ? 'required' : ''); ?>>
                                    <?php elseif($field->type === 'boolean'): ?>
                                        <select 
                                            id="spec_<?php echo e($field->id); ?>" 
                                            name="spec_values[<?php echo e($field->id); ?>]" 
                                            class="form-control <?php $__errorArgs = ['spec_values.'.$field->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            <?php echo e($field->is_required ? 'required' : ''); ?>>
                                            <option value="">-- <?php echo e(__('messages.select') ?? 'Select'); ?> --</option>
                                            <option value="1" <?php echo e(old('spec_values.'.$field->id, $specValues[$field->id] ?? '') === '1' ? 'selected' : ''); ?>><?php echo e(__('messages.yes')); ?></option>
                                            <option value="0" <?php echo e(old('spec_values.'.$field->id, $specValues[$field->id] ?? '') === '0' ? 'selected' : ''); ?>><?php echo e(__('messages.no')); ?></option>
                                        </select>
                                    <?php elseif($field->type === 'select'): ?>
                                        <select 
                                            id="spec_<?php echo e($field->id); ?>" 
                                            name="spec_values[<?php echo e($field->id); ?>]" 
                                            class="form-control <?php $__errorArgs = ['spec_values.'.$field->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            <?php echo e($field->is_required ? 'required' : ''); ?>>
                                            <option value="">-- <?php echo e(__('messages.select') ?? 'Select'); ?> --</option>
                                            <?php $__currentLoopData = $field->options ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($option); ?>" <?php echo e(old('spec_values.'.$field->id, $specValues[$field->id] ?? '') === $option ? 'selected' : ''); ?>>
                                                    <?php echo e($option); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    <?php endif; ?>
                                    
                                    <?php $__errorArgs = ['spec_values.'.$field->id];
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #64748b; text-align: center; padding: 20px;">
                            <i class="fas fa-info-circle"></i> <?php echo e(__('messages.no_specs_for_category')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?php echo e(__('messages.update_product')); ?>

            </button>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

            </a>
        </div>

        <!-- Danger Zone -->
        <div class="delete-section">
            <div class="danger-zone">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo e(__('messages.danger_zone_product')); ?>

                </h3>
                <p><?php echo e(__('messages.delete_product_warning')); ?></p>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_product')); ?>

                </button>
            </div>
        </div>
    </div>
</form>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
    function confirmDelete() {
        if (confirm('<?php echo e(__("messages.confirm_delete_product_message")); ?>')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Character counter functionality
    function initCharCounters() {
        document.querySelectorAll('.char-counter-input').forEach(input => {
            const counter = input.parentElement.querySelector('.char-counter');
            if (!counter) return;
            
            const countSpan = counter.querySelector('.char-count');
            const maxLength = parseInt(input.dataset.maxLength) || parseInt(input.maxLength) || 120;
            
            function updateCounter() {
                const length = input.value.length;
                countSpan.textContent = length;
                
                // Update styling based on usage
                const percentage = (length / maxLength) * 100;
                
                counter.classList.remove('warning', 'danger');
                input.classList.remove('near-limit', 'at-limit');
                
                if (percentage >= 100) {
                    counter.classList.add('danger');
                    input.classList.add('at-limit');
                } else if (percentage >= 85) {
                    counter.classList.add('warning');
                    input.classList.add('near-limit');
                }
            }
            
            // Initial update
            updateCounter();
            
            // Update on input
            input.addEventListener('input', updateCounter);
        });
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', initCharCounters);

    // Dynamic attribute and specification loading on category change
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const attributesCard = document.getElementById('attributes-card');
        const attributesContainer = document.getElementById('attributes-container');
        const specificationsCard = document.getElementById('specifications-card');
        const specificationsContainer = document.getElementById('specifications-container');
        const currentCategoryId = '<?php echo e($product->category_id); ?>';
        
        // Pricing auto-calculation: price, sale_price, discount_percentage
        const priceInput = document.getElementById('price');
        const salePriceInput = document.getElementById('sale_price');
        const discountInput = document.getElementById('discount_percentage');

        if (priceInput && salePriceInput && discountInput) {
            priceInput.addEventListener('input', function() {
                const price = parseFloat(priceInput.value);
                const sale = parseFloat(salePriceInput.value);
                const discount = parseFloat(discountInput.value);
                if (price > 0 && sale > 0 && sale < price) {
                    discountInput.value = (((price - sale) / price) * 100).toFixed(2);
                } else if (price > 0 && discount > 0 && discount <= 100) {
                    salePriceInput.value = (price * (1 - discount / 100)).toFixed(2);
                }
            });

            salePriceInput.addEventListener('input', function() {
                const price = parseFloat(priceInput.value);
                const sale = parseFloat(salePriceInput.value);
                if (price > 0 && sale > 0 && sale < price) {
                    discountInput.value = (((price - sale) / price) * 100).toFixed(2);
                } else if (sale > 0 && !price) {
                    discountInput.value = '';
                }
            });

            discountInput.addEventListener('input', function() {
                const price = parseFloat(priceInput.value);
                const sale = parseFloat(salePriceInput.value);
                const discount = parseFloat(discountInput.value);
                if (discount > 0 && discount <= 100) {
                    if (price > 0) {
                        salePriceInput.value = (price * (1 - discount / 100)).toFixed(2);
                    } else if (sale > 0) {
                        priceInput.value = (sale / (1 - discount / 100)).toFixed(2);
                    }
                }
            });
        }
        
        // Store currently selected attribute values
        let selectedValues = [];
        
        function getSelectedValues() {
            const checkboxes = document.querySelectorAll('input[name="attribute_values[]"]:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        // Load attributes and specifications when category changes
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Store selected values before reload
            selectedValues = getSelectedValues();
            
            if (!categoryId) {
                attributesCard.style.display = 'none';
                specificationsCard.style.display = 'none';
                return;
            }

            // Show loading state for attributes
            attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> <?php echo e(__('messages.loading')); ?>...</p>';
            attributesCard.style.display = 'block';

            // Show loading state for specifications
            specificationsContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> <?php echo e(__('messages.loading')); ?>...</p>';
            specificationsCard.style.display = 'block';

            // Fetch attributes for this category
            fetch(`/admin/products/category-attributes/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.attributes && data.attributes.length > 0) {
                        renderAttributes(data.attributes, categoryId === currentCategoryId);
                    } else {
                        attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-info-circle"></i> <?php echo e(__('messages.no_attributes_for_category')); ?></p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading attributes:', error);
                    attributesContainer.innerHTML = '<p style="color: #dc2626; text-align: center; padding: 20px;"><i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.error')); ?></p>';
                });

            // Fetch specification fields for this category
            fetch(`/admin/spec-templates/category-fields/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.hasTemplate && data.fields && data.fields.length > 0) {
                        renderSpecifications(data.fields);
                        specificationsCard.style.display = 'block';
                    } else {
                        specificationsContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-info-circle"></i> <?php echo e(__("messages.no_specs_for_category")); ?></p>';
                        specificationsCard.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading specifications:', error);
                    specificationsContainer.innerHTML = '<p style="color: #dc2626; text-align: center; padding: 20px;"><i class="fas fa-exclamation-triangle"></i> <?php echo e(__('messages.error')); ?></p>';
                });
        });

        function renderSpecifications(fields) {
            let html = '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">';
            
            fields.forEach(field => {
                const required = field.is_required ? 'required' : '';
                const requiredStar = field.is_required ? '<span class="required">*</span>' : '';
                const unit = field.unit ? `<span style="color: #64748b; font-size: 12px;">(${escapeHtml(field.unit)})</span>` : '';
                
                html += `<div class="form-group">
                    <label for="spec_${field.id}" class="form-label">
                        ${escapeHtml(field.label)} ${requiredStar} ${unit}
                    </label>`;
                
                if (field.type === 'text') {
                    html += `<input type="text" id="spec_${field.id}" name="spec_values[${field.id}]" 
                             class="form-control" placeholder="${escapeHtml(field.label)}" ${required}>`;
                } else if (field.type === 'number') {
                    html += `<input type="number" id="spec_${field.id}" name="spec_values[${field.id}]" 
                             class="form-control" placeholder="${escapeHtml(field.label)}" step="any" ${required}>`;
                } else if (field.type === 'boolean') {
                    html += `<select id="spec_${field.id}" name="spec_values[${field.id}]" class="form-control" ${required}>
                             <option value="">-- Select --</option>
                             <option value="1"><?php echo e(__("messages.yes")); ?></option>
                             <option value="0"><?php echo e(__("messages.no")); ?></option>
                             </select>`;
                } else if (field.type === 'select' && field.options) {
                    html += `<select id="spec_${field.id}" name="spec_values[${field.id}]" class="form-control" ${required}>
                             <option value="">-- Select --</option>`;
                    field.options.forEach(option => {
                        html += `<option value="${escapeHtml(option)}">${escapeHtml(option)}</option>`;
                    });
                    html += '</select>';
                }
                
                html += '</div>';
            });
            
            html += '</div>';
            specificationsContainer.innerHTML = html;
        }

        function renderAttributes(attributes, keepSelection) {
            let html = '<div style="display: flex; flex-direction: column; gap: 24px;">';

            attributes.forEach(attribute => {
                html += `
                    <div class="form-group">
                        <label class="form-label">
                            <strong>${escapeHtml(attribute.name)}</strong>
                            ${attribute.unit ? `<span style="color: #64748b; font-size: 12px;">(${escapeHtml(attribute.unit)})</span>` : ''}
                        </label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-top: 8px;">
                `;

                attribute.values.forEach(value => {
                    const inputId = `attr_${attribute.id}_${value.id}`;
                    const isChecked = keepSelection && selectedValues.includes(value.id.toString());
                    html += `
                        <label class="checkbox-group" style="margin: 0;">
                            <input 
                                type="checkbox" 
                                id="${inputId}" 
                                name="attribute_values[]" 
                                value="${value.id}"
                                ${isChecked ? 'checked' : ''}>
                            <span>
                                ${value.color_code ? `<span style="display: inline-block; width: 16px; height: 16px; border-radius: 3px; background: ${escapeHtml(value.color_code)}; border: 1px solid #ddd; margin-right: 6px; vertical-align: middle;"></span>` : ''}
                                ${escapeHtml(value.value)}
                            </span>
                        </label>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            attributesContainer.innerHTML = html;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>