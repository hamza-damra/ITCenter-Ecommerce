@extends('admin.layout')

@section('title', __('messages.edit_user'))

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <h1><i class="fas fa-user-edit"></i> {{ __('messages.edit_user') }}</h1>
        <p>{{ __('messages.edit_user_subtitle', ['name' => $user->name]) }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_users') }}
        </a>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary">
            <i class="fas fa-eye"></i> {{ __('messages.view_details') }}
        </a>
    </div>
</div>

<!-- Edit User Form -->
<div class="card">
    <div class="card-header">
        <h2>{{ __('messages.user_information') }}</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form-layout">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-title">{{ __('messages.basic_information') }}</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="first_name">
                            {{ __('messages.first_name') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               class="form-control @error('first_name') is-invalid @enderror" 
                               placeholder="{{ __('messages.first_name_placeholder') }}"
                               value="{{ old('first_name', $user->first_name) }}"
                               required>
                        @error('first_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="last_name">
                            {{ __('messages.last_name') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               class="form-control @error('last_name') is-invalid @enderror" 
                               placeholder="{{ __('messages.last_name_placeholder') }}"
                               value="{{ old('last_name', $user->last_name) }}"
                               required>
                        @error('last_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <div class="section-title">{{ __('messages.contact_information') }}</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="email">
                            {{ __('messages.email') }} <span class="required">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="{{ __('messages.email_placeholder') }}"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('messages.email_must_be_unique') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">
                            {{ __('messages.phone') }}
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               placeholder="{{ __('messages.phone_placeholder') }}"
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="form-section">
                <div class="section-title">{{ __('messages.account_settings') }}</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="role">
                            {{ __('messages.user_role') }} <span class="required">*</span>
                        </label>
                        <select name="role" 
                                id="role" 
                                class="form-control @error('role') is-invalid @enderror"
                                required>
                            <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>
                                {{ __('messages.customer') }}
                            </option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                {{ __('messages.admin') }}
                            </option>
                        </select>
                        @error('role')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('messages.role_help_text') }}</div>
                    </div>
                </div>
            </div>

            <!-- Change Password (Optional) -->
            <div class="form-section">
                <div class="section-title">{{ __('messages.change_password') }} {{ __('messages.optional') }}</div>
                <div style="background: #fef3c7; padding: 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #f59e0b;">
                    <p style="margin: 0; color: #78350f; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle"></i>
                        {{ __('messages.leave_blank_to_keep_current_password') }}
                    </p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">
                            {{ __('messages.new_password') }}
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="{{ __('messages.password_placeholder') }}">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('messages.password_min_8_chars') }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            {{ __('messages.confirm_new_password') }}
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="form-control" 
                               placeholder="{{ __('messages.confirm_password_placeholder') }}">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 24px; border-top: 2px solid var(--border);">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> {{ __('messages.update_user') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- User Info Card -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <h2 style="color: var(--dark);">
            <i class="fas fa-info-circle"></i> {{ __('messages.user_info') }}
        </h2>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="padding: 16px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 8px;">
                <div style="font-size: 13px; color: #1e40af; margin-bottom: 4px; font-weight: 600;">
                    <i class="fas fa-calendar-plus"></i> {{ __('messages.registered_on') }}
                </div>
                <div style="font-size: 16px; font-weight: 700; color: #1e3a8a;">
                    {{ $user->created_at->format('Y-m-d H:i') }}
                </div>
                <div style="font-size: 12px; color: #3b82f6; margin-top: 4px;">
                    {{ $user->created_at->diffForHumans() }}
                </div>
            </div>

            <div style="padding: 16px; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 8px;">
                <div style="font-size: 13px; color: #15803d; margin-bottom: 4px; font-weight: 600;">
                    <i class="fas fa-edit"></i> {{ __('messages.last_updated') }}
                </div>
                <div style="font-size: 16px; font-weight: 700; color: #166534;">
                    {{ $user->updated_at->format('Y-m-d H:i') }}
                </div>
                <div style="font-size: 12px; color: #16a34a; margin-top: 4px;">
                    {{ $user->updated_at->diffForHumans() }}
                </div>
            </div>

            <div style="padding: 16px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 8px;">
                <div style="font-size: 13px; color: #92400e; margin-bottom: 4px; font-weight: 600;">
                    <i class="fas fa-hashtag"></i> {{ __('messages.user_id') }}
                </div>
                <div style="font-size: 16px; font-weight: 700; color: #78350f;">
                    #{{ $user->id }}
                </div>
                <div style="font-size: 12px; color: #a16207; margin-top: 4px;">
                    {{ __('messages.system_id') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

