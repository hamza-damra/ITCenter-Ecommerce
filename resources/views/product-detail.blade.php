@extends('layouts.app')

@section('title', $product->name . ' - IT Center')

@section('content')
<style>
    .product-detail-container {
        padding: 3rem 0;
        background: #fff;
    }

    .product-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    /* Product Images Section */
    .product-images {
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .main-image {
        width: 100%;
        height: 500px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
        position: relative;
        border: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        transition: transform 0.3s;
        padding: 10px;
    }

    .main-image:hover img {
        transform: scale(1.05);
    }

    .thumbnail-images {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .thumbnail {
        height: 100px;
        background: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .thumbnail:hover,
    .thumbnail.active {
        border-color: #4169E1;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        padding: 5px;
    }

    /* Product Info Section */
    .product-info {
        padding: 1rem 0;
    }

    .product-category {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .product-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .stars {
        color: #ffa500;
        font-size: 1.1rem;
    }

    .rating-text {
        color: #666;
        font-size: 0.9rem;
    }

    .product-price {
        display: flex;
        align-items: baseline;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .current-price {
        font-size: 2.5rem;
        font-weight: 700;
        color: #4169E1;
    }

    .original-price {
        font-size: 1.5rem;
        color: #999;
        text-decoration: line-through;
    }

    .discount-badge {
        background: #ff4444;
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #d4edda;
        color: #155724;
        border-radius: 6px;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    .stock-status.out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }

    .product-description {
        color: #555;
        line-height: 1.8;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    /* Quantity Selector */
    .quantity-section {
        margin-bottom: 1.5rem;
    }

    .quantity-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        display: block;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .quantity-btn {
        background: #f5f5f5;
        border: none;
        padding: 0.8rem 1.2rem;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background 0.3s;
    }

    .quantity-btn:hover {
        background: #e0e0e0;
    }

    .quantity-input {
        border: none;
        width: 60px;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 0.8rem 0;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .btn-add-cart {
        flex: 1;
        background: #4169E1;
        color: #fff;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-add-cart:hover {
        background: #1E90FF;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(65, 105, 225, 0.3);
    }

    .btn-add-cart:disabled,
    .btn-buy-now:disabled,
    .quantity-btn:disabled {
        background: #ccc;
        color: #666;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-add-cart:disabled:hover,
    .btn-buy-now:disabled:hover,
    .quantity-btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .btn-buy-now {
        flex: 1;
        background: #000;
        color: #fff;
        border: none;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-buy-now:hover {
        background: #333;
        transform: translateY(-2px);
    }

    .btn-wishlist {
        background: #fff;
        border: 2px solid #4169E1;
        color: #4169E1;
        padding: 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.3rem;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background: #fff;
        color: #ff0000;
        border-color: #ff0000;
    }

    .btn-wishlist.active {
        background: #fff;
        color: #ff0000;
        border-color: #ff0000;
    }

    .btn-wishlist.active i {
        color: #ff0000;
    }

    .btn-wishlist i {
        transition: color 0.3s;
    }

    /* Product Features */
    .product-features {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .feature-item:last-child {
        border-bottom: none;
    }

    .feature-icon {
        color: #4169E1;
        font-size: 1.3rem;
        width: 30px;
    }

    .feature-text {
        color: #555;
        font-size: 0.95rem;
    }

    /* Specifications Section */
    .specifications-section {
        margin-top: 3rem;
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #4169E1;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .spec-item {
        display: flex;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .spec-label {
        font-weight: 600;
        color: #333;
        min-width: 150px;
    }

    .spec-value {
        color: #666;
    }

    /* Related Products */
    .related-products {
        margin-top: 4rem;
        padding: 3rem 0;
        background: #f8f9fa;
    }

    .related-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 2rem;
        text-align: center;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .product-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .product-card-image {
        width: 100%;
        height: 200px;
        background: #f5f5f5;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-card-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        padding: 10px;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-card-image img {
        transform: scale(1.08);
    }

    .product-card-content {
        padding: 1rem;
    }

    .product-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-card-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #4169E1;
    }

    @media (max-width: 968px) {
        .product-main {
            grid-template-columns: 1fr;
        }

        .product-images {
            position: relative;
            top: 0;
        }

        .specs-grid {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 568px) {
        .products-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }


    /* أخفي الأسهم في كروم/إيدج/سفاري */
.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* أخفيها في فايرفوكس */
.quantity-input {
  -moz-appearance: textfield;
  appearance: textfield; /* دعم عام */
}

</style>

<div class="product-detail-container">
    <div class="container">
        <div class="product-main">
            <!-- Product Images -->
            <div class="product-images">
                <div class="main-image">
                    @php
                        $mainImageUrl = $product->main_image 
                            ? (filter_var($product->main_image, FILTER_VALIDATE_URL) 
                                ? $product->main_image 
                                : asset('storage/' . $product->main_image))
                            : 'https://via.placeholder.com/800x800/f5f5f5/666666?text=' . urlencode($product->name);
                    @endphp
                    <img src="{{ $mainImageUrl }}" alt="{{ $product->name }}" id="mainImage" onerror="this.src='https://via.placeholder.com/800x800/f5f5f5/666666?text=No+Image'">
                </div>
                <div class="thumbnail-images">
                    @if($product->images->count() > 0)
                        @foreach($product->images->take(4) as $index => $image)
                            <div class="thumbnail {{ $index === 0 ? 'active' : '' }}">
                                @php
                                    $thumbnailUrl = $image->image_path 
                                        ? (filter_var($image->image_path, FILTER_VALIDATE_URL) 
                                            ? $image->image_path 
                                            : asset('storage/' . $image->image_path))
                                        : 'https://via.placeholder.com/200x200/f5f5f5/666666?text=Image+' . ($index + 1);
                                @endphp
                                <img src="{{ $thumbnailUrl }}" alt="{{ $product->name }}" onclick="changeImage(this)" onerror="this.src='https://via.placeholder.com/200x200/f5f5f5/666666?text=No+Image'">
                            </div>
                        @endforeach
                    @else
                        <div class="thumbnail active">
                            <img src="{{ $mainImageUrl }}" alt="{{ $product->name }}" onclick="changeImage(this)" onerror="this.src='https://via.placeholder.com/200x200/f5f5f5/666666?text=No+Image'">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }} @if($product->brand) / {{ $product->brand->name }}@endif</div>
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-rating">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product->avg_rating))
                                <i class="fas fa-star"></i>
                            @elseif($i - $product->avg_rating < 1)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-text">{{ number_format($product->avg_rating, 1) }} ({{ $product->reviews_count }} reviews)</span>
                </div>

                <div class="product-price">
                    <span class="current-price">${{ number_format($product->final_price, 2) }}</span>
                    @if($product->is_on_sale)
                        <span class="original-price">${{ number_format($product->price, 2) }}</span>
                        <span class="discount-badge">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>

                <div class="stock-status {{ $product->stock_status === 'out_of_stock' ? 'out-of-stock' : '' }}">
                    @if($product->stock_status === 'in_stock')
                        <i class="fas fa-check-circle"></i>
                        <span>In Stock - Ready to Ship</span>
                    @else
                        <i class="fas fa-times-circle"></i>
                        <span>Out of Stock</span>
                    @endif
                </div>

                <p class="product-description">
                    {{ $product->short_description ?? $product->description }}
                </p>

                <div class="product-features">
                    <div class="feature-item">
                        <i class="fas fa-shipping-fast feature-icon"></i>
                        <span class="feature-text">Free shipping on orders over $50</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-undo feature-icon"></i>
                        <span class="feature-text">30-day return policy</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <span class="feature-text">1 year manufacturer warranty</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-headset feature-icon"></i>
                        <span class="feature-text">24/7 customer support</span>
                    </div>
                </div>

                <div class="quantity-section">
                    <label class="quantity-label">Quantity:</label>
                    <div class="quantity-selector">
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="decreaseQuantity()" {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>-</button>
                            <input type="number" class="quantity-input" value="1" min="1" 
                                   max="{{ $product->track_stock ? $product->stock_quantity : 999 }}" 
                                   id="quantity" 
                                   {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                            <button class="quantity-btn" onclick="increaseQuantity()" {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>+</button>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-add-cart" 
                            type="button"
                            onclick="addToCartWithQuantity({{ $product->id }}, this)" 
                            {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i>
                        {{ $product->stock_status === 'out_of_stock' ? 'Out of Stock' : 'Add to Cart' }}
                    </button>
                    <button class="btn-buy-now" 
                            type="button"
                            {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                        {{ $product->stock_status === 'out_of_stock' ? 'Unavailable' : 'Buy Now' }}
                    </button>
                    <button class="btn-wishlist wishlist-btn" 
                            type="button"
                            data-product-id="{{ $product->id }}">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="specifications-section">
            <h2 class="section-title">Technical Specifications</h2>
            <div class="specs-grid">
                @if($product->specifications && is_array($product->specifications))
                    @foreach($product->specifications as $key => $value)
                        <div class="spec-item">
                            <span class="spec-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="spec-value">{{ $value }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="spec-item">
                        <span class="spec-label">SKU:</span>
                        <span class="spec-value">{{ $product->sku }}</span>
                    </div>
                    @if($product->weight)
                        <div class="spec-item">
                            <span class="spec-label">Weight:</span>
                            <span class="spec-value">{{ $product->weight }} kg</span>
                        </div>
                    @endif
                    @if($product->warranty)
                        <div class="spec-item">
                            <span class="spec-label">Warranty:</span>
                            <span class="spec-value">{{ $product->warranty }}</span>
                        </div>
                    @endif
                    @if($product->length && $product->width && $product->height)
                        <div class="spec-item">
                            <span class="spec-label">Dimensions:</span>
                            <span class="spec-value">{{ $product->length }} x {{ $product->width }} x {{ $product->height }} cm</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Full Description -->
        @if($product->description && $product->description != $product->short_description)
        <div class="specifications-section" style="margin-top: 2rem;">
            <h2 class="section-title">Product Description</h2>
            <div style="color: #555; line-height: 1.8; font-size: 1rem;">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Related Products -->
<div class="related-products">
    <div class="container">
        <h2 class="related-title">Related Products</h2>
        <div class="products-grid">
            @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('product.detail', $relatedProduct->slug) }}" style="text-decoration: none; color: inherit;">
                    <div class="product-card">
                        <div class="product-card-image">
                            @php
                                $relatedImageUrl = $relatedProduct->main_image 
                                    ? (filter_var($relatedProduct->main_image, FILTER_VALIDATE_URL) 
                                        ? $relatedProduct->main_image 
                                        : asset('storage/' . $relatedProduct->main_image))
                                    : 'https://via.placeholder.com/300x200/f5f5f5/666666?text=' . urlencode($relatedProduct->name);
                            @endphp
                            <img src="{{ $relatedImageUrl }}" alt="{{ $relatedProduct->name }}" onerror="this.src='https://via.placeholder.com/300x200/f5f5f5/666666?text=No+Image'">
                        </div>
                        <div class="product-card-content">
                            <h3 class="product-card-title">{{ $relatedProduct->name }}</h3>
                            <div class="product-card-price">${{ number_format($relatedProduct->final_price, 2) }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Change main image
    function changeImage(element) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = element.src.replace('150x150', '600x600');

        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        element.parentElement.classList.add('active');
    }

    // Quantity controls
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }

    // Add to cart functionality with quantity support
    function addToCartWithQuantity(productId, button) {
        console.log('Add to cart clicked for product:', productId);
        
        const quantityInput = document.getElementById('quantity');
        const quantity = parseInt(quantityInput.value) || 1;
        const originalText = button.innerHTML;

        console.log('Quantity:', quantity);

        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('Security token not found. Please refresh the page.');
            return;
        }

        console.log('Sending request to:', `/cart/add/${productId}`);

        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Show success feedback
                button.innerHTML = '<i class="fas fa-check"></i> Added!';
                button.style.background = '#28a745';
                
                // Update cart count in header
                updateCartCount();

                // Reset button after 2 seconds
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    button.style.background = '';
                }, 2000);

                // Show notification
                showNotification(data.message || 'Product added to cart successfully!');
            } else {
                // Show error
                button.disabled = false;
                button.innerHTML = originalText;
                showNotification(data.message || 'Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification('An error occurred. Please try again.');
        });
    }

    // Wishlist functionality is handled by the global script in layout
</script>
@endsection
