@extends('layouts.app')

@section('title', 'Home - IT Center')

@section('content')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(to right, rgba(0, 0, 0, 0.2), transparent), radial-gradient(circle, rgba(0, 0, 0, 0.5),transparent), url('{{ asset('images/assets/wallpaper2.png') }}');
        background-size: cover;
        background-repeat: no-repeat;
        padding: 8rem 2rem;
        margin-bottom: 3rem;
        position: relative;
        height: 700px;
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

    /* Categories Section */
    .categories-section {
        background: #fff;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        font-size: 2rem;
        color: #333;
        font-weight: 700;
    }

    .view-more {
        color: #666;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .view-more:hover {
        color: #e69270ff;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .category-item:hover {
        transform: translateY(-5px);
    }

    .category-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s;
    }

    .category-item:hover .category-icon {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .category-icon img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }

    .category-name {
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
    }

    /* Featured Brands Slider */
    .brands-section {
        background: #1a1a1a;
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .brands-section .section-header h2 {
        color: #fff;
    }

    .brands-section .view-more {
        color: #aaa;
    }

    .brands-section .view-more:hover {
        color: #fff;
    }

    .brands-slider {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 1rem 0;
        scrollbar-width: none;
    }

    .brands-slider::-webkit-scrollbar {
        display: none;
    }

    .brand-card {
        min-width: 200px;
        height: 120px;
        background: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .brand-card:hover {
        transform: scale(1.05);
    }

    .brand-card img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Banner Sliders */
    .banners-section {
        padding: 3rem 0;
        margin-bottom: 3rem;
    }

    .banners-slider {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .banner-large, .banner-small {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .banner-large {
        height: 350px;
    }

    .banner-small {
        height: 350px;
    }

    .banner-large:hover, .banner-small:hover {
        transform: scale(1.02);
    }

    .banner-large img, .banner-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Special Cards Grid */
    .special-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .special-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s;
    }

    .special-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .special-card-image {
        height: 250px;
        position: relative;
    }

    .special-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .special-card-content {
        padding: 1.5rem;
    }

    .special-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .special-card-subtitle {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .special-card-btn {
        color: #e69270ff;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Builder Cards */
    .builder-cards {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .builder-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem 1.5rem;
        color: #fff;
        cursor: pointer;
        transition: transform 0.3s;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .builder-card:nth-child(1) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .builder-card:nth-child(2) { background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%); }
    .builder-card:nth-child(3) { background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%); }
    .builder-card:nth-child(4) { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .builder-card:nth-child(5) { background: linear-gradient(135deg, #06beb6 0%, #48b1bf 100%); }

    .builder-card:hover {
        transform: translateY(-5px);
    }

    .builder-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .builder-card-link {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.9);
    }

    .builder-card-image {
        margin-top: 1rem;
        height: 100px;
    }

    .builder-card-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Popular Tags */
    .popular-tags {
        background: #1a1a1a;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 3rem;
    }

    .popular-tags h3 {
        color: #fff;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        font-style: italic;
        font-weight: 700;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .tag {
        background: rgba(255,255,255,0.1);
        color: #fff;
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .tag:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }

    /* Product Grid */
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
        z-index: 10;
    }

    .wishlist-btn:hover {
        background: #ff4757;
        color: #fff;
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

    @media (max-width: 1200px) {
        .special-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        .builder-cards {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .banners-slider {
            grid-template-columns: 1fr;
        }
        .special-cards {
            grid-template-columns: 1fr;
        }
        .builder-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        .categories-grid {
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1>Welcome to IT Center</h1>
            <p>Discover our latest collection of premium technology products. From laptops to accessories, we have everything you need to stay connected and productive.</p>
            <a href="{{ route('products') }}" class="hero-btn">Shop Now</a>
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Categories</h2>
            <a href="{{ route('categories') }}" class="view-more">
                View More <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="categories-grid">
            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/screen.png') }}" alt="Computers">
                </div>
                <div class="category-name">Computers</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/1024.png') }}" alt="Printer">
                </div>
                <div class="category-name">Printer</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/mouse.png') }}" alt="Mobile Accessories">
                </div>
                <div class="category-name">Mobile Accessories</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/ssd.png') }}" alt="Laptop Bag">
                </div>
                <div class="category-name">LAPTOP BAG</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/screen.png') }}" alt="Laptop">
                </div>
                <div class="category-name">Laptop</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/keyboardrazer.png') }}" alt="Laptop Accessories">
                </div>
                <div class="category-name">Laptop Accessories</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/screen.png') }}" alt="Monitors">
                </div>
                <div class="category-name">MONITORS</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/controllerxbox.png') }}" alt="Computer Case">
                </div>
                <div class="category-name">Computer Case</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/usb.png') }}" alt="Motherboard">
                </div>
                <div class="category-name">Motherboard</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/1024.png') }}" alt="CPU">
                </div>
                <div class="category-name">CPU</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/mouse.png') }}" alt="COOLER">
                </div>
                <div class="category-name">COOLER</div>
            </div>

            <div class="category-item" onclick="window.location.href='{{ route('categories') }}'">
                <div class="category-icon">
                    <img src="{{ asset('images/products/ssd.png') }}" alt="GRAPHIC CARD">
                </div>
                <div class="category-name">GRAPHIC CARD</div>
            </div>
        </div>
    </div>
</div>

<!-- Special Offer Cards -->
<div class="container">
    <div class="special-cards">
        <div class="special-card" onclick="window.location.href='{{ route('products') }}'">
            <div class="special-card-image">
                <img src="https://via.placeholder.com/400x250/667eea/fff?text=Gaming+Setup" alt="Dominate Every Match">
            </div>
            <div class="special-card-content">
                <div class="special-card-title">Dominate Every Match</div>
                <div class="special-card-subtitle">Power. Precision. Performance.</div>
                <a href="{{ route('products') }}" class="special-card-btn">
                    Shop Top Gear <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="special-card" onclick="window.location.href='{{ route('offers') }}'">
            <div class="special-card-image">
                <img src="https://via.placeholder.com/400x250/ff6b6b/fff?text=Hot+Deals" alt="Fantastic Deals">
            </div>
            <div class="special-card-content">
                <div class="special-card-title">Fantastic Deals</div>
                <div class="special-card-subtitle">Under $30, Under $75 & More!</div>
                <a href="{{ route('offers') }}" class="special-card-btn">
                    Shop All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="special-card" onclick="window.location.href='{{ route('offers') }}'">
            <div class="special-card-image">
                <img src="https://via.placeholder.com/400x250/48b1bf/fff?text=Smart+Picks" alt="Season's Best">
            </div>
            <div class="special-card-content">
                <div class="special-card-title">Season's Best</div>
                <div class="special-card-subtitle">Holiday deals across every category</div>
                <a href="{{ route('offers') }}" class="special-card-btn">
                    See Deals <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="special-card" onclick="window.location.href='{{ route('products') }}'">
            <div class="special-card-image">
                <img src="https://via.placeholder.com/400x250/4ecdc4/fff?text=New+Arrivals" alt="Feature Event">
            </div>
            <div class="special-card-content">
                <div class="special-card-title">Feature Event</div>
                <div class="special-card-subtitle">Be the first to explore today's newest finds.</div>
                <a href="{{ route('products') }}" class="special-card-btn">
                    See What's New <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Most Popular Sales -->
    <div class="section-header">
        <h2>Most Popular Sales</h2>
        <a href="{{ route('products') }}" class="view-more">
            View More <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="product-grid">
        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 1) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/ssd.png') }}" alt="SSD Storage">
            </div>
            <div class="product-info">
                <div class="product-title">Transcend 1TB ESD270C</div>
                <div class="product-description">The Transcend ESD270C 1TB Portable External SSD</div>
                <div class="product-footer">
                    <div class="product-price">₪ 390</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 2) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/1024.png') }}" alt="Network Adapter">
            </div>
            <div class="product-info">
                <div class="product-title">EMK Optical Splitter</div>
                <div class="product-description">The EMK Optical Splitter 1in to 2out</div>
                <div class="product-footer">
                    <div class="product-price">₪ 25</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 3) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <div class="product-badge">HOT</div>
                <img src="{{ asset('images/products/keyboardrazer.png') }}" alt="Mechanical Keyboard RGB">
            </div>
            <div class="product-info">
                <div class="product-title">Mechanical Keyboard RGB</div>
                <div class="product-description">Gaming mechanical keyboard with customizable RGB lighting</div>
                <div class="product-footer">
                    <div class="product-price">₪ 129</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 4) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/usb.png') }}" alt="USB-C to Lightning">
            </div>
            <div class="product-info">
                <div class="product-title">USB-C to Lightning</div>
                <div class="product-description">The Cycle Premium USB-C to Lightning Cable</div>
                <div class="product-footer">
                    <div class="product-price">₪ 19</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 5) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <div class="product-badge">NEW</div>
                <img src="{{ asset('images/products/screen.png') }}" alt="Monitor">
            </div>
            <div class="product-info">
                <div class="product-title">Samsung 27" 4K Monitor</div>
                <div class="product-description">Ultra HD display with HDR support</div>
                <div class="product-footer">
                    <div class="product-price">₪ 499</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 6) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/mouse.png') }}" alt="Gaming Mouse">
            </div>
            <div class="product-info">
                <div class="product-title">Logitech G Pro Wireless</div>
                <div class="product-description">Professional gaming mouse with HERO sensor</div>
                <div class="product-footer">
                    <div class="product-price">₪ 149</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 7) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <div class="product-badge">SALE</div>
                <img src="{{ asset('images/products/controllerxbox.png') }}" alt="Controller">
            </div>
            <div class="product-info">
                <div class="product-title">Xbox Wireless Controller</div>
                <div class="product-description">Compatible with Xbox and PC</div>
                <div class="product-footer">
                    <div class="product-price">₪ 59</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 8) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/1024.png') }}" alt="Webcam">
            </div>
            <div class="product-info">
                <div class="product-title">Logitech C920 HD Pro</div>
                <div class="product-description">Full HD 1080p webcam for streaming</div>
                <div class="product-footer">
                    <div class="product-price">₪ 89</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Builder Cards -->
    <div class="section-header">
        <h2>Build Your Dream Setup</h2>
    </div>

    <div class="builder-cards">
        <div class="builder-card" onclick="window.location.href='{{ route('products') }}'">
            <div class="builder-card-title">PC Builder</div>
            <div class="builder-card-link">Check it out ›</div>
            <div class="builder-card-image">
                <img src="{{ asset('images/products/screen.png') }}" alt="PC Builder">
            </div>
        </div>

        <div class="builder-card" onclick="window.location.href='{{ route('products') }}'">
            <div class="builder-card-title">NAS Builder</div>
            <div class="builder-card-link">Check it out ›</div>
            <div class="builder-card-image">
                <img src="{{ asset('images/products/controllerxbox.png') }}" alt="NAS Builder">
            </div>
        </div>

        <div class="builder-card" onclick="window.location.href='{{ route('products') }}'">
            <div class="builder-card-title">PC Upgrader</div>
            <div class="builder-card-link">Check it out ›</div>
            <div class="builder-card-image">
                <img src="{{ asset('images/products/ssd.png') }}" alt="PC Upgrader">
            </div>
        </div>

        <div class="builder-card" onclick="window.location.href='{{ route('products') }}'">
            <div class="builder-card-title">Gaming PC Finder</div>
            <div class="builder-card-link">Check it out ›</div>
            <div class="builder-card-image">
                <img src="{{ asset('images/products/mouse.png') }}" alt="Gaming PC">
            </div>
        </div>

        <div class="builder-card" onclick="window.location.href='{{ route('products') }}'">
            <div class="builder-card-title">Server Configurator</div>
            <div class="builder-card-link">Check it out ›</div>
            <div class="builder-card-image">
                <img src="{{ asset('images/products/1024.png') }}" alt="Server">
            </div>
        </div>
    </div>

    <!-- Most Popular Sales -->
    <div class="section-header">
        <h2>Most Popular Sales</h2>
        <a href="{{ route('products') }}" class="view-more">
            View More <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <div class="product-grid">
        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 1) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/ssd.png') }}" alt="SSD Storage">
            </div>
            <div class="product-info">
                <div class="product-title">Transcend 1TB ESD270C</div>
                <div class="product-description">The Transcend ESD270C 1TB Portable External SSD</div>
                <div class="product-footer">
                    <div class="product-price">₪ 390</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 2) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/1024.png') }}" alt="Network Adapter">
            </div>
            <div class="product-info">
                <div class="product-title">EMK Optical Splitter</div>
                <div class="product-description">The EMK Optical Splitter 1in to 2out</div>
                <div class="product-footer">
                    <div class="product-price">₪ 25</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 3) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <div class="product-badge">HOT</div>
                <img src="{{ asset('images/products/keyboardrazer.png') }}" alt="Mechanical Keyboard RGB">
            </div>
            <div class="product-info">
                <div class="product-title">Mechanical Keyboard RGB</div>
                <div class="product-description">Gaming mechanical keyboard with customizable RGB lighting</div>
                <div class="product-footer">
                    <div class="product-price">₪ 129</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 4) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/usb.png') }}" alt="USB-C to Lightning">
            </div>
            <div class="product-info">
                <div class="product-title">USB-C to Lightning</div>
                <div class="product-description">The Cycle Premium USB-C to Lightning Cable</div>
                <div class="product-footer">
                    <div class="product-price">₪ 19</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 5) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <div class="product-badge">NEW</div>
                <img src="{{ asset('images/products/screen.png') }}" alt="Monitor">
            </div>
            <div class="product-info">
                <div class="product-title">Samsung 27" 4K Monitor</div>
                <div class="product-description">Ultra HD display with HDR support</div>
                <div class="product-footer">
                    <div class="product-price">₪ 499</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 6) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/mouse.png') }}" alt="Gaming Mouse">
            </div>
            <div class="product-info">
                <div class="product-title">Logitech G Pro Wireless</div>
                <div class="product-description">Professional gaming mouse with HERO sensor</div>
                <div class="product-footer">
                    <div class="product-price">₪ 149</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 7) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <div class="product-badge">SALE</div>
                <img src="{{ asset('images/products/controllerxbox.png') }}" alt="Controller">
            </div>
            <div class="product-info">
                <div class="product-title">Xbox Wireless Controller</div>
                <div class="product-description">Compatible with Xbox and PC</div>
                <div class="product-footer">
                    <div class="product-price">₪ 59</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>

        <div class="product-card" onclick="window.location.href='{{ route('product.detail', 8) }}'">
            <div class="product-image">
                <div class="wishlist-btn" onclick="event.stopPropagation();">
                    <i class="far fa-heart"></i>
                </div>
                <img src="{{ asset('images/products/1024.png') }}" alt="Webcam">
            </div>
            <div class="product-info">
                <div class="product-title">Logitech C920 HD Pro</div>
                <div class="product-description">Full HD 1080p webcam for streaming</div>
                <div class="product-footer">
                    <div class="product-price">₪ 89</div>
                    <button class="add-to-cart" onclick="event.stopPropagation();">Add to cart</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
