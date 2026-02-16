@extends('admin.layout')

@section('title', __('messages.edit_product'))

@section('content')
<style>
    /* Product Edit Page Specific Styles */
    .product-form-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    /* Character Counter Styles */
    .char-counter {
        text-align: right;
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }
    
    .char-counter.warning {
        color: #f59e0b;
    }
    
    .char-counter.danger {
        color: #ef4444;
        font-weight: 600;
    }
    
    .char-counter-input.near-limit {
        border-color: #f59e0b !important;
    }
    
    .char-counter-input.at-limit {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
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

    .current-additional-image-item {
        position: relative;
    }

    .current-additional-image-item .dropzone-remove {
        z-index: 2;
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
        <h1><i class="fas fa-edit"></i> {{ __('messages.edit_product') }}</h1>
        <p>{{ __('messages.update_product_info') }}: <strong>{{ $product->name }}</strong></p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_products') }}
        </a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="product-form-grid">
    @csrf
    @method('PUT')

    <!-- Main Form Content -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> {{ __('messages.basic_information') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            {{ __('messages.product_name_english') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control char-counter-input @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en', $product->name_en) }}" 
                            placeholder="Enter product name in English"
                            maxlength="{{ $inputLimits['name'] ?? 120 }}"
                            data-max-length="{{ $inputLimits['name'] ?? 120 }}"
                            required>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max">{{ $inputLimits['name'] ?? 120 }}</span>
                        </div>
                        @error('name_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
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
                            class="form-control char-counter-input @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar', $product->name_ar) }}" 
                            placeholder="أدخل اسم المنتج بالعربية"
                            maxlength="{{ $inputLimits['name'] ?? 120 }}"
                            data-max-length="{{ $inputLimits['name'] ?? 120 }}"
                            required 
                            dir="rtl">
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max">{{ $inputLimits['name'] ?? 120 }}</span>
                        </div>
                        @error('name_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name_he" class="form-label">
                            {{ __('messages.product_name_hebrew') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <input
                            type="text"
                            id="name_he"
                            name="name_he"
                            class="form-control char-counter-input @error('name_he') is-invalid @enderror"
                            value="{{ old('name_he', $product->name_he) }}"
                            placeholder="{{ __('messages.enter_product_name_hebrew') }}"
                            maxlength="{{ $inputLimits['name'] ?? 120 }}"
                            data-max-length="{{ $inputLimits['name'] ?? 120 }}"
                            dir="rtl">
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max">{{ $inputLimits['name'] ?? 120 }}</span>
                        </div>
                        @error('name_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            {{ __('messages.category') }}
                            <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">{{ __('messages.select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="brand_id" class="form-label">
                            {{ __('messages.brand') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                            <option value="">{{ __('messages.select_brand') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-dollar-sign"></i> {{ __('messages.pricing_inventory') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">
                            {{ __('messages.regular_price') }}
                            <span class="required">*</span>
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: var(--secondary); font-weight: 600;">$</span>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                class="form-control @error('price') is-invalid @enderror" 
                                step="0.01" 
                                value="{{ old('price', $product->price) }}" 
                                placeholder="0.00"
                                style="padding-left: 28px;"
                                required>
                        </div>
                        @error('price')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_price" class="form-label">
                            {{ __('messages.sale_price') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 12px; color: var(--secondary); font-weight: 600;">$</span>
                            <input 
                                type="number" 
                                id="sale_price" 
                                name="sale_price" 
                                class="form-control @error('sale_price') is-invalid @enderror" 
                                step="0.01" 
                                value="{{ old('sale_price', $product->sale_price) }}"
                                placeholder="0.00"
                                style="padding-left: 28px;">
                        </div>
                        @error('sale_price')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="discount_percentage" class="form-label">
                            {{ __('messages.discount_percentage') ?? 'Discount Percentage' }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="number" 
                                id="discount_percentage" 
                                name="discount_percentage" 
                                class="form-control @error('discount_percentage') is-invalid @enderror" 
                                step="0.01" 
                                min="0"
                                max="100"
                                value="{{ old('discount_percentage', $product->discount_percentage) }}" 
                                placeholder="0.00"
                                style="padding-right: 32px;">
                            <span style="position: absolute; right: 12px; top: 12px; color: var(--secondary); font-weight: 600;">%</span>
                        </div>
                        @error('discount_percentage')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="stock_quantity" class="form-label">
                            {{ __('messages.stock_quantity') }}
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            class="form-control @error('stock_quantity') is-invalid @enderror" 
                            value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                            placeholder="0"
                            required>
                        @error('stock_quantity')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-images"></i> {{ __('messages.product_images') }}</h2>
            </div>
            <div class="card-body">
                <!-- Image Source Toggle -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">{{ __('messages.image_source') ?? 'Image Source' }}</label>
                    <div style="display: flex; gap: 12px;">
                        <label class="checkbox-group" style="margin: 0; flex: 1; padding: 12px; border: 2px solid var(--primary); border-radius: 8px; background: #eff6ff; cursor: pointer;" id="source-file-label">
                            <input type="radio" name="image_source_type" value="file" {{ old('image_source_type', 'file') === 'file' ? 'checked' : '' }} onchange="toggleImageSource('file')">
                            <span>
                                <strong><i class="fas fa-upload"></i> {{ __('messages.upload_files') ?? 'Upload Files' }}</strong>
                                <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.upload_from_device') ?? 'Upload images from your device (stored on server)' }}</p>
                            </span>
                        </label>
                        <label class="checkbox-group" style="margin: 0; flex: 1; padding: 12px; border: 2px solid var(--border); border-radius: 8px; cursor: pointer;" id="source-url-label">
                            <input type="radio" name="image_source_type" value="url" {{ old('image_source_type', 'file') === 'url' ? 'checked' : '' }} onchange="toggleImageSource('url')">
                            <span>
                                <strong><i class="fas fa-link"></i> {{ __('messages.image_url') ?? 'Image URL' }}</strong>
                                <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.paste_external_url') ?? 'Paste external image URLs' }}</p>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Current Main Image Preview -->
                @if($product->main_image)
                    @php $primaryImage = $product->images->where('is_primary', true)->first(); @endphp
                    <div class="current-image-container" style="margin-bottom: 16px; position: relative;" id="current-main-image-container">
                        <div class="current-image-label">
                            <i class="fas fa-image"></i>
                            {{ __('messages.current_main_image') }}
                        </div>
                        <div style="position: relative; display: inline-block;">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                            @if($primaryImage)
                                <button type="button" class="dropzone-remove" onclick="deleteCurrentImage({{ $primaryImage->id }}, 'main', this)" title="{{ __('messages.delete') ?? 'Delete' }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                        <p class="form-text" style="margin-top: 8px;">
                            <i class="fas fa-info-circle"></i> {{ __('messages.leave_empty_to_keep') ?? 'Leave empty to keep the current image' }}
                        </p>
                    </div>
                @endif

                <!-- FILE UPLOAD MODE -->
                <div id="image-source-file" style="{{ old('image_source_type', 'file') === 'file' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label class="form-label">
                            {{ __('messages.main_product_image') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional_on_edit') }})</span>
                        </label>
                        <div class="dropzone-area @error('main_image_file') dropzone-error @enderror" id="main-dropzone" onclick="document.getElementById('main_image_file').click()">
                            <input type="file" id="main_image_file" name="main_image_file" accept="image/jpeg,image/png,image/webp" style="display:none;">
                            <div class="dropzone-content" id="main-dropzone-content">
                                <div class="dropzone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <p class="dropzone-title">{{ __('messages.drag_drop_image') }}</p>
                                <p class="dropzone-subtitle">{{ __('messages.or_click_to_browse') }}</p>
                                <span class="dropzone-formats">{{ __('messages.accepted_formats') }}</span>
                            </div>
                            <div class="dropzone-preview" id="main-dropzone-preview" style="display:none;">
                                <img id="main-image-preview-img" src="" alt="Preview">
                                <button type="button" class="dropzone-remove" onclick="event.stopPropagation(); removeMainImage();" title="{{ __('messages.remove') }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @error('main_image_file')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label class="form-label">
                            {{ __('messages.additional_images') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }} - {{ __('messages.max_10_files') }})</span>
                        </label>
                        <div class="dropzone-area dropzone-multi @error('additional_images_files') dropzone-error @enderror @error('additional_images_files.*') dropzone-error @enderror" id="additional-dropzone" onclick="document.getElementById('additional_images_files').click()">
                            <input type="file" id="additional_images_files" name="additional_images_files[]" accept="image/jpeg,image/png,image/webp" multiple style="display:none;">
                            <div class="dropzone-content" id="additional-dropzone-content">
                                <div class="dropzone-icon"><i class="fas fa-images"></i></div>
                                <p class="dropzone-title">{{ __('messages.drag_drop_images') }}</p>
                                <p class="dropzone-subtitle">{{ __('messages.or_click_to_browse') }}</p>
                                <span class="dropzone-formats">{{ __('messages.select_multiple_images') }}</span>
                            </div>
                        </div>
                        <div class="dropzone-grid" id="additional-images-preview"></div>
                        @error('additional_images_files')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        @error('additional_images_files.*')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- URL MODE (legacy) -->
                <div id="image-source-url" style="{{ old('image_source_type', 'file') === 'url' ? '' : 'display:none;' }}">
                    <div class="form-group">
                        <label for="main_image" class="form-label">
                            {{ __('messages.main_product_image') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional_on_edit') ?? 'Optional - leave empty to keep current' }})</span>
                        </label>
                        <input 
                            type="url" 
                            id="main_image" 
                            name="main_image" 
                            class="form-control @error('main_image') is-invalid @enderror" 
                            value="{{ old('main_image') }}" 
                            placeholder="https://example.com/image.jpg">
                        <p class="form-text">
                            <i class="fas fa-lightbulb"></i> {{ __('messages.image_services_recommendation') }}
                        </p>
                        @error('main_image')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="additional_images" class="form-label">
                            {{ __('messages.additional_images') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional_one_url_per_line') ?? 'Optional - one URL per line' }})</span>
                        </label>
                        <textarea 
                            id="additional_images" 
                            name="additional_images" 
                            class="form-control @error('additional_images') is-invalid @enderror" 
                            rows="5" 
                            placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg">{{ old('additional_images', $product->images->where('is_primary', false)->pluck('image_path')->implode("\n")) }}</textarea>
                        <p class="form-text">
                            <i class="fas fa-info-circle"></i> {{ __('messages.enter_image_url_per_line') ?? 'Enter each image URL on a separate line' }}
                        </p>
                        @error('additional_images')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Current Additional Images -->
                @if($product->images->where('is_primary', false)->count() > 0)
                    <div class="additional-images-preview" style="margin-top: 16px;" id="current-additional-images-container">
                        <strong>
                            <i class="fas fa-images"></i>
                            <span id="additional-images-count-label">{{ __('messages.current_additional_images') }} ({{ $product->images->where('is_primary', false)->count() }})</span>
                        </strong>
                        <div class="images-grid" style="margin-top: 10px;">
                            @foreach($product->images->where('is_primary', false) as $image)
                                <div class="current-additional-image-item" id="additional-image-{{ $image->id }}">
                                    <img src="{{ $image->image_url }}" alt="Product Image">
                                    <button type="button" class="dropzone-remove" onclick="deleteCurrentImage({{ $image->id }}, 'additional', this)" title="{{ __('messages.delete') ?? 'Delete' }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <style>
            .dropzone-area {
                border: 2px dashed var(--border);
                border-radius: 12px;
                padding: 32px 20px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                position: relative;
            }
            .dropzone-area:hover, .dropzone-area.dragover {
                border-color: var(--primary);
                background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
            }
            .dropzone-area.dropzone-error {
                border-color: var(--danger);
                background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            }
            .dropzone-icon { font-size: 36px; color: var(--primary); margin-bottom: 12px; opacity: 0.7; }
            .dropzone-area:hover .dropzone-icon { opacity: 1; }
            .dropzone-title { font-size: 15px; font-weight: 600; color: var(--dark); margin-bottom: 4px; }
            .dropzone-subtitle { font-size: 13px; color: var(--secondary); margin-bottom: 8px; }
            .dropzone-formats { display: inline-block; font-size: 12px; color: var(--secondary); background: white; padding: 4px 12px; border-radius: 20px; border: 1px solid var(--border); }
            .dropzone-preview { position: relative; display: inline-block; }
            .dropzone-preview img { max-width: 100%; max-height: 220px; border-radius: 10px; object-fit: contain; }
            .dropzone-remove { position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; border-radius: 50%; background: var(--danger); color: white; border: 2px solid white; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; box-shadow: 0 2px 8px rgba(239,68,68,0.4); transition: transform 0.2s; }
            .dropzone-remove:hover { transform: scale(1.15); }
            [dir="rtl"] .dropzone-remove { right: auto; left: -8px; }
            .dropzone-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; margin-top: 12px; }
            .dropzone-grid-item { position: relative; border-radius: 10px; overflow: hidden; border: 2px solid var(--border); background: #f8fafc; }
            .dropzone-grid-item img { width: 100%; height: 100px; object-fit: cover; display: block; }
            .dropzone-grid-item .file-name { font-size: 11px; color: var(--secondary); padding: 4px 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        </style>

        <script>
        function toggleImageSource(type) {
            const fileSection = document.getElementById('image-source-file');
            const urlSection = document.getElementById('image-source-url');
            const fileLabel = document.getElementById('source-file-label');
            const urlLabel = document.getElementById('source-url-label');
            if (type === 'file') {
                fileSection.style.display = '';
                urlSection.style.display = 'none';
                fileLabel.style.borderColor = 'var(--primary)';
                fileLabel.style.background = '#eff6ff';
                urlLabel.style.borderColor = 'var(--border)';
                urlLabel.style.background = '';
            } else {
                fileSection.style.display = 'none';
                urlSection.style.display = '';
                urlLabel.style.borderColor = 'var(--primary)';
                urlLabel.style.background = '#eff6ff';
                fileLabel.style.borderColor = 'var(--border)';
                fileLabel.style.background = '';
            }
        }

        function removeMainImage() {
            const input = document.getElementById('main_image_file');
            input.value = '';
            document.getElementById('main-dropzone-preview').style.display = 'none';
            document.getElementById('main-dropzone-content').style.display = '';
        }

        function setupDropzone(dropzoneId, inputId) {
            const zone = document.getElementById(dropzoneId);
            if (!zone) return;
            ['dragenter', 'dragover'].forEach(e => {
                zone.addEventListener(e, function(ev) { ev.preventDefault(); zone.classList.add('dragover'); });
            });
            ['dragleave', 'drop'].forEach(e => {
                zone.addEventListener(e, function(ev) { ev.preventDefault(); zone.classList.remove('dragover'); });
            });
            zone.addEventListener('drop', function(ev) {
                const input = document.getElementById(inputId);
                if (ev.dataTransfer.files.length) {
                    input.files = ev.dataTransfer.files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupDropzone('main-dropzone', 'main_image_file');
            setupDropzone('additional-dropzone', 'additional_images_files');

            const mainInput = document.getElementById('main_image_file');
            if (mainInput) {
                mainInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('main-image-preview-img').src = e.target.result;
                            document.getElementById('main-dropzone-preview').style.display = '';
                            document.getElementById('main-dropzone-content').style.display = 'none';
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            const additionalInput = document.getElementById('additional_images_files');
            if (additionalInput) {
                additionalInput.addEventListener('change', function() {
                    const grid = document.getElementById('additional-images-preview');
                    grid.innerHTML = '';
                    if (this.files && this.files.length) {
                        document.getElementById('additional-dropzone-content').innerHTML =
                            '<div class="dropzone-icon"><i class="fas fa-check-circle" style="color:var(--success);"></i></div>' +
                            '<p class="dropzone-title">' + this.files.length + ' {{ __("messages.files_selected") }}</p>' +
                            '<p class="dropzone-subtitle">{{ __("messages.click_to_change") }}</p>';
                        Array.from(this.files).forEach(function(file) {
                            const item = document.createElement('div');
                            item.className = 'dropzone-grid-item';
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                item.innerHTML = '<img src="' + e.target.result + '" alt="Preview"><div class="file-name">' + file.name + '</div>';
                            };
                            reader.readAsDataURL(file);
                            grid.appendChild(item);
                        });
                    }
                });
            }
        });

        function deleteCurrentImage(imageId, type, buttonEl) {
            if (!confirm('{{ __("messages.confirm_delete_image") ?? "Are you sure you want to delete this image?" }}')) {
                return;
            }

            const url = '{{ route("admin.products.delete-image", $product->id) }}';
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            buttonEl.disabled = true;
            buttonEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ image_id: imageId, type: type })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (type === 'main') {
                        const container = document.getElementById('current-main-image-container');
                        if (container) {
                            container.style.transition = 'opacity 0.3s ease';
                            container.style.opacity = '0';
                            setTimeout(() => container.remove(), 300);
                        }
                    } else {
                        const item = document.getElementById('additional-image-' + imageId);
                        if (item) {
                            item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            item.style.opacity = '0';
                            item.style.transform = 'scale(0.8)';
                            setTimeout(() => {
                                item.remove();
                                const remaining = document.querySelectorAll('.current-additional-image-item');
                                const countLabel = document.getElementById('additional-images-count-label');
                                if (remaining.length === 0) {
                                    const container = document.getElementById('current-additional-images-container');
                                    if (container) container.remove();
                                } else if (countLabel) {
                                    countLabel.textContent = '{{ __("messages.current_additional_images") }} (' + remaining.length + ')';
                                }
                            }, 300);
                        }
                    }
                } else {
                    alert(data.message || '{{ __("messages.error_deleting_image") ?? "Error deleting image." }}');
                    buttonEl.disabled = false;
                    buttonEl.innerHTML = '<i class="fas fa-times"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __("messages.error_deleting_image") ?? "Error deleting image." }}');
                buttonEl.disabled = false;
                buttonEl.innerHTML = '<i class="fas fa-times"></i>';
            });
        }
        </script>

        <!-- Search Keywords Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-search"></i> {{ __('messages.search_optimization') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="search_keywords" class="form-label">
                        {{ __('messages.search_keywords') }}
                        <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                    </label>
                    <textarea
                        id="search_keywords"
                        name="search_keywords"
                        class="form-control @error('search_keywords') is-invalid @enderror"
                        placeholder="{{ __('messages.search_keywords_placeholder') }}"
                        style="min-height: 100px;">{{ old('search_keywords', $product->search_keywords) }}</textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.search_keywords_help') }}
                    </p>
                    @error('search_keywords')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Descriptions Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-align-left"></i> {{ __('messages.descriptions') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="short_description_en" class="form-label">
                            {{ __('messages.short_description_english') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <textarea 
                            id="short_description_en" 
                            name="short_description_en" 
                            class="form-control @error('short_description_en') is-invalid @enderror"
                            placeholder="Brief description for product listings"
                            style="min-height: 80px;">{{ old('short_description_en', $product->short_description_en) }}</textarea>
                        @error('short_description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="short_description_ar" class="form-label">
                            وصف قصير (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري)</span>
                        </label>
                        <textarea 
                            id="short_description_ar" 
                            name="short_description_ar" 
                            class="form-control @error('short_description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="وصف قصير للمنتج"
                            style="min-height: 80px;">{{ old('short_description_ar', $product->short_description_ar) }}</textarea>
                        @error('short_description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="short_description_he" class="form-label">
                            {{ __('messages.short_description_hebrew') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <textarea
                            id="short_description_he"
                            name="short_description_he"
                            class="form-control @error('short_description_he') is-invalid @enderror"
                            dir="rtl"
                            placeholder="{{ __('messages.brief_description_hebrew') }}"
                            style="min-height: 80px;">{{ old('short_description_he', $product->short_description_he) }}</textarea>
                        @error('short_description_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            {{ __('messages.full_description_english') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }} - {{ $inputLimits['description'] ?? 3000 }})</span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control char-counter-input @error('description_en') is-invalid @enderror"
                            placeholder="Complete product description with details"
                            maxlength="{{ $inputLimits['description'] ?? 3000 }}"
                            data-max-length="{{ $inputLimits['description'] ?? 3000 }}"
                            style="min-height: 150px;">{{ old('description_en', $product->description_en) }}</textarea>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max">{{ $inputLimits['description'] ?? 3000 }}</span>
                        </div>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            وصف كامل (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري - {{ $inputLimits['description'] ?? 3000 }} حرف)</span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control char-counter-input @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="وصف المنتج الكامل بالتفاصيل"
                            maxlength="{{ $inputLimits['description'] ?? 3000 }}"
                            data-max-length="{{ $inputLimits['description'] ?? 3000 }}"
                            style="min-height: 150px;">{{ old('description_ar', $product->description_ar) }}</textarea>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max">{{ $inputLimits['description'] ?? 3000 }}</span>
                        </div>
                        @error('description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_he" class="form-label">
                            {{ __('messages.full_description_hebrew') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }} - {{ $inputLimits['description'] ?? 3000 }})</span>
                        </label>
                        <textarea
                            id="description_he"
                            name="description_he"
                            class="form-control char-counter-input @error('description_he') is-invalid @enderror"
                            dir="rtl"
                            placeholder="{{ __('messages.complete_description_hebrew') }}"
                            maxlength="{{ $inputLimits['description'] ?? 3000 }}"
                            data-max-length="{{ $inputLimits['description'] ?? 3000 }}"
                            style="min-height: 150px;">{{ old('description_he', $product->description_he) }}</textarea>
                        <div class="char-counter">
                            <span class="char-count">0</span> / <span class="char-max">{{ $inputLimits['description'] ?? 3000 }}</span>
                        </div>
                        @error('description_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tags Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-tags"></i> {{ __('messages.product_tags') }}</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.select_tags') }}</label>
                    
                    <!-- Tag Input with Autocomplete -->
                    <div class="tag-input-wrapper">
                        <div class="selected-tags" id="selectedTags">
                            <!-- Pre-populated tags will appear here -->
                        </div>
                        <div class="tag-input-container">
                            <input type="text" 
                                   id="tagSearchInput" 
                                   class="tag-search-input" 
                                   placeholder="{{ __('messages.type_to_search_or_add_tag') }}"
                                   autocomplete="off">
                            <div class="tag-suggestions" id="tagSuggestions"></div>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs container -->
                    <div id="tagHiddenInputs"></div>
                    
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.tag_input_help') }}
                    </p>
                </div>
            </div>
        </div>
        
        <style>
        .tag-input-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            background: #f9fafb;
            min-height: 50px;
        }
        .selected-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }
        .selected-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 13px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .selected-tag .tag-color {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .selected-tag .remove-tag {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            margin-left: 4px;
            font-size: 14px;
            line-height: 1;
        }
        .selected-tag .remove-tag:hover {
            color: #ef4444;
        }
        .selected-tag.new-tag {
            background: #eff6ff;
            border-color: #3b82f6;
        }
        .selected-tag.new-tag::after {
            content: '{{ __("messages.new") }}';
            font-size: 10px;
            background: #3b82f6;
            color: white;
            padding: 1px 5px;
            border-radius: 10px;
            margin-left: 4px;
        }
        .tag-input-container {
            position: relative;
        }
        .tag-search-input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 8px;
            font-size: 14px;
            outline: none;
        }
        .tag-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .tag-suggestion {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
        }
        .tag-suggestion:last-child {
            border-bottom: none;
        }
        .tag-suggestion:hover {
            background: #f9fafb;
        }
        .tag-suggestion .tag-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        .tag-suggestion.create-new {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 500;
        }
        .tag-suggestion.create-new:hover {
            background: #dbeafe;
        }
        .tag-suggestion.create-new i {
            color: #3b82f6;
        }
        </style>
        
        @php
            $existingTags = $product->tags->map(function($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name_en,
                    'color' => $tag->color,
                    'icon' => $tag->icon,
                    'isNew' => false
                ];
            })->values();
        @endphp
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const availableTags = @json($tags ?? []);
            // Pre-populate with existing product tags
            let selectedTags = @json($existingTags);
            
            const searchInput = document.getElementById('tagSearchInput');
            const suggestionsDiv = document.getElementById('tagSuggestions');
            const selectedTagsDiv = document.getElementById('selectedTags');
            const hiddenInputsDiv = document.getElementById('tagHiddenInputs');
            
            // Initial render
            renderSelectedTags();
            
            // Search input handler
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                if (query.length === 0) {
                    suggestionsDiv.style.display = 'none';
                    return;
                }
                
                // Filter available tags
                const filtered = availableTags.filter(tag => 
                    !selectedTags.some(s => s.id === tag.id) &&
                    (tag.name_en.toLowerCase().includes(query) || 
                     tag.name_ar.toLowerCase().includes(query))
                );
                
                let html = '';
                
                // Show matching tags
                filtered.slice(0, 8).forEach(tag => {
                    const icon = tag.icon 
                        ? `<i class="${tag.icon}" style="color: ${tag.color}"></i>`
                        : `<span class="tag-color" style="background: ${tag.color}"></span>`;
                    html += `<div class="tag-suggestion" data-id="${tag.id}" data-name="${tag.name_en}" data-color="${tag.color}" data-icon="${tag.icon || ''}">
                        ${icon}
                        <span>${tag.name_en}</span>
                        <span style="color: #9ca3af; font-size: 12px;">(${tag.name_ar})</span>
                    </div>`;
                });
                
                // Show "Create new tag" option
                const exactMatch = availableTags.some(tag => 
                    tag.name_en.toLowerCase() === query || tag.name_ar.toLowerCase() === query
                );
                
                if (!exactMatch && query.length >= 2) {
                    html += `<div class="tag-suggestion create-new" data-new="true" data-name="${this.value.trim()}">
                        <i class="fas fa-plus"></i>
                        <span>{{ __('messages.create_tag') }}: "${this.value.trim()}"</span>
                    </div>`;
                }
                
                if (html) {
                    suggestionsDiv.innerHTML = html;
                    suggestionsDiv.style.display = 'block';
                    
                    // Add click handlers
                    suggestionsDiv.querySelectorAll('.tag-suggestion').forEach(el => {
                        el.addEventListener('click', function() {
                            if (this.dataset.new === 'true') {
                                addNewTag(this.dataset.name);
                            } else {
                                addExistingTag(parseInt(this.dataset.id), this.dataset.name, this.dataset.color, this.dataset.icon);
                            }
                            searchInput.value = '';
                            suggestionsDiv.style.display = 'none';
                        });
                    });
                } else {
                    suggestionsDiv.style.display = 'none';
                }
            });
            
            // Handle Enter key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        // Check if exact match exists
                        const exactMatch = availableTags.find(tag => 
                            tag.name_en.toLowerCase() === query.toLowerCase() || 
                            tag.name_ar.toLowerCase() === query.toLowerCase()
                        );
                        
                        if (exactMatch && !selectedTags.some(s => s.id === exactMatch.id)) {
                            addExistingTag(exactMatch.id, exactMatch.name_en, exactMatch.color, exactMatch.icon);
                        } else if (!exactMatch) {
                            addNewTag(query);
                        }
                        this.value = '';
                        suggestionsDiv.style.display = 'none';
                    }
                }
            });
            
            // Hide suggestions on click outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                    suggestionsDiv.style.display = 'none';
                }
            });
            
            function addExistingTag(id, name, color, icon) {
                if (selectedTags.some(t => t.id === id)) return;
                
                selectedTags.push({ id, name, color, icon, isNew: false });
                renderSelectedTags();
            }
            
            function addNewTag(name) {
                if (selectedTags.some(t => t.name.toLowerCase() === name.toLowerCase())) return;
                
                const tempId = 'new_' + Date.now();
                selectedTags.push({ id: tempId, name, color: '#3b82f6', icon: '', isNew: true });
                renderSelectedTags();
            }
            
            function removeTag(id) {
                selectedTags = selectedTags.filter(t => t.id !== id);
                renderSelectedTags();
            }
            
            function renderSelectedTags() {
                // Render visual tags
                selectedTagsDiv.innerHTML = selectedTags.map(tag => {
                    const icon = tag.icon 
                        ? `<i class="${tag.icon}" style="color: ${tag.color}"></i>`
                        : `<span class="tag-color" style="background: ${tag.color}"></span>`;
                    return `<span class="selected-tag ${tag.isNew ? 'new-tag' : ''}" data-id="${tag.id}">
                        ${icon}
                        <span>${tag.name}</span>
                        <button type="button" class="remove-tag" onclick="window.removeTagById('${tag.id}')">&times;</button>
                    </span>`;
                }).join('');
                
                // Render hidden inputs
                let hiddenHtml = '';
                selectedTags.forEach(tag => {
                    if (tag.isNew) {
                        hiddenHtml += `<input type="hidden" name="new_tags_array[]" value="${tag.name}">`;
                    } else {
                        hiddenHtml += `<input type="hidden" name="tags[]" value="${tag.id}">`;
                    }
                });
                hiddenInputsDiv.innerHTML = hiddenHtml;
            }
            
            // Global function for remove button
            window.removeTagById = function(id) {
                if (typeof id === 'string' && id.startsWith('new_')) {
                    selectedTags = selectedTags.filter(t => t.id !== id);
                } else {
                    selectedTags = selectedTags.filter(t => t.id !== parseInt(id));
                }
                renderSelectedTags();
            };
        });
        </script>

        <!-- Product Settings Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> {{ __('messages.product_settings') }}</h2>
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
                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-eye"></i> {{ __('messages.active') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.display_product_in_store') }}</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_featured" 
                            name="is_featured" 
                            value="1" 
                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-star"></i> {{ __('messages.featured') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.show_homepage_featured') }}</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_new" 
                            name="is_new" 
                            value="1" 
                            {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-badge"></i> {{ __('messages.new_product') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.mark_new_highlight') }}</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_bestseller" 
                            name="is_bestseller" 
                            value="1" 
                            {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-fire"></i> {{ __('messages.bestseller') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.mark_bestselling_product') }}</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_special_offer" 
                            name="is_special_offer" 
                            value="1" 
                            {{ old('is_special_offer', $product->is_special_offer ?? false) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-gift"></i> {{ __('messages.special_offer') }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.show_special_offer_homepage') }}</p>
                        </span>
                    </label>

                    <label class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="is_strong_offer" 
                            name="is_strong_offer" 
                            value="1" 
                            {{ old('is_strong_offer', $product->is_strong_offer ?? false) ? 'checked' : '' }}>
                        <span>
                            <strong><i class="fas fa-bolt"></i> {{ __('messages.strong_offer') ?? 'Strong Offer' }}</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ __('messages.mark_as_strong_promotional_offer') ?? 'Mark as strong promotional offer for filtering' }}</p>
                        </span>
                    </label>

                    <!-- Custom Home Sections -->
                    @if(isset($customSections) && $customSections->count() > 0)
                        @foreach($customSections as $cs)
                        <label class="checkbox-group">
                            <input 
                                type="checkbox" 
                                name="home_sections[]" 
                                value="{{ $cs->id }}" 
                                {{ in_array($cs->id, old('home_sections', $selectedHomeSections ?? [])) ? 'checked' : '' }}>
                            <span>
                                <strong><i class="fas fa-th-list"></i> {{ $cs->title }}</strong>
                                @if($cs->subtitle)
                                    <p style="color: #64748b; font-size: 12px; margin-top: 2px;">{{ $cs->subtitle }}</p>
                                @endif
                            </span>
                        </label>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Attributes Card -->
        <div class="card" id="attributes-card" style="{{ !empty($categoryAttributes) && $categoryAttributes->count() > 0 ? '' : 'display: none;' }}">
            <div class="card-header">
                <h2><i class="fas fa-tags"></i> {{ __('messages.product_attributes') }}</h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;">{{ __('messages.select_attributes_for_category') }}</p>
            </div>
            <div class="card-body">
                <div id="attributes-container">
                    @if(!empty($categoryAttributes) && $categoryAttributes->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 24px;">
                            @foreach($categoryAttributes as $attribute)
                                <div class="form-group">
                                    <label class="form-label">
                                        <strong>{{ $attribute->name }}</strong>
                                        @if($attribute->unit)
                                            <span style="color: #64748b; font-size: 12px;">({{ $attribute->unit }})</span>
                                        @endif
                                    </label>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin-top: 8px;">
                                        @foreach($attribute->values as $value)
                                            <label class="checkbox-group" style="margin: 0;">
                                                <input 
                                                    type="checkbox" 
                                                    id="attr_{{ $attribute->id }}_{{ $value->id }}" 
                                                    name="attribute_values[]" 
                                                    value="{{ $value->id }}"
                                                    {{ in_array($value->id, old('attribute_values', $selectedAttributeValues)) ? 'checked' : '' }}>
                                                <span>
                                                    @if($value->color_code)
                                                        <span style="display: inline-block; width: 16px; height: 16px; border-radius: 3px; background: {{ $value->color_code }}; border: 1px solid #ddd; margin-right: 6px; vertical-align: middle;"></span>
                                                    @endif
                                                    {{ $value->value }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #64748b; text-align: center; padding: 20px;">
                            <i class="fas fa-info-circle"></i> {{ __('messages.no_attributes_for_category') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Specifications Card -->
        <div class="card" id="specifications-card" style="{{ isset($specFields) && $specFields->count() > 0 ? '' : 'display: none;' }}">
            <div class="card-header">
                <h2><i class="fas fa-clipboard-list"></i> {{ __('messages.product_specifications') }}</h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;">{{ __('messages.fill_specs_for_category') ?? 'Fill in the specifications for this product category' }}</p>
            </div>
            <div class="card-body">
                <div id="specifications-container">
                    @if(isset($specFields) && $specFields->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                            @foreach($specFields as $field)
                                <div class="form-group">
                                    <label for="spec_{{ $field->id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span class="required">*</span>
                                        @endif
                                        @if($field->unit)
                                            <span style="color: #64748b; font-size: 12px;">({{ $field->unit }})</span>
                                        @endif
                                    </label>
                                    
                                    @if($field->type === 'text')
                                        <input 
                                            type="text" 
                                            id="spec_{{ $field->id }}" 
                                            name="spec_values[{{ $field->id }}]" 
                                            class="form-control @error('spec_values.'.$field->id) is-invalid @enderror"
                                            value="{{ old('spec_values.'.$field->id, $specValues[$field->id] ?? '') }}"
                                            placeholder="{{ $field->label }}"
                                            {{ $field->is_required ? 'required' : '' }}>
                                    @elseif($field->type === 'number')
                                        <input 
                                            type="number" 
                                            id="spec_{{ $field->id }}" 
                                            name="spec_values[{{ $field->id }}]" 
                                            class="form-control @error('spec_values.'.$field->id) is-invalid @enderror"
                                            value="{{ old('spec_values.'.$field->id, $specValues[$field->id] ?? '') }}"
                                            placeholder="{{ $field->label }}"
                                            step="any"
                                            {{ $field->is_required ? 'required' : '' }}>
                                    @elseif($field->type === 'boolean')
                                        <select 
                                            id="spec_{{ $field->id }}" 
                                            name="spec_values[{{ $field->id }}]" 
                                            class="form-control @error('spec_values.'.$field->id) is-invalid @enderror"
                                            {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">-- {{ __('messages.select') ?? 'Select' }} --</option>
                                            <option value="1" {{ old('spec_values.'.$field->id, $specValues[$field->id] ?? '') === '1' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                                            <option value="0" {{ old('spec_values.'.$field->id, $specValues[$field->id] ?? '') === '0' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                                        </select>
                                    @elseif($field->type === 'select')
                                        <select 
                                            id="spec_{{ $field->id }}" 
                                            name="spec_values[{{ $field->id }}]" 
                                            class="form-control @error('spec_values.'.$field->id) is-invalid @enderror"
                                            {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">-- {{ __('messages.select') ?? 'Select' }} --</option>
                                            @foreach($field->options ?? [] as $option)
                                                <option value="{{ $option }}" {{ old('spec_values.'.$field->id, $specValues[$field->id] ?? '') === $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    
                                    @error('spec_values.'.$field->id)
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #64748b; text-align: center; padding: 20px;">
                            <i class="fas fa-info-circle"></i> {{ __('messages.no_specs_for_category') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> {{ __('messages.update_product') }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </a>
        </div>

        <!-- Danger Zone -->
        <div class="delete-section">
            <div class="danger-zone">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('messages.danger_zone_product') }}
                </h3>
                <p>{{ __('messages.delete_product_warning') }}</p>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> {{ __('messages.delete_product') }}
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete() {
        if (confirm('{{ __("messages.confirm_delete_product_message") }}')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Character counter functionality
    function initCharCounters() {
        document.querySelectorAll('.char-counter-input').forEach(input => {
            const counter = input.parentElement.querySelector('.char-counter');
            if (!counter) return;
            
            const countSpan = counter.querySelector('.char-count');
            const maxLength = parseInt(input.dataset.maxLength) || parseInt(input.maxLength) || 120;
            
            function updateCounter() {
                const length = input.value.length;
                countSpan.textContent = length;
                
                // Update styling based on usage
                const percentage = (length / maxLength) * 100;
                
                counter.classList.remove('warning', 'danger');
                input.classList.remove('near-limit', 'at-limit');
                
                if (percentage >= 100) {
                    counter.classList.add('danger');
                    input.classList.add('at-limit');
                } else if (percentage >= 85) {
                    counter.classList.add('warning');
                    input.classList.add('near-limit');
                }
            }
            
            // Initial update
            updateCounter();
            
            // Update on input
            input.addEventListener('input', updateCounter);
        });
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', initCharCounters);

    // Dynamic attribute and specification loading on category change
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const attributesCard = document.getElementById('attributes-card');
        const attributesContainer = document.getElementById('attributes-container');
        const specificationsCard = document.getElementById('specifications-card');
        const specificationsContainer = document.getElementById('specifications-container');
        const currentCategoryId = '{{ $product->category_id }}';
        
        // Pricing auto-calculation: price, sale_price, discount_percentage
        const priceInput = document.getElementById('price');
        const salePriceInput = document.getElementById('sale_price');
        const discountInput = document.getElementById('discount_percentage');

        if (priceInput && salePriceInput && discountInput) {
            priceInput.addEventListener('input', function() {
                const price = parseFloat(priceInput.value);
                const sale = parseFloat(salePriceInput.value);
                const discount = parseFloat(discountInput.value);
                if (price > 0 && sale > 0 && sale < price) {
                    discountInput.value = (((price - sale) / price) * 100).toFixed(2);
                } else if (price > 0 && discount > 0 && discount <= 100) {
                    salePriceInput.value = (price * (1 - discount / 100)).toFixed(2);
                }
            });

            salePriceInput.addEventListener('input', function() {
                const price = parseFloat(priceInput.value);
                const sale = parseFloat(salePriceInput.value);
                if (price > 0 && sale > 0 && sale < price) {
                    discountInput.value = (((price - sale) / price) * 100).toFixed(2);
                } else if (sale > 0 && !price) {
                    discountInput.value = '';
                }
            });

            discountInput.addEventListener('input', function() {
                const price = parseFloat(priceInput.value);
                const sale = parseFloat(salePriceInput.value);
                const discount = parseFloat(discountInput.value);
                if (discount > 0 && discount <= 100) {
                    if (price > 0) {
                        salePriceInput.value = (price * (1 - discount / 100)).toFixed(2);
                    } else if (sale > 0) {
                        priceInput.value = (sale / (1 - discount / 100)).toFixed(2);
                    }
                }
            });
        }
        
        // Store currently selected attribute values
        let selectedValues = [];
        
        function getSelectedValues() {
            const checkboxes = document.querySelectorAll('input[name="attribute_values[]"]:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        // Load attributes and specifications when category changes
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Store selected values before reload
            selectedValues = getSelectedValues();
            
            if (!categoryId) {
                attributesCard.style.display = 'none';
                specificationsCard.style.display = 'none';
                return;
            }

            // Show loading state for attributes
            attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> {{ __('messages.loading') }}...</p>';
            attributesCard.style.display = 'block';

            // Show loading state for specifications
            specificationsContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> {{ __('messages.loading') }}...</p>';
            specificationsCard.style.display = 'block';

            // Fetch attributes for this category
            fetch(`/admin/products/category-attributes/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.attributes && data.attributes.length > 0) {
                        renderAttributes(data.attributes, categoryId === currentCategoryId);
                    } else {
                        attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-info-circle"></i> {{ __('messages.no_attributes_for_category') }}</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading attributes:', error);
                    attributesContainer.innerHTML = '<p style="color: #dc2626; text-align: center; padding: 20px;"><i class="fas fa-exclamation-triangle"></i> {{ __('messages.error') }}</p>';
                });

            // Fetch specification fields for this category
            fetch(`/admin/spec-templates/category-fields/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.hasTemplate && data.fields && data.fields.length > 0) {
                        renderSpecifications(data.fields);
                        specificationsCard.style.display = 'block';
                    } else {
                        specificationsContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-info-circle"></i> {{ __("messages.no_specs_for_category") }}</p>';
                        specificationsCard.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading specifications:', error);
                    specificationsContainer.innerHTML = '<p style="color: #dc2626; text-align: center; padding: 20px;"><i class="fas fa-exclamation-triangle"></i> {{ __('messages.error') }}</p>';
                });
        });

        function renderSpecifications(fields) {
            let html = '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">';
            
            fields.forEach(field => {
                const required = field.is_required ? 'required' : '';
                const requiredStar = field.is_required ? '<span class="required">*</span>' : '';
                const unit = field.unit ? `<span style="color: #64748b; font-size: 12px;">(${escapeHtml(field.unit)})</span>` : '';
                
                html += `<div class="form-group">
                    <label for="spec_${field.id}" class="form-label">
                        ${escapeHtml(field.label)} ${requiredStar} ${unit}
                    </label>`;
                
                if (field.type === 'text') {
                    html += `<input type="text" id="spec_${field.id}" name="spec_values[${field.id}]" 
                             class="form-control" placeholder="${escapeHtml(field.label)}" ${required}>`;
                } else if (field.type === 'number') {
                    html += `<input type="number" id="spec_${field.id}" name="spec_values[${field.id}]" 
                             class="form-control" placeholder="${escapeHtml(field.label)}" step="any" ${required}>`;
                } else if (field.type === 'boolean') {
                    html += `<select id="spec_${field.id}" name="spec_values[${field.id}]" class="form-control" ${required}>
                             <option value="">-- Select --</option>
                             <option value="1">{{ __("messages.yes") }}</option>
                             <option value="0">{{ __("messages.no") }}</option>
                             </select>`;
                } else if (field.type === 'select' && field.options) {
                    html += `<select id="spec_${field.id}" name="spec_values[${field.id}]" class="form-control" ${required}>
                             <option value="">-- Select --</option>`;
                    field.options.forEach(option => {
                        html += `<option value="${escapeHtml(option)}">${escapeHtml(option)}</option>`;
                    });
                    html += '</select>';
                }
                
                html += '</div>';
            });
            
            html += '</div>';
            specificationsContainer.innerHTML = html;
        }

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

@endsection
