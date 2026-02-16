@extends('admin.layout')

@section('content')
<div class="promo-create-page">
    <!-- Page Header -->
    <div class="page-hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-sparkles"></i>
                <span>{{ __('messages.new_offer') }}</span>
            </div>
            <h1>{{ __('messages.create_new_promotional_offer') }}</h1>
            <p>{{ __('messages.create_promotional_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.promotional-offers.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>{{ __('messages.back_to_list') }}</span>
        </a>
    </div>

    @if($errors->any())
    <div class="error-banner">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="error-content">
            <strong>{{ __('messages.please_correct_errors') }}</strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="error-dismiss" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <form action="{{ route('admin.promotional-offers.store') }}" method="POST" id="promoForm" class="promo-form">
        @csrf
        
        <div class="form-grid">
            <!-- Left Column -->
            <div class="form-column">
                <!-- Product Selection Section -->
                <section class="form-section product-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-cube"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('messages.product_selection') }}</h2>
                            <p>{{ __('messages.select_product_desc') }}</p>
                        </div>
                        <span class="step-badge">1</span>
                    </div>
                    
                    <div class="section-body">
                        <div class="field-group">
                            <label class="field-label">
                                {{ __('messages.product') }}
                                <span class="required-dot"></span>
                            </label>
                            <div class="custom-select" id="productSelectWrapper">
                                <select name="product_id" id="product_id" required>
                                    <option value="">{{ __('messages.select_product_placeholder') }}</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->price }}"
                                            data-image="{{ $product->main_image }}"
                                            data-name="{{ $product->name }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (&#8362;{{ number_format($product->price, 2) }})
                                    </option>
                                    @endforeach
                                </select>
                                <div class="select-arrow">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Product Preview Card -->
                        <div id="product_preview" class="product-preview-card" style="display: none;">
                            <div class="preview-image">
                                <img id="preview_image" src="" alt="Product">
                            </div>
                            <div class="preview-details">
                                <span class="preview-label">{{ __('messages.selected_product') }}</span>
                                <h4 id="preview_name"></h4>
                                <div class="preview-price">
                                    <span class="price-label">{{ __('messages.current_price') }}</span>
                                    <span class="price-value" id="preview_price"></span>
                                </div>
                            </div>
                            <div class="preview-check">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Titles Section -->
                <section class="form-section titles-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-language"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('messages.offer_title_section') }}</h2>
                            <p>{{ __('messages.offer_title_desc') }}</p>
                        </div>
                        <span class="step-badge">2</span>
                    </div>
                    
                    <div class="section-body">
                        <div class="language-tabs">
                            <button type="button" class="lang-tab active" data-lang="ar">
                                <span class="flag">🇸🇦</span>
                                <span>{{ __('messages.arabic') }}</span>
                            </button>
                            <button type="button" class="lang-tab" data-lang="en">
                                <span class="flag">🇬🇧</span>
                                <span>{{ __('messages.english') }}</span>
                            </button>
                            <button type="button" class="lang-tab" data-lang="he">
                                <span class="flag">🇮🇱</span>
                                <span>{{ __('messages.hebrew') }}</span>
                            </button>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane active" data-lang="ar">
                                <div class="field-group">
                                    <label class="field-label">
                                        {{ __('messages.title_arabic') }}
                                        <span class="required-dot"></span>
                                    </label>
                                    <input type="text" name="title_ar" id="title_ar" 
                                           class="text-input rtl-input" 
                                           value="{{ old('title_ar') }}" 
                                           placeholder="مثال: جهاز تحكم الألعاب + كابل USB 3.0"
                                           dir="rtl"
                                           required>
                                    <span class="field-hint">{{ __('messages.title_hint_ar') }}</span>
                                </div>
                            </div>
                            <div class="tab-pane" data-lang="en">
                                <div class="field-group">
                                    <label class="field-label">
                                        {{ __('messages.title_english') }}
                                        <span class="required-dot"></span>
                                    </label>
                                    <input type="text" name="title_en" id="title_en" 
                                           class="text-input" 
                                           value="{{ old('title_en') }}" 
                                           placeholder="Example: Game Controller + USB 3.0 Cable"
                                           required>
                                    <span class="field-hint">{{ __('messages.title_hint_en') }}</span>
                                </div>
                            </div>
                            <div class="tab-pane" data-lang="he">
                                <div class="field-group">
                                    <label class="field-label">
                                        {{ __('messages.title_hebrew') }}
                                    </label>
                                    <input type="text" name="title_he" id="title_he" 
                                           class="text-input rtl-input" 
                                           value="{{ old('title_he') }}"
                                           placeholder="לדוגמה: בקר משחק + כבל USB 3.0"
                                           dir="rtl">
                                    <span class="field-hint optional">{{ __('messages.optional') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Features Section -->
                <section class="form-section features-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('messages.offer_features') }}</h2>
                            <p>{{ __('messages.offer_features_desc') }}</p>
                        </div>
                        <span class="step-badge">3</span>
                    </div>
                    
                    <div class="section-body">
                        <div class="features-grid">
                            <div class="field-group">
                                <label class="field-label with-flag">
                                    <span class="flag">🇸🇦</span>
                                    {{ __('messages.features_arabic') }}
                                </label>
                                <textarea name="features_ar" id="features_ar" 
                                          class="textarea-input rtl-input" 
                                          rows="4" 
                                          dir="rtl"
                                          placeholder="شحن مجاني للطلبات أكثر من 50 دولار&#10;ضمان شامل&#10;كمية محدودة">{{ old('features_ar') }}</textarea>
                                <span class="field-hint">{{ __('messages.features_hint') }}</span>
                            </div>

                            <div class="field-group">
                                <label class="field-label with-flag">
                                    <span class="flag">🇬🇧</span>
                                    {{ __('messages.features_english') }}
                                </label>
                                <textarea name="features_en" id="features_en" 
                                          class="textarea-input" 
                                          rows="4" 
                                          placeholder="Free Shipping for orders over $50&#10;Full Warranty&#10;Limited Stock">{{ old('features_en') }}</textarea>
                                <span class="field-hint">{{ __('messages.features_hint') }}</span>
                            </div>

                            <div class="field-group">
                                <label class="field-label with-flag">
                                    <span class="flag">🇮🇱</span>
                                    {{ __('messages.features_hebrew') }}
                                </label>
                                <textarea name="features_he" id="features_he" 
                                          class="textarea-input rtl-input" 
                                          rows="4"
                                          dir="rtl">{{ old('features_he') }}</textarea>
                                <span class="field-hint optional">{{ __('messages.optional') }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column -->
            <div class="form-column sidebar-column">
                <!-- Pricing Section -->
                <section class="form-section pricing-section sticky-section">
                    <div class="section-header compact">
                        <div class="section-icon pricing-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('messages.pricing') }}</h2>
                            <p>{{ __('messages.pricing_desc') }}</p>
                        </div>
                    </div>
                    
                    <div class="section-body">
                        <div class="price-inputs">
                            <div class="field-group">
                                <label class="field-label">
                                    {{ __('messages.original_price') }}
                                    <span class="required-dot"></span>
                                </label>
                                <div class="currency-input">
                                    <span class="currency-symbol">&#8362;</span>
                                    <input type="number" step="0.01" name="original_price" id="original_price" 
                                           class="number-input" 
                                           value="{{ old('original_price') }}" 
                                           placeholder="0.00"
                                           required>
                                </div>
                            </div>

                            <div class="price-arrow">
                                <i class="fas fa-arrow-down"></i>
                            </div>

                            <div class="field-group">
                                <label class="field-label sale-label">
                                    {{ __('messages.sale_price') }}
                                    <span class="required-dot"></span>
                                </label>
                                <div class="currency-input sale">
                                    <span class="currency-symbol">&#8362;</span>
                                    <input type="number" step="0.01" name="sale_price" id="sale_price" 
                                           class="number-input" 
                                           value="{{ old('sale_price') }}" 
                                           placeholder="0.00"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Calculator -->
                        <div id="discount_calculator" class="discount-calculator">
                            <div class="calculator-header">
                                <i class="fas fa-calculator"></i>
                                <span>{{ __('messages.calculated_discount') }}</span>
                            </div>
                            <div class="calculator-body" id="discount_display">
                                <div class="no-calculation">
                                    <i class="fas fa-coins"></i>
                                    <span>{{ __('messages.enter_prices_to_calculate') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Duration Section -->
                <section class="form-section duration-section">
                    <div class="section-header compact">
                        <div class="section-icon duration-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('messages.offer_duration') }}</h2>
                            <p>{{ __('messages.offer_duration_desc') }}</p>
                        </div>
                    </div>
                    
                    <div class="section-body">
                        <div class="date-fields">
                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-play-circle"></i>
                                    {{ __('messages.start_date_label') }}
                                    <span class="required-dot"></span>
                                </label>
                                <input type="datetime-local" name="start_date" id="start_date" 
                                       class="date-input" 
                                       value="{{ old('start_date') }}" 
                                       required>
                            </div>

                            <div class="date-connector">
                                <div class="connector-line"></div>
                                <i class="fas fa-arrow-right"></i>
                                <div class="connector-line"></div>
                            </div>

                            <div class="field-group">
                                <label class="field-label">
                                    <i class="fas fa-stop-circle"></i>
                                    {{ __('messages.end_date_label') }}
                                    <span class="required-dot"></span>
                                </label>
                                <input type="datetime-local" name="end_date" id="end_date" 
                                       class="date-input" 
                                       value="{{ old('end_date') }}" 
                                       required>
                            </div>
                        </div>

                        <div id="duration_preview" class="duration-preview" style="display: none;">
                            <i class="fas fa-hourglass-half"></i>
                            <span id="duration_text"></span>
                        </div>
                    </div>
                </section>

                <!-- Settings Section -->
                <section class="form-section settings-section">
                    <div class="section-header compact">
                        <div class="section-icon settings-icon">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div class="section-title">
                            <h2>{{ __('messages.settings') }}</h2>
                        </div>
                    </div>
                    
                    <div class="section-body">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-sort-numeric-up-alt"></i>
                                {{ __('messages.display_order') }}
                            </label>
                            <input type="number" name="display_order" id="display_order" 
                                   class="number-input small" 
                                   value="{{ old('display_order', 0) }}"
                                   placeholder="0">
                            <span class="field-hint">{{ __('messages.display_order_hint') }}</span>
                        </div>

                        <div class="toggle-field">
                            <label class="modern-toggle">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="toggle-track">
                                    <span class="toggle-thumb"></span>
                                </span>
                                <span class="toggle-label">
                                    <i class="fas fa-bolt"></i>
                                    {{ __('messages.activate_offer_immediately') }}
                                </span>
                            </label>
                            <span class="field-hint">{{ __('messages.will_appear_on_homepage') }}</span>
                        </div>
                    </div>
                </section>

                <!-- Submit Actions -->
                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <span class="btn-icon"><i class="fas fa-rocket"></i></span>
                        <span class="btn-text">{{ __('messages.save_promotional_offer') }}</span>
                        <span class="btn-shine"></span>
                    </button>
                    <a href="{{ route('admin.promotional-offers.index') }}" class="cancel-btn">
                        <i class="fas fa-times"></i>
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* ============================================
   PROMOTIONAL OFFER CREATE - PROFESSIONAL UI
   ============================================ */

/* CSS Variables */
.promo-create-page {
    --accent-primary: #0ea5e9;
    --accent-secondary: #6366f1;
    --accent-success: #10b981;
    --accent-warning: #f59e0b;
    --accent-danger: #ef4444;
    --bg-primary: #ffffff;
    --bg-secondary: #f8fafc;
    --bg-tertiary: #f1f5f9;
    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-muted: #94a3b8;
    --border-color: #e2e8f0;
    --border-hover: #cbd5e1;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Page Container */
.promo-create-page {
    padding: 0;
    max-width: 1400px;
    margin: 0 auto;
}

/* Page Hero Header */
.page-hero {
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    border-radius: var(--radius-xl);
    padding: 2.5rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.page-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    pointer-events: none;
}

.page-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    pointer-events: none;
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.hero-badge i {
    font-size: 0.875rem;
}

.page-hero h1 {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.page-hero p {
    color: rgba(255, 255, 255, 0.85);
    font-size: 1rem;
    margin: 0;
}

.back-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    transition: var(--transition);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateX(-4px);
}

[dir="rtl"] .back-btn:hover {
    transform: translateX(4px);
}

/* Error Banner */
.error-banner {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 1px solid #fecaca;
    border-radius: var(--radius-lg);
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    position: relative;
}

.error-icon {
    width: 40px;
    height: 40px;
    background: var(--accent-danger);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.error-content {
    flex: 1;
}

.error-content strong {
    color: #991b1b;
    font-size: 0.9375rem;
    display: block;
    margin-bottom: 0.5rem;
}

.error-content ul {
    margin: 0;
    padding-left: 1.25rem;
    color: #b91c1c;
    font-size: 0.875rem;
}

[dir="rtl"] .error-content ul {
    padding-left: 0;
    padding-right: 1.25rem;
}

.error-dismiss {
    background: none;
    border: none;
    color: #dc2626;
    cursor: pointer;
    padding: 0.25rem;
    opacity: 0.6;
    transition: var(--transition);
}

.error-dismiss:hover {
    opacity: 1;
}

/* Form Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 2rem;
    align-items: start;
}

@media (max-width: 1200px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.sidebar-column {
    position: sticky;
    top: 24px;
}

@media (max-width: 1200px) {
    .sidebar-column {
        position: static;
    }
}

/* Form Sections */
.form-section {
    background: var(--bg-primary);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
}

.form-section:hover {
    border-color: var(--border-hover);
    box-shadow: var(--shadow-md);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.section-header.compact {
    padding: 1.25rem 1.5rem;
}

.section-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.section-icon.pricing-icon {
    background: linear-gradient(135deg, var(--accent-success) 0%, #059669 100%);
}

.section-icon.duration-icon {
    background: linear-gradient(135deg, var(--accent-warning) 0%, #d97706 100%);
}

.section-icon.settings-icon {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
}

.section-title {
    flex: 1;
}

.section-title h2 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.25rem 0;
}

.section-title p {
    font-size: 0.8125rem;
    color: var(--text-secondary);
    margin: 0;
}

.step-badge {
    width: 28px;
    height: 28px;
    background: var(--accent-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
}

.section-body {
    padding: 1.5rem;
}

/* Field Groups */
.field-group {
    margin-bottom: 1.25rem;
}

.field-group:last-child {
    margin-bottom: 0;
}

.field-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.field-label.with-flag .flag {
    font-size: 1.125rem;
}

.field-label.sale-label {
    color: var(--accent-success);
}

.field-label i {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.required-dot {
    width: 6px;
    height: 6px;
    background: var(--accent-danger);
    border-radius: 50%;
    display: inline-block;
}

.field-hint {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 0.5rem;
}

.field-hint.optional {
    font-style: italic;
}

/* Text Inputs */
.text-input,
.number-input,
.textarea-input,
.date-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.9375rem;
    font-family: inherit;
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: var(--transition);
}

.text-input:focus,
.number-input:focus,
.textarea-input:focus,
.date-input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.text-input::placeholder,
.textarea-input::placeholder {
    color: var(--text-muted);
}

.rtl-input {
    text-align: right;
}

.textarea-input {
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

.number-input.small {
    max-width: 120px;
}

/* Custom Select */
.custom-select {
    position: relative;
}

.custom-select select {
    width: 100%;
    padding: 0.875rem 3rem 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.9375rem;
    font-family: inherit;
    background: var(--bg-primary);
    color: var(--text-primary);
    appearance: none;
    cursor: pointer;
    transition: var(--transition);
}

[dir="rtl"] .custom-select select {
    padding: 0.875rem 1rem 0.875rem 3rem;
}

.custom-select select:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.select-arrow {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
    transition: var(--transition);
}

[dir="rtl"] .select-arrow {
    right: auto;
    left: 1rem;
}

.custom-select select:focus + .select-arrow {
    color: var(--accent-primary);
}

/* Product Preview Card */
.product-preview-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #bae6fd;
    border-radius: var(--radius-md);
    margin-top: 1rem;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.preview-image {
    width: 72px;
    height: 72px;
    border-radius: var(--radius-md);
    overflow: hidden;
    flex-shrink: 0;
    background: white;
    border: 2px solid white;
    box-shadow: var(--shadow-sm);
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-details {
    flex: 1;
}

.preview-label {
    display: block;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--accent-primary);
    margin-bottom: 0.25rem;
}

.preview-details h4 {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.preview-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.price-label {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.price-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.preview-check {
    width: 32px;
    height: 32px;
    background: var(--accent-success);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
}

/* Language Tabs */
.language-tabs {
    display: flex;
    gap: 0.5rem;
    padding: 0.5rem;
    background: var(--bg-tertiary);
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
}

.lang-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border: none;
    background: transparent;
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: var(--transition);
}

.lang-tab:hover {
    background: rgba(255, 255, 255, 0.5);
}

.lang-tab.active {
    background: white;
    color: var(--accent-primary);
    box-shadow: var(--shadow-sm);
}

.lang-tab .flag {
    font-size: 1.125rem;
}

.tab-content {
    position: relative;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Features Grid */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

/* Currency Input */
.currency-input {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-symbol {
    position: absolute;
    left: 1rem;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-muted);
    z-index: 1;
}

[dir="rtl"] .currency-symbol {
    left: auto;
    right: 1rem;
}

.currency-input input {
    padding-left: 2.5rem;
}

[dir="rtl"] .currency-input input {
    padding-left: 1rem;
    padding-right: 2.5rem;
}

.currency-input.sale .currency-symbol {
    color: var(--accent-success);
}

.currency-input.sale input {
    border-color: #86efac;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
}

.currency-input.sale input:focus {
    border-color: var(--accent-success);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Price Arrow */
.price-arrow {
    display: flex;
    justify-content: center;
    padding: 0.5rem 0;
    color: var(--text-muted);
}

/* Discount Calculator */
.discount-calculator {
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    overflow: hidden;
    margin-top: 1.5rem;
}

.calculator-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: var(--bg-tertiary);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
}

.calculator-body {
    padding: 1.5rem;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-calculation {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.875rem;
}

.no-calculation i {
    font-size: 1.5rem;
    opacity: 0.5;
}

.discount-result {
    text-align: center;
    width: 100%;
}

.discount-amount {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--accent-success);
    margin-bottom: 0.5rem;
}

.discount-percent {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, var(--accent-success) 0%, #059669 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    font-size: 0.9375rem;
    font-weight: 700;
}

.discount-percent i {
    font-size: 0.75rem;
}

/* Date Fields */
.date-fields {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.date-connector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    padding: 0.25rem 0;
}

.connector-line {
    flex: 1;
    height: 1px;
    background: var(--border-color);
}

/* Duration Preview */
.duration-preview {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #fcd34d;
    border-radius: var(--radius-md);
    margin-top: 1rem;
    color: #92400e;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Toggle Field */
.toggle-field {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.modern-toggle {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
}

.modern-toggle input {
    display: none;
}

.toggle-track {
    width: 52px;
    height: 28px;
    background: var(--border-color);
    border-radius: 100px;
    position: relative;
    transition: var(--transition);
    flex-shrink: 0;
}

.toggle-thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 22px;
    height: 22px;
    background: white;
    border-radius: 50%;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.modern-toggle input:checked + .toggle-track {
    background: var(--accent-success);
}

.modern-toggle input:checked + .toggle-track .toggle-thumb {
    left: 27px;
}

.toggle-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text-primary);
}

.toggle-label i {
    color: var(--accent-warning);
}

/* Form Actions */
.form-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding-top: 1rem;
}

.submit-btn {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    overflow: hidden;
    transition: var(--transition);
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(14, 165, 233, 0.4);
}

.submit-btn:active {
    transform: translateY(0);
}

.btn-icon {
    font-size: 1.125rem;
}

.btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: 0.5s;
}

.submit-btn:hover .btn-shine {
    left: 100%;
}

.cancel-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.875rem 2rem;
    background: var(--bg-secondary);
    color: var(--text-secondary);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.9375rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.cancel-btn:hover {
    background: var(--bg-tertiary);
    border-color: var(--border-hover);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .page-hero {
        flex-direction: column;
        align-items: flex-start;
        gap: 1.5rem;
        padding: 1.5rem;
    }

    .page-hero h1 {
        font-size: 1.5rem;
    }

    .back-btn {
        width: 100%;
        justify-content: center;
    }

    .form-grid {
        gap: 1.5rem;
    }

    .section-header {
        flex-wrap: wrap;
    }

    .language-tabs {
        flex-wrap: wrap;
    }

    .lang-tab {
        min-width: calc(50% - 0.25rem);
    }

    .features-grid {
        grid-template-columns: 1fr;
    }
}

/* RTL Specific Adjustments */
[dir="rtl"] .page-hero {
    text-align: right;
}

[dir="rtl"] .back-btn {
    flex-direction: row-reverse;
}

[dir="rtl"] .section-header {
    flex-direction: row-reverse;
}

[dir="rtl"] .field-label {
    flex-direction: row-reverse;
}

[dir="rtl"] .toggle-label {
    flex-direction: row-reverse;
}

[dir="rtl"] .submit-btn,
[dir="rtl"] .cancel-btn {
    flex-direction: row-reverse;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const originalPriceInput = document.getElementById('original_price');
    const salePriceInput = document.getElementById('sale_price');
    const discountDisplay = document.getElementById('discount_display');
    const productPreview = document.getElementById('product_preview');
    const previewImage = document.getElementById('preview_image');
    const previewName = document.getElementById('preview_name');
    const previewPrice = document.getElementById('preview_price');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const durationPreview = document.getElementById('duration_preview');
    const durationText = document.getElementById('duration_text');
    const promoForm = document.getElementById('promoForm');

    // Language tabs functionality
    document.querySelectorAll('.lang-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const lang = this.dataset.lang;
            
            // Update tabs
            document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update content
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
            document.querySelector(`.tab-pane[data-lang="${lang}"]`).classList.add('active');
        });
    });

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
            previewImage.src = productImage;
            previewName.textContent = productName;
            previewPrice.textContent = '&#8362;' + parseFloat(productPrice).toFixed(2);

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

        if (original > 0 && sale > 0 && sale < original) {
            const discountAmount = original - sale;
            const discountPercentage = ((discountAmount / original) * 100).toFixed(1);

            discountDisplay.innerHTML = `
                <div class="discount-result">
                    <div class="discount-amount">-&#8362;${discountAmount.toFixed(2)}</div>
                    <div class="discount-percent">
                        <i class="fas fa-arrow-down"></i>
                        ${discountPercentage}% OFF
                    </div>
                </div>
            `;
        } else {
            discountDisplay.innerHTML = `
                <div class="no-calculation">
                    <i class="fas fa-coins"></i>
                    <span>{{ __('messages.enter_prices_to_calculate') }}</span>
                </div>
            `;
        }
    }

    // Duration calculator
    [startDateInput, endDateInput].forEach(input => {
        input.addEventListener('change', calculateDuration);
    });

    function calculateDuration() {
        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);

        if (start && end && end > start) {
            const diff = end - start;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

            let durationStr = '';
            if (days > 0) {
                durationStr += days + ' {{ __("messages.days") }}';
            }
            if (hours > 0) {
                durationStr += (durationStr ? ' {{ __("messages.and") }} ' : '') + hours + ' {{ __("messages.hours") }}';
            }

            durationPreview.style.display = 'flex';
            durationText.textContent = '{{ __("messages.offer_lasts") }}: ' + durationStr;
        } else {
            durationPreview.style.display = 'none';
        }
    }

    // Form validation
    promoForm.addEventListener('submit', function(e) {
        const original = parseFloat(originalPriceInput.value) || 0;
        const sale = parseFloat(salePriceInput.value) || 0;

        if (sale >= original) {
            e.preventDefault();
            alert('{{ __("messages.sale_price_must_be_less") }}');
            salePriceInput.focus();
            return false;
        }

        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);

        if (end <= start) {
            e.preventDefault();
            alert('{{ __("messages.end_date_must_be_after_start") }}');
            endDateInput.focus();
            return false;
        }
    });

    // Initialize if there are old values
    if (productSelect.value) {
        productSelect.dispatchEvent(new Event('change'));
    }
    calculateDiscount();
    calculateDuration();
});
</script>
@endsection
