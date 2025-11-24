<style>
    /* Reviews Section Styles */
    .reviews-section {
        margin-top: 3rem;
        background: #fff;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .reviews-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .write-review-btn {
        background: #2762f3;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .write-review-btn:hover {
        background: #1e4fc7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 98, 243, 0.3);
    }

    /* Rating Summary */
    .rating-summary {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 2rem;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .rating-summary {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }

    .rating-overview {
        text-align: center;
    }

    .avg-rating-number {
        font-size: 3.5rem;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .rating-stars-large {
        font-size: 1.5rem;
        color: #ffc107;
        margin-bottom: 0.5rem;
    }

    .total-reviews-text {
        color: #666;
        font-size: 0.95rem;
    }

    /* Rating Distribution */
    .rating-distribution {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .rating-bar-row {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .rating-label {
        min-width: 60px;
        font-size: 0.9rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .rating-bar-container {
        flex: 1;
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }

    .rating-bar-fill {
        height: 100%;
        background: #ffc107;
        transition: width 0.3s;
    }

    .rating-count {
        min-width: 40px;
        text-align: <?php echo e(is_rtl() ? 'left' : 'right'); ?>;
        font-size: 0.9rem;
        color: #666;
    }

    /* Reviews Controls */
    .reviews-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .sort-dropdown {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .sort-dropdown:hover {
        border-color: #2762f3;
    }

    /* Review Item */
    .review-item {
        padding: 1.5rem 0;
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    /* Smooth slide-in animation for new reviews */
    .review-item.new-review-animation {
        animation: slideInFromTop 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    /* Highlight flash for updated reviews */
    .review-item.updated-flash {
        animation: highlightFlash 1s ease-out forwards;
    }

    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-15px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes highlightFlash {
        0%, 100% {
            background-color: transparent;
        }
        50% {
            background-color: #e3f2fd;
        }
    }

    /* Fade-out animation for deleted reviews */
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }

    .review-item.deleting {
        animation: fadeOut 0.4s ease-out forwards;
    }

    /* Highlight for current user's review */
    .my-review {
        background: #fffdf5;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        padding-left: 1rem;
    }

    .your-review-badge {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        background: #eef2ff;
        color: #1d4ed8;
        padding: .25rem .5rem;
        border-radius: 4px;
        font-size: .75rem;
        font-weight: 700;
    }

    /* Edit and Delete button styles */
    .review-actions .helpful-btn[style*="border-color:#0d6efd"] {
        transition: all 0.3s ease;
    }

    .review-actions .helpful-btn[style*="border-color:#0d6efd"]:hover {
        background: #0d6efd;
        color: white !important;
    }

    .review-actions .helpful-btn[style*="border-color:#dc3545"] {
        transition: all 0.3s ease;
    }

    .review-actions .helpful-btn[style*="border-color:#dc3545"]:hover {
        background: #dc3545;
        color: white !important;
    }


    .review-item:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .reviewer-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2762f3, #e69270ff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .reviewer-details h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .review-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        font-size: 0.85rem;
        color: #666;
    }

    .review-rating {
        color: #ffc107;
        font-size: 0.9rem;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .review-date {
        color: #999;
    }

    .review-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .review-comment {
        color: #555;
        line-height: 1.7;
        margin-bottom: 1rem;
    }

    .review-images {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .review-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .review-image:hover {
        transform: scale(1.05);
    }

    .review-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .review-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .helpful-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
        color: #666;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .helpful-btn:hover {
        border-color: #2762f3;
        color: #2762f3;
        background: #f0f5ff;
    }

    .helpful-btn.active {
        border-color: #2762f3;
        background: #2762f3;
        color: white;
    }

    /* Review Form */
    .review-form-container {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .review-form-container.active {
        display: block;
        animation: slideDown 0.3s ease forwards;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #2762f3;
        box-shadow: 0 0 0 3px rgba(39, 98, 243, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .star-rating-input {
        display: flex;
        gap: 0.5rem;
        font-size: 2rem;
    }

    .star-rating-input i {
        cursor: pointer;
        color: #ddd;
        transition: color 0.2s;
    }

    .star-rating-input i.active,
    .star-rating-input i:hover {
        color: #ffc107;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-submit {
        background: #2762f3;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #1e4fc7;
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #666;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .no-reviews-message {
        text-align: center;
        padding: 3rem 2rem;
        color: #666;
    }

    .no-reviews-message i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination button {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .pagination button:hover:not(:disabled) {
        border-color: #2762f3;
        color: #2762f3;
    }

    .pagination button.active {
        background: #2762f3;
        color: white;
        border-color: #2762f3;
    }

    .pagination button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Toast Notification Styles */
    .review-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        opacity: 0;
        transform: translateX(400px);
        transition: all 0.3s ease;
        max-width: 400px;
    }

    .review-toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .review-toast-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .review-toast-content i {
        font-size: 1.25rem;
    }

    .review-toast-success {
        border-left: 4px solid #28a745;
    }

    .review-toast-success i {
        color: #28a745;
    }

    .review-toast-error {
        border-left: 4px solid #dc3545;
    }

    .review-toast-error i {
        color: #dc3545;
    }

    .review-toast-info {
        border-left: 4px solid #2762f3;
    }

    .review-toast-info i {
        color: #2762f3;
    }

    /* Localized loading overlay for review form and individual review items */
    .local-loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 3;
        border-radius: 12px;
    }

    .local-loading-overlay .spinner {
        width: 28px;
        height: 28px;
        border: 3px solid rgba(0, 0, 0, 0.15);
        border-top-color: #2762f3;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .review-form-container.is-loading > *:not(.local-loading-overlay),
    .review-item.is-loading > *:not(.local-loading-overlay) {
        opacity: 0.5;
    }

    .review-form-container.is-loading .local-loading-overlay,
    .review-item.is-loading .local-loading-overlay {
        display: flex;
    }

    /* Ensure containers create a stacking context for the local overlay */
    .review-form-container,
    .review-item {
        position: relative;
    }

    /* Inline Error Styles */
    .inline-error {
        background: #fee;
        border: 1px solid #fcc;
        border-left: 4px solid #dc3545;
        color: #c33;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.3s ease;
    }

    .inline-error i {
        font-size: 1.25rem;
        color: #dc3545;
    }

    .inline-error.fade-out {
        animation: fadeOut 0.3s ease forwards;
    }

    /* Mobile responsive toast */
    @media (max-width: 768px) {
        .review-toast {
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }


</style>

<!-- Reviews Section -->
<?php
    $authUser = auth()->user();
    $userReview = null;
    $hasReviewed = false;
    if ($authUser) {
        $userReview = $product->reviews()->where('user_id', $authUser->id)->first();
        $hasReviewed = (bool) $userReview;
    }
?>

<div class="reviews-section" id="reviews-section">
    <!-- Reviews Header -->
    <div class="reviews-header">
        <h2><?php echo e(__('messages.customer_reviews')); ?></h2>
        <?php if(auth()->guard()->check()): ?>
            <?php if(!$hasReviewed): ?>
                <button class="write-review-btn" onclick="toggleReviewForm()">
                    <i class="fas fa-pen"></i>
                    <?php echo e(__('messages.write_review')); ?>

                </button>
            <?php else: ?>
                <button class="write-review-btn" style="background:#6c757d" onclick="startEditReviewById(<?php echo e($userReview->id); ?>)">
                    <i class="fas fa-edit"></i>
                    <?php echo e(__('messages.edit') ?? 'Edit'); ?> <?php echo e(__('messages.review') ?? 'Review'); ?>

                </button>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="write-review-btn">
                <i class="fas fa-sign-in-alt"></i>
                <?php echo e(__('messages.login_to_review')); ?>

            </a>
        <?php endif; ?>
    </div>

    <!-- Rating Summary -->
    <div class="rating-summary">
        <div class="rating-overview">
            <div class="avg-rating-number" id="avg-rating-display">
                <?php echo e(number_format($product->avg_rating ?? 0, 1)); ?>

            </div>
            <div class="rating-stars-large">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star<?php echo e($i <= round($product->avg_rating ?? 0) ? '' : '-o'); ?>"></i>
                <?php endfor; ?>
            </div>
            <div class="total-reviews-text" id="total-reviews-text">
                <?php echo e(__('messages.based_on_reviews', ['count' => $product->reviews_count ?? 0])); ?>

            </div>
        </div>

        <div class="rating-distribution" id="rating-distribution">
            <?php
                $distribution = $product->rating_distribution ?? [];
            ?>
            <?php for($i = 5; $i >= 1; $i--): ?>
                <?php
                    $data = $distribution[$i] ?? ['count' => 0, 'percentage' => 0];
                ?>
                <div class="rating-bar-row">
                    <div class="rating-label">
                        <?php echo e($i); ?> <i class="fas fa-star" style="color: #ffc107; font-size: 0.8rem;"></i>
                    </div>
                    <div class="rating-bar-container">
                        <div class="rating-bar-fill" style="width: <?php echo e($data['percentage']); ?>%"></div>
                    </div>
                    <div class="rating-count"><?php echo e($data['count']); ?></div>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Review Form (Hidden by default) -->
    <?php if(auth()->guard()->check()): ?>
    <div class="review-form-container" id="review-form-container">
        <h3 style="margin-bottom: 1.5rem;"><?php echo e(__('messages.write_review')); ?></h3>
        <form id="review-form" onsubmit="submitReview(event)">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label><?php echo e(__('messages.your_rating')); ?> <span style="color: red;">*</span></label>
                <div class="star-rating-input" id="star-rating-input">
                    <i class="far fa-star" data-rating="1" onclick="setRating(1)"></i>
                    <i class="far fa-star" data-rating="2" onclick="setRating(2)"></i>
                    <i class="far fa-star" data-rating="3" onclick="setRating(3)"></i>
                    <i class="far fa-star" data-rating="4" onclick="setRating(4)"></i>
                    <i class="far fa-star" data-rating="5" onclick="setRating(5)"></i>
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
            </div>

        <div class="local-loading-overlay"><div class="spinner"></div></div>

            <div class="form-group">
                <label><?php echo e(__('messages.review_title')); ?></label>
                <input type="text" name="title" id="review-title" placeholder="<?php echo e(__('messages.review_title')); ?>" maxlength="100">
            </div>

            <div class="form-group">
                <label><?php echo e(__('messages.review_comment')); ?> <span style="color: red;">*</span></label>
                <textarea name="comment" id="review-comment" placeholder="<?php echo e(__('messages.review_comment')); ?>" required minlength="10" maxlength="1000"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> <?php echo e(__('messages.submit_review')); ?>

                </button>
                <button type="button" class="btn-cancel" onclick="toggleReviewForm()">
                    <?php echo e(__('messages.cancel')); ?>

                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Reviews Controls -->
    <div class="reviews-controls">
        <div>
            <span style="font-weight: 600; color: #1a1a1a;" id="reviews-count-display">
                <?php echo e($product->reviews_count ?? 0); ?> <?php echo e(__('messages.reviews')); ?>

            </span>
        </div>
        <div>
            <select class="sort-dropdown" id="sort-reviews" onchange="loadReviews()">
                <option value="recent"><?php echo e(__('messages.most_recent')); ?></option>
                <option value="helpful"><?php echo e(__('messages.most_helpful')); ?></option>
                <option value="highest"><?php echo e(__('messages.highest_rating')); ?></option>
                <option value="lowest"><?php echo e(__('messages.lowest_rating')); ?></option>
            </select>
        </div>
    </div>

    <!-- Reviews List -->
    <div id="reviews-list">
        <div style="text-align: center; padding: 2rem;">
            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #2762f3;"></i>
        </div>
    </div>

    <!-- Pagination -->
    <div id="reviews-pagination"></div>
</div>



<script>
    // Ensure functions are defined in global scope immediately
    (function() {
        'use strict';

        // Global variables
        window.reviewsCurrentPage = 1;
        window.reviewsCurrentRating = 0;
        window.reviewsProductSlug = '<?php echo e($product->slug); ?>';
        window.authUserId = <?php echo json_encode(auth()->id(), 15, 512) ?>;
        window.userReviewId = <?php echo json_encode(optional($userReview)->id, 15, 512) ?>;
        window.hasUserReviewed = <?php echo json_encode($hasReviewed, 15, 512) ?>;

        // Localized loading helper: toggles a small overlay on a specific container
        window.setLocalLoading = function(targetEl, enable = true) {
            if (!targetEl) return;
            let overlay = targetEl.querySelector('.local-loading-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'local-loading-overlay';
                overlay.innerHTML = '<div class="spinner"></div>';
                targetEl.appendChild(overlay);
            }
            if (enable) {
                targetEl.classList.add('is-loading');
            } else {
                targetEl.classList.remove('is-loading');
            }
        };

        // Toggle review form - Define globally
        window.toggleReviewForm = function() {
            const formContainer = document.getElementById('review-form-container');

            if (!formContainer) {
                console.error('Review form container not found. User may not be authenticated.');
                // Show a toast message to the user
                showToast('<?php echo e(__("messages.login_to_review") ?? "Please login to write a review"); ?>', 'info');
                return;
            }

            formContainer.classList.toggle('active');

            if (formContainer.classList.contains('active')) {
                const reviewForm = document.getElementById('review-form');
                if (reviewForm) {
                    // Small delay to ensure the form is visible before scrolling
                    setTimeout(() => {
                        reviewForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 100);
                }
            } else {
                // Reset editing state and form when closing
                window.editingReviewId = null;
                const reviewForm = document.getElementById('review-form');
                if (reviewForm) {
                    reviewForm.reset();
                    const submitBtn2 = reviewForm.querySelector('.btn-submit');
                    if (submitBtn2) submitBtn2.innerHTML = '<i class="fas fa-paper-plane"></i> <?php echo e(__("messages.submit_review")); ?>';
                }
                const header = formContainer.querySelector('h3');
                if (header) header.textContent = '<?php echo e(__("messages.write_review")); ?>';
                window.setRating(0);
            }
        };

        // Set rating - Define globally
        window.setRating = function(rating) {
            window.reviewsCurrentRating = rating;
            const ratingInput = document.getElementById('rating-input');
            if (ratingInput) {
                ratingInput.value = rating;
            }

            const stars = document.querySelectorAll('#star-rating-input i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'active');
                } else {
                    star.classList.remove('fas', 'active');
                    star.classList.add('far');
                }
            });
        };

        // Submit review - Define globally
        window.submitReview = async function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('.btn-submit');

            // Validate rating
            const ratingInput = document.getElementById('rating-input');
            if (!ratingInput || !ratingInput.value) {
                // Show inline error instead of alert
                showInlineError('<?php echo e(__("messages.please_select_rating") ?? "Please select a rating"); ?>');
                return;
            }

            if (!submitBtn) {
                console.error('Submit button not found');
                return;
            }

            // Localized loading: form when creating, specific review card when editing
            const isEditMode = !!window.editingReviewId;
            let localLoadingTarget = null;
            if (isEditMode) {
                localLoadingTarget = document.querySelector(`.review-item[data-review-id="${window.editingReviewId}"]`);
            } else {
                localLoadingTarget = document.getElementById('review-form-container');
            }
            if (localLoadingTarget) window.setLocalLoading(localLoadingTarget, true);

            // Disable submit button
            submitBtn.disabled = true;
            const originalButtonText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__("messages.submitting") ?? "Submitting..."); ?>';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                const isEdit = !!window.editingReviewId;
                let url = isEdit ? `/api/v1/reviews/${window.editingReviewId}` : `/api/v1/products/${window.reviewsProductSlug}/reviews`;
                let method = 'POST';
                if (isEdit) {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method,
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                // Handle 419 CSRF token mismatch - redirect to login
                if (response.status === 419) {
                    console.warn('Session expired (419). Redirecting to login...');
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();

                if (response.ok && data.success) {
                    const isEdit = !!window.editingReviewId;
                    // Show success toast notification
                    const defaultMsg = isEdit ? '<?php echo e(__("messages.review_updated_success") ?? "Review updated successfully!"); ?>' : '<?php echo e(__("messages.review_submitted_success") ?? "Review submitted successfully!"); ?>';
                    showToast(data.message || defaultMsg, 'success');

                    // Reset form and editing state
                    form.reset();
                    window.setRating(0);
                    window.editingReviewId = null;

                    // Close the form
                    const formContainer = document.getElementById('review-form-container');
                    if (formContainer) {
                        formContainer.classList.remove('active');
                    }

                    // Update the UI dynamically without page reload
                    if (isEdit) {
                        // For edit: update the review in place with flash animation
                        if (data.data && data.data.review) {
                            window.updateReviewInPlace(data.data.review);
                        }

                        // Button should remain as "Edit Review" since user still has a review
                        const writeReviewBtn = document.querySelector('.write-review-btn');
                        if (writeReviewBtn && window.userReviewId) {
                            writeReviewBtn.innerHTML = '<i class="fas fa-edit"></i> <?php echo e(__("messages.edit") ?? "Edit"); ?> <?php echo e(__("messages.review") ?? "Review"); ?>';
                            writeReviewBtn.setAttribute('onclick', `startEditReviewById(${window.userReviewId})`);
                            writeReviewBtn.style.background = '#6c757d';
                        }
                    } else {
                        // For new review: prepend with smooth animation (optimistic UI)
                        window.hasUserReviewed = true;
                        if (data.data && data.data.review) {
                            window.userReviewId = data.data.review.id;
                            // Prepend the new review at the top with animation
                            window.prependNewReview(data.data.review);
                        }

                        // Update the button from "Write Review" to "Edit Review"
                        const writeReviewBtn = document.querySelector('.write-review-btn');
                        if (writeReviewBtn && window.userReviewId) {
                            writeReviewBtn.innerHTML = '<i class="fas fa-edit"></i> <?php echo e(__("messages.edit") ?? "Edit"); ?> <?php echo e(__("messages.review") ?? "Review"); ?>';
                            writeReviewBtn.setAttribute('onclick', `startEditReviewById(${window.userReviewId})`);
                            writeReviewBtn.style.background = '#6c757d';
                        }
                    }

                    // Reset form header and button text
                    const header = formContainer?.querySelector('h3');
                    if (header) header.textContent = '<?php echo e(__("messages.write_review")); ?>';
                    const submitBtn2 = form.querySelector('.btn-submit');
                    if (submitBtn2) submitBtn2.innerHTML = '<i class="fas fa-paper-plane"></i> <?php echo e(__("messages.submit_review")); ?>';
                } else {
                    // Handle error response
                    const errorMessage = data.message || '<?php echo e(__("messages.review_submit_failed") ?? "Failed to submit review"); ?>';
                    showToast(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error submitting review:', error);
                showToast('<?php echo e(__("messages.review_submit_failed") ?? "Failed to submit review. Please try again."); ?>', 'error');
            } finally {
                // Hide localized loading overlay
                if (typeof localLoadingTarget !== 'undefined' && localLoadingTarget) window.setLocalLoading(localLoadingTarget, false);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalButtonText || '<i class="fas fa-paper-plane"></i> <?php echo e(__("messages.submit_review")); ?>';
            }
        };

        // Load reviews - Define globally
        window.loadReviews = async function(page = 1) {
            window.reviewsCurrentPage = page;
            const sortByElement = document.getElementById('sort-reviews');
            const sortBy = sortByElement ? sortByElement.value : 'recent';
            const reviewsList = document.getElementById('reviews-list');

            if (!reviewsList) {
                console.error('Reviews list element not found');
                return;
            }

            // Show loading
            reviewsList.innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #2762f3;"></i></div>';

            try {
                const response = await fetch(`/api/v1/products/${window.reviewsProductSlug}/reviews?page=${page}&sort_by=${sortBy}&per_page=10`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.data) {
                    window.displayReviews(data.data.reviews || []);
                    window.displayPagination(data.meta || {});
                    if (data.data.stats) {
                        window.updateRatingStats(data.data.stats);
                    }
                } else {
                    reviewsList.innerHTML = '<div class="no-reviews-message"><i class="fas fa-comment-slash"></i><p><?php echo e(__("messages.no_reviews_yet")); ?></p></div>';
                }
            } catch (error) {
                console.error('Error loading reviews:', error);
                reviewsList.innerHTML = '<div class="no-reviews-message"><p><?php echo e(__("messages.error_loading_reviews") ?? "Error loading reviews. Please try again later."); ?></p></div>';
            }
        };

        // Display reviews - Define globally
        window.displayReviews = function(reviews) {
            const reviewsList = document.getElementById('reviews-list');

            if (!reviewsList) {
                console.error('Reviews list element not found');
                return;
            }

            if (!reviews || reviews.length === 0) {
                reviewsList.innerHTML = '<div class="no-reviews-message"><i class="fas fa-comment-slash"></i><p><?php echo e(__("messages.no_reviews_yet")); ?></p></div>';
                return;
            }

            // cache for edit/delete
            window.reviewsCache = reviews;

            let html = '';
            reviews.forEach(review => {
                try {
                    const userInitial = review.user?.name ? review.user.name.charAt(0).toUpperCase() : 'U';
                    const stars = window.generateStars(review.rating || 0);
                    const verifiedBadge = review.is_verified_purchase ?
                        `<span class=\"verified-badge\"><i class=\"fas fa-check-circle\"></i> <?php echo e(__("messages.verified_purchase")); ?></span>` : '';
                    const isMine = !!(window.authUserId && review.user && String(review.user.id) === String(window.authUserId));
                    const yourBadge = isMine ? '<span class=\"your-review-badge\"><i class=\"fas fa-user-check\"></i> Your review</span>' : '';

                    html += `
                        <div class="review-item ${isMine ? 'my-review' : ''}" data-review-id="${review.id}">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">${userInitial}</div>
                                    <div class="reviewer-details">
                                        <h4>${window.escapeHtml(review.user?.name || 'Anonymous')}</h4>
                                        <div class="review-meta">
                                            <span class="review-rating">${stars}</span>
                                            ${verifiedBadge}
                                            ${yourBadge}
                                            <span class="review-date">${window.formatDate(review.created_at)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ${review.title ? `<div class="review-title">${window.escapeHtml(review.title)}</div>` : ''}
                            <div class="review-comment">${window.escapeHtml(review.comment || '')}</div>
                            <div class="review-actions">
                                <button class="helpful-btn" onclick="markHelpful(${review.id})">
                                    <i class="fas fa-thumbs-up"></i>
                                    <?php echo e(__("messages.helpful")); ?> (${review.helpful_count || 0})
                                </button>
                                ${isMine ? `
                                    <button class="helpful-btn" style="border-color:#0d6efd;color:#0d6efd" onclick="startEditReviewById(${review.id})">
                                        <i class="fas fa-edit"></i> <?php echo e(__("messages.edit") ?? 'Edit'); ?>

                                    </button>
                                    <button class="helpful-btn" style="border-color:#dc3545;color:#dc3545" onclick="deleteReviewById(${review.id})">
                                        <i class="fas fa-trash"></i> <?php echo e(__("messages.delete")); ?>

                                    </button>
                                ` : ''}
                            </div>
                            <div class=\"local-loading-overlay\"><div class=\"spinner\"></div></div>
                        </div>
                    `;
                } catch (error) {
                    console.error('Error rendering review:', error, review);
                }
            });

            reviewsList.innerHTML = html;
        };

        // Helper: Prepend a single new review with animation (optimistic UI)
        window.prependNewReview = function(reviewData) {
            const reviewsList = document.getElementById('reviews-list');
            if (!reviewsList) return;

            // Update cache: prepend to the beginning of the cache array
            if (!window.reviewsCache) {
                window.reviewsCache = [];
            }
            // Add to beginning of cache (since we're prepending to DOM)
            window.reviewsCache.unshift(reviewData);

            // Build HTML for the single review
            const userInitial = reviewData.user?.name ? reviewData.user.name.charAt(0).toUpperCase() : 'U';
            const stars = window.generateStars(reviewData.rating || 0);
            const verifiedBadge = reviewData.is_verified_purchase ?
                `<span class="verified-badge"><i class="fas fa-check-circle"></i> <?php echo e(__("messages.verified_purchase")); ?></span>` : '';
            const isMine = !!(window.authUserId && reviewData.user && String(reviewData.user.id) === String(window.authUserId));
            const yourBadge = isMine ? '<span class="your-review-badge"><i class="fas fa-user-check"></i> Your review</span>' : '';

            const reviewHTML = `
                <div class="review-item new-review-animation ${isMine ? 'my-review' : ''}" data-review-id="${reviewData.id}">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">${userInitial}</div>
                            <div class="reviewer-details">
                                <h4>${window.escapeHtml(reviewData.user?.name || 'Anonymous')}</h4>
                                <div class="review-meta">
                                    <span class="review-rating">${stars}</span>
                                    ${verifiedBadge}
                                    ${yourBadge}
                                    <span class="review-date">${window.formatDate(reviewData.created_at)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    ${reviewData.title ? `<div class="review-title">${window.escapeHtml(reviewData.title)}</div>` : ''}
                    <div class="review-comment">${window.escapeHtml(reviewData.comment || '')}</div>
                    <div class="review-actions">
                        <button class="helpful-btn" onclick="markHelpful(${reviewData.id})">
                            <i class="fas fa-thumbs-up"></i>
                            <?php echo e(__("messages.helpful")); ?> (${reviewData.helpful_count || 0})
                        </button>
                        ${isMine ? `
                            <button class="helpful-btn" style="border-color:#0d6efd;color:#0d6efd" onclick="startEditReviewById(${reviewData.id})">
                                <i class="fas fa-edit"></i> <?php echo e(__("messages.edit") ?? 'Edit'); ?>

                            </button>
                            <button class="helpful-btn" style="border-color:#dc3545;color:#dc3545" onclick="deleteReviewById(${reviewData.id})">
                                <i class="fas fa-trash"></i> <?php echo e(__("messages.delete")); ?>

                            </button>
                        ` : ''}
                    </div>
                    <div class="local-loading-overlay"><div class="spinner"></div></div>
                </div>
            `;

            // Check if "no reviews" message exists and remove it
            const noReviewsMsg = reviewsList.querySelector('.no-reviews-message');
            if (noReviewsMsg) {
                noReviewsMsg.remove();
            }

            // Prepend the new review at the top
            reviewsList.insertAdjacentHTML('afterbegin', reviewHTML);

            // Remove animation class after animation completes
            setTimeout(() => {
                const newReviewEl = reviewsList.querySelector('.new-review-animation');
                if (newReviewEl) newReviewEl.classList.remove('new-review-animation');
            }, 400);
        };

        // Helper: Update a single review in place with flash animation
        window.updateReviewInPlace = function(reviewData) {
            const reviewElement = document.querySelector(`.review-item[data-review-id="${reviewData.id}"]`);
            if (!reviewElement) {
                // If not found, fallback to reload
                window.loadReviews(window.reviewsCurrentPage || 1);
                return;
            }

            // Update cache: find and replace the review data
            if (window.reviewsCache && Array.isArray(window.reviewsCache)) {
                const index = window.reviewsCache.findIndex(r => String(r.id) === String(reviewData.id));
                if (index !== -1) {
                    window.reviewsCache[index] = reviewData;
                }
            }

            // Build updated HTML
            const userInitial = reviewData.user?.name ? reviewData.user.name.charAt(0).toUpperCase() : 'U';
            const stars = window.generateStars(reviewData.rating || 0);
            const verifiedBadge = reviewData.is_verified_purchase ?
                `<span class="verified-badge"><i class="fas fa-check-circle"></i> <?php echo e(__("messages.verified_purchase")); ?></span>` : '';
            const isMine = !!(window.authUserId && reviewData.user && String(reviewData.user.id) === String(window.authUserId));
            const yourBadge = isMine ? '<span class="your-review-badge"><i class="fas fa-user-check"></i> Your review</span>' : '';

            const updatedHTML = `
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">${userInitial}</div>
                        <div class="reviewer-details">
                            <h4>${window.escapeHtml(reviewData.user?.name || 'Anonymous')}</h4>
                            <div class="review-meta">
                                <span class="review-rating">${stars}</span>
                                ${verifiedBadge}
                                ${yourBadge}
                                <span class="review-date">${window.formatDate(reviewData.created_at)}</span>
                            </div>
                        </div>
                    </div>
                </div>
                ${reviewData.title ? `<div class="review-title">${window.escapeHtml(reviewData.title)}</div>` : ''}
                <div class="review-comment">${window.escapeHtml(reviewData.comment || '')}</div>
                <div class="review-actions">
                    <button class="helpful-btn" onclick="markHelpful(${reviewData.id})">
                        <i class="fas fa-thumbs-up"></i>
                        <?php echo e(__("messages.helpful")); ?> (${reviewData.helpful_count || 0})
                    </button>
                    ${isMine ? `
                        <button class="helpful-btn" style="border-color:#0d6efd;color:#0d6efd" onclick="startEditReviewById(${reviewData.id})">
                            <i class="fas fa-edit"></i> <?php echo e(__("messages.edit") ?? 'Edit'); ?>

                        </button>
                        <button class="helpful-btn" style="border-color:#dc3545;color:#dc3545" onclick="deleteReviewById(${reviewData.id})">
                            <i class="fas fa-trash"></i> <?php echo e(__("messages.delete")); ?>

                        </button>
                    ` : ''}
                </div>
                <div class="local-loading-overlay"><div class="spinner"></div></div>
            `;

            // Replace content and add flash animation
            reviewElement.innerHTML = updatedHTML;
            reviewElement.classList.add('updated-flash');

            // Remove flash class after animation
            setTimeout(() => {
                reviewElement.classList.remove('updated-flash');
            }, 1000);
        };

        // Generate stars HTML - Define globally
        window.generateStars = function(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<i class="fas fa-star${i <= rating ? '' : '-o'}"></i>`;
            }
            return stars;
        };

        // Format date - Define globally
        window.formatDate = function(dateString) {
            if (!dateString) return '<?php echo e(__("messages.unknown_date") ?? "Unknown date"); ?>';

            try {
                const date = new Date(dateString);

                // Check if date is valid
                if (isNaN(date.getTime())) {
                    return '<?php echo e(__("messages.unknown_date") ?? "Unknown date"); ?>';
                }

                const now = new Date();
                const diffTime = Math.abs(now - date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays === 0) return '<?php echo e(__("messages.today") ?? "Today"); ?>';
                if (diffDays === 1) return '<?php echo e(__("messages.yesterday") ?? "Yesterday"); ?>';
                if (diffDays < 7) return `${diffDays} <?php echo e(__("messages.days_ago") ?? "days ago"); ?>`;
                if (diffDays < 30) return `${Math.floor(diffDays / 7)} <?php echo e(__("messages.weeks_ago") ?? "weeks ago"); ?>`;
                if (diffDays < 365) return `${Math.floor(diffDays / 30)} <?php echo e(__("messages.months_ago") ?? "months ago"); ?>`;
                return `${Math.floor(diffDays / 365)} <?php echo e(__("messages.years_ago") ?? "years ago"); ?>`;
            } catch (error) {
                console.error('Error formatting date:', error);
                return '<?php echo e(__("messages.unknown_date") ?? "Unknown date"); ?>';
            }
        };

        // Escape HTML - Define globally
        window.escapeHtml = function(text) {
            if (!text) return '';

            try {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            } catch (error) {
                console.error('Error escaping HTML:', error);
                return String(text);
            }
        };

        // Display pagination - Define globally
        window.displayPagination = function(meta) {
            const pagination = document.getElementById('reviews-pagination');

            if (!pagination) {
                console.error('Pagination element not found');
                return;
            }

            if (!meta || !meta.last_page || meta.last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            const currentPage = meta.current_page || 1;
            const lastPage = meta.last_page || 1;

            let html = '<div class="pagination">';

            // Previous button
            html += `<button onclick="window.loadReviews(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-<?php echo e(is_rtl() ? 'right' : 'left'); ?>"></i>
            </button>`;

            // Page numbers
            for (let i = 1; i <= lastPage; i++) {
                if (i === 1 || i === lastPage || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    html += `<button onclick="window.loadReviews(${i})" class="${i === currentPage ? 'active' : ''}">${i}</button>`;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    html += '<span>...</span>';
                }
            }

            // Next button
            html += `<button onclick="window.loadReviews(${currentPage + 1})" ${currentPage === lastPage ? 'disabled' : ''}>
                <i class="fas fa-chevron-<?php echo e(is_rtl() ? 'left' : 'right'); ?>"></i>
            </button>`;

            html += '</div>';
            pagination.innerHTML = html;
        };

        // Update rating stats - Define globally
        window.updateRatingStats = function(stats) {
            if (!stats) return;

            const avgRatingDisplay = document.getElementById('avg-rating-display');
            const totalReviewsText = document.getElementById('total-reviews-text');
            const reviewsCountDisplay = document.getElementById('reviews-count-display');

            if (avgRatingDisplay && stats.average_rating !== undefined && stats.average_rating !== null) {
                avgRatingDisplay.textContent = Number(stats.average_rating).toFixed(1);
            }

            if (totalReviewsText && stats.total_reviews !== undefined) {
                const reviewsText = '<?php echo e(__("messages.based_on_reviews", ["count" => "COUNT_PLACEHOLDER"])); ?>';
                totalReviewsText.textContent = reviewsText.replace('COUNT_PLACEHOLDER', stats.total_reviews);
            }

            if (reviewsCountDisplay && stats.total_reviews !== undefined) {
                reviewsCountDisplay.textContent = `${stats.total_reviews} <?php echo e(__("messages.reviews")); ?>`;
            }

            // Update rating distribution if available
            if (stats.rating_distribution) {
                window.updateRatingDistribution(stats.rating_distribution);
            }
        };

        // Update rating distribution bars - Define globally
        window.updateRatingDistribution = function(distribution) {
            if (!distribution) return;
            const totalReviews = Object.values(distribution).reduce((sum, count) => sum + count, 0);

            for (let rating = 5; rating >= 1; rating--) {
                const count = distribution[rating] || 0;
                const percentage = totalReviews > 0 ? (count / totalReviews * 100) : 0;

                // Update the rating bar if elements exist
                const ratingBars = document.querySelectorAll('.rating-bar-row');
                if (ratingBars && ratingBars[5 - rating]) {
                    const barRow = ratingBars[5 - rating];
                    const fillBar = barRow.querySelector('.rating-bar-fill');
                    const countElement = barRow.querySelector('.rating-count');

                    if (fillBar) {
                        fillBar.style.width = `${percentage}%`;
                    }
                    if (countElement) {
                        countElement.textContent = count;
                    }
                }
            }
        };

        // Start edit for a given review id - Define globally
        window.startEditReviewById = function(reviewId) {
            try {
                const list = window.reviewsCache || [];
                const review = list.find(r => String(r.id) === String(reviewId));
                if (!review) {
                    showToast('Unable to find your review to edit.', 'error');
                    return;
                }
                // Ensure ownership
                if (!(window.authUserId && review.user && String(review.user.id) === String(window.authUserId))) {
                    showToast('You can only edit your own review.', 'error');
                    return;
                }
                window.editingReviewId = reviewId;

                // Open the form and pre-fill
                const formContainer = document.getElementById('review-form-container');
                const form = document.getElementById('review-form');
                if (!formContainer || !form) {
                    showToast('Review form is not available.', 'error');
                    return;
                }
                if (!formContainer.classList.contains('active')) {
                    formContainer.classList.add('active');
                }

                // Prefill fields
                window.setRating(parseInt(review.rating || 0, 10));
                const titleInput = document.getElementById('review-title');
                const commentInput = document.getElementById('review-comment');
                if (titleInput) titleInput.value = review.title || '';
                if (commentInput) commentInput.value = review.comment || '';

                // Update header and submit button
                const header = formContainer.querySelector('h3');
                if (header) header.textContent = '<?php echo e(__("messages.edit") ?? "Edit"); ?> <?php echo e(__("messages.review") ?? "Review"); ?>';
                const submitBtn = form.querySelector('.btn-submit');
                if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save"></i> <?php echo e(__("messages.save_changes") ?? "Save Changes"); ?>';

                setTimeout(() => {
                    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            } catch (e) {
                console.error('Error starting edit mode:', e);
            }
        };

        // Delete review by id - Define globally
        window.deleteReviewById = async function(reviewId) {
            // Localized loading: only on the specific review card
            const reviewElement = document.querySelector(`.review-item[data-review-id="${reviewId}"]`) || document.querySelector(`.review-item [onclick*="deleteReviewById(${reviewId})"]`)?.closest('.review-item');
            if (reviewElement) {
                window.setLocalLoading(reviewElement, true);
                reviewElement.classList.add('deleting');
            }

            // Remove confirmation dialog - delete immediately with animation
            try {

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const response = await fetch(`/api/v1/reviews/${reviewId}`, {
                    method: 'DELETE',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                // Handle 419 CSRF token mismatch - redirect to login
                if (response.status === 419) {
                    console.warn('Session expired (419). Redirecting to login...');
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();
                if (response.ok && data.success) {
                    // Show success toast
                    showToast(data.message || '<?php echo e(__("messages.review_deleted_success")); ?>', 'success');

                    // Update state to reflect that user no longer has a review
                    window.hasUserReviewed = false;
                    window.userReviewId = null;

                    // Wait for animation to complete, then remove from DOM
                    setTimeout(() => {
                        // Remove from cache
                        if (window.reviewsCache && Array.isArray(window.reviewsCache)) {
                            window.reviewsCache = window.reviewsCache.filter(r => String(r.id) !== String(reviewId));
                        }

                        // Remove the review element from DOM
                        if (reviewElement) {
                            reviewElement.remove();
                        }

                        // Check if there are any reviews left
                        const reviewsList = document.getElementById('reviews-list');
                        const remainingReviews = reviewsList?.querySelectorAll('.review-item');

                        // If no reviews left, show "no reviews" message
                        if (reviewsList && (!remainingReviews || remainingReviews.length === 0)) {
                            reviewsList.innerHTML = '<div class="no-reviews-message"><i class="fas fa-comment-slash"></i><p><?php echo e(__("messages.no_reviews_yet")); ?></p></div>';
                        }

                        // Update the button from "Edit Review" to "Write Review"
                        const writeReviewBtn = document.querySelector('.write-review-btn');
                        if (writeReviewBtn) {
                            writeReviewBtn.innerHTML = '<i class="fas fa-pen"></i> <?php echo e(__("messages.write_review") ?? "Write Review"); ?>';
                            writeReviewBtn.setAttribute('onclick', 'toggleReviewForm()');
                            writeReviewBtn.style.background = ''; // Reset to default color
                        }

                        // Close the form if it's open
                        const formContainer = document.getElementById('review-form-container');
                        if (formContainer && formContainer.classList.contains('active')) {
                            formContainer.classList.remove('active');
                        }

                        // Reset editing state
                        window.editingReviewId = null;

                        // Update review count in header if it exists
                        const reviewCountElement = document.querySelector('.reviews-header h2');
                        if (reviewCountElement) {
                            const currentText = reviewCountElement.textContent;
                            const match = currentText.match(/(\d+)/);
                            if (match) {
                                const currentCount = parseInt(match[1]);
                                const newCount = Math.max(0, currentCount - 1);
                                reviewCountElement.textContent = currentText.replace(/\d+/, newCount);
                            }
                        }

                        /* localized overlay will be removed with the element */
                    }, 500);
                } else {
                    // Remove deleting class if failed
                    if (reviewElement) {
                        reviewElement.classList.remove('deleting');
                    }
                    // Hide loading overlay
                    if (reviewElement) window.setLocalLoading(reviewElement, false);
                    showToast(data.message || '<?php echo e(__("messages.review_delete_failed") ?? "Failed to delete review"); ?>', 'error');
                }
            } catch (error) {
                console.error('Error deleting review', error);
                // Hide loading overlay
                if (reviewElement) window.setLocalLoading(reviewElement, false);
                showToast('<?php echo e(__("messages.review_delete_failed") ?? "Failed to delete review"); ?>', 'error');
            }
        };


        // Mark review as helpful - Define globally
        window.markHelpful = async function(reviewId) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                // Find the helpful button to update its count
                const helpfulBtn = document.querySelector(`.review-item [onclick*="markHelpful(${reviewId})"]`);
                const originalText = helpfulBtn ? helpfulBtn.innerHTML : '';

                const response = await fetch(`/api/v1/reviews/${reviewId}/helpful`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json',
                    }
                });

                // Handle 419 CSRF token mismatch - redirect to login
                if (response.status === 419) {
                    console.warn('Session expired (419). Redirecting to login...');
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();

                if (response.ok && data.success) {
                    // Show success toast
                    showToast('<?php echo e(__("messages.marked_helpful") ?? "Marked as helpful!"); ?>', 'success');

                    // Update the helpful count dynamically without reloading
                    if (helpfulBtn && data.data && data.data.helpful_count !== undefined) {
                        const newCount = data.data.helpful_count;
                        helpfulBtn.innerHTML = `<i class="fas fa-thumbs-up"></i> <?php echo e(__("messages.helpful")); ?> (${newCount})`;

                        // Add a brief animation to show the change
                        helpfulBtn.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            helpfulBtn.style.transform = 'scale(1)';
                        }, 200);
                    } else {
                        // Fallback: reload reviews if count not in response
                        window.loadReviews(window.reviewsCurrentPage);
                    }
                } else {
                    const errorMessage = data.message || '<?php echo e(__("messages.error_marking_helpful") ?? "Error marking review as helpful"); ?>';
                    showToast(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error marking review as helpful:', error);
                showToast('<?php echo e(__("messages.error_marking_helpful") ?? "Error marking review as helpful. Please try again."); ?>', 'error');
            }
        };

        // Toast notification system - Define globally
        window.showToast = function(message, type = 'info') {
            // Remove any existing toasts
            const existingToast = document.querySelector('.review-toast');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `review-toast review-toast-${type}`;
            toast.innerHTML = `
                <div class="review-toast-content">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;

            // Add to body
            document.body.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        };

        // Inline error display - Define globally
        window.showInlineError = function(message) {
            const form = document.getElementById('review-form');
            if (!form) return;

            // Remove any existing error messages
            const existingError = form.querySelector('.inline-error');
            if (existingError) {
                existingError.remove();
            }

            // Create error element
            const errorDiv = document.createElement('div');
            errorDiv.className = 'inline-error';
            errorDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <span>${message}</span>
            `;

            // Insert at the top of the form
            form.insertBefore(errorDiv, form.firstChild);

            // Auto remove after 4 seconds
            setTimeout(() => {
                errorDiv.classList.add('fade-out');
                setTimeout(() => {
                    errorDiv.remove();
                }, 300);
            }, 4000);
        };

        // Load reviews on page load
        document.addEventListener('DOMContentLoaded', function() {
            window.loadReviews();
        });

    })(); // End of IIFE
</script>


<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views\partials\reviews-section.blade.php ENDPATH**/ ?>