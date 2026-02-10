@extends('admin.layout')

@section('title', __('messages.edit_employee'))

@section('content')
<style>
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .form-actions .btn {
        padding: 12px 28px;
    }

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<div class="admin-hero">
    <div class="admin-hero-content">
        <div class="admin-hero-text">
            <div class="admin-hero-icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <div>
                <h1>{{ __('messages.edit_employee') }}: {{ $employee->name }}</h1>
                <p>{{ __('messages.edit_employee_subtitle') }}</p>
            </div>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.employees.update', $employee) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h2><i class="fas fa-user" style="color: var(--primary);"></i> {{ __('messages.employee_information') }}</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.full_name') }} <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" placeholder="{{ __('messages.enter_full_name') }}" required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.email') }} <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" placeholder="{{ __('messages.enter_email') }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $employee->phone) }}" placeholder="{{ __('messages.enter_phone') }}">
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.role') }} <span class="required">*</span></label>
                    <select name="employee_role_id" class="form-control @error('employee_role_id') is-invalid @enderror" required>
                        <option value="">{{ __('messages.select_role') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('employee_role_id', $employee->employee_role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_role_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.new_password') }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('messages.leave_blank_to_keep') }}">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ __('messages.password_change_hint') }}</div>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('messages.confirm_password_placeholder') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('messages.status') }}</label>
                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>
                @error('status')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ __('messages.save_changes') }}
        </button>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
        </a>
    </div>
</form>
@endsection
