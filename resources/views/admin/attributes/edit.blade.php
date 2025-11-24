@extends('admin.layout')

@section('title', 'Edit Attribute')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Edit Attribute</h1>
        <p>Update attribute information</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Attributes
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-edit"></i> Attribute Information</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST" class="form-layout">
            @csrf
            @method('PUT')

            <!-- Multi-language Names Section -->
            <div class="form-section">
                <h3 class="section-title">Attribute Names (Multi-language)</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            Name (English)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en', $attribute->name_en) }}" 
                            placeholder="e.g., Refresh Rate"
                            required>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_ar" class="form-label">
                            Name (Arabic)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_ar" 
                            name="name_ar" 
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar', $attribute->name_ar) }}" 
                            placeholder="مثال: معدل التحديث"
                            required 
                            dir="rtl">
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name_he" class="form-label">
                            Name (Hebrew)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_he" 
                            name="name_he" 
                            class="form-control @error('name_he') is-invalid @enderror" 
                            value="{{ old('name_he', $attribute->name_he) }}" 
                            placeholder="לדוגמה: קצב רענון"
                            required 
                            dir="rtl">
                        @error('name_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">
                            Slug
                        </label>
                        <input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            class="form-control @error('slug') is-invalid @enderror" 
                            value="{{ old('slug', $attribute->slug) }}" 
                            placeholder="Auto-generated from English name">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Leave empty to auto-generate from English name
                        </p>
                        @error('slug')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Attribute Configuration Section -->
            <div class="form-section">
                <h3 class="section-title">Attribute Configuration</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="type" class="form-label">
                            Type
                            <span class="required">*</span>
                        </label>
                        <select 
                            id="type" 
                            name="type" 
                            class="form-control @error('type') is-invalid @enderror"
                            required>
                            <option value="">Select Type</option>
                            <option value="select" {{ old('type', $attribute->type) == 'select' ? 'selected' : '' }}>Select (Single Choice)</option>
                            <option value="multi_select" {{ old('type', $attribute->type) == 'multi_select' ? 'selected' : '' }}>Multi-Select (Multiple Choices)</option>
                            <option value="range" {{ old('type', $attribute->type) == 'range' ? 'selected' : '' }}>Range (Min-Max)</option>
                            <option value="color" {{ old('type', $attribute->type) == 'color' ? 'selected' : '' }}>Color</option>
                        </select>
                        @error('type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit" class="form-label">
                            Unit
                        </label>
                        <input 
                            type="text" 
                            id="unit" 
                            name="unit" 
                            class="form-control @error('unit') is-invalid @enderror" 
                            value="{{ old('unit', $attribute->unit) }}" 
                            placeholder="e.g., Hz, GB, inches">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Optional unit of measurement (e.g., Hz, GB, inches)
                        </p>
                        @error('unit')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="order" class="form-label">
                            Display Order
                        </label>
                        <input 
                            type="number" 
                            id="order" 
                            name="order" 
                            class="form-control @error('order') is-invalid @enderror" 
                            value="{{ old('order', $attribute->order) }}" 
                            min="0"
                            placeholder="0">
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> Lower numbers appear first
                        </p>
                        @error('order')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div class="form-section">
                <h3 class="section-title">Settings</h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_filterable" 
                            name="is_filterable" 
                            value="1" 
                            {{ old('is_filterable', $attribute->is_filterable) ? 'checked' : '' }}>
                        <span>
                            <strong>Filterable</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Show this attribute in filter sidebars</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_active" 
                            name="is_active" 
                            value="1" 
                            {{ old('is_active', $attribute->is_active) ? 'checked' : '' }}>
                        <span>
                            <strong>Active</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Make this attribute available for use</p>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Attribute Values Info -->
            @if($attribute->values->count() > 0)
                <div class="form-section">
                    <h3 class="section-title">Attribute Values ({{ $attribute->values->count() }})</h3>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <p style="margin: 0 0 12px 0; color: #64748b; font-size: 14px;">
                            <i class="fas fa-info-circle"></i> This attribute has {{ $attribute->values->count() }} value(s). Manage values separately.
                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($attribute->values as $value)
                                <span style="background: white; padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; color: #475569;">
                                    {{ $value->value_en }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Actions -->
            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e2e8f0; margin-top: 24px;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Attribute
                </button>
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
