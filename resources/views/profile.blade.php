@extends('layouts.app')

@section('title', __t('messages.my_profile') . ' - IT Center')

@section('content')
<!-- Import shared components CSS -->
<link rel="stylesheet" href="{{ asset('css/components.css') }}">

<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--space-12) var(--space-8);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light-blue) 100%);
        color: var(--text-white);
        padding: var(--space-8);
        border-radius: var(--radius-xl);
        margin-bottom: var(--space-8);
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .profile-header-content {
        display: flex;
        align-items: center;
        gap: var(--space-8);
        position: relative;
        z-index: 1;
    }

    .profile-avatar-section {
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--text-white);
        box-shadow: var(--shadow-lg);
        transition: all var(--transition-bounce);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-4xl);
        color: var(--text-white);
        border: 4px solid var(--text-white);
        box-shadow: var(--shadow-lg);
        transition: all var(--transition-bounce);
    }

    .profile-avatar-placeholder:hover {
        transform: scale(1.05);
        background: rgba(255, 255, 255, 0.3);
    }

    .profile-info h1 {
        margin: 0 0 var(--space-2) 0;
        font-size: var(--text-4xl);
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .profile-info p {
        margin: var(--space-1) 0;
        opacity: 0.95;
        font-size: var(--text-lg);
        font-weight: 400;
    }

    .profile-stats {
        display: flex;
        gap: var(--space-8);
        margin-top: var(--space-4);
        flex-wrap: wrap;
    }

    .profile-stat {
        display: flex;
        align-items: center;
        gap: var(--space-2);
        background: rgba(255, 255, 255, 0.1);
        padding: var(--space-3) var(--space-4);
        border-radius: var(--radius-lg);
        backdrop-filter: blur(10px);
        transition: all var(--transition-normal);
    }

    .profile-stat:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .profile-stat i {
        font-size: var(--text-xl);
        opacity: 0.9;
    }

    .profile-stat-value {
        font-weight: 700;
        font-size: var(--text-lg);
    }

    .profile-stat-label {
        font-size: var(--text-sm);
        opacity: 0.8;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-8);
    }

    @media (max-width: 968px) {
        .profile-content {
            grid-template-columns: 1fr;
            gap: var(--space-6);
        }
    }

    .profile-card {
        background: var(--bg-card);
        border-radius: var(--radius-xl);
        padding: var(--space-8);
        box-shadow: var(--shadow-md);
        transition: all var(--transition-bounce);
        border: 1px solid transparent;
    }

    .profile-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
        border-color: var(--primary-blue);
    }

    .profile-card.full-width {
        grid-column: 1 / -1;
    }

    .profile-card h2 {
        margin: 0 0 var(--space-6) 0;
        font-size: var(--text-2xl);
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: var(--space-3);
    }

    .profile-card h2 i {
        color: var(--primary-blue);
        font-size: var(--text-xl);
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
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar-section">
                @if($user->avatar)
                    <img src="{{ asset('media/' . $user->avatar) }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">
                        {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                @if($user->phone)
                <p><i class="fas fa-phone"></i> {{ $user->phone }}</p>
                @endif
                <div class="profile-stats">
                    <div class="profile-stat">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ __t('messages.member_since') }} {{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="profile-stat">
                        <i class="fas fa-box"></i>
                        <span>{{ $ordersCount }} {{ __t('messages.orders') }}</span>
                    </div>
                    <div class="profile-stat">
                        <i class="fas fa-star"></i>
                        <span>{{ $reviewsCount }} {{ __t('messages.reviews') }}</span>
                    </div>
                    @if($hasVerifiedPurchases)
                    <div class="profile-stat">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        <span>{{ __t('messages.verified_buyer') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Personal Information -->
        <div class="profile-card">
            <h2><i class="fas fa-user"></i> {{ __t('messages.personal_information') }}</h2>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar Upload -->
                <div class="avatar-upload-section">
                    @if($user->avatar)
                        <img src="{{ asset('media/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar-preview" id="avatarPreview">
                    @else
                        <div class="avatar-preview-placeholder" id="avatarPreview">
                            {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="avatar-upload-actions">
                        <div class="file-input-wrapper">
                            <label for="avatar" class="btn btn-outline">
                                <i class="fas fa-upload"></i> {{ __t('messages.change_avatar') }}
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp">
                        </div>
                        @if($user->avatar)
                        <form action="{{ route('profile.avatar.delete') }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" onclick="return confirm('{{ __t('messages.confirm_delete_avatar') }}')">
                                <i class="fas fa-trash"></i> {{ __t('messages.remove') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @error('avatar')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="first_name">{{ __t('messages.first_name') }} *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    @error('first_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">{{ __t('messages.last_name') }} *</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    @error('last_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ __t('messages.email') }} *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">{{ __t('messages.phone') }}</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __t('messages.save_changes') }}
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="profile-card">
            <h2><i class="fas fa-lock"></i> {{ __t('messages.change_password') }}</h2>

            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">{{ __t('messages.current_password') }} *</label>
                    <input type="password" id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">{{ __t('messages.new_password') }} *</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8">
                    <small style="color: #666;">{{ __t('messages.password_min_8') }}</small>
                    @error('new_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">{{ __t('messages.confirm_new_password') }} *</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                    @error('new_password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> {{ __t('messages.update_password') }}
                </button>
            </form>
        </div>

        <!-- Quick Links -->
        <div class="profile-card">
            <h2><i class="fas fa-link"></i> {{ __t('messages.quick_links') }}</h2>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('orders.index') }}" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-box"></i> {{ __t('messages.my_orders') }}
                </a>
                <a href="{{ route('favorites') }}" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-heart"></i> {{ __t('messages.my_favorites') }}
                </a>
                <a href="{{ route('cart.index') }}" class="btn btn-outline" style="text-decoration: none;">
                    <i class="fas fa-shopping-cart"></i> {{ __t('messages.my_cart') }}
                </a>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="profile-card">
            <h2><i class="fas fa-chart-bar"></i> {{ __t('messages.account_statistics') }}</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;">{{ $ordersCount }}</div>
                    <div style="color: #666; margin-top: 0.5rem;">{{ __t('messages.total_orders') }}</div>
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;">{{ $reviewsCount }}</div>
                    <div style="color: #666; margin-top: 0.5rem;">{{ __t('messages.total_reviews') }}</div>
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;">{{ $user->favoriteProducts()->count() }}</div>
                    <div style="color: #666; margin-top: 0.5rem;">{{ __t('messages.favorites') }}</div>
                </div>
                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: #4169E1;">{{ (int) \Carbon\Carbon::parse($user->created_at)->diffInDays(\Carbon\Carbon::now()) }}</div>
                    <div style="color: #666; margin-top: 0.5rem;">{{ __t('messages.days_member') }}</div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="profile-card full-width">
            <div class="danger-zone">
                <h3><i class="fas fa-exclamation-triangle"></i> {{ __t('messages.danger_zone') }}</h3>
                <p>{{ __t('messages.delete_account_warning') }}</p>
                <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
                    <i class="fas fa-trash"></i> {{ __t('messages.delete_account') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal" id="deleteAccountModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __t('messages.confirm_delete_account') }}</h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <form action="{{ route('profile.destroy') }}" method="POST">
            @csrf
            @method('DELETE')

            <p style="color: #666; margin-bottom: 1.5rem;">
                {{ __t('messages.delete_account_confirmation') }}
            </p>

            <div class="form-group">
                <label for="delete_password">{{ __t('messages.enter_password_to_confirm') }} *</label>
                <input type="password" id="delete_password" name="password" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    {{ __t('messages.cancel') }}
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> {{ __t('messages.delete_account') }}
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
                alert('{{ __t('messages.avatar_size_error') }}');
                e.target.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('{{ __t('messages.avatar_format_error') }}');
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
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __t('messages.processing') }}';

                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 5000);
            }
        });
    });
</script>

@endsection
