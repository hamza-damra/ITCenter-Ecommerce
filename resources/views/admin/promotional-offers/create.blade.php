@extends('admin.layout')

@section('content')
<div class="admin-content">
    <div class="content-header" style="margin-bottom: 2rem;">
        <div>
            <h1 style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <i class="fas fa-bullhorn" style="color: #667eea;"></i> {{ __('messages.create_new_promotional_offer') }}
            </h1>
            <p style="color: #666; margin: 0;">{{ __('messages.create_promotional_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.promotional-offers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_list') }}
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <i class="fas fa-exclamation-circle"></i>
            <strong>{{ __('messages.please_correct_errors') }}</strong>
        </div>
        <ul style="margin: 0.5rem 0 0 0; padding-right: 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.promotional-offers.store') }}" method="POST" id="promoForm">
        @csrf
        
        <!-- Product Selection Card -->
        <div class="promo-card">
            <div class="card-header-custom">
                <div class="header-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <div>
                    <h3>{{ __('messages.product_selection') }}</h3>
                    <p>{{ __('messages.select_product_desc') }}</p>
                </div>
            </div>
            
            <div class="card-body-custom">
                <div class="form-group-enhanced">
                    <label for="product_id">{{ __('messages.product') }} <span class="required">*</span></label>
                    <div class="select-wrapper">
                        <select name="product_id" id="product_id" class="form-control-enhanced" required>
                            <option value="">{{ __('messages.select_product_placeholder') }}</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    data-image="{{ $product->main_image }}">
                                {{ $product->name }} (₪{{ number_format($product->price, 2) }})
                            </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down select-icon"></i>
                    </div>
                    <div id="product_preview" class="product-preview" style="display: none;">
                        <img id="preview_image" src="" alt="Product">
                        <div class="preview-info">
                            <span id="preview_name"></span>
                            <span id="preview_price" class="price-tag"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Titles Card -->
        <div class="promo-card">
            <div class="card-header-custom">
                <div class="header-icon">
                    <i class="fas fa-heading"></i>
                </div>
                <div>
                    <h3>{{ __('messages.offer_title_section') }}</h3>
                    <p>{{ __('messages.offer_title_desc') }}</p>
                </div>
            </div>
            
            <div class="card-body-custom">
                <div class="form-row">
                    <div class="form-group-enhanced">
                        <label for="title_ar">
                            <span class="flag-icon">🇸🇦</span> {{ __('messages.title_arabic') }} <span class="required">*</span>
                        </label>
                        <input type="text" name="title_ar" id="title_ar" class="form-control-enhanced" 
                               value="{{ old('title_ar') }}" 
                               placeholder="مثال: جهاز تحكم الألعاب + كابل USB 3.0"
                               required>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="title_en">
                            <span class="flag-icon">🇬🇧</span> {{ __('messages.title_english') }} <span class="required">*</span>
                        </label>
                        <input type="text" name="title_en" id="title_en" class="form-control-enhanced" 
                               value="{{ old('title_en') }}" 
                               placeholder="Example: Game Controller + USB 3.0 Cable"
                               required>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="title_he">
                            <span class="flag-icon">🇮🇱</span> {{ __('messages.title_hebrew') }}
                        </label>
                        <input type="text" name="title_he" id="title_he" class="form-control-enhanced" 
                               value="{{ old('title_he') }}"
                               placeholder="לדוגמה: בקר משחק + כבל USB 3.0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Card -->
        <div class="promo-card">
            <div class="card-header-custom">
                <div class="header-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <h3>{{ __('messages.pricing') }}</h3>
                    <p>{{ __('messages.pricing_desc') }}</p>
                </div>
            </div>
            
            <div class="card-body-custom">
                <div class="pricing-grid">
                    <div class="form-group-enhanced">
                        <label for="original_price">
                            <i class="fas fa-dollar-sign"></i> {{ __('messages.original_price') }} <span class="required">*</span>
                        </label>
                        <div class="input-with-icon">
                            <span class="currency">₪</span>
                            <input type="number" step="0.01" name="original_price" id="original_price" 
                                   class="form-control-enhanced" 
                                   value="{{ old('original_price') }}" 
                                   placeholder="0.00"
                                   required>
                        </div>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="sale_price">
                            <i class="fas fa-percent"></i> {{ __('messages.sale_price') }} <span class="required">*</span>
                        </label>
                        <div class="input-with-icon">
                            <span class="currency sale">₪</span>
                            <input type="number" step="0.01" name="sale_price" id="sale_price" 
                                   class="form-control-enhanced sale-input" 
                                   value="{{ old('sale_price') }}" 
                                   placeholder="0.00"
                                   required>
                        </div>
                    </div>

                    <div class="discount-display">
                        <div class="discount-label">{{ __('messages.calculated_discount') }}</div>
                        <div id="discount_preview" class="discount-value">
                            <span class="no-discount">{{ __('messages.enter_prices_to_calculate') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Card -->
        <div class="promo-card">
            <div class="card-header-custom">
                <div class="header-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h3>{{ __('messages.offer_features') }}</h3>
                    <p>{{ __('messages.offer_features_desc') }}</p>
                </div>
            </div>
            
            <div class="card-body-custom">
                <div class="form-row">
                    <div class="form-group-enhanced">
                        <label for="features_ar">
                            <span class="flag-icon">🇸🇦</span> {{ __('messages.features_arabic') }}
                        </label>
                        <textarea name="features_ar" id="features_ar" class="form-control-enhanced textarea-enhanced" 
                                  rows="4" 
                                  placeholder="شحن مجاني للطلبات أكثر من 50 دولار&#10;ضمان شامل&#10;كمية محدودة">{{ old('features_ar') }}</textarea>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="features_en">
                            <span class="flag-icon">🇬🇧</span> {{ __('messages.features_english') }}
                        </label>
                        <textarea name="features_en" id="features_en" class="form-control-enhanced textarea-enhanced" 
                                  rows="4" 
                                  placeholder="Free Shipping for orders over $50&#10;Full Warranty&#10;Limited Stock">{{ old('features_en') }}</textarea>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="features_he">
                            <span class="flag-icon">🇮🇱</span> {{ __('messages.features_hebrew') }}
                        </label>
                        <textarea name="features_he" id="features_he" class="form-control-enhanced textarea-enhanced" 
                                  rows="4">{{ old('features_he') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Duration Card -->
        <div class="promo-card">
            <div class="card-header-custom">
                <div class="header-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h3>{{ __('messages.offer_duration') }}</h3>
                    <p>{{ __('messages.offer_duration_desc') }}</p>
                </div>
            </div>
            
            <div class="card-body-custom">
                <div class="date-grid">
                    <div class="form-group-enhanced">
                        <label for="start_date">
                            <i class="fas fa-calendar-check"></i> {{ __('messages.start_date_label') }} <span class="required">*</span>
                        </label>
                        <input type="datetime-local" name="start_date" id="start_date" 
                               class="form-control-enhanced" 
                               value="{{ old('start_date') }}" 
                               required>
                    </div>

                    <div class="form-group-enhanced">
                        <label for="end_date">
                            <i class="fas fa-calendar-times"></i> {{ __('messages.end_date_label') }} <span class="required">*</span>
                        </label>
                        <input type="datetime-local" name="end_date" id="end_date" 
                               class="form-control-enhanced" 
                               value="{{ old('end_date') }}" 
                               required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Card -->
        <div class="promo-card">
            <div class="card-header-custom">
                <div class="header-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <h3>{{ __('messages.settings') }}</h3>
                    <p>{{ __('messages.additional_settings') }}</p>
                </div>
            </div>
            
            <div class="card-body-custom">
                <div class="settings-grid">
                    <div class="form-group-enhanced">
                        <label for="display_order">
                            <i class="fas fa-sort-numeric-down"></i> {{ __('messages.display_order') }}
                        </label>
                        <input type="number" name="display_order" id="display_order" 
                               class="form-control-enhanced" 
                               value="{{ old('display_order', 0) }}"
                               placeholder="0">
                        <small class="form-hint">
                            <i class="fas fa-info-circle"></i> {{ __('messages.display_order_hint') }}
                        </small>
                    </div>

                    <div class="form-group-enhanced">
                        <div class="toggle-wrapper">
                            <label class="toggle-label">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }} 
                                       class="toggle-input">
                                <span class="toggle-slider"></span>
                                <span class="toggle-text">
                                    <i class="fas fa-power-off"></i> {{ __('messages.activate_offer_immediately') }}
                                </span>
                            </label>
                            <small class="form-hint">{{ __('messages.will_appear_on_homepage') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions-enhanced">
            <button type="submit" class="btn-enhanced btn-primary-enhanced">
                <i class="fas fa-save"></i>
                <span>{{ __('messages.save_promotional_offer') }}</span>
            </button>
            <a href="{{ route('admin.promotional-offers.index') }}" class="btn-enhanced btn-secondary-enhanced">
                <i class="fas fa-times"></i>
                <span>{{ __('messages.cancel') }}</span>
            </a>
        </div>
    </form>
</div>

<style>
/* Enhanced Promo Card Styles */
.promo-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.promo-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.card-header-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.card-header-custom h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
}

.card-header-custom p {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 0.9rem;
}

.card-body-custom {
    padding: 2rem;
}

/* Form Enhancements */
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.form-group-enhanced {
    margin-bottom: 0;
}

.form-group-enhanced label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.95rem;
}

.flag-icon {
    font-size: 1.2rem;
}

.required {
    color: #e53e3e;
    font-weight: 700;
}

.form-control-enhanced {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f7fafc;
}

.form-control-enhanced:focus {
    outline: none;
    border-color: #667eea;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.textarea-enhanced {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
}

/* Select Wrapper */
.select-wrapper {
    position: relative;
}

.select-wrapper select {
    appearance: none;
}

/* LTR: Arrow on the right, padding on the right */
[dir="ltr"] .select-wrapper select {
    padding-right: 3rem;
    padding-left: 1rem;
}

/* RTL: Arrow on the left, padding on the left */
[dir="rtl"] .select-wrapper select {
    padding-left: 3rem;
    padding-right: 1rem;
}

.select-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    pointer-events: none;
}

/* LTR: Icon on the right side */
[dir="ltr"] .select-icon {
    right: 1rem;
    left: auto;
}

/* RTL: Icon on the left side */
[dir="rtl"] .select-icon {
    left: 1rem;
    right: auto;
}

/* Product Preview */
.product-preview {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-preview img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #fff;
}

.preview-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.preview-info span:first-child {
    font-weight: 600;
    color: #2d3748;
}

.price-tag {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

/* Pricing Grid */
.pricing-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1.5fr;
    gap: 1.5rem;
    align-items: start;
}

@media (max-width: 968px) {
    .pricing-grid {
        grid-template-columns: 1fr;
    }
}

.input-with-icon {
    position: relative;
}

.currency {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 700;
    color: #4a5568;
    font-size: 1.1rem;
}

.currency.sale {
    color: #667eea;
}

.input-with-icon input {
    padding-right: 3rem;
}

.sale-input {
    border-color: #667eea;
    background: linear-gradient(135deg, #f7fafc 0%, #e6f0ff 100%);
}

/* Discount Display */
.discount-display {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    padding: 1.5rem;
    border-radius: 12px;
    color: white;
    text-align: center;
}

.discount-label {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.discount-value {
    font-size: 1.5rem;
    font-weight: 700;
}

.no-discount {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Date Grid */
.date-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

@media (max-width: 768px) {
    .date-grid {
        grid-template-columns: 1fr;
    }
}

/* Settings Grid */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
}

.form-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    color: #718096;
    font-size: 0.85rem;
}

/* Toggle Switch */
.toggle-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.toggle-label {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    user-select: none;
}

.toggle-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: relative;
    width: 52px;
    height: 28px;
    background: #cbd5e0;
    border-radius: 34px;
    transition: all 0.3s ease;
}

.toggle-slider:before {
    content: "";
    position: absolute;
    height: 20px;
    width: 20px;
    right: 4px;
    bottom: 4px;
    background: white;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.toggle-input:checked + .toggle-slider {
    background: #48bb78;
}

.toggle-input:checked + .toggle-slider:before {
    transform: translateX(-24px);
}

.toggle-text {
    font-weight: 600;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Enhanced Action Buttons */
.form-actions-enhanced {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding: 2rem 0;
}

.btn-enhanced {
    padding: 1rem 2.5rem;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-primary-enhanced {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.btn-secondary-enhanced {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary-enhanced:hover {
    background: #cbd5e0;
}

@media (max-width: 768px) {
    .form-actions-enhanced {
        flex-direction: column;
    }
    
    .btn-enhanced {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const originalPriceInput = document.getElementById('original_price');
    const salePriceInput = document.getElementById('sale_price');
    const discountDisplay = document.getElementById('discount-display');
    const productPreview = document.getElementById('product-preview');
    const promoForm = document.getElementById('promoForm');

    // Product selection change
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const productName = selectedOption.dataset.name;
            const productPrice = selectedOption.dataset.price;
            const productImage = selectedOption.dataset.image;

            // Update original price
            originalPriceInput.value = productPrice;

            // Show preview
            productPreview.style.display = 'flex';
            productPreview.innerHTML = `
                <img src="${productImage}" alt="${productName}">
                <div>
                    <strong style="display: block; margin-bottom: 0.25rem;">${productName}</strong>
                    <span style="color: #667eea; font-size: 1.1rem; font-weight: 600;">₪${productPrice}</span>
                </div>
            `;

            calculateDiscount();
        } else {
            productPreview.style.display = 'none';
            originalPriceInput.value = '';
        }
    });

    // Calculate discount on price change
    [originalPriceInput, salePriceInput].forEach(input => {
        input.addEventListener('input', calculateDiscount);
    });

    function calculateDiscount() {
        const original = parseFloat(originalPriceInput.value) || 0;
        const sale = parseFloat(salePriceInput.value) || 0;

        if (original > 0 && sale > 0) {
            const discountAmount = original - sale;
            const discountPercentage = ((discountAmount / original) * 100).toFixed(1);

            if (discountAmount > 0) {
                discountDisplay.style.display = 'block';
                discountDisplay.innerHTML = `
                    <i class="fas fa-tag"></i>
                    <div>
                        <strong>الخصم:</strong> ₪${discountAmount.toFixed(2)} 
                        <span style="background: rgba(16, 185, 129, 0.2); color: #059669; padding: 0.25rem 0.5rem; border-radius: 6px; font-weight: 600; margin-right: 0.5rem;">
                            ${discountPercentage}%
                        </span>
                    </div>
                `;
            } else {
                discountDisplay.style.display = 'none';
            }
        } else {
            discountDisplay.style.display = 'none';
        }
    }

    // Form validation
    promoForm.addEventListener('submit', function(e) {
        const original = parseFloat(originalPriceInput.value) || 0;
        const sale = parseFloat(salePriceInput.value) || 0;

        if (sale >= original) {
            e.preventDefault();
            alert('سعر البيع يجب أن يكون أقل من السعر الأصلي!');
            salePriceInput.focus();
            return false;
        }
    });
});
</script>
@endsection
