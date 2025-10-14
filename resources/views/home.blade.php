@extends('layouts.app')

@section('title', 'Home - IT Center')

@section('content')
<!--        background: linear-gradient(to right, rgba(0, 0, 0, 0.9), transparent), url('{{ asset('images/assets/wallpaper2.png') }}');
-->
<style>
    .hero-section {
        background: linear-gradient(to right, rgba(0, 0, 0, 0.2), transparent), radial-gradient(circle, rgba(0, 0, 0, 0.5),transparent), url('{{ asset('images/assets/wallpaper2.png') }}');

        background-size: cover;
        background-repeat: no-repeat;
        padding: 12rem 2rem;
        margin-bottom: 3rem;
        position: relative;
        height: 100%;
    }

    .hero-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .hero-content {
        max-width: 600px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .hero-content h1 {
        font-size: 3.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        font-weight: 700;
    }

    .hero-content p {
        color: rgba(255,255,255,1);
        font-size: 1.2rem;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .hero-btn {
        background: #fff;
        color: #333;
        padding: 1rem 3rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        background: #f0f0f0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .section-title h2 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        font-size: 1.8rem;
        color: #333;
    }

    .view-more {
        color: #333;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .view-more:hover {
        color: #667eea;
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
        color: #333;
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

    .product-dots {
        display: flex;
        gap: 0.3rem;
        justify-content: center;
        margin-top: 0.5rem;
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ddd;
    }

    .dot.active {
        background: #fcfcfcff;
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

<div class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1>Welcome to IT Center</h1>
            <p>Discover our latest collection of premium technology products. From laptops to accessories, we have everything you need to stay connected and productive.</p>
            <a href="{{ route('products') }}" class="hero-btn">Shop Now</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="section-header">
        <h2>Most popular sales</h2>
        <a href="{{ route('products') }}" class="view-more">
            View More <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="product-grid">
        <div class="product-card">
            <div class="product-image">
                <div class="wishlist-btn">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/ssd.png') }}" alt="SSD Storage">
                <div class="product-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="product-info">
                <div class="product-title">Transcend 1TB ESD270C</div>
                <div class="product-description">The Transcend ESD270C 1TB Portable External SSD</div>
                <div class="product-footer">
                    <div class="product-price">₪ 390</div>
                    <button class="add-to-cart">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card">
            <div class="product-image">
                <div class="wishlist-btn">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/1024.png') }}" alt="Network Adapter">
                <div class="product-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="product-info">
                <div class="product-title">EMK Optical Splitter</div>
                <div class="product-description">The EMK Optical Splitter 1in to 2out</div>
                <div class="product-footer">
                    <div class="product-price">₪ 25</div>
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
                <img src="{{ asset('images/products/usb.png') }}" alt="USB-C to Lightning">
                <div class="product-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="product-info">
                <div class="product-title">USB-C to Lightning</div>
                <div class="product-description">The Cycle Premium USB-C to Lightning Cable</div>
                <div class="product-footer">
                    <div class="product-price">₪ 19</div>
                    <button class="add-to-cart">Add to cart</button>
                </div>
            </div>
        </div>
    </div>

    <div class="section-header">
        <h2>New Arrivals</h2>
        <a href="{{ route('products') }}" class="view-more">
            View More <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="product-grid">
        <div class="product-card">
            <div class="product-image">
                <div class="wishlist-btn">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/screen.png') }}" alt="Type C to HDMI">
                <div class="product-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="product-info">
                <div class="product-title">Type C to HDMI Adapter</div>
                <div class="product-description">Effortlessly connect your USB-C device to HDMI</div>
                <div class="product-footer">
                    <div class="product-price">₪ 35</div>
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
                <img src="{{ asset('images/products/usb.png') }}" alt="USB SanDisk">
                <div class="product-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="product-info">
                <div class="product-title">USB 2.0 SanDisk 64GB</div>
                <div class="product-description">Store and transfer your files with ease</div>
                <div class="product-footer">
                    <div class="product-price">₪ 30</div>
                    <button class="add-to-cart">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card">
            <div class="product-image">
                <div class="wishlist-btn">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/1024.png') }}" alt="Network Connector">
                <div class="product-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="product-info">
                <div class="product-title">UGREEN Network Connector</div>
                <div class="product-description">Enjoy ultra-fast and reliable networking</div>
                <div class="product-footer">
                    <div class="product-price">₪ 25</div>
                    <button class="add-to-cart">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card">
            <div class="product-image">
                <div class="wishlist-btn">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/controllerxbox.png') }}" alt="HDMI H-Speed Black 1.5M">
                <div class="product-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
            <div class="product-info">
                <div class="product-title">HDMI H-Speed Black 1.5M</div>
                <div class="product-description">Experience flawless audio-visual quality</div>
                <div class="product-footer">
                    <div class="product-price">₪ 20</div>
                    <button class="add-to-cart">Add to cart</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
