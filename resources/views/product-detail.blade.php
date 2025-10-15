@extends('layouts.app')

@section('title', 'Product Details - IT Center')

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
        background: #f5f5f5;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
        position: relative;
        border: 1px solid #e0e0e0;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s;
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
    }

    .thumbnail:hover,
    .thumbnail.active {
        border-color: #e69270ff;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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
        color: #e69270ff;
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
        background: #e69270ff;
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
        background: #d4af37;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(230, 146, 112, 0.3);
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
        border: 2px solid #e69270ff;
        color: #e69270ff;
        padding: 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1.3rem;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        background: #e69270ff;
        color: #fff;
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
        color: #e69270ff;
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
        border-bottom: 3px solid #e69270ff;
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
    }

    .product-card-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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
        color: #e69270ff;
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
                    <img src="{{ asset('images/products/1024.png') }}" alt="Product" id="mainImage">
                </div>
                <div class="thumbnail-images">
                    <div class="thumbnail active">
                        <img src="{{ asset('images/products/1024.png') }}" alt="Thumbnail 1" onclick="changeImage(this)">
                    </div>
                    <div class="thumbnail">
                        <img src="{{ asset('images/products/rtx2.png') }}" alt="Thumbnail 2" onclick="changeImage(this)">
                    </div>
                    <div class="thumbnail">
                        <img src="{{ asset('images/products/rtx3.png') }}" alt="Thumbnail 3" onclick="changeImage(this)">
                    </div>
                    <div class="thumbnail">
                        <img src="{{ asset('images/products/rtx4.png') }}" alt="Thumbnail 4" onclick="changeImage(this)">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-category">Laptops / Gaming</div>
                <h1 class="product-title">HP Pavilion Gaming Laptop 15.6" FHD 144Hz</h1>

                <div class="product-rating">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="rating-text">4.5 (128 reviews)</span>
                </div>

                <div class="product-price">
                    <span class="current-price">$899.99</span>
                    <span class="original-price">$1,199.99</span>
                    <span class="discount-badge">-25%</span>
                </div>

                <div class="stock-status">
                    <i class="fas fa-check-circle"></i>
                    <span>In Stock - Ready to Ship</span>
                </div>

                <p class="product-description">
                    Experience ultimate gaming performance with the HP Pavilion Gaming Laptop. Featuring a powerful Intel Core i7 processor,
                    NVIDIA GeForce RTX 3060 graphics, and a stunning 144Hz display. Perfect for gaming, content creation, and demanding tasks.
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
                            <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" id="quantity">
                            <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn-add-cart">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                    <button class="btn-buy-now">Buy Now</button>
                    <button class="btn-wishlist">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="specifications-section">
            <h2 class="section-title">Technical Specifications</h2>
            <div class="specs-grid">
                <div class="spec-item">
                    <span class="spec-label">Processor:</span>
                    <span class="spec-value">Intel Core i7-11800H (8 cores, 16 threads)</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Graphics:</span>
                    <span class="spec-value">NVIDIA GeForce RTX 3060 6GB</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">RAM:</span>
                    <span class="spec-value">16GB DDR4 3200MHz</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Storage:</span>
                    <span class="spec-value">512GB NVMe SSD</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Display:</span>
                    <span class="spec-value">15.6" FHD (1920x1080) 144Hz IPS</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Battery:</span>
                    <span class="spec-value">4-cell 70Wh Li-ion</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Weight:</span>
                    <span class="spec-value">2.3 kg (5.07 lbs)</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Operating System:</span>
                    <span class="spec-value">Windows 11 Home</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Connectivity:</span>
                    <span class="spec-value">Wi-Fi 6, Bluetooth 5.2</span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Ports:</span>
                    <span class="spec-value">3x USB-A, 1x USB-C, HDMI, Audio Jack</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
<div class="related-products">
    <div class="container">
        <h2 class="related-title">Related Products</h2>
        <div class="products-grid">
            <a href="{{ route('product.detail', 2) }}" style="text-decoration: none; color: inherit;">
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="https://via.placeholder.com/300x200/f5f5f5/666666?text=Product+1" alt="Related Product 1">
                    </div>
                    <div class="product-card-content">
                        <h3 class="product-card-title">ASUS ROG Gaming Laptop</h3>
                        <div class="product-card-price">$1,099.99</div>
                    </div>
                </div>
            </a>
            <a href="{{ route('product.detail', 3) }}" style="text-decoration: none; color: inherit;">
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="https://via.placeholder.com/300x200/f5f5f5/666666?text=Product+2" alt="Related Product 2">
                    </div>
                    <div class="product-card-content">
                        <h3 class="product-card-title">Dell Gaming G15</h3>
                        <div class="product-card-price">$849.99</div>
                    </div>
                </div>
            </a>
            <a href="{{ route('product.detail', 4) }}" style="text-decoration: none; color: inherit;">
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="https://via.placeholder.com/300x200/f5f5f5/666666?text=Product+3" alt="Related Product 3">
                    </div>
                    <div class="product-card-content">
                        <h3 class="product-card-title">Lenovo Legion 5</h3>
                        <div class="product-card-price">$949.99</div>
                    </div>
                </div>
            </a>
            <a href="{{ route('product.detail', 5) }}" style="text-decoration: none; color: inherit;">
                <div class="product-card">
                    <div class="product-card-image">
                        <img src="https://via.placeholder.com/300x200/f5f5f5/666666?text=Product+4" alt="Related Product 4">
                    </div>
                    <div class="product-card-content">
                        <h3 class="product-card-title">MSI Katana Gaming</h3>
                        <div class="product-card-price">$799.99</div>
                    </div>
                </div>
            </a>
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
        input.value = parseInt(input.value) + 1;
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    // Wishlist toggle
    document.querySelector('.btn-wishlist').addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
        }
    });
</script>
@endsection
