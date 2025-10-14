@extends('layouts.app')

@section('title', 'Our Products - IT Center')

@section('content')
<style>
    .products-section {
        padding: 3rem 2rem;
        background: #f5f5f5;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #333;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .product-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 250px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .product-image img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .product-image .icon-placeholder {
        font-size: 4rem;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ff4757;
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .wishlist-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #fff;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .wishlist-btn:hover {
        background: #ff4757;
        color: #fff;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .product-description {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
    }

    .add-to-cart {
        background: #000;
        color: #fff;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.3s;
    }

    .add-to-cart:hover {
        background: #333;
    }
</style>

<div class="products-section">
    <div class="container">
        <div class="section-header">
            <h2>All Products</h2>
        </div>

        <div class="product-grid">
            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <img src="{{ asset('images/products/screen.png') }}" alt="Dell XPS 15">
                </div>
                <div class="product-info">
                    <div class="product-title">Dell XPS 15</div>
                    <div class="product-description">High-performance laptop with stunning 4K display and Intel Core i7</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 1,299</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <img src="{{ asset('images/products/usb.png') }}" alt="HP Pavilion Desktop">
                </div>
                <div class="product-info">
                    <div class="product-title">HP Pavilion Desktop</div>
                    <div class="product-description">Powerful desktop for everyday computing with AMD Ryzen 7</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 899</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <div class="product-badge">HOT</div>
                    <img src="{{ asset('images/products/mouse.png') }}" alt="Logitech MX Master 3">
                </div>
                <div class="product-info">
                    <div class="product-title">Logitech MX Master 3</div>
                    <div class="product-description">Premium wireless mouse for productivity and precision</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 99</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <div class="product-badge">SALE</div>
                    <img src="{{ asset('images/products/controllerxbox.png') }}" alt="ASUS ROG Gaming PC">
                </div>
                <div class="product-info">
                    <div class="product-title">ASUS ROG Gaming PC</div>
                    <div class="product-description">High-end gaming desktop with RTX 4080 graphics card</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 1,899</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <img src="{{ asset('images/products/ssd.png') }}" alt="Apple MacBook Pro">
                </div>
                <div class="product-info">
                    <div class="product-title">Apple MacBook Pro</div>
                    <div class="product-description">Professional laptop with M3 chip and Retina display</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 2,499</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <img src="{{ asset('images/products/screen.png') }}" alt="Samsung Monitor">
                </div>
                <div class="product-info">
                    <div class="product-title">Samsung 27" Monitor</div>
                    <div class="product-description">4K UHD monitor with HDR support and 144Hz refresh rate</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 349</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <img src="{{ asset('images/products/keyboardrazer.png') }}" alt="Mechanical Keyboard RGB">
                </div>
                <div class="product-info">
                    <div class="product-title">Mechanical Keyboard RGB</div>
                    <div class="product-description">Gaming mechanical keyboard with customizable RGB lighting</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 129</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">
                    <div class="wishlist-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <div class="product-badge">NEW</div>
                    <img src="{{ asset('images/products/1024.png') }}" alt="Sony Headphones">
                </div>
                <div class="product-info">
                    <div class="product-title">Sony WH-1000XM5</div>
                    <div class="product-description">Premium noise-cancelling wireless headphones</div>
                    <div class="product-footer">
                        <div class="product-price">₪ 399</div>
                        <button class="add-to-cart">Add to cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
