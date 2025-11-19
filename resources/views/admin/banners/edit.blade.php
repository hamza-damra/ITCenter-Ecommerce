@extends('admin.layout')

@section('title', 'تعديل بانر')

@section('content')
<style>
.form-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.input { width: 100%; padding: 10px 12px; border: 1px solid #e6edf8; border-radius: 8px; }
.checks { display: flex; gap: 10px; align-items: center; }
</style>

<div class="form-card">
    <h2>تعديل بانر</h2>
    <form method="POST" action="{{ route('admin.banners.update', $banner) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div>
                <label>Title (EN)</label>
                <input class="input" name="title_en" value="{{ $banner->title_en }}" required />
            </div>
            <div>
                <label>Title (AR)</label>
                <input class="input" name="title_ar" value="{{ $banner->title_ar }}" required />
            </div>
            <div>
                <label>Image URL</label>
                <input class="input" name="image_url" value="{{ $banner->image_url }}" required />
            </div>
            <div>
                <label>Section</label>
                <select class="input" name="section">
                    <option value="strong_offers" {{ $banner->section==='strong_offers' ? 'selected' : '' }}>Strong Offers</option>
                    <option value="gift_ideas" {{ $banner->section==='gift_ideas' ? 'selected' : '' }}>Gift Ideas</option>
                    <option value="hero" {{ $banner->section==='hero' ? 'selected' : '' }}>Hero</option>
                </select>
            </div>

            <div class="full">
                <label>Link Type</label>
                <select class="input" name="link_type">
                    <option value="products" {{ $banner->link_type==='products' ? 'selected' : '' }}>Products</option>
                    <option value="category" {{ $banner->link_type==='category' ? 'selected' : '' }}>Category</option>
                    <option value="external" {{ $banner->link_type==='external' ? 'selected' : '' }}>External URL</option>
                    <option value="categories" {{ $banner->link_type==='categories' ? 'selected' : '' }}>All Categories</option>
                </select>
            </div>

            <div>
                <label>Category (optional)</label>
                <select class="input" name="category_id">
                    <option value="">-- None --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $banner->category_id===$cat->id ? 'selected' : '' }}>{{ $cat->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Display Order</label>
                <input class="input" name="display_order" type="number" value="{{ $banner->display_order }}" />
            </div>

            <div class="full">
                <label>Filter Options (check to apply)</label>
                <div class="checks">
                    <label><input type="checkbox" name="filter_featured" {{ data_get($banner->filter_options,'featured') ? 'checked' : '' }} /> Featured</label>
                    <label><input type="checkbox" name="filter_new" {{ data_get($banner->filter_options,'new') ? 'checked' : '' }} /> New</label>
                    <label><input type="checkbox" name="filter_bestseller" {{ data_get($banner->filter_options,'bestseller') ? 'checked' : '' }} /> Bestseller</label>
                    <label><input type="checkbox" name="filter_special_offer" {{ data_get($banner->filter_options,'special_offer') ? 'checked' : '' }} /> Special Offer</label>
                </div>
            </div>

            <div class="full">
                <label>Button Text (EN)</label>
                <input class="input" name="button_text_en" value="{{ $banner->button_text_en }}" />
            </div>

            <div class="full">
                <label>External Link (Optional)</label>
                <input class="input" name="link_url" value="{{ $banner->link_url }}" placeholder="https://example.com" />
            </div>

            <div>
                <label>Active</label>
                <input type="checkbox" name="is_active" {{ $banner->is_active ? 'checked' : '' }} />
            </div>
            <div>
                <label>Save</label>
                <button class="create-btn" type="submit">Save</button>
            </div>
        </div>
    </form>
</div>

@endsection