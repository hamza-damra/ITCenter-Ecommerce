@extends('admin.layout')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div>
            <h1><i class="fas fa-edit"></i> تعديل العرض الترويجي</h1>
            <p>قم بتحديث معلومات العرض الترويجي</p>
        </div>
        <a href="{{ route('admin.promotional-offers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> رجوع
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
            <h3 style="margin-bottom: 2rem;"><i class="fas fa-info-circle"></i> معلومات المنتج</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="product_id">اختر المنتج <span style="color: red;">*</span></label>
                    <select name="product_id" id="product_id" class="form-control" required>
                        <option value="">-- اختر منتج --</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old('product_id', $promotionalOffer->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (₪{{ number_format($product->price, 2) }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem;"><i class="fas fa-language"></i> العناوين</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="title_ar">العنوان بالعربية <span style="color: red;">*</span></label>
                    <input type="text" name="title_ar" id="title_ar" class="form-control" value="{{ old('title_ar', $promotionalOffer->title_ar) }}" required>
                </div>

                <div class="form-group">
                    <label for="title_en">العنوان بالإنجليزية <span style="color: red;">*</span></label>
                    <input type="text" name="title_en" id="title_en" class="form-control" value="{{ old('title_en', $promotionalOffer->title_en) }}" required>
                </div>

                <div class="form-group">
                    <label for="title_he">العنوان بالعبرية</label>
                    <input type="text" name="title_he" id="title_he" class="form-control" value="{{ old('title_he', $promotionalOffer->title_he) }}">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem;"><i class="fas fa-dollar-sign"></i> الأسعار</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="original_price">السعر الأصلي <span style="color: red;">*</span></label>
                    <input type="number" step="0.01" name="original_price" id="original_price" class="form-control" value="{{ old('original_price', $promotionalOffer->original_price) }}" required>
                </div>

                <div class="form-group">
                    <label for="sale_price">سعر العرض <span style="color: red;">*</span></label>
                    <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', $promotionalOffer->sale_price) }}" required>
                </div>

                <div class="form-group">
                    <label>الخصم المحسوب</label>
                    <div id="discount_preview" style="padding: 10px; background: #f8f9fa; border-radius: 5px; font-weight: bold;">
                        --
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem;"><i class="fas fa-list"></i> الميزات (اختياري)</h3>
            <p style="color: #666; margin-bottom: 1rem;">أدخل كل ميزة في سطر منفصل</p>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="features_ar">الميزات بالعربية</label>
                    <textarea name="features_ar" id="features_ar" class="form-control" rows="4" placeholder="شحن مجاني&#10;ضمان شامل&#10;كمية محدودة">{{ old('features_ar') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="features_en">الميزات بالإنجليزية</label>
                    <textarea name="features_en" id="features_en" class="form-control" rows="4" placeholder="Free Shipping&#10;Full Warranty&#10;Limited Stock">{{ old('features_en') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="features_he">الميزات بالعبرية</label>
                    <textarea name="features_he" id="features_he" class="form-control" rows="4">{{ old('features_he') }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem;"><i class="fas fa-calendar"></i> مدة العرض</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="start_date">تاريخ البداية <span style="color: red;">*</span></label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $promotionalOffer->start_date->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="form-group">
                    <label for="end_date">تاريخ النهاية <span style="color: red;">*</span></label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $promotionalOffer->end_date->format('Y-m-d\TH:i')) }}" required>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom: 2rem;"><i class="fas fa-cog"></i> الإعدادات</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="display_order">ترتيب العرض</label>
                    <input type="number" name="display_order" id="display_order" class="form-control" value="{{ old('display_order', $promotionalOffer->display_order) }}">
                    <small style="color: #666;">رقم أقل = يظهر أولاً</small>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promotionalOffer->is_active) ? 'checked' : '' }}>
                        <span>تفعيل العرض فوراً</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> تحديث العرض
            </button>
            <a href="{{ route('admin.promotional-offers.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> إلغاء
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
            <span style="color: #28a745;">وفر ₪${discount.toFixed(2)} (${percentage}%)</span>
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
}
</style>
@endsection
