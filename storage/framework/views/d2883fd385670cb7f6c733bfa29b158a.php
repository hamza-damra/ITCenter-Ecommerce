<?php $__env->startSection('title', __('messages.reviews')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Page-specific styles that extend unified components */
    
    /* Search Box in Hero */
    .admin-hero .search-box {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .admin-hero .search-box input {
        padding: 0.75rem 1.25rem;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        min-width: 250px;
        background: rgba(255,255,255,0.95);
        color: #334155;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .admin-hero .search-box input::placeholder {
        color: #94a3b8;
    }

    .admin-hero .search-box input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
    }

    .admin-hero .btn-search {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        border: 2px solid rgba(255,255,255,0.3);
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-hero .btn-search:hover {
        background: rgba(255,255,255,0.3);
    }

    .admin-hero .btn-clear {
        background: rgba(255,255,255,0.1);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-hero .btn-clear:hover {
        background: rgba(255,255,255,0.2);
    }

    .admin-hero .btn-delete-all {
        background: #dc2626;
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-hero .btn-delete-all:hover {
        background: #b91c1c;
        transform: translateY(-2px);
    }

    .admin-hero .btn-delete-all:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Product Cell */
    .product-cell {
        display: flex;
        flex-direction: column;
    }

    .product-cell .product-name {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .product-cell .product-slug {
        font-size: 0.8rem;
        color: #94a3b8;
        font-family: 'Courier New', monospace;
    }

    /* User Cell */
    .user-cell {
        display: flex;
        flex-direction: column;
    }

    .user-cell .user-name {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.25rem;
    }

    .user-cell .user-email {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    /* Rating Stars */
    .rating-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .rating-stars {
        display: flex;
        gap: 0.15rem;
    }

    .rating-stars i {
        font-size: 0.95rem;
    }

    .rating-stars .fas.fa-star {
        color: #f59e0b;
    }

    .rating-stars .far.fa-star {
        color: #e2e8f0;
    }

    .rating-value {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Comment Cell */
    .comment-text {
        font-size: 0.9rem;
        color: #475569;
        line-height: 1.5;
        max-width: 350px;
    }

    /* Date Cell */
    .date-text {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Action Buttons */
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-delete {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        transform: translateY(-2px);
    }

    /* Alert */
    .alert-success-custom {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-left: 4px solid #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .alert-info-custom {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-left: 4px solid #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        border-top: 1px solid #f1f5f9;
    }

    /* RTL Support */
    [dir="rtl"] .header-actions {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .admin-hero .search-box {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .rating-stars {
        flex-direction: row-reverse;
    }

    /* Toast */
    .admin-toast {
        position: fixed;
        top: 80px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        opacity: 0;
        transform: translateX(400px);
        transition: all 0.3s ease;
        max-width: 400px;
    }

    .admin-toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .admin-toast-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-toast-content i {
        font-size: 1.25rem;
    }

    .admin-toast-success {
        border-left: 4px solid #10b981;
    }

    .admin-toast-success i {
        color: #10b981;
    }

    .admin-toast-error {
        border-left: 4px solid #ef4444;
    }

    .admin-toast-error i {
        color: #ef4444;
    }

    .admin-toast-info {
        border-left: 4px solid #3b82f6;
    }

    .admin-toast-info i {
        color: #3b82f6;
    }

    /* Deleting Animation */
    @keyframes fadeOutRow {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }

    .deleting-row {
        animation: fadeOutRow 0.4s ease-out forwards;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
        }

        .admin-hero .search-box {
            flex-direction: column;
        }

        .admin-hero .search-box input {
            min-width: unset;
            width: 100%;
        }

        .comment-text {
            max-width: 200px;
        }
    }
</style>

<!-- Page Header - Using unified admin-hero component -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <h1><?php echo e(__('messages.reviews')); ?></h1>
                <p><?php echo e(__('messages.manage_product_reviews')); ?></p>
            </div>
        </div>
        <div class="header-actions">
            <form method="GET" action="<?php echo e(route('admin.reviews.index')); ?>" class="search-box">
                <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="<?php echo e(__('messages.search')); ?>...">
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> <?php echo e(__('messages.search')); ?>

                </button>
                <?php if($search): ?>
                    <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn-clear">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>
            <form id="delete-all-reviews-form" method="POST" action="<?php echo e(route('admin.reviews.delete-all')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="button" class="btn-delete-all" onclick="return confirmDeleteAllReviews();" <?php echo e(($totalReviews ?? 0) == 0 ? 'disabled' : ''); ?>>
                    <i class="fas fa-trash-alt"></i> <?php echo e(__('messages.delete_all')); ?>

                </button>
            </form>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert-success-custom">
        <i class="fas fa-check-circle"></i>
        <span><?php echo e(session('success')); ?></span>
    </div>
<?php endif; ?>

<?php if(session('info')): ?>
    <div class="alert-info-custom">
        <i class="fas fa-info-circle"></i>
        <span><?php echo e(session('info')); ?></span>
    </div>
<?php endif; ?>

<!-- Statistics - Using unified admin-stats-grid component -->
<?php
    $totalReviewsCount = $totalReviews ?? $reviews->total() ?? 0;
    $avgRating = \App\Models\Review::avg('rating') ?? 0;
    $fiveStarCount = \App\Models\Review::where('rating', 5)->count();
    $oneStarCount = \App\Models\Review::where('rating', '<=', 2)->count();
?>
<div class="admin-stats-grid">
    <div class="admin-stat-card stat-indigo">
        <h4><i class="fas fa-comments"></i> <?php echo e(__('messages.total_reviews')); ?></h4>
        <div class="stat-value"><?php echo e($totalReviewsCount); ?></div>
    </div>
    <div class="admin-stat-card stat-warning">
        <h4><i class="fas fa-star"></i> <?php echo e(__('messages.average_rating')); ?></h4>
        <div class="stat-value"><?php echo e(number_format($avgRating, 1)); ?></div>
    </div>
    <div class="admin-stat-card stat-success">
        <h4><i class="fas fa-trophy"></i> <?php echo e(__('messages.five_star_reviews')); ?></h4>
        <div class="stat-value"><?php echo e($fiveStarCount); ?></div>
    </div>
    <div class="admin-stat-card stat-danger">
        <h4><i class="fas fa-thumbs-down"></i> <?php echo e(__('messages.low_ratings')); ?></h4>
        <div class="stat-value"><?php echo e($oneStarCount); ?></div>
    </div>
</div>

<!-- Reviews Table - Using unified admin-table-container component -->
<div class="admin-table-container">
    <div class="admin-table-header">
        <h3><i class="fas fa-list"></i> <?php echo e(__('messages.reviews_list')); ?></h3>
    </div>
    
    <?php if($reviews->count() > 0): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><?php echo e(__('messages.product')); ?></th>
                    <th><?php echo e(__('messages.user')); ?></th>
                    <th><?php echo e(__('messages.rating')); ?></th>
                    <th><?php echo e(__('messages.comment')); ?></th>
                    <th><?php echo e(__('messages.date')); ?></th>
                    <th><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="product-cell">
                            <?php if($review->product): ?>
                                <span class="product-name"><?php echo e($review->product->name_en ?? $review->product->name); ?></span>
                                <span class="product-slug"><?php echo e($review->product->slug); ?></span>
                            <?php else: ?>
                                <em style="color: #94a3b8;">—</em>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="user-cell">
                            <?php if($review->user): ?>
                                <span class="user-name"><?php echo e($review->user->name); ?></span>
                                <span class="user-email"><?php echo e($review->user->email); ?></span>
                            <?php else: ?>
                                <em style="color: #94a3b8;">—</em>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="rating-display">
                            <div class="rating-stars">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= (int)$review->rating): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-value">(<?php echo e(number_format((float)$review->rating, 1)); ?>)</span>
                        </div>
                    </td>
                    <td>
                        <p class="comment-text"><?php echo e(\Illuminate\Support\Str::limit($review->comment, 120)); ?></p>
                    </td>
                    <td>
                        <span class="date-text"><?php echo e($review->created_at?->format('Y-m-d H:i')); ?></span>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.reviews.destroy', $review->id)); ?>" class="delete-review-form" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-action btn-delete">
                                <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        <?php echo e($reviews->links()); ?>

    </div>
    <?php else: ?>
    <!-- Empty State - Using unified admin-empty-state component -->
    <div class="admin-empty-state">
        <div class="admin-empty-state-icon">
            <i class="fas fa-star"></i>
        </div>
        <h3><?php echo e(__('messages.no_reviews_found')); ?></h3>
        <p><?php echo e(__('messages.no_reviews_description')); ?></p>
    </div>
    <?php endif; ?>
</div>

<script>
// Toast notification
function showAdminToast(message, type = 'info') {
    const existingToast = document.querySelector('.admin-toast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = `admin-toast admin-toast-${type}`;
    toast.innerHTML = `
        <div class="admin-toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Handle individual review deletion with animation
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-review-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const row = this.closest('tr');
            row.classList.add('deleting-row');

            setTimeout(() => {
                this.submit();
            }, 200);
        });
    });
});

function confirmDeleteAllReviews() {
    var total = <?php echo e((int)($totalReviews ?? 0)); ?>;
    if (total <= 0) {
        showAdminToast('<?php echo e(__('messages.no_records_to_delete')); ?>', 'info');
        return false;
    }

    showAdminToast('Deleting all ' + total + ' reviews...', 'info');

    setTimeout(() => {
        document.getElementById('delete-all-reviews-form').submit();
    }, 800);

    return false;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>