<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IT Center')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            color: #333;
        }

        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo img {
            height: 35px;
            width: auto;
            object-fit: contain;
            margin-top:3px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: bold;
            color: #d4af37;
            font-style: italic;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            color: #333;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
            font-weight: 500;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: #e69270ff;
        }

        .search-bar {
            display: flex;
            flex: 1;
            max-width: 500px;
            background: #f5f5f5;
            border-radius: 5px;
            overflow: hidden;
        }

        .search-bar input {
            flex: 1;
            border: none;
            padding: 0.7rem 1rem;
            background: transparent;
            outline: none;
        }

        .search-bar button {
            background: #3e8cebff;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .search-bar button:hover {
            background: #333;
        }

        .header-icons {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .header-icon {
            position: relative;
            cursor: pointer;
            color: #333;
            font-size: 1.3rem;
        }

        .header-icon .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #000;
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        main {
            min-height: calc(100vh - 200px);
            background-color: #f5f5f5;
        }

        footer {
            background: #1a1a1a;
            color: #fff;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #d4af37;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #d4af37;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #333;
            color: #888;
        }

        .social-icons {
            position: fixed;
            left: 20px;
            top: 80%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            z-index: 999;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            background: #000000ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
            color: #ffffffff;
            font-size: 1.2rem;
        }

        .social-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo">
                </a>
            </div>

            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home Page</a></li>
                <li><a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}">Categories</a></li>
                <li><a href="{{ route('brands') }}" class="{{ request()->routeIs('brands') ? 'active' : '' }}">brands</a></li>
                <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}">Our Products</a></li>
                <li><a href="{{ route('offers') }}" class="{{ request()->routeIs('offers') ? 'active' : '' }}">Offers</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a></li>
            </ul>

            <div class="search-bar">
                <input type="text" placeholder="Search Product...">
                <button>Search</button>
            </div>

            <div class="header-icons">
                <div class="header-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="header-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="header-icon">
                    <i class="fas fa-heart"></i>
                    <span class="badge">0</span>
                </div>
                <div class="header-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge">0</span>
                </div>
            </div>
        </div>
    </header>

    <div class="social-icons">
        <div class="social-icon">
            <i class="fab fa-facebook-f"></i>
        </div>
        <div class="social-icon">
            <i class="fab fa-instagram"></i>
        </div>
        <div class="social-icon">
            <i class="fab fa-whatsapp"></i>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>IT Center</h3>
                <p>Your trusted partner for all technology needs. Quality products at competitive prices.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('products') }}">Products</a></li>
                    <li><a href="{{ route('offers') }}">Special Offers</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Categories</h3>
                <ul>
                    <li><a href="#">Laptops</a></li>
                    <li><a href="#">Desktops</a></li>
                    <li><a href="#">Accessories</a></li>
                    <li><a href="#">Components</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li><i class="fas fa-phone"></i>&nbsp;&nbsp;0595910045</li>
                    <li><i class="fas fa-envelope"></i>&nbsp;&nbsp;support@itcenter.vip</li>
                    <li><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;Palestine, Hebrew</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} IT Center. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
