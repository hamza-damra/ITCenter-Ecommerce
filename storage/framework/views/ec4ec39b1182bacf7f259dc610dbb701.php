<?php $__env->startSection('title', 'Edit Product'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Product Edit Page Specific Styles */
    .product-form-grid {
        max-width: 900px;
        margin: 0 auto;
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
        <h1><i class="fas fa-edit"></i> Edit Product</h1>
        <p>Update product information: <strong><?php echo e($product->name); ?></strong></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
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
                <h2><i class="fas fa-info-circle"></i> Basic Information</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            Product Name (English)
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
                            value="<?php echo e(old('name_en', $product->name_en)); ?>" 
                            placeholder="Enter product name in English"
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
                            اسم المنتج (عربي)
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
                            value="<?php echo e(old('name_ar', $product->name_ar)); ?>" 
                            placeholder="أدخل اسم المنتج بالعربية"
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

                    <div class="form-group">
                        <label for="name_he" class="form-label">
                            <?php echo e(__('messages.product_name_hebrew')); ?>

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
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
                            value="<?php echo e(old('name_he', $product->name_he)); ?>"
                            placeholder="<?php echo e(__('messages.enter_product_name_hebrew')); ?>"
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
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            Category
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
                            <option value="">Select a Category</option>
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
                            Brand
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <select id="brand_id" name="brand_id" class="form-control <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Select a Brand</option>
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
                <h2><i class="fas fa-dollar-sign"></i> Pricing & Inventory</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">
                            Regular Price
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
                            Sale Price
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
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
                        <label for="stock_quantity" class="form-label">
                            Stock Quantity
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
                <h2><i class="fas fa-images"></i> Product Images</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="main_image" class="form-label">
                        Main Product Image
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
                        <i class="fas fa-lightbulb"></i> Recommended: Use services like <strong>picsum.photos</strong> or <strong>placehold.co</strong>
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
                                Current Main Image
                            </div>
                            <img src="<?php echo e($product->main_image); ?>" alt="<?php echo e($product->name); ?>">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="additional_images" class="form-label">
                        Additional Images
                        <span style="color: #64748b; font-size: 12px;">(Optional - One URL per line)</span>
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
                        <i class="fas fa-info-circle"></i> Enter each image URL on a new line for the product gallery
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
                                Current Additional Images (<?php echo e($product->images->where('is_primary', false)->count()); ?>)
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
                <h2><i class="fas fa-align-left"></i> Descriptions</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="short_description_en" class="form-label">
                            Short Description (English)
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
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
                            Full Description (English)
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
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
                            placeholder="Complete product description with details"
                            style="min-height: 150px;"><?php echo e(old('description_en', $product->description_en)); ?></textarea>
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
                            <span style="color: #64748b; font-size: 12px;">(اختياري)</span>
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
                            placeholder="وصف المنتج الكامل بالتفاصيل"
                            style="min-height: 150px;"><?php echo e(old('description_ar', $product->description_ar)); ?></textarea>
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

                            <span style="color: #64748b; font-size: 12px;">(<?php echo e(__('messages.optional')); ?>)</span>
                        </label>
                        <textarea
                            id="description_he"
                            name="description_he"
                            class="form-control <?php $__errorArgs = ['description_he'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            dir="rtl"
                            placeholder="<?php echo e(__('messages.complete_description_hebrew')); ?>"
                            style="min-height: 150px;"><?php echo e(old('description_he', $product->description_he)); ?></textarea>
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

        <!-- Product Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> Product Settings</h2>
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
                            <strong><i class="fas fa-eye"></i> Active</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Display this product in the store</p>
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
                            <strong><i class="fas fa-star"></i> Featured</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Show on homepage featured section</p>
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
                            <strong><i class="fas fa-badge"></i> New Product</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Mark as new to highlight in store</p>
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
                            <strong><i class="fas fa-fire"></i> Bestseller</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Mark as popular/bestselling product</p>
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
                            <strong><i class="fas fa-gift"></i> Special Offer</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Show as special offer card on homepage</p>
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
                </div>

                <!-- Strong Offer Discount Percentage -->
                <div class="form-group" id="discount-percentage-group" style="margin-top: 16px; <?php echo e(old('is_strong_offer', $product->is_strong_offer ?? false) ? '' : 'display: none;'); ?>">
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
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.discount_percentage_help') ?? 'Enter discount percentage between 0 and 100'); ?>

                    </p>
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
        </div>

        <!-- Product Attributes Card -->
        <div class="card" id="attributes-card" style="<?php echo e(!empty($categoryAttributes) && $categoryAttributes->count() > 0 ? '' : 'display: none;'); ?>">
            <div class="card-header">
                <h2><i class="fas fa-tags"></i> Product Attributes</h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;">Select attributes specific to this product's category</p>
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
                            <i class="fas fa-info-circle"></i> No attributes configured for this category
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Product
            </button>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>

        <!-- Danger Zone -->
        <div class="delete-section">
            <div class="danger-zone">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    Danger Zone
                </h3>
                <p>Deleting this product will permanently remove it from your store. This action cannot be undone.</p>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> Delete Product
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
        if (confirm('Are you sure you want to delete "<?php echo e($product->name); ?>"?\n\nThis action cannot be undone and will permanently remove this product from your store.')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Dynamic attribute loading on category change
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const attributesCard = document.getElementById('attributes-card');
        const attributesContainer = document.getElementById('attributes-container');
        const currentCategoryId = '<?php echo e($product->category_id); ?>';
        
        // Strong Offer checkbox toggle for discount percentage field
        const strongOfferCheckbox = document.getElementById('is_strong_offer');
        const discountPercentageGroup = document.getElementById('discount-percentage-group');
        
        if (strongOfferCheckbox && discountPercentageGroup) {
            // Show/hide discount percentage field based on checkbox state
            function toggleDiscountField() {
                if (strongOfferCheckbox.checked) {
                    discountPercentageGroup.style.display = 'block';
                } else {
                    discountPercentageGroup.style.display = 'none';
                    document.getElementById('discount_percentage').value = '';
                }
            }
            
            // Initial state
            toggleDiscountField();
            
            // Listen for changes
            strongOfferCheckbox.addEventListener('change', toggleDiscountField);
        }
        
        // Store currently selected attribute values
        let selectedValues = [];
        
        function getSelectedValues() {
            const checkboxes = document.querySelectorAll('input[name="attribute_values[]"]:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        // Load attributes when category changes
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Store selected values before reload
            selectedValues = getSelectedValues();
            
            if (!categoryId) {
                attributesCard.style.display = 'none';
                return;
            }

            // Show loading state
            attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Loading attributes...</p>';
            attributesCard.style.display = 'block';

            // Fetch attributes for this category
            fetch(`/admin/products/category-attributes/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.attributes && data.attributes.length > 0) {
                        renderAttributes(data.attributes, categoryId === currentCategoryId);
                    } else {
                        attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-info-circle"></i> No attributes configured for this category</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading attributes:', error);
                    attributesContainer.innerHTML = '<p style="color: #dc2626; text-align: center; padding: 20px;"><i class="fas fa-exclamation-triangle"></i> Error loading attributes</p>';
                });
        });

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

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\admin\products\edit.blade.php ENDPATH**/ ?>