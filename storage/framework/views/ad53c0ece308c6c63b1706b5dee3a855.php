<?php $__env->startSection('title', __('messages.manage_category_attributes')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .attributes-form-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        padding: 30px;
        max-width: 900px;
        margin: 0 auto;
    }

    .category-info-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .category-info-box h3 {
        margin: 0 0 8px 0;
        color: var(--dark);
        font-size: 20px;
    }

    .category-info-box p {
        margin: 0;
        color: var(--secondary);
        font-size: 14px;
    }

    .attributes-section {
        margin-bottom: 30px;
    }

    .attributes-section h4 {
        font-size: 16px;
        color: var(--dark);
        margin-bottom: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .attributes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 12px;
    }

    .attribute-checkbox-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: #f8fafc;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .attribute-checkbox-item:hover {
        background: #f1f5f9;
        border-color: var(--primary);
    }

    .attribute-checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 12px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    .attribute-checkbox-item label {
        cursor: pointer;
        margin: 0;
        flex: 1;
        font-size: 14px;
        color: var(--dark);
        font-weight: 500;
    }

    .attribute-checkbox-item.checked {
        background: #dbeafe;
        border-color: var(--primary);
    }

    .attribute-type-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-left: 8px;
    }

    .attribute-type-select {
        background: #e0e7ff;
        color: #3730a3;
    }

    .attribute-type-multi_select {
        background: #fce7f3;
        color: #831843;
    }

    .attribute-type-range {
        background: #d1fae5;
        color: #065f46;
    }

    .attribute-type-color {
        background: #fef3c7;
        color: #78350f;
    }

    .empty-attributes-message {
        text-align: center;
        padding: 40px 20px;
        color: var(--secondary);
    }

    .empty-attributes-message i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
        display: block;
    }

    .empty-attributes-message p {
        margin: 0 0 20px 0;
        font-size: 16px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .selection-summary {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .selection-summary i {
        color: var(--primary);
        font-size: 20px;
    }

    .selection-summary span {
        color: var(--dark);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .attributes-grid {
            grid-template-columns: 1fr;
        }

        .attributes-form-wrapper {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><?php echo e(__('messages.manage_category_attributes')); ?></h1>
        <p><?php echo e(__('messages.assign_attributes_to_category_subtitle')); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_categories')); ?>

        </a>
    </div>
</div>

<div class="attributes-form-wrapper">
    <!-- Category Info -->
    <div class="category-info-box">
        <h3>
            <i class="fas fa-folder"></i> <?php echo e($category->name_en ?? $category->name); ?>

        </h3>
        <p>
            <strong><?php echo e(__('messages.slug')); ?>:</strong> <?php echo e($category->slug); ?>

            <?php if($category->parent): ?>
                | <strong><?php echo e(__('messages.parent_category')); ?>:</strong> <?php echo e($category->parent->name_en ?? $category->parent->name); ?>

            <?php endif; ?>
        </p>
    </div>

    <!-- Selection Summary -->
    <div class="selection-summary" id="selectionSummary" style="display: none;">
        <i class="fas fa-check-circle"></i>
        <span id="selectionCount">0</span> <?php echo e(__('messages.attributes_selected')); ?>

    </div>

    <!-- Form -->
    <form action="<?php echo e(route('admin.categories.attributes.update', $category)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="attributes-section">
            <h4>
                <i class="fas fa-filter"></i> <?php echo e(__('messages.available_attributes')); ?>

            </h4>

            <?php if($allAttributes->count() > 0): ?>
                <div class="attributes-grid">
                    <?php $__currentLoopData = $allAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="attribute-checkbox-item <?php echo e(in_array($attribute->id, $assignedAttributes) ? 'checked' : ''); ?>" 
                             onclick="toggleCheckbox(this)">
                            <input 
                                type="checkbox" 
                                name="attributes[]" 
                                value="<?php echo e($attribute->id); ?>"
                                id="attribute_<?php echo e($attribute->id); ?>"
                                <?php echo e(in_array($attribute->id, $assignedAttributes) ? 'checked' : ''); ?>

                                onchange="updateSelectionSummary()"
                            >
                            <label for="attribute_<?php echo e($attribute->id); ?>">
                                <?php echo e($attribute->name_en ?? $attribute->name); ?>

                                <span class="attribute-type-badge attribute-type-<?php echo e($attribute->type); ?>">
                                    <?php echo e($attribute->type); ?>

                                </span>
                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="empty-attributes-message">
                    <i class="fas fa-inbox"></i>
                    <p><?php echo e(__('messages.no_filterable_attributes_available')); ?></p>
                    <a href="<?php echo e(route('admin.attributes.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> <?php echo e(__('messages.create_attribute')); ?>

                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Form Actions -->
        <?php if($allAttributes->count() > 0): ?>
            <div class="form-actions">
                <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> <?php echo e(__('messages.save_assignments')); ?>

                </button>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    function toggleCheckbox(element) {
        const checkbox = element.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        
        if (checkbox.checked) {
            element.classList.add('checked');
        } else {
            element.classList.remove('checked');
        }
        
        updateSelectionSummary();
    }

    function updateSelectionSummary() {
        const checkedBoxes = document.querySelectorAll('input[name="attributes[]"]:checked');
        const summary = document.getElementById('selectionSummary');
        const count = document.getElementById('selectionCount');
        
        count.textContent = checkedBoxes.length;
        
        if (checkedBoxes.length > 0) {
            summary.style.display = 'flex';
        } else {
            summary.style.display = 'none';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectionSummary();
    });
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/categories/attributes.blade.php ENDPATH**/ ?>