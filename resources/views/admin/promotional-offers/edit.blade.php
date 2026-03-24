@extends('admin.layout')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div>
            <h1><i class="fas fa-edit"></i> {{ __('messages.edit_promotional_offer') }}</h1>
            <p>{{ __('messages.update_promotional_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.promotional-offers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-right: 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.promotional-offers.update', $promotionalOffer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;"><i class="fas fa-info-circle text-primary"></i> {{ __('messages.product_information') }}</h3>

            <div class="form-grid" style="grid-template-columns: 1fr;">
                <div class="form-group">
                    <label for="product_id">{{ __('messages.product') }} <span style="color: red;">*</span></label>
                    <select name="product_id" id="product_id" class="form-control" required style="max-width: 100%;">
                        <option value="">{{ __('messages.select_product_placeholder') }}</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old('product_id', $promotionalOffer->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (&#8362;{{ number_format($product->price, 2) }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;"><i class="fas fa-language text-primary"></i> {{ __('messages.offer_title_section') }}</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="title_ar">{{ __('messages.title_arabic') }} <span style="color: red;">*</span></label>
                    <input type="text" name="title_ar" id="title_ar" class="form-control" value="{{ old('title_ar', $promotionalOffer->title_ar) }}" required dir="rtl">
                </div>

                <div class="form-group">
                    <label for="title_en">{{ __('messages.title_english') }} <span style="color: red;">*</span></label>
                    <input type="text" name="title_en" id="title_en" class="form-control" value="{{ old('title_en', $promotionalOffer->title_en) }}" required dir="ltr">
                </div>

                <div class="form-group">
                    <label for="title_he">{{ __('messages.title_hebrew') }}</label>
                    <input type="text" name="title_he" id="title_he" class="form-control" value="{{ old('title_he', $promotionalOffer->title_he) }}" dir="rtl">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;"><i class="fas fa-dollar-sign text-primary"></i> {{ __('messages.pricing') }}</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="original_price">{{ __('messages.original_price') }} <span style="color: red;">*</span></label>
                    <div style="position: relative;">
                        <input type="number" step="0.01" name="original_price" id="original_price" class="form-control" value="{{ old('original_price', $promotionalOffer->original_price) }}" required style="padding-left: 2rem;" dir="ltr">
                        <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #666; font-weight: bold;">&#8362;</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sale_price">{{ __('messages.sale_price') }} <span style="color: red;">*</span></label>
                    <div style="position: relative;">
                        <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', $promotionalOffer->sale_price) }}" required style="padding-left: 2rem;" dir="ltr">
                        <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #666; font-weight: bold;">&#8362;</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ __('messages.calculated_discount') }}</label>
                    <div id="discount_preview" style="padding: 10px 15px; background: #ebfbee; border: 1px solid #b7ebc5; border-radius: 8px; font-weight: bold; min-height: 45px; display: flex; align-items: center; color: #166534;">
                        --
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 1rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;"><i class="fas fa-list text-primary"></i> {{ __('messages.offer_features') }}</h3>
            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.5rem; background: #f9fafb; padding: 10px; border-radius: 6px; border-left: 4px solid #667eea;">
                <i class="fas fa-info-circle" style="margin-right: 5px;"></i> {{ __('messages.offer_features_desc') }}
            </p>

            <div class="form-grid">
                <div class="form-group">
                    <label for="features_ar">{{ __('messages.features_arabic') }}</label>
                    <textarea name="features_ar" id="features_ar" class="form-control" rows="5" placeholder="شحن مجاني&#10;ضمان شامل&#10;كمية محدودة" dir="rtl">{{ old('features_ar', $promotionalOffer->features_ar) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="features_en">{{ __('messages.features_english') }}</label>
                    <textarea name="features_en" id="features_en" class="form-control" rows="5" placeholder="Free Shipping&#10;Full Warranty&#10;Limited Stock" dir="ltr">{{ old('features_en', $promotionalOffer->features_en) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="features_he">{{ __('messages.features_hebrew') }}</label>
                    <textarea name="features_he" id="features_he" class="form-control" rows="5" dir="rtl">{{ old('features_he', $promotionalOffer->features_he) }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;"><i class="fas fa-calendar text-primary"></i> {{ __('messages.offer_duration') }}</h3>

            <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                <div class="form-group">
                    <label for="start_date">{{ __('messages.start_date_label') }} <span style="color: red;">*</span></label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $promotionalOffer->start_date ? $promotionalOffer->start_date->format('Y-m-d\TH:i') : '') }}" required>
                </div>

                <div class="form-group">
                    <label for="end_date">{{ __('messages.end_date_label') }} <span style="color: red;">*</span></label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $promotionalOffer->end_date ? $promotionalOffer->end_date->format('Y-m-d\TH:i') : '') }}" required>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem;"><i class="fas fa-cog text-primary"></i> {{ __('messages.settings') }}</h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="display_order">{{ __('messages.display_order') }}</label>
                    <input type="number" name="display_order" id="display_order" class="form-control" value="{{ old('display_order', $promotionalOffer->display_order) }}" style="max-width: 200px;">
                    <small style="color: #6b7280; display: block; margin-top: 5px;"><i class="fas fa-sort-numeric-down" style="margin-right: 3px;"></i> {{ __('messages.display_order_hint') }}</small>
                </div>

                <div class="form-group" style="display: flex; align-items: center; padding-top: 1.5rem;">
                    <label class="checkbox-label" style="background: #f8f9fa; padding: 10px 15px; border-radius: 8px; border: 1px solid #eaeaea; width: 100%; display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-weight: 600; margin-bottom: 0; color: #333;">{{ __('messages.activate_offer_immediately') }}</span>
                        <div class="toggle-switch">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $promotionalOffer->is_active) ? 'checked' : '' }} style="display: none;">
                            <label for="is_active" style="cursor: pointer; position: relative; display: inline-block; width: 50px; height: 26px; background-color: #ccc; border-radius: 26px; transition: .4s; margin-bottom: 0;"></label>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ __('messages.update_offer') }}
            </button>
            <a href="{{ route('admin.promotional-offers.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('messages.cancel') }}
            </a>
        </div>
    </form>
</div>

<script>
// Auto-fill price when product selected
document.getElementById('product_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    if (price) {
        document.getElementById('original_price').value = price;
    }
});

// Calculate discount
function calculateDiscount() {
    const original = parseFloat(document.getElementById('original_price').value) || 0;
    const sale = parseFloat(document.getElementById('sale_price').value) || 0;

    if (original > 0 && sale > 0 && sale < original) {
        const discount = original - sale;
        const percentage = Math.round((discount / original) * 100);
        document.getElementById('discount_preview').innerHTML = `
            <span style="color: #28a745;">وفر &#8362;${discount.toFixed(2)} (${percentage}%)</span>
        `;
    } else {
        document.getElementById('discount_preview').textContent = '--';
    }
}

document.getElementById('original_price').addEventListener('input', calculateDiscount);
document.getElementById('sale_price').addEventListener('input', calculateDiscount);
</script>

<style>
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.form-actions .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.text-primary {
    color: #667eea;
}

/* Custom Toggle Switch for Active status */
input[type=checkbox]#is_active:checked + label {
    background-color: #10b981;
}

input[type=checkbox]#is_active:checked + label:before {
    transform: translateX(24px);
}

input[type=checkbox]#is_active + label:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

</style>
@endsection
