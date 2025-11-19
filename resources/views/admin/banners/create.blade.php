@extends('admin.layout')

@section('title', 'إضافة بانر')

@section('content')
<style>
.form-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-grid .full { grid-column: 1 / -1; }
.input { width: 100%; padding: 10px 12px; border: 1px solid #e6edf8; border-radius: 8px; }
.checks { display: flex; gap: 10px; align-items: center; }
</style>

<div class="form-card">
    <h2>إضافة بانر</h2>
    <form method="POST" action="{{ route('admin.banners.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label>Title (EN)</label>
                <input class="input" name="title_en" required />
            </div>
            <div>
                <label>Title (AR)</label>
                <input class="input" name="title_ar" required />
            </div>
            <div>
                <label>Image URL</label>
                <input class="input" name="image_url" required />
            </div>
            <div>
                <label>Section</label>
                <select class="input" name="section">
                    <option value="strong_offers">Strong Offers</option>
                    <option value="gift_ideas">Gift Ideas</option>
                    <option value="hero">Hero</option>
                </select>
            </div>

            <div class="full">
                <label>Link Type</label>
                <select class="input" name="link_type">
                    <option value="products">Products</option>
                    <option value="category">Category</option>
                    <option value="external">External URL</option>
                    <option value="categories">All Categories</option>
                </select>
            </div>

            <div>
                <label>Category (optional)</label>
                <select class="input" name="category_id">
                    <option value="">-- None --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Display Order</label>
                <input class="input" name="display_order" type="number" value="0" />
            </div>

            <div class="full">
                <label>Filter Options (check to apply)</label>
                <div class="checks">
                    <label><input type="checkbox" name="filter_featured" /> Featured</label>
                    <label><input type="checkbox" name="filter_new" /> New</label>
                    <label><input type="checkbox" name="filter_bestseller" /> Bestseller</label>
                    <label><input type="checkbox" name="filter_special_offer" /> Special Offer</label>
                </div>
            </div>

            <div class="full">
                <label>Button Text (EN)</label>
                <input class="input" name="button_text_en" />
            </div>

            <div class="full">
                <label>External Link (Optional)</label>
                <input class="input" name="link_url" placeholder="https://example.com" />
            </div>

            <div>
                <label>Active</label>
                <input type="checkbox" name="is_active" checked />
            </div>
            <div>
                <label>Save</label>
                <button class="create-btn" type="submit">Save</button>
            </div>
        </div>
    </form>
</div>

@endsection