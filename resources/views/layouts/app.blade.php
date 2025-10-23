<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ locale_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Center')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(is_rtl())
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: {{ is_rtl() ? "'Cairo', sans-serif" : "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif" }};
            line-height: 1.6;
            background-color: #f5f5f5;
            color: #333;
            direction: {{ locale_direction() }};
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            max-width: 100vw;
        }
        
        *:not(html):not(body) {
            max-width: 100%;
        }
        
        img {
            max-width: 100%;
            height: auto;
        }
        
        .container,
        .header-container,
        section {
            max-width: 100%;
            box-sizing: border-box;
        }

        header {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background 0.3s ease, backdrop-filter 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
        }

        header.scrolled {
            background: rgba(242, 239, 237, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
            width: 100%;
            box-sizing: border-box;
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #333333;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 1001;
        }
        
        .mobile-menu-toggle:hover {
            color: #e69270ff;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo img {
            height: 40px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
            margin-top: 3px;
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
            margin: 0;
            padding: 0;
        }

        .nav-menu a {
            color: #333333;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #2762f3, #5b8def);
            transition: width 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: #2762f3;
        }

        .nav-menu a:hover::after,
        .nav-menu a.active::after {
            width: 100%;
        }
        
        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mobile-menu-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Search Bar Styles - Modern & Wider Design */
        .search-bar {
            display: flex;
            flex-direction: {{ is_rtl() ? 'row' : 'row' }};
            flex: 1;
            max-width: 650px;
            min-width: 400px;
            gap: 0;
            align-items: center;
            position: relative;
            margin: 0 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .search-bar:focus-within {
            box-shadow: 0 4px 16px rgba(39, 98, 243, 0.15);
            transform: translateY(-1px);
        }

        .search-bar input {
            flex: 1;
            height: 50px;
            padding: 0 24px;
            border: 2px solid #e8eef7;
            background: #ffffff;
            color: #1a1a2e;
            font-size: 0.98rem;
            outline: none;
            transition: all 0.3s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: {{ is_rtl() ? '0 12px 12px 0' : '12px 0 0 12px' }};
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            text-align: {{ is_rtl() ? 'right' : 'left' }};
            font-weight: 400;
            letter-spacing: 0.01em;
        }
        
        .search-bar input:focus {
            background: #ffffff;
            border-color: #5b8def;
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.1);
        }
        
        .search-bar input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .search-btn {
            height: 50px;
            padding: 0 28px;
            background: linear-gradient(135deg, #2762f3 0%, #1a4dbf 100%);
            color: #ffffff;
            border: none;
            border-radius: {{ is_rtl() ? '12px 0 0 12px' : '0 12px 12px 0' }};
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            unicode-bidi: embed;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 90px;
            position: relative;
            overflow: hidden;
        }

        .search-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .search-btn:hover::before {
            opacity: 1;
        }

        .search-btn:hover {
            background: linear-gradient(135deg, #1a4dbf 0%, #0d3a9f 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 98, 243, 0.35);
        }

        .search-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(39, 98, 243, 0.3);
        }

        .search-btn span {
            display: inline-block;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            unicode-bidi: embed;
            position: relative;
            z-index: 1;
        }

        .search-btn i {
            position: relative;
            z-index: 1;
        }

        .header-icons {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .header-icon {
            position: relative;
            cursor: pointer;
            color: #333333;
            font-size: 1.3rem;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .header-icon:hover {
            color: #2762f3;
            transform: translateY(-2px);
        }

        .header-icon .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #2762f3;
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            transition: opacity 0.2s ease;
        }
        
        /* Prevent flash on page load */
        .header-icon .badge.badge-loading {
            /* Badge shows initial server-side count, no need to hide */
            opacity: 1;
        }

        /* Ensure page loading indicator stops */
        .page-loaded {
            /* This class is added when page is fully loaded */
        }

        .page-interactive {
            /* This class is added when page becomes interactive */
        }

        /* Hide any loading indicators after page load */
        body.loaded .loading,
        body.loaded .spinner,
        body.loaded [class*="loading"] {
            display: none !important;
        }

        /* Force stop browser loading indicator */
        html.page-loaded,
        html.page-interactive {
            /* These classes help ensure the browser stops showing loading indicator */
        }

        /* Additional CSS to prevent loading indicators */
        body.loaded::before,
        body.loaded::after {
            display: none !important;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            width: 100%;
            box-sizing: border-box;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 0 0.8rem;
            }
        }

        main {
            min-height: calc(100vh - 200px);
            background-color: #fafafaff;
        }

        footer {
            background: #F2EFED;
            color: #333333;
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
            color: #2762f3;
            font-weight: 600;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #2762f3;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(39, 98, 243, 0.2);
            color: #64748b;
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

        /* Language Dropdown Styles */
        .language-dropdown {
            position: relative;
        }
        .fas:hover{
            color: #2762f3;
        }
        .language-toggle {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .language-toggle:hover {
            color: #2762f3;
            background: rgba(39, 98, 243, 0.05);
        }

        .language-dropdown.active .language-toggle {
            background: rgba(39, 98, 243, 0.08);
            color: #2762f3;
        }

        .language-dropdown.active .language-toggle .fa-chevron-down {
            transform: rotate(180deg);
        }

        .current-lang {
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .language-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            {{ is_rtl() ? 'left: 0;' : 'right: 0;' }}
            background: #ffffff;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 2px solid #e8eef7;
            border-radius: 12px;
            min-width: 200px;
            box-shadow: 0 8px 24px rgba(39, 98, 243, 0.12);
            overflow: hidden;
            z-index: 1001;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .language-dropdown.active .language-dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .language-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -7px;
            {{ is_rtl() ? 'left: 20px;' : 'right: 20px;' }}
            width: 12px;
            height: 12px;
            background: #ffffff;
            border-top: 2px solid #e8eef7;
            border-left: 2px solid #e8eef7;
            transform: rotate(45deg);
        }

        .language-option {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.9rem 1.2rem;
            color: #1a1a2e;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border-bottom: 1px solid rgba(39, 98, 243, 0.06);
        }

        .language-option:last-child {
            border-bottom: none;
        }

        .language-option:hover {
            background: rgba(39, 98, 243, 0.06);
            color: #2762f3;
            padding-{{ is_rtl() ? 'right' : 'left' }}: 1.4rem;
        }

        .language-option.active {
            background: rgba(39, 98, 243, 0.1);
            color: #2762f3;
            font-weight: 600;
        }

        .language-option.active::before {
            content: '';
            position: absolute;
            {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #2762f3, #5b8def);
        }

        .lang-icon {
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(39, 98, 243, 0.08);
        }

        .lang-name {
            flex: 1;
            font-size: 0.95rem;
        }

        .lang-check {
            font-size: 0.85rem;
            color: #2762f3;
            animation: checkIn 0.3s ease;
        }

        /* RTL: Move checkmark to the left (after text) */
        @if(is_rtl())
        .language-option {
            flex-direction: row-reverse;
        }

        .lang-check {
            margin-right: auto;
            margin-left: 0;
        }
        @endif

        @keyframes checkIn {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive breakpoints for search bar */
        @media (min-width: 1400px) {
            .search-bar {
                max-width: 700px;
            }
        }

        @media (min-width: 1600px) {
            .search-bar {
                max-width: 750px;
            }
        }

        /* User Dropdown Menu Styles */
        .user-dropdown.active .user-dropdown-menu {
            display: block !important;
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .user-dropdown-menu form {
            margin: 0 !important;
        }

        .user-dropdown .user-toggle:hover {
            color: #2762f3;
        }

        .user-dropdown.active .user-toggle {
            color: #2762f3;
        }

        /* Default state for all menu items - Force color */
        .user-dropdown-menu .user-menu-item {
            color: #1a1a2e !important;
            transition: background 0.3s ease, padding 0.3s ease, color 0.3s ease !important;
        }

        .user-dropdown-menu .user-menu-item i {
            color: #1a1a2e !important;
            transition: color 0.3s ease !important;
        }

        .user-dropdown-menu .user-menu-item span {
            color: #1a1a2e !important;
            transition: color 0.3s ease !important;
        }

        /* Hover state for all menu items */
        .user-dropdown-menu .user-menu-item:hover {
            background: rgba(39, 98, 243, 0.06) !important;
            color: #2762f3 !important;
            padding-{{ is_rtl() ? 'right' : 'left' }}: 1.4rem !important;
        }

        .user-dropdown-menu .user-menu-item:hover i {
            color: #2762f3 !important;
        }

        .user-dropdown-menu .user-menu-item:hover span {
            color: #2762f3 !important;
        }

        /* Responsive adjustments */
        @media (max-width: 968px) {
            header {
                padding: 0.8rem 1rem;
            }
            
            .header-container {
                gap: 1rem;
            }
            
            .logo img {
                height: 35px;
            }
            
            .nav-menu {
                gap: 1rem;
            }
            
            .nav-menu a {
                font-size: 0.9rem;
                padding: 0.6rem 1rem;
            }
            
            .search-bar {
                max-width: 450px;
                min-width: 300px;
                margin: 0 1.5rem;
            }

            .search-bar input {
                height: 48px;
                font-size: 0.95rem;
                padding: 0 20px;
            }

            .search-btn {
                height: 48px;
                padding: 0 24px;
                min-width: 85px;
            }
            
            .header-icon {
                padding: 0.6rem;
            }
            
            .cart-count {
                width: 18px;
                height: 18px;
                font-size: 0.65rem;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                padding: 0.8rem 1rem;
                flex-wrap: nowrap;
                gap: 0.8rem;
            }
            
            /* Show mobile menu toggle */
            .mobile-menu-toggle {
                display: block;
                order: 1;
            }
            
            .logo {
                order: 2;
                flex: 0 0 auto;
            }
            
            .logo img {
                height: 32px;
                max-width: 120px;
            }
            
            .header-icons {
                order: 3;
                gap: 0.5rem;
                margin-{{ is_rtl() ? 'right' : 'left' }}: auto;
                display: flex;
                flex-direction: row;
            }
            
            .search-bar {
                order: 4;
                position: fixed;
                top: 65px;
                left: 0;
                right: 0;
                width: 100%;
                max-width: 100%;
                min-width: auto;
                margin: 0;
                padding: 1rem 1rem;
                background: #ffffff;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                z-index: 998;
                display: none;
                flex-direction: {{ is_rtl() ? 'row' : 'row' }};
                border-radius: 0;
            }
            
            .search-bar.mobile-search-active {
                display: flex;
            }
            
            .search-bar input {
                font-size: 0.95rem;
                padding: 0 20px;
                height: 48px;
                border-radius: {{ is_rtl() ? '0 12px 12px 0' : '12px 0 0 12px' }};
            }

            .search-btn {
                height: 48px;
                padding: 0 24px;
                border-radius: {{ is_rtl() ? '12px 0 0 12px' : '0 12px 12px 0' }};
            }
                width: 100%;
                border-radius: {{ is_rtl() ? '0 25px 25px 0' : '25px 0 0 25px' }};
            }
            
            .search-btn {
                border-radius: {{ is_rtl() ? '25px 0 0 25px' : '0 25px 25px 0' }};
            }
            
            .search-input-icon {
                {{ is_rtl() ? 'left' : 'right' }}: 1.5rem;
            }
            
            /* Mobile Sidebar Menu */
            .nav-menu {
                position: fixed;
                top: 0;
                {{ is_rtl() ? 'right' : 'left' }}: -100%;
                width: 280px;
                height: 100vh;
                background: #1a1a1a;
                flex-direction: column;
                align-items: flex-start;
                padding: 5rem 0 2rem 0;
                gap: 0;
                overflow-y: auto;
                transition: {{ is_rtl() ? 'right' : 'left' }} 0.3s ease;
                z-index: 1000;
                box-shadow: 2px 0 10px rgba(0,0,0,0.3);
            }
            
            .nav-menu.active {
                {{ is_rtl() ? 'right' : 'left' }}: 0;
            }
            
            .nav-menu li {
                width: 100%;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .nav-menu a {
                font-size: 1rem;
                padding: 1rem 1.5rem;
                width: 100%;
                display: block;
                transition: all 0.3s;
            }
            
            .nav-menu a:hover,
            .nav-menu a.active {
                background: rgba(230, 146, 112, 0.1);
                padding-{{ is_rtl() ? 'right' : 'left' }}: 2rem;
            }
            
            .header-icon {
                padding: 0.5rem;
            }
            
            .header-icon i {
                font-size: 1.2rem;
            }
            
            .cart-count {
                width: 18px;
                height: 18px;
                font-size: 0.65rem;
                top: -5px;
                {{ is_rtl() ? 'left' : 'right' }}: -5px;
            }
            
            .language-dropdown-menu,
            .user-dropdown-menu {
                min-width: 180px;
                {{ is_rtl() ? 'left' : 'right' }}: 0;
            }
            
            .language-option,
            .user-dropdown-menu a {
                padding: 0.9rem 1.2rem;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 480px) {
            .header-container {
                padding: 0.6rem 0.8rem;
                gap: 0.5rem;
            }
            
            .logo img {
                height: 28px;
                max-width: 100px;
            }
            
            .header-icons {
                gap: 0.3rem;
            }
            
            .header-icon {
                padding: 0.4rem;
            }
            
            .header-icon i {
                font-size: 1.1rem;
            }
            
            .mobile-menu-toggle {
                font-size: 1.3rem;
                padding: 0.4rem;
            }
            
            .nav-menu {
                width: 260px;
                padding: 4.5rem 0 2rem 0;
            }
            
            .nav-menu a {
                font-size: 0.95rem;
                padding: 0.9rem 1.2rem;
            }
            
            .search-bar {
                top: 60px;
                padding: 0.9rem 0.8rem;
                flex-direction: {{ is_rtl() ? 'row' : 'row' }};
            }
            
            .search-bar input {
                font-size: 0.92rem;
                padding: 0 18px;
                height: 46px;
                border-radius: {{ is_rtl() ? '0 12px 12px 0' : '12px 0 0 12px' }};
            }
            
            .search-btn {
                height: 46px;
                padding: 0 20px;
                min-width: 80px;
                border-radius: {{ is_rtl() ? '12px 0 0 12px' : '0 12px 12px 0' }};
            }
            
            .cart-count {
                width: 16px;
                height: 16px;
                font-size: 0.6rem;
            }
        }
        
        @media (max-width: 360px) {
            .header-container {
                padding: 0.5rem 0.6rem;
                gap: 0.4rem;
            }
            
            .logo img {
                height: 26px;
                max-width: 90px;
            }
            
            .header-icons {
                gap: 0.2rem;
            }
            
            .header-icon {
                padding: 0.3rem;
            }
            
            .header-icon i {
                font-size: 1rem;
            }
            
            .mobile-menu-toggle {
                font-size: 1.2rem;
                padding: 0.3rem;
            }
            
            .nav-menu {
                width: 240px;
            }
            
            .nav-menu a {
                font-size: 0.9rem;
                padding: 0.85rem 1rem;
            }
            
            .cart-count {
                width: 14px;
                height: 14px;
                font-size: 0.55rem;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <header>
        <div class="header-container">
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo">
                </a>
            </div>

            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __t('messages.home') }}</a></li>
                <li><a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}">{{ __t('messages.categories') }}</a></li>
                <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}">{{ __t('messages.products') }}</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __t('messages.about') }}</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __t('messages.contact') }}</a></li>
            </ul>

            <form action="{{ route('products') }}" method="GET" class="search-bar" role="search">
                <input type="search" name="search" placeholder="{{ __t('messages.search') }}">
                <button class="search-btn" type="submit" aria-label="{{ __t('messages.search') }}">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div class="header-icons">
                @guest
                <div class="header-icon" style="position: relative;">
                    <a href="{{ route('login') }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
                @else
                <div class="header-icon user-dropdown" style="position: relative;">
                    <div class="user-toggle" style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user-circle"></i>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.3s;"></i>
                    </div>
                    <div class="user-dropdown-menu" style="display: none; position: absolute; top: calc(100% + 10px); {{ is_rtl() ? 'left: 0;' : 'right: 0;' }} background: #ffffff; backdrop-filter: blur(10px); border: 2px solid #e8eef7; border-radius: 12px; min-width: 200px; box-shadow: 0 8px 24px rgba(39, 98, 243, 0.12); overflow: hidden; z-index: 1001; opacity: 0; transform: translateY(-10px); transition: opacity 0.3s ease, transform 0.3s ease;">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="user-menu-item" style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: {{ is_rtl() ? 'right' : 'left' }}; font-family: inherit; font-size: inherit; text-decoration: none;">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>{{ __t('messages.logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endguest

                <div class="header-icon language-dropdown" style="position: relative;">
                    <div class="language-toggle" style="cursor: pointer; display: flex; align-items: center; gap: 0.4rem;">
                        <i class="fas fa-globe"></i>
                        <span class="current-lang" style="font-size: 0.85rem; font-weight: 600;">{{ strtoupper(current_locale()) }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.3s;"></i>
                    </div>
                    <div class="language-dropdown-menu">
                        @foreach(available_locales() as $locale)
                            <a href="{{ switch_locale_url($locale) }}" 
                               class="language-option {{ $locale === current_locale() ? 'active' : '' }}"
                               data-locale="{{ $locale }}">
                                <span class="lang-icon">
                                    @if($locale === 'en')
                                        🇬🇧
                                    @else
                                        🇵🇸
                                    @endif
                                </span>
                                <span class="lang-name">{{ locale_name($locale) }}</span>
                                @if($locale === current_locale())
                                    <i class="fas fa-check lang-check"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="header-icon">
                    <a href="{{ route('favorites') }}" style="color: inherit; text-decoration: none;">
                        <i class="fas fa-heart"></i>
                        <span class="badge badge-loading" id="favorites-count">
                            @php
                                // Get initial favorites count from server to prevent flash
                                if (Auth::check()) {
                                    $initialFavCount = Auth::user()->favoriteProducts()->count();
                                } else {
                                    $initialFavCount = count(Session::get('favorites', []));
                                }
                            @endphp
                            {{ $initialFavCount }}
                        </span>
                    </a>
                </div>
                @auth
                <div class="header-icon">
                    <a href="{{ route('orders.index') }}" style="color: inherit; text-decoration: none; position: relative;" title="{{ __t('messages.my_orders') }}">
                        <i class="fas fa-box"></i>
                    </a>
                </div>
                @endauth
                <div class="header-icon">
                    <a href="{{ route('cart.index') }}" style="color: inherit; text-decoration: none;">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge badge-loading" id="cart-count">
                            @php
                                // Get initial cart count from server to prevent flash
                                if (Auth::check()) {
                                    $initialCartCount = Auth::user()->cartItems()->sum('quantity');
                                } else {
                                    $sessionId = Session::get('cart_session_id', Session::getId());
                                    $initialCartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum('quantity');
                                }
                            @endphp
                            {{ $initialCartCount }}
                        </span>
                    </a>
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
                <p>{{ __('messages.footer_description') }}</p>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.quick_links') }}</h3>
                <ul>
                    <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                    <li><a href="{{ route('products') }}">{{ __('messages.products') }}</a></li>
                    <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.footer_categories') }}</h3>
                <ul>
                    <li><a href="#">{{ __('messages.laptops') }}</a></li>
                    <li><a href="#">{{ __('messages.desktops') }}</a></li>
                    <li><a href="#">{{ __('messages.accessories') }}</a></li>
                    <li><a href="#">{{ __('messages.components') }}</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.contact_us') }}</h3>
                <ul>
                    <li><i class="fas fa-phone"></i>&nbsp;&nbsp;0595910045</li>
                    <li><i class="fas fa-envelope"></i>&nbsp;&nbsp;support@itcenter.vip</li>
                    <li><i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;{{ __('messages.location') }}</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} IT Center. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            const scrollThreshold = window.innerHeight * 0.1; // 10% of viewport height

            if (window.scrollY > scrollThreshold) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Language and User dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Language Dropdown
            const languageDropdown = document.querySelector('.language-dropdown');
            const languageToggle = languageDropdown?.querySelector('.language-toggle');
            const languageMenu = languageDropdown?.querySelector('.language-dropdown-menu');

            // User Dropdown
            const userDropdown = document.querySelector('.user-dropdown');
            const userToggle = userDropdown?.querySelector('.user-toggle');
            const userMenu = userDropdown?.querySelector('.user-dropdown-menu');
            
            if (languageToggle && languageMenu) {
                languageToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    languageDropdown.classList.toggle('active');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!languageDropdown.contains(e.target)) {
                        languageDropdown.classList.remove('active');
                    }
                });

                // Close dropdown when clicking a language option
                const languageOptions = languageMenu.querySelectorAll('.language-option');
                languageOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        languageDropdown.classList.remove('active');
                    });
                });
            }

            // User dropdown toggle
            if (userToggle && userMenu) {
                // Force remove inline color styles on menu items
                const menuItems = userMenu.querySelectorAll('.user-menu-item');
                menuItems.forEach(item => {
                    item.style.removeProperty('color');
                    const icons = item.querySelectorAll('i');
                    const spans = item.querySelectorAll('span');
                    icons.forEach(icon => icon.style.removeProperty('color'));
                    spans.forEach(span => span.style.removeProperty('color'));
                });

                userToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('active');

                    // Toggle menu visibility
                    if (userDropdown.classList.contains('active')) {
                        userMenu.style.display = 'block';
                        setTimeout(() => {
                            userMenu.style.opacity = '1';
                            userMenu.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        userMenu.style.opacity = '0';
                        userMenu.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            userMenu.style.display = 'none';
                        }, 300);
                    }
                });

                // Close user dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('active');
                        userMenu.style.opacity = '0';
                        userMenu.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            userMenu.style.display = 'none';
                        }, 300);
                    }
                });

                // Add hover effects to user menu items
                const userMenuItems = userMenu.querySelectorAll('a, button');
                userMenuItems.forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        this.style.background = 'rgba(212, 175, 55, 0.1)';
                        this.style.color = '#d4af37';
                    });
                    item.addEventListener('mouseleave', function() {
                        this.style.background = '';
                        this.style.color = '#ecececff';
                    });
                });
            }

            // Load and update favorites count (only once on page load)
            updateFavoritesCount();

            // Load and update cart count (only once on page load)
            updateCartCount();

            // Initialize all wishlist buttons on the page
            initializeWishlistButtons();
        });

        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        /**
         * Update the favorites count in header
         */
        function updateFavoritesCount(skipButtonUpdate = false) {
            fetch('/favorites/ids')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('favorites-count');
                    const newCount = data.favoriteIds ? data.favoriteIds.length : 0;
                    
                    if (badge) {
                        // Only update if count changed to prevent unnecessary DOM updates
                        const currentCount = parseInt(badge.textContent);
                        if (currentCount !== newCount) {
                            badge.textContent = newCount;
                        }
                        // Remove loading class after first update
                        badge.classList.remove('badge-loading');
                    }
                    
                    // Store favorite IDs globally for quick checks
                    window.favoriteIds = data.favoriteIds || [];
                    
                    // ALWAYS update button states to ensure UI is in sync with server
                    // This fixes issues where visual state doesn't match actual state
                    updateWishlistButtonStates();
                })
                .catch(error => console.error('Error updating favorites count:', error));
        }

        /**
         * Update all wishlist button states based on current favorites
         */
        function updateWishlistButtonStates() {
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            
            // Ensure favoriteIds is an array of integers
            if (!window.favoriteIds || !Array.isArray(window.favoriteIds)) {
                window.favoriteIds = [];
            }
            
            wishlistButtons.forEach(button => {
                const productId = parseInt(button.getAttribute('data-product-id'));
                const isInFavorites = window.favoriteIds.includes(productId);
                
                if (isInFavorites) {
                    button.classList.add('active');
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        icon.style.setProperty('color', '#ff0000', 'important');
                    }
                } else {
                    button.classList.remove('active');
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        icon.style.setProperty('color', '#666', 'important');
                    }
                }
            });
        }

        /**
         * Initialize wishlist button click handlers
         */
        function initializeWishlistButtons() {
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            wishlistButtons.forEach(button => {
                // Check if already initialized
                if (button.dataset.initialized) {
                    return;
                }
                
                button.dataset.initialized = 'true';
                
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const productId = this.getAttribute('data-product-id');
                    if (productId) {
                        toggleFavorite(productId, this);
                    }
                });
            });
        }

        /**
         * Toggle favorite status for a product
         */
        function toggleFavorite(productId, button) {
            // Prevent double-clicking by disabling the button temporarily
            if (button.dataset.processing === 'true') {
                return;
            }
            
            button.dataset.processing = 'true';
            const icon = button.querySelector('i');
            
            // Optimistic UI update
            button.classList.toggle('active');
            if (icon) {
                icon.classList.toggle('fas');
                icon.classList.toggle('far');
                
                // Force color change with !important priority
                if (icon.classList.contains('fas')) {
                    icon.style.setProperty('color', '#ff0000', 'important');
                } else {
                    icon.style.setProperty('color', '#666', 'important');
                }
            }
            
            // Send request to server
            fetch(`/favorites/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                button.dataset.processing = 'false';
                
                if (data.success) {
                    const badge = document.getElementById('favorites-count');
                    const wasAdded = data.action === 'added';
                    
                    // Update global favoriteIds array
                    if (!window.favoriteIds) {
                        window.favoriteIds = [];
                    }
                    
                    const productIdInt = parseInt(productId);
                    
                    if (wasAdded) {
                        // Add to favorites
                        if (!window.favoriteIds.includes(productIdInt)) {
                            window.favoriteIds.push(productIdInt);
                        }
                    } else {
                        // Remove from favorites
                        window.favoriteIds = window.favoriteIds.filter(id => id !== productIdInt);
                    }
                    
                    // Update badge count
                    if (badge) {
                        badge.textContent = window.favoriteIds.length;
                    }
                    
                    // If we reached 0 favorites, force a full UI refresh to ensure all hearts are gray
                    if (window.favoriteIds.length === 0) {
                        updateWishlistButtonStates();
                    }
                    
                    // Show a subtle notification
                    showNotification(data.message);
                } else {
                    // Revert UI if request failed
                    button.classList.toggle('active');
                    if (icon) {
                        icon.classList.toggle('fas');
                        icon.classList.toggle('far');
                        // Revert color
                        if (icon.classList.contains('fas')) {
                            icon.style.setProperty('color', '#ff0000', 'important');
                        } else {
                            icon.style.setProperty('color', '#666', 'important');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling favorite:', error);
                button.dataset.processing = 'false';
                
                // Revert UI on error
                button.classList.toggle('active');
                if (icon) {
                    icon.classList.toggle('fas');
                    icon.classList.toggle('far');
                    // Revert color
                    if (icon.classList.contains('fas')) {
                        icon.style.setProperty('color', '#ff0000', 'important');
                    } else {
                        icon.style.setProperty('color', '#666', 'important');
                    }
                }
            });
        }

        /**
         * Show a notification message
         */
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 2 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 2000);
        }

        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        /**
         * GLOBAL HELPER: Handle 403 responses
         */
        window.handleAccountStatus = function(response) {
            if (response.status === 403) {
                return response.json().then(data => {
                    showNotification(data.message || 'Access denied');
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                    throw new Error('Access denied');
                });
            }
            return Promise.resolve(response);
        };

        /**
         * CART FUNCTIONS
         */

        /**
         * Add product to cart
         */
        function addToCart(productId, button) {
            // Disable button temporarily
            button.disabled = true;
            const originalText = button.innerHTML;
            const addedText = button.getAttribute('data-added-text') || 'Added';
            const originalTextAttr = button.getAttribute('data-original-text') || 'Add to Cart';
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(response => handleAccountStatus(response))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button state
                    button.classList.add('in-cart');
                    @if(is_rtl())
                    button.innerHTML = addedText + ' <i class="fas fa-check"></i>';
                    @else
                    button.innerHTML = '<i class="fas fa-check"></i> ' + addedText;
                    @endif
                    
                    // Add product ID to global cart array
                    if (window.cartProductIds && !window.cartProductIds.includes(productId)) {
                        window.cartProductIds.push(productId);
                    }
                    
                    // Update cart count
                    updateCartCount();
                    
                    // Show notification
                    showNotification(data.message);
                    
                    // Keep the "Added" state permanently
                    button.disabled = false;
                } else {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    showNotification(data.message || 'Failed to add to cart');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                showNotification('Error adding to cart');
            });
        }

        /**
         * Request out-of-stock product
         */
        function requestProduct(productId, productName) {
            // Escape product name for display
            const escapedName = productName.replace(/'/g, "\\'");

            // Show confirmation dialog
            if (confirm(`{{ __t('messages.request_product') }}: ${productName}?\n\n{{ __t('messages.contact_us') }}: 0599-123456`)) {
                showNotification(`{{ __t('messages.request_product') }}: ${productName}`);
            }
        }

        /**
         * Update cart count in header
         */
        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cart-count');
                    const newCount = data.count || 0;
                    
                    if (badge) {
                        // Only update if count changed to prevent unnecessary DOM updates
                        const currentCount = parseInt(badge.textContent);
                        if (currentCount !== newCount) {
                            badge.textContent = newCount;
                        }
                        // Remove loading class after first update
                        badge.classList.remove('badge-loading');
                    }
                    
                    // Store cart product IDs globally
                    return fetch('/cart/products');
                })
                .then(response => response.json())
                .then(data => {
                    window.cartProductIds = data.productIds || [];
                    updateCartButtonStates();
                })
                .catch(error => console.error('Error updating cart count:', error));
        }

        /**
         * Update all add-to-cart button states
         */
        function updateCartButtonStates() {
            const cartButtons = document.querySelectorAll('.add-to-cart[data-product-id]');
            cartButtons.forEach(button => {
                const productId = parseInt(button.getAttribute('data-product-id'));
                const addedText = button.getAttribute('data-added-text') || 'In Cart';
                const originalText = button.getAttribute('data-original-text') || 'Add to Cart';
                
                if (window.cartProductIds && window.cartProductIds.includes(productId)) {
                    button.classList.add('in-cart');
                    // Update button text if not already updated
                    if (!button.innerHTML.includes('check')) {
                        @if(is_rtl())
                        button.innerHTML = addedText + ' <i class="fas fa-check"></i>';
                        @else
                        button.innerHTML = '<i class="fas fa-check"></i> ' + addedText;
                        @endif
                    }
                } else {
                    button.classList.remove('in-cart');
                    // Restore original text
                    if (button.innerHTML.includes('check')) {
                        @if(is_rtl())
                        button.innerHTML = originalText + ' <i class="fas fa-shopping-cart"></i>';
                        @else
                        button.innerHTML = '<i class="fas fa-shopping-cart"></i> ' + originalText;
                        @endif
                    }
                }
            });
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        // Ensure page loading indicator stops after page is fully loaded
        window.addEventListener('load', function() {
            // Force stop any loading indicators
            document.body.classList.add('loaded');
            
            // Remove any loading spinners or indicators
            const loadingElements = document.querySelectorAll('.loading, .spinner, [class*="loading"]');
            loadingElements.forEach(el => el.style.display = 'none');
            
            // Ensure all images are loaded
            const images = document.querySelectorAll('img');
            let loadedImages = 0;
            const totalImages = images.length;
            
            if (totalImages === 0) {
                // No images, page is fully loaded
                document.documentElement.classList.add('page-loaded');
            } else {
                images.forEach(img => {
                    if (img.complete) {
                        loadedImages++;
                    } else {
                        img.addEventListener('load', () => {
                            loadedImages++;
                            if (loadedImages === totalImages) {
                                document.documentElement.classList.add('page-loaded');
                            }
                        });
                        img.addEventListener('error', () => {
                            loadedImages++;
                            if (loadedImages === totalImages) {
                                document.documentElement.classList.add('page-loaded');
                            }
                        });
                    }
                });
                
                // Fallback timeout to ensure loading stops
                setTimeout(() => {
                    document.documentElement.classList.add('page-loaded');
                }, 3000);
            }
        });

        // Additional fix for browser loading indicator
        document.addEventListener('DOMContentLoaded', function() {
            // Mark page as interactive
            document.documentElement.classList.add('page-interactive');
            
            // Force stop loading after a short delay
            setTimeout(() => {
                document.documentElement.classList.add('page-loaded');
                document.body.classList.add('loaded');
            }, 100);
        });

        // Handle page visibility changes
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                document.documentElement.classList.add('page-loaded');
                document.body.classList.add('loaded');
            }
        });
        
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navMenu = document.getElementById('navMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        
        if (mobileMenuToggle && navMenu && mobileMenuOverlay) {
            // Toggle menu
            mobileMenuToggle.addEventListener('click', function() {
                const isActive = navMenu.classList.contains('active');
                
                if (isActive) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
            
            // Close menu when clicking overlay
            mobileMenuOverlay.addEventListener('click', function() {
                closeMenu();
            });
            
            // Close menu when clicking a link
            const menuLinks = navMenu.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setTimeout(closeMenu, 300);
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMenu();
                }
            });
            
            function openMenu() {
                navMenu.classList.add('active');
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Change icon to X
                const icon = mobileMenuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            }
            
            function closeMenu() {
                navMenu.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
                
                // Change icon back to bars
                const icon = mobileMenuToggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        }

        // Global image error handler for broken external URLs
        // Use event delegation to handle dynamically loaded images
        document.addEventListener('error', function(e) {
            if (e.target.tagName === 'IMG' && !e.target.classList.contains('error-handled')) {
                e.target.classList.add('error-handled');
                
                const parent = e.target.parentElement;
                if (!parent) return;
                
                // Check if there's already a placeholder
                const existingPlaceholder = parent.querySelector('.no-image');
                if (existingPlaceholder) {
                    e.target.style.display = 'none';
                    existingPlaceholder.style.display = 'flex';
                    return;
                }
                
                // Create a placeholder
                const div = document.createElement('div');
                div.className = 'no-image';
                div.innerHTML = '<i class="fas fa-image"></i>';
                div.style.cssText = 'display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; background: #f5f5f5; color: #999;';
                
                // Try to replace the image
                try {
                    parent.replaceChild(div, e.target);
                } catch (error) {
                    // If replace fails, just hide the image
                    e.target.style.display = 'none';
                    parent.appendChild(div);
                }
            }
        }, true); // Use capture phase to catch all errors
    </script>
</body>
</html>
