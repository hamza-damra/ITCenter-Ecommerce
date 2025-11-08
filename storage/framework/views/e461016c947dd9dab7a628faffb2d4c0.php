<?php $__env->startSection('title', __t('messages.my_profile') . ' - IT Center'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .profile-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .profile-header {
        background: linear-gradient(135deg, #4169E1 0%, #2762f3 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(65, 105, 225, 0.2);
    }

    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar-section {
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .profile-info h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
    }

    .profile-info p {
        margin: 0.25rem 0;
        opacity: 0.95;
    }

    .profile-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1rem;
    }

    .profile-stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-stat i {
        font-size: 1.2rem;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-card.full-width {
        grid-column: 1 / -1;
    }

    .profile-card h2 {
        margin: 0 0 1.5rem 0;
        font-size: 1.5rem;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-card h2 i {
        color: #4169E1;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #4169E1;
        box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.1);
    }

    .form-group input.error {
        border-color: #dc3545;
    }

    .form-group .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: #4169E1;
        color: white;
    }

    .btn-primary:hover {
        background: #2762f3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(65, 105, 225, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid #4169E1;
        color: #4169E1;
    }

    .btn-outline:hover {
        background: #4169E1;
        color: white;
    }

    .avatar-upload-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .avatar-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #4169E1;
    }

    .avatar-preview-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #666;
        border: 3px solid #e0e0e0;
    }

    .avatar-upload-actions {
        flex: 1;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .file-input-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert i {
        font-size: 1.25rem;
    }

    .danger-zone {
        border: 2px solid #dc3545;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .danger-zone h3 {
        color: #dc3545;
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .danger-zone p {
        color: #666;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
        }

        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }

        .profile-stats {
            justify-content: center;
        }

        .profile-info h1 {
            font-size: 1.5rem;
        }
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-header h3 {
        margin: 0;
        color: #1a1a1a;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
    }

    .modal-close:hover {
        color: #000;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
</style>

<div class="profile-container">
    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo e(session('success')); ?></span>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo e(session('error')); ?></span>
    </div>
    <?php endif; ?>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar-section">
                <?php if($user->avatar): ?>
                    <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="profile-avatar">
                <?php else: ?>
                    <div class="profile-avatar-placeholder">
                        <?php echo e(strtoupper(substr($user->first_name ?? $user->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h1><?php echo e($user->name); ?></h1>
                <p><i class="fas fa-envelope"></i> <?php echo e($user->email); ?></p>
                <?php if($user->phone): ?>
                <p><i class="fas fa-phone"></i> <?php echo e($user->phone); ?></p>
                <?php endif; ?>
                <div class="profile-stats">
                    <div class="profile-stat">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?php echo e(__t('messages.member_since')); ?> <?php echo e($user->created_at->format('M Y')); ?></span>
                    </div>
                    <div class="profile-stat">
                        <i class="fas fa-box"></i>
                        <span><?php echo e($ordersCount); ?> <?php echo e(__t('messages.orders')); ?></span>
                    </div>
                    <div class="profile-stat">
                        <i class="fas fa-star"></i>
                        <span><?php echo e($reviewsCount); ?> <?php echo e(__t('messages.reviews')); ?></span>
                    </div>
                    <?php if($hasVerifiedPurchases): ?>
                    <div class="profile-stat">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        <span><?php echo e(__t('messages.verified_buyer')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Personal Information -->
        <div class="profile-card">
            <h2><i class="fas fa-user"></i> <?php echo e(__t('messages.personal_information')); ?></h2>

            <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Avatar Upload -->
                <div class="avatar-upload-section">
                    <?php if($user->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="avatar-preview" id="avatarPreview">
                    <?php else: ?>
                        <div class="avatar-preview-placeholder" id="avatarPreview">
                            <?php echo e(strtoupper(substr($user->first_name ?? $user->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>
                    <div class="avatar-upload-actions">
                        <div class="file-input-wrapper">
                            <label for="avatar" class="btn btn-outline">
                                <i class="fas fa-upload"></i> <?php echo e(__t('messages.change_avatar')); ?>

                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp">
                        </div>
                        <?php if($user->avatar): ?>
                        <form action="<?php echo e(route('profile.avatar.delete')); ?>" method="POST" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-secondary" onclick="return confirm('<?php echo e(__t('messages.confirm_delete_avatar')); ?>')">
                                <i class="fas fa-trash"></i> <?php echo e(__t('messages.remove')); ?>

                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="form-group">
                    <label for="first_name"><?php echo e(__t('messages.first_name')); ?> *</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" required>
                    <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="last_name"><?php echo e(__t('messages.last_name')); ?> *</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" required>
                    <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="email"><?php echo e(__t('messages.email')); ?> *</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="phone"><?php echo e(__t('messages.phone')); ?></label>
                    <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo e(__t('messages.save_changes')); ?>

                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="profile-card">
            <h2><i class="fas fa-lock"></i> <?php echo e(__t('messages.change_password')); ?></h2>

            <form action="<?php echo e(route('profile.password.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label for="current_password"><?php echo e(__t('messages.current_password')); ?> *</label>
                    <input type="password" id="current_password" name="current_password" required>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="new_password"><?php echo e(__t('messages.new_password')); ?> *</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8">
                    <small style="color: #666;"><?php echo e(__t('messages.password_min_8')); ?></small>
                    <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation"><?php echo e(__t('messages.confirm_new_password')); ?> *</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                    <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> <?php echo e(__t('messages.update_password')); ?>

                </button>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="profile-card">
            <h2><i class="fas fa-link"></i> <?php echo e(__t('messages.quick_links')); ?></h2>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-box"></i> <?php echo e(__t('messages.my_orders')); ?>

                </a>
                <a href="<?php echo e(route('favorites')); ?>" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-heart"></i> <?php echo e(__t('messages.my_favorites')); ?>

                </a>
                <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-shopping-cart"></i> <?php echo e(__t('messages.my_cart')); ?>

                </a>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="profile-card">
            <h2><i class="fas fa-chart-bar"></i> <?php echo e(__t('messages.account_statistics')); ?></h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;"><?php echo e($ordersCount); ?></div>
                    <div style="color: #666; margin-top: 0.5rem;"><?php echo e(__t('messages.total_orders')); ?></div>
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;"><?php echo e($reviewsCount); ?></div>
                    <div style="color: #666; margin-top: 0.5rem;"><?php echo e(__t('messages.total_reviews')); ?></div>
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;"><?php echo e($user->favoriteProducts()->count()); ?></div>
                    <div style="color: #666; margin-top: 0.5rem;"><?php echo e(__t('messages.favorites')); ?></div>
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;"><?php echo e((int) \Carbon\Carbon::parse($user->created_at)->diffInDays(\Carbon\Carbon::now())); ?></div>
                    <div style="color: #666; margin-top: 0.5rem;"><?php echo e(__t('messages.days_member')); ?></div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="profile-card full-width">
            <div class="danger-zone">
                <h3><i class="fas fa-exclamation-triangle"></i> <?php echo e(__t('messages.danger_zone')); ?></h3>
                <p><?php echo e(__t('messages.delete_account_warning')); ?></p>
                <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
                    <i class="fas fa-trash"></i> <?php echo e(__t('messages.delete_account')); ?>

                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal" id="deleteAccountModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><?php echo e(__t('messages.confirm_delete_account')); ?></h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <form action="<?php echo e(route('profile.destroy')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <p style="color: #666; margin-bottom: 1.5rem;">
                <?php echo e(__t('messages.delete_account_confirmation')); ?>

            </p>

            <div class="form-group">
                <label for="delete_password"><?php echo e(__t('messages.enter_password_to_confirm')); ?> *</label>
                <input type="password" id="delete_password" name="password" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    <?php echo e(__t('messages.cancel')); ?>

                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> <?php echo e(__t('messages.delete_account')); ?>

                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Avatar preview
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('<?php echo e(__t('messages.avatar_size_error')); ?>');
                e.target.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('<?php echo e(__t('messages.avatar_format_error')); ?>');
                e.target.value = '';
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace placeholder with image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Avatar Preview';
                    img.className = 'avatar-preview';
                    img.id = 'avatarPreview';
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Delete account modal
    function openDeleteModal() {
        document.getElementById('deleteAccountModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteAccountModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('delete_password').value = '';
    }

    // Close modal on outside click
    document.getElementById('deleteAccountModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('deleteAccountModal');
            if (modal.classList.contains('active')) {
                closeDeleteModal();
            }
        }
    });

    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });

    // Password confirmation validation
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');

    if (newPassword && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (this.value !== newPassword.value) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
    }

    // Form submission loading state
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?php echo e(__t('messages.processing')); ?>';

                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 5000);
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/profile.blade.php ENDPATH**/ ?>