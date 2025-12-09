@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
<style>
    /* Product Edit Page Specific Styles */
    .product-form-grid {
        max-width: 900px;
        margin: 0 auto;
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
        <h1><i class="fas fa-edit"></i> Edit Product</h1>
        <p>Update product information: <strong>{{ $product->name }}</strong></p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" class="product-form-grid">
    @csrf
    @method('PUT')

    <!-- Main Form Content -->
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Basic Information Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Basic Information</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name_en" class="form-label">
                            Product Name (English)
                            <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name_en" 
                            name="name_en" 
                            class="form-control @error('name_en') is-invalid @enderror" 
                            value="{{ old('name_en', $product->name_en) }}" 
                            placeholder="Enter product name in English"
                            required>
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
                            class="form-control @error('name_ar') is-invalid @enderror" 
                            value="{{ old('name_ar', $product->name_ar) }}" 
                            placeholder="أدخل اسم المنتج بالعربية"
                            required 
                            dir="rtl">
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
                            class="form-control @error('name_he') is-invalid @enderror"
                            value="{{ old('name_he', $product->name_he) }}"
                            placeholder="{{ __('messages.enter_product_name_hebrew') }}"
                            dir="rtl">
                        @error('name_he')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">
                            Category
                            <span class="required">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Select a Category</option>
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
                            Brand
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                            <option value="">Select a Brand</option>
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
                <h2><i class="fas fa-dollar-sign"></i> Pricing & Inventory</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">
                            Regular Price
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
                            Sale Price
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
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
                        <label for="stock_quantity" class="form-label">
                            Stock Quantity
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
                <h2><i class="fas fa-images"></i> Product Images</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="main_image" class="form-label">
                        Main Product Image
                        <span class="required">*</span>
                    </label>
                    <input 
                        type="url" 
                        id="main_image" 
                        name="main_image" 
                        class="form-control @error('main_image') is-invalid @enderror" 
                        value="{{ old('main_image', $product->main_image) }}" 
                        placeholder="https://picsum.photos/800/800"
                        required>
                    <p class="form-text">
                        <i class="fas fa-lightbulb"></i> Recommended: Use services like <strong>picsum.photos</strong> or <strong>placehold.co</strong>
                    </p>
                    @error('main_image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    
                    @if($product->main_image)
                        <div class="current-image-container">
                            <div class="current-image-label">
                                <i class="fas fa-image"></i>
                                Current Main Image
                            </div>
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="additional_images" class="form-label">
                        Additional Images
                        <span style="color: #64748b; font-size: 12px;">(Optional - One URL per line)</span>
                    </label>
                    <textarea 
                        id="additional_images" 
                        name="additional_images" 
                        class="form-control @error('additional_images') is-invalid @enderror" 
                        rows="5" 
                        placeholder="https://picsum.photos/800/801&#10;https://picsum.photos/800/802&#10;https://picsum.photos/800/803">{{ old('additional_images', $product->images->where('is_primary', false)->pluck('image_path')->implode("\n")) }}</textarea>
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> Enter each image URL on a new line for the product gallery
                    </p>
                    @error('additional_images')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    
                    @if($product->images->where('is_primary', false)->count() > 0)
                        <div class="additional-images-preview">
                            <strong>
                                <i class="fas fa-images"></i>
                                Current Additional Images ({{ $product->images->where('is_primary', false)->count() }})
                            </strong>
                            <div class="images-grid">
                                @foreach($product->images->where('is_primary', false) as $image)
                                    <img src="{{ $image->image_path }}" alt="Product Image">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

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
                <h2><i class="fas fa-align-left"></i> Descriptions</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="short_description_en" class="form-label">
                            Short Description (English)
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
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
                            Full Description (English)
                            <span style="color: #64748b; font-size: 12px;">(Optional)</span>
                        </label>
                        <textarea 
                            id="description_en" 
                            name="description_en" 
                            class="form-control @error('description_en') is-invalid @enderror"
                            placeholder="Complete product description with details"
                            style="min-height: 150px;">{{ old('description_en', $product->description_en) }}</textarea>
                        @error('description_en')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            وصف كامل (عربي)
                            <span style="color: #64748b; font-size: 12px;">(اختياري)</span>
                        </label>
                        <textarea 
                            id="description_ar" 
                            name="description_ar" 
                            class="form-control @error('description_ar') is-invalid @enderror"
                            dir="rtl"
                            placeholder="وصف المنتج الكامل بالتفاصيل"
                            style="min-height: 150px;">{{ old('description_ar', $product->description_ar) }}</textarea>
                        @error('description_ar')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_he" class="form-label">
                            {{ __('messages.full_description_hebrew') }}
                            <span style="color: #64748b; font-size: 12px;">({{ __('messages.optional') }})</span>
                        </label>
                        <textarea
                            id="description_he"
                            name="description_he"
                            class="form-control @error('description_he') is-invalid @enderror"
                            dir="rtl"
                            placeholder="{{ __('messages.complete_description_hebrew') }}"
                            style="min-height: 150px;">{{ old('description_he', $product->description_he) }}</textarea>
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
                <h2><i class="fas fa-cog"></i> Product Settings</h2>
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
                            <strong><i class="fas fa-eye"></i> Active</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Display this product in the store</p>
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
                            <strong><i class="fas fa-star"></i> Featured</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Show on homepage featured section</p>
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
                            <strong><i class="fas fa-badge"></i> New Product</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Mark as new to highlight in store</p>
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
                            <strong><i class="fas fa-fire"></i> Bestseller</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Mark as popular/bestselling product</p>
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
                            <strong><i class="fas fa-gift"></i> Special Offer</strong>
                            <p style="color: #64748b; font-size: 12px; margin-top: 2px;">Show as special offer card on homepage</p>
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
                </div>

                <!-- Strong Offer Discount Percentage -->
                <div class="form-group" id="discount-percentage-group" style="margin-top: 16px; {{ old('is_strong_offer', $product->is_strong_offer ?? false) ? '' : 'display: none;' }}">
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
                    <p class="form-text">
                        <i class="fas fa-info-circle"></i> {{ __('messages.discount_percentage_help') ?? 'Enter discount percentage between 0 and 100' }}
                    </p>
                    @error('discount_percentage')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Product Attributes Card -->
        <div class="card" id="attributes-card" style="{{ !empty($categoryAttributes) && $categoryAttributes->count() > 0 ? '' : 'display: none;' }}">
            <div class="card-header">
                <h2><i class="fas fa-tags"></i> Product Attributes</h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 4px;">Select attributes specific to this product's category</p>
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
                            <i class="fas fa-info-circle"></i> No attributes configured for this category
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 12px; padding-top: 24px;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>

        <!-- Danger Zone -->
        <div class="delete-section">
            <div class="danger-zone">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    Danger Zone
                </h3>
                <p>Deleting this product will permanently remove it from your store. This action cannot be undone.</p>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i> Delete Product
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
        if (confirm('Are you sure you want to delete "{{ $product->name }}"?\n\nThis action cannot be undone and will permanently remove this product from your store.')) {
            document.getElementById('deleteForm').submit();
        }
    }

    // Dynamic attribute loading on category change
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const attributesCard = document.getElementById('attributes-card');
        const attributesContainer = document.getElementById('attributes-container');
        const currentCategoryId = '{{ $product->category_id }}';
        
        // Strong Offer checkbox toggle for discount percentage field
        const strongOfferCheckbox = document.getElementById('is_strong_offer');
        const discountPercentageGroup = document.getElementById('discount-percentage-group');
        
        if (strongOfferCheckbox && discountPercentageGroup) {
            // Show/hide discount percentage field based on checkbox state
            function toggleDiscountField() {
                if (strongOfferCheckbox.checked) {
                    discountPercentageGroup.style.display = 'block';
                } else {
                    discountPercentageGroup.style.display = 'none';
                    document.getElementById('discount_percentage').value = '';
                }
            }
            
            // Initial state
            toggleDiscountField();
            
            // Listen for changes
            strongOfferCheckbox.addEventListener('change', toggleDiscountField);
        }
        
        // Store currently selected attribute values
        let selectedValues = [];
        
        function getSelectedValues() {
            const checkboxes = document.querySelectorAll('input[name="attribute_values[]"]:checked');
            return Array.from(checkboxes).map(cb => cb.value);
        }

        // Load attributes when category changes
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            
            // Store selected values before reload
            selectedValues = getSelectedValues();
            
            if (!categoryId) {
                attributesCard.style.display = 'none';
                return;
            }

            // Show loading state
            attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-spinner fa-spin"></i> Loading attributes...</p>';
            attributesCard.style.display = 'block';

            // Fetch attributes for this category
            fetch(`/admin/products/category-attributes/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.attributes && data.attributes.length > 0) {
                        renderAttributes(data.attributes, categoryId === currentCategoryId);
                    } else {
                        attributesContainer.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;"><i class="fas fa-info-circle"></i> No attributes configured for this category</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading attributes:', error);
                    attributesContainer.innerHTML = '<p style="color: #dc2626; text-align: center; padding: 20px;"><i class="fas fa-exclamation-triangle"></i> Error loading attributes</p>';
                });
        });

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
