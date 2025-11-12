<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <div class="content-header">
        <div>
            <h1><i class="fas fa-bullhorn"></i> <?php echo e(__('messages.promotional_offers_title')); ?></h1>
            <p><?php echo e(__('messages.promotional_offers_subtitle')); ?></p>
        </div>
        <a href="<?php echo e(route('admin.promotional-offers.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_offer')); ?>

        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?php echo e(__('messages.image')); ?></th>
                        <th><?php echo e(__('messages.title')); ?></th>
                        <th><?php echo e(__('messages.product')); ?></th>
                        <th><?php echo e(__('messages.original_price')); ?></th>
                        <th><?php echo e(__('messages.sale_price')); ?></th>
                        <th><?php echo e(__('messages.discount')); ?></th>
                        <th><?php echo e(__('messages.start_date')); ?></th>
                        <th><?php echo e(__('messages.end_date')); ?></th>
                        <th><?php echo e(__('messages.status')); ?></th>
                        <th><?php echo e(__('messages.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($offer->product && $offer->product->main_image): ?>
                            <img src="<?php echo e($offer->product->main_image); ?>" alt="<?php echo e($offer->title); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            <?php else: ?>
                            <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: #999;"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e($offer->title); ?></strong>
                        </td>
                        <td><?php echo e($offer->product ? $offer->product->name : 'N/A'); ?></td>
                        <td>₪<?php echo e(number_format($offer->original_price, 2)); ?></td>
                        <td><strong style="color: #ff4757;">₪<?php echo e(number_format($offer->sale_price, 2)); ?></strong></td>
                        <td>
                            <span class="badge badge-success">
                                <?php echo e($offer->discount_percentage); ?>% (₪<?php echo e(number_format($offer->discount_amount, 2)); ?>)
                            </span>
                        </td>
                        <td><?php echo e($offer->start_date->format('Y-m-d H:i')); ?></td>
                        <td><?php echo e($offer->end_date->format('Y-m-d H:i')); ?></td>
                        <td>
                            <button class="badge <?php echo e($offer->is_active ? 'badge-success' : 'badge-danger'); ?>" 
                                    onclick="toggleActive(<?php echo e($offer->id); ?>)"
                                    style="cursor: pointer; border: none;">
                                <?php echo e($offer->is_active ? __('messages.active') : __('messages.inactive')); ?>

                            </button>
                        </td>
                        <td class="action-btns">
                            <a href="<?php echo e(route('admin.promotional-offers.edit', $offer->id)); ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.promotional-offers.destroy', $offer->id)); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('<?php echo e(__('messages.confirm_delete_offer')); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                            <p><?php echo e(__('messages.no_offers_currently')); ?></p>
                            <a href="<?php echo e(route('admin.promotional-offers.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_offer')); ?>

                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($offers->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($offers->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleActive(offerId) {
    if (!confirm('<?php echo e(__('messages.confirm_toggle_status')); ?>')) return;
    
    fetch(`/admin/promotional-offers/${offerId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/admin/promotional-offers/index.blade.php ENDPATH**/ ?>