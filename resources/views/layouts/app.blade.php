<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ locale_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IT Center')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Horizontal Scroller CSS --}}
    <link rel="stylesheet" href="{{ asset('css/horizontal-scroller.css') }}">
    
    {{-- Search Autocomplete CSS --}}
    <link rel="stylesheet" href="{{ asset('css/search-autocomplete.css') }}">
    
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
            background: #f5f5f5;
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background 0.3s ease, backdrop-filter 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
        }

        header.scrolled {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
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
        
        /* Mobile Menu Toggle - Professional Hamburger */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 12px;
            {{ is_rtl() ? 'right' : 'left' }}: 12px;
            z-index: 1100;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            width: 44px;
            height: 44px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            pointer-events: auto;
            -webkit-tap-highlight-color: transparent;
        }

        .mobile-menu-toggle:hover {
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        .mobile-menu-toggle .hamburger-icon {
            display: flex;
            flex-direction: column;
            gap: 5px;
            width: 20px;
        }

        .mobile-menu-toggle .hamburger-icon span {
            display: block;
            width: 100%;
            height: 2px;
            background: #334155;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle.active {
            background: #2563eb;
            border-color: #2563eb;
        }

        .mobile-menu-toggle.active .hamburger-icon span {
            background: #ffffff;
        }

        .mobile-menu-toggle.active .hamburger-icon span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-menu-toggle.active .hamburger-icon span:nth-child(2) {
            opacity: 0;
            transform: translateX(-10px);
        }

        .mobile-menu-toggle.active .hamburger-icon span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        .mobile-menu-toggle i {
            display: none;
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
            transition: filter 0.3s ease;
        }
        
        header.scrolled .logo img {
            filter: brightness(1.2) contrast(1.1);
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: bold;
            color: #d4af37;
            font-style: italic;
            transition: color 0.3s ease;
        }
        
        header.scrolled .logo-text {
            color: #fbbf24;
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
        
        header.scrolled .nav-menu a {
            color: #ffffff;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            transition: width 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: #2563eb;
        }
        
        header.scrolled .nav-menu a:hover,
        header.scrolled .nav-menu a.active {
            color: #3b82f6;
        }

        .nav-menu a:hover::after,
        .nav-menu a.active::after {
            width: 100%;
        }
        
        /* Mobile Menu Overlay - Only visible on mobile when menu is open */
        .mobile-menu-overlay {
            display: none;
        }
        
        @media (max-width: 768px) {
            .mobile-menu-overlay {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.35);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
                pointer-events: none;
            }
            
            .mobile-menu-overlay.active {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }
        }

        /* Search Bar Styles - Modern & Professional Design */
        .search-bar {
            display: flex;
            flex-direction: {{ is_rtl() ? 'row' : 'row' }};
            flex: 1;
            max-width: 600px;
            min-width: 350px;
            gap: 0;
            align-items: center;
            position: relative;
            margin: 0 2rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border-radius: 50px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: visible;
            background: #ffffff;
        }
        
        .search-bar input {
            border-radius: 50px;
        }

        .search-bar:focus-within {
            box-shadow: 0 4px 20px rgba(31, 41, 55, 0.12);
        }
        
        header.scrolled .search-bar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        }
        
        header.scrolled .search-bar:focus-within {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .search-bar input {
            flex: 1;
            height: 48px;
            padding: 0 20px;
            border: none;
            background: transparent;
            color: #1f2937;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 50px;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            text-align: {{ is_rtl() ? 'right' : 'left' }};
            font-weight: 400;
            letter-spacing: 0.01em;
            line-height: 48px;
        }
        
        header.scrolled .search-bar input {
            color: #ffffff;
        }
        
        header.scrolled .search-bar input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .search-bar input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        
        /* Search Icon */
        .search-bar::before {
            content: '\f002';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            {{ is_rtl() ? 'right' : 'left' }}: 24px;
            color: #9ca3af;
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.3s ease;
            z-index: 1;
        }
        
        header.scrolled .search-bar::before {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .search-bar:focus-within::before {
            color: #1f2937;
        }
        
        header.scrolled .search-bar:focus-within::before {
            color: #ffffff;
        }
        
        .search-bar input {
            padding-{{ is_rtl() ? 'right' : 'left' }}: 52px !important;
        }

        .search-btn {
            height: 40px;
            padding: 0 24px;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: #ffffff;
            border: none;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
            unicode-bidi: embed;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 100px;
            position: relative;
            overflow: hidden;
            margin: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        header.scrolled .search-btn {
            background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
            color: #1f2937;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
        }
        
        header.scrolled .search-btn:hover {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #111827;
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
            background: linear-gradient(135deg, #111827 0%, #000000 100%);
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
        }

        .search-btn:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
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
        
        header.scrolled .header-icon {
            color: #ffffff;
        }

        .header-icon:hover {
            color: #60a5fa;
        }
        
        header.scrolled .header-icon:hover {
            color: #60a5fa;
        }

        .header-icon .badge {
            position: absolute;
            top: -6px;
            {{ is_rtl() ? 'left' : 'right' }}: -8px;
            background: #e11e1eff;
            color: #fff;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            transition: background 0.2s ease;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Hide badge only when it has the 'hidden' class */
        .header-icon .badge.hidden {
            display: none !important;
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

        /* Footer - Matching Scrolled Header Style (Black) */
        footer {
            background: #0a0a0a;
            color: #ecececff;
            padding: 4rem 0 1.5rem;
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(212, 175, 55, 0.3);
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #60a5fa 0%, #3b82f6 50%, #60a5fa 100%);
        }
        
        footer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }
        
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        .footer-section h3 {
            margin-bottom: 1.5rem;
            color: #d4af37;
            font-weight: 700;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 0.75rem;
        }
        
        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            {{ is_rtl() ? 'right' : 'left' }}: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #d4af37, #f4d03f);
            border-radius: 2px;
        }
        
        .footer-section p {
            color: #a0a0a0;
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .footer-section ul li:hover {
            transform: translateX({{ is_rtl() ? '-8px' : '8px' }});
        }
        
        .footer-section ul li i {
            color: #d4af37;
            font-size: 0.85rem;
            width: 20px;
            transition: transform 0.3s ease;
        }
        
        .footer-section ul li:hover i {
            transform: scale(1.2);
            color: #f4d03f;
        }

        .footer-section a {
            color: #a0a0a0;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
        }

        .footer-section a:hover {
            color: #d4af37;
        }
        
        .footer-section a::before {
            content: '';
            position: absolute;
            bottom: -2px;
            {{ is_rtl() ? 'right' : 'left' }}: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #d4af37, #f4d03f);
            transition: width 0.3s ease;
            border-radius: 1px;
        }
        
        .footer-section a:hover::before {
            width: 100%;
        }
        
        /* Footer Social Icons */
        .footer-social {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .footer-social a {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d4af37;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.3);
        }
        
        .footer-social a:hover {
            background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
            color: #0a0a0a;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }
        
        .footer-social a::before {
            display: none;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            color: #808080;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }
        
        .footer-bottom p {
            margin: 0;
            letter-spacing: 0.5px;
        }
        
        .footer-bottom a {
            color: #d4af37;
            font-weight: 600;
        }
        
        .footer-bottom a:hover {
            color: #f4d03f;
        }
        
        /* Footer Logo */
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .footer-logo img {
            height: 45px;
            width: auto;
        }
        
        .footer-logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ecececff;
            letter-spacing: -0.5px;
        }
        
        .footer-logo-text span {
            color: #d4af37;
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
            text-decoration: none;
        }

        .social-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Social icon brand colors */
        .social-icon .fa-facebook-f { color: #fff; }
        .social-icon .fa-instagram { color: #fff; }
        .social-icon .fa-whatsapp { color: #fff; }

        /* Mobile Social Icons Toggle */
        .social-icons-toggle {
            display: none;
            position: fixed;
            {{ is_rtl() ? 'right' : 'left' }}: 20px;
            bottom: 25px;
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #1877f2 0%, #0d65d9 100%);
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(24, 119, 242, 0.4);
            cursor: pointer;
            z-index: 1000;
            color: #fff;
            font-size: 1.4rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .social-icons-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(24, 119, 242, 0.5);
        }

        .social-icons-toggle.active {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
            transform: rotate(45deg);
        }

        .social-icons-toggle.active i {
            transform: rotate(-45deg);
        }

        /* Mobile Social Icons Popup */
        .social-icons-mobile {
            display: none;
            position: fixed;
            {{ is_rtl() ? 'right' : 'left' }}: 20px;
            bottom: 95px;
            flex-direction: column;
            gap: 12px;
            z-index: 999;
            opacity: 0;
            transform: translateY(20px) scale(0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .social-icons-mobile.active {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .social-icons-mobile .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.3rem;
            opacity: 0;
            transform: scale(0) translateY(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .social-icons-mobile .social-icon:nth-child(1) {
            background: linear-gradient(135deg, #1877f2 0%, #0d65d9 100%);
            box-shadow: 0 4px 15px rgba(24, 119, 242, 0.4);
        }

        .social-icons-mobile .social-icon:nth-child(2) {
            background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            box-shadow: 0 4px 15px rgba(225, 48, 108, 0.4);
        }

        .social-icons-mobile .social-icon:nth-child(3) {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        }

        .social-icons-mobile.active .social-icon {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        .social-icons-mobile.active .social-icon:nth-child(1) { transition-delay: 0.1s; }
        .social-icons-mobile.active .social-icon:nth-child(2) { transition-delay: 0.15s; }
        .social-icons-mobile.active .social-icon:nth-child(3) { transition-delay: 0.2s; }

        .social-icons-mobile .social-icon:hover {
            transform: scale(1.15);
        }

        @media (max-width: 768px) {
            .social-icons {
                display: none !important;
            }
            
            .social-icons-toggle {
                display: flex;
            }
            
            .social-icons-mobile {
                display: flex;
            }
        }

        /* Language Dropdown Styles */
        .language-dropdown {
            position: relative;
        }
        .fas:hover{
            color: #60a5fa;
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
            color: #60a5fa;
            background: rgba(37, 99, 235, 0.05);
        }

        .language-dropdown.active .language-toggle {
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
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
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.12);
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
            border-bottom: 1px solid rgba(37, 99, 235, 0.06);
        }

        .language-option:last-child {
            border-bottom: none;
        }

        .language-option:hover {
            background: rgba(37, 99, 235, 0.06);
            color: #2563eb;
            padding-{{ is_rtl() ? 'right' : 'left' }}: 1.4rem;
        }

        .language-option.active {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            font-weight: 600;
        }

        .language-option.active::before {
            content: '';
            position: absolute;
            {{ is_rtl() ? 'right: 0;' : 'left: 0;' }}
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #2563eb, #3b82f6);
        }

        .lang-icon {
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(37, 99, 235, 0.08);
        }

        .lang-name {
            flex: 1;
            font-size: 0.95rem;
        }

        .lang-check {
            font-size: 0.85rem;
            color: #2563eb;
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
            color: #2563eb;
        }

        .user-dropdown.active .user-toggle {
            color: #2563eb;
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
            background: rgba(37, 99, 235, 0.06) !important;
            color: #2563eb !important;
            padding-{{ is_rtl() ? 'right' : 'left' }}: 1.4rem !important;
        }

        .user-dropdown-menu .user-menu-item:hover i {
            color: #2563eb !important;
        }

        .user-dropdown-menu .user-menu-item:hover span {
            color: #2563eb !important;
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
            
            .nav-menu-list a {
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
                top: -5px;
                {{ is_rtl() ? 'left' : 'right' }}: -5px;
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
                display: flex !important;
                position: fixed !important;
                top: 12px !important;
                {{ is_rtl() ? 'right' : 'left' }}: 12px !important;
                z-index: 1100 !important;
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
            
            /* Mobile Sidebar Menu - Clean Design Matching Site Colors */
            .nav-menu {
                display: flex !important;
                position: fixed !important;
                top: 0 !important;
                {{ is_rtl() ? 'right' : 'left' }}: 0 !important;
                width: 280px !important;
                max-width: 85vw !important;
                height: 100vh !important;
                height: 100dvh !important;
                background: #ffffff !important;
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 0 !important;
                gap: 0 !important;
                overflow-y: auto !important;
                z-index: 1060 !important;
                box-shadow: 4px 0 25px rgba(0, 0, 0, 0.2) !important;
                visibility: hidden !important;
                transform: {{ is_rtl() ? 'translateX(100%)' : 'translateX(-100%)' }} !important;
                transition: transform 0.3s ease, visibility 0.3s ease !important;
            }
            
            .nav-menu.active {
                visibility: visible !important;
                transform: translateX(0) !important;
                z-index: 1060 !important;
            }

            /* Sidebar Header */
            .nav-menu-header {
                display: flex !important;
                padding: 20px 24px !important;
                border-bottom: 1px solid #e2e8f0 !important;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .nav-menu-header img {
                max-width: 140px !important;
                height: auto !important;
                max-height: 40px !important;
                object-fit: contain !important;
            }
            
            /* Navigation Menu List */
            .nav-menu-list {
                list-style: none !important;
                margin: 0 !important;
                padding: 8px 0 !important;
            }
            
            .nav-menu-list li {
                width: 100% !important;
                margin: 0 !important;
                list-style: none !important;
            }
            
            .nav-menu-list a {
                display: flex !important;
                align-items: center !important;
                gap: 14px !important;
                font-size: 15px !important;
                font-weight: 500 !important;
                padding: 16px 24px !important;
                width: 100% !important;
                color: #334155 !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
                position: relative !important;
                margin: 0 !important;
                border-radius: 0 !important;
                border-bottom: 1px solid #f1f5f9 !important;
                background: transparent !important;
                pointer-events: auto !important;
                cursor: pointer !important;
                z-index: 10 !important;
            }

            .nav-menu-list a::after {
                display: none !important;
            }

            .nav-menu-list a i {
                width: 22px !important;
                font-size: 18px !important;
                flex-shrink: 0 !important;
                color: #64748b !important;
                transition: color 0.2s ease !important;
            }

            .nav-menu-list a::before {
                content: '' !important;
                position: absolute !important;
                {{ is_rtl() ? 'right' : 'left' }}: 0 !important;
                top: 0 !important;
                bottom: 0 !important;
                width: 4px !important;
                height: 100% !important;
                background: #2563eb !important;
                border-radius: 0 !important;
                transform: scaleY(0) !important;
                transition: transform 0.2s ease !important;
                pointer-events: none !important;
            }
            
            .nav-menu-list a:hover {
                background: #f1f5f9 !important;
                color: #2563eb !important;
                padding-{{ is_rtl() ? 'right' : 'left' }}: 28px !important;
            }

            .nav-menu-list a:hover i {
                color: #2563eb !important;
            }

            .nav-menu-list a:hover::before {
                transform: scaleY(1) !important;
            }
            
            .nav-menu-list a.active {
                background: linear-gradient({{ is_rtl() ? '270deg' : '90deg' }}, rgba(37, 99, 235, 0.1) 0%, transparent 100%) !important;
                color: #2563eb !important;
                font-weight: 600 !important;
            }

            .nav-menu-list a.active i {
                color: #2563eb !important;
            }

            .nav-menu-list a.active::before {
                transform: scaleY(1) !important;
            }

            .nav-menu::-webkit-scrollbar {
                width: 4px !important;
            }

            .nav-menu::-webkit-scrollbar-track {
                background: #f1f5f9 !important;
            }

            .nav-menu::-webkit-scrollbar-thumb {
                background: #cbd5e1 !important;
                border-radius: 2px !important;
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
            
            /* Mobile Nav Menu Icons Section */
            .nav-menu-icons-section {
                display: flex !important;
                flex-direction: column !important;
                padding: 8px 0 !important;
                border-top: 1px solid #e2e8f0 !important;
                margin-top: 8px !important;
            }
            
            .nav-menu-icons-section .nav-icon-item {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 14px !important;
                font-size: 15px !important;
                font-weight: 500 !important;
                padding: 16px 24px !important;
                width: 100% !important;
                color: #334155 !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
                position: relative !important;
                border-bottom: 1px solid #f1f5f9 !important;
                background: transparent !important;
            }
            
            .nav-menu-icons-section .nav-icon-item:hover {
                background: #f1f5f9 !important;
                color: #2563eb !important;
            }
            
            .nav-menu-icons-section .nav-icon-item i {
                width: 22px !important;
                font-size: 18px !important;
                flex-shrink: 0 !important;
                color: #64748b !important;
                transition: color 0.2s ease !important;
            }
            
            .nav-menu-icons-section .nav-icon-item:hover i {
                color: #2563eb !important;
            }
            
            .nav-menu-icons-section .nav-icon-item .nav-icon-content {
                display: flex !important;
                align-items: center !important;
                gap: 14px !important;
                flex: 1 !important;
            }
            
            .nav-menu-icons-section .nav-icon-item .nav-badge {
                background: #e11e1e !important;
                color: #fff !important;
                font-size: 0.7rem !important;
                padding: 2px 8px !important;
                border-radius: 10px !important;
                min-width: 20px !important;
                text-align: center !important;
                font-weight: 600 !important;
            }
            
            .nav-menu-icons-section .nav-icon-item .nav-badge.hidden {
                display: none !important;
            }
            
            /* Language selector in mobile nav */
            .nav-menu-language-section {
                display: flex !important;
                flex-direction: column !important;
                padding: 8px 0 !important;
                border-top: 1px solid #e2e8f0 !important;
            }
            
            .nav-menu-language-section .language-title {
                padding: 12px 24px 8px !important;
                font-size: 12px !important;
                font-weight: 600 !important;
                color: #94a3b8 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
            }
            
            .nav-menu-language-section .nav-lang-item {
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                font-size: 15px !important;
                font-weight: 500 !important;
                padding: 14px 24px !important;
                width: 100% !important;
                color: #334155 !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
                background: transparent !important;
            }
            
            .nav-menu-language-section .nav-lang-item:hover {
                background: #f1f5f9 !important;
                color: #2563eb !important;
            }
            
            .nav-menu-language-section .nav-lang-item.active {
                background: rgba(37, 99, 235, 0.1) !important;
                color: #2563eb !important;
                font-weight: 600 !important;
            }
            
            .nav-menu-language-section .nav-lang-item .lang-flag {
                font-size: 1.2rem !important;
            }
            
            .nav-menu-language-section .nav-lang-item .fa-check {
                margin-{{ is_rtl() ? 'right' : 'left' }}: auto !important;
                color: #2563eb !important;
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
                display: flex !important;
                width: 40px !important;
                height: 40px !important;
            }
            
            .nav-menu {
                width: 260px !important;
            }
            
            .nav-menu-list a {
                font-size: 14px !important;
                padding: 14px 20px !important;
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
                top: -5px;
                {{ is_rtl() ? 'left' : 'right' }}: -5px;
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
                display: flex !important;
                width: 38px !important;
                height: 38px !important;
            }

            .nav-menu {
                width: 240px !important;
            }
            
            .nav-menu-list a {
                font-size: 13px !important;
                padding: 12px 18px !important;
            }
            
            .cart-count {
                width: 14px;
                height: 14px;
                font-size: 0.55rem;
                top: -4px;
                {{ is_rtl() ? 'left' : 'right' }}: -4px;
            }
        }
    </style>
</head>
<body>
    @sectionMissing('hideHeader')
    <!-- Mobile Menu Toggle - Outside header for proper fixed positioning -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu" type="button" style="display: none; position: fixed; top: 12px; {{ is_rtl() ? 'right' : 'left' }}: 12px; z-index: 1100; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; width: 44px; height: 44px; cursor: pointer; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
        <div class="hamburger-icon" style="display: flex; flex-direction: column; gap: 5px; width: 20px;">
            <span style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
            <span style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
            <span style="display: block; width: 100%; height: 2px; background: #334155; border-radius: 2px; transition: all 0.3s ease;"></span>
        </div>
    </button>

    <!-- Mobile Menu Overlay - positioned to NOT cover the nav-menu -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1040; opacity: 0; visibility: hidden; pointer-events: none;"></div>
    
    <!-- Mobile Navigation Menu - OUTSIDE header for proper z-index -->
    <nav class="nav-menu" id="navMenu" style="display: none;">
        <div class="nav-menu-header">
            <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo">
        </div>
        <ul class="nav-menu-list">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> {{ __t('messages.home') }}</a></li>
            <li><a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}"><i class="fas fa-th-large"></i> {{ __t('messages.categories') }}</a></li>
            <li><a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}"><i class="fas fa-box"></i> {{ __t('messages.products') }}</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> {{ __t('messages.about') }}</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i class="fas fa-envelope"></i> {{ __t('messages.contact') }}</a></li>
        </ul>
        
        {{-- Mobile Nav Icons Section (Cart, Wishlist, Account) --}}
        <div class="nav-menu-icons-section">
            @php
                // Get cart count for mobile nav
                if (Auth::check()) {
                    $mobileCartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                } else {
                    $sessionId = Session::getId();
                    $mobileCartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum('quantity');
                }
                
                // Get favorites count for mobile nav
                $mobileFavCount = 0;
                try {
                    if (\App\Services\DatabaseStateService::isDatabaseAvailable()) {
                        if (Auth::check()) {
                            $mobileFavCount = \App\Models\Favorite::where('user_id', Auth::id())->count();
                        } else {
                            $sessionId = Session::getId();
                            $mobileFavCount = \App\Models\Favorite::where('session_id', $sessionId)->count();
                        }
                    }
                } catch (\Exception $e) {
                    $mobileFavCount = 0;
                }
            @endphp
            
            <a href="{{ route('cart.index') }}" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ __t('messages.cart') }}</span>
                </span>
                <span class="nav-badge {{ $mobileCartCount > 0 ? '' : 'hidden' }}" id="mobile-cart-count">{{ $mobileCartCount }}</span>
            </a>
            
            <a href="{{ route('favorites') }}" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-heart"></i>
                    <span>{{ __t('messages.favorites') }}</span>
                </span>
                <span class="nav-badge {{ $mobileFavCount > 0 ? '' : 'hidden' }}" id="mobile-favorites-count">{{ $mobileFavCount }}</span>
            </a>
            
            @guest
            <a href="{{ route('login') }}" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-user"></i>
                    <span>{{ __t('messages.login') }}</span>
                </span>
            </a>
            <a href="{{ route('register') }}" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-user-plus"></i>
                    <span>{{ __t('messages.register') }}</span>
                </span>
            </a>
            @else
            <a href="{{ route('profile.index') }}" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ __t('messages.my_profile') }}</span>
                </span>
            </a>
            <a href="{{ route('orders.index') }}" class="nav-icon-item">
                <span class="nav-icon-content">
                    <i class="fas fa-box"></i>
                    <span>{{ __t('messages.my_orders') }}</span>
                </span>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="nav-icon-item" style="width: 100%; border: none; cursor: pointer; font-family: inherit;">
                    <span class="nav-icon-content">
                        <i class="fas fa-sign-out-alt" style="color: #dc3545 !important;"></i>
                        <span style="color: #dc3545;">{{ __t('messages.logout') }}</span>
                    </span>
                </button>
            </form>
            @endguest
        </div>
        
        {{-- Language Selector in Mobile Nav --}}
        <div class="nav-menu-language-section">
            <div class="language-title">{{ __t('messages.language') }}</div>
            @foreach(available_locales() as $locale)
                <a href="{{ switch_locale_url($locale) }}" class="nav-lang-item {{ $locale === current_locale() ? 'active' : '' }}">
                    <span class="lang-flag">
                        @if($locale === 'en')
                            🇬🇧
                        @elseif($locale === 'ar')
                            🇵🇸
                        @elseif($locale === 'he')
                            🇮🇱
                        @else
                            🌐
                        @endif
                    </span>
                    <span>{{ locale_name($locale) }}</span>
                    @if($locale === current_locale())
                        <i class="fas fa-check"></i>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>
    
    <style>
        /* Force mobile menu toggle to show on mobile */
        @media (max-width: 768px) {
            #mobileMenuToggle {
                display: flex !important;
            }
            #mobileMenuOverlay {
                display: block !important;
            }
            #mobileMenuOverlay.active {
                opacity: 1 !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }
            /* Mobile nav menu styles */
            #navMenu {
                display: flex !important;
                position: fixed !important;
                top: 0 !important;
                {{ is_rtl() ? 'right' : 'left' }}: 0 !important;
                width: 280px !important;
                max-width: 85vw !important;
                height: 100vh !important;
                background: #ffffff !important;
                flex-direction: column !important;
                z-index: 1060 !important;
                box-shadow: 4px 0 25px rgba(0, 0, 0, 0.2) !important;
                visibility: hidden !important;
                transform: {{ is_rtl() ? 'translateX(100%)' : 'translateX(-100%)' }} !important;
                transition: transform 0.3s ease, visibility 0.3s ease !important;
                overflow-y: auto !important;
            }
            #navMenu.active {
                visibility: visible !important;
                transform: translateX(0) !important;
            }
        }
    </style>
    
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo">
                </a>
            </div>

            <form action="{{ route('products') }}" method="GET" class="search-bar" role="search" autocomplete="off">
                <input type="search" 
                       name="search" 
                       placeholder="{{ __t('messages.search') }}"
                       autocomplete="off"
                       aria-autocomplete="list"
                       aria-expanded="false"
                       aria-haspopup="listbox"
                       value="{{ request('search') }}">
                <!--<button class="search-btn" type="submit" aria-label="{{ __t('messages.search') }}">
                    <i class="fas fa-search"></i>
                </button>-->
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
                    <div class="user-dropdown-menu" style="display: none; position: absolute; top: calc(100% + 10px); {{ is_rtl() ? 'left: 0;' : 'right: 0;' }} background: #ffffff; backdrop-filter: blur(10px); border: 2px solid #e8eef7; border-radius: 12px; min-width: max-content; box-shadow: 0 8px 24px rgba(39, 98, 243, 0.12); overflow: hidden; z-index: 1001; opacity: 0; transform: translateY(-10px); transition: opacity 0.3s ease, transform 0.3s ease;">
                        <a href="{{ route('profile.index') }}" class="user-menu-item" style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: {{ is_rtl() ? 'right' : 'left' }}; font-family: inherit; font-size: inherit; text-decoration: none; color: inherit; white-space: nowrap;">
                            <i class="fas fa-user"></i>
                            <span>{{ __t('messages.my_profile') }}</span>
                        </a>
                        <a href="{{ route('orders.index') }}" class="user-menu-item" style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: {{ is_rtl() ? 'right' : 'left' }}; font-family: inherit; font-size: inherit; text-decoration: none; color: inherit; white-space: nowrap;">
                            <i class="fas fa-box"></i>
                            <span>{{ __t('messages.my_orders') }}</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="user-menu-item" style="width: 100%; display: flex; align-items: center; gap: 0.8rem; padding: 0.9rem 1.2rem; background: none; border: none; cursor: pointer; transition: background 0.3s ease, padding 0.3s ease; text-align: {{ is_rtl() ? 'right' : 'left' }}; font-family: inherit; font-size: inherit; text-decoration: none; color: #dc3545; white-space: nowrap;">
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
                        @php
                            // Get initial favorites count from server to prevent flash
                            $initialFavCount = 0;
                            try {
                                // Check if database is available before querying
                                if (\App\Services\DatabaseStateService::isDatabaseAvailable()) {
                                    if (Auth::check()) {
                                        $initialFavCount = \App\Models\Favorite::where('user_id', Auth::id())->count();
                                    } else {
                                        $sessionId = Session::getId();
                                        $initialFavCount = \App\Models\Favorite::where('session_id', $sessionId)->count();
                                    }
                                }
                            } catch (\Exception $e) {
                                // Database not available or query failed - use 0 as default
                                $initialFavCount = 0;
                            }
                        @endphp
                        <span class="badge {{ $initialFavCount > 0 ? '' : 'hidden' }}" id="favorites-count">{{ $initialFavCount }}</span>
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
                        @php
                            // Get initial cart count from server to prevent flash
                            if (Auth::check()) {
                                $initialCartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                            } else {
                                $sessionId = Session::getId();
                                $initialCartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum('quantity');
                            }
                        @endphp
                        <span class="badge {{ $initialCartCount > 0 ? '' : 'hidden' }}" id="cart-count">{{ $initialCartCount }}</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Category Navigation - Only show on home page --}}
    @if(isset($navigationCategories) && $navigationCategories->count() > 0 && request()->routeIs('home'))
        <x-category-nav :categories="$navigationCategories" />
    @endif

    <!-- Desktop Social Icons -->
    <div class="social-icons">
        <a href="https://facebook.com" target="_blank" class="social-icon">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" class="social-icon">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://wa.me/" target="_blank" class="social-icon">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- Mobile Social Icons Toggle -->
    <div class="social-icons-toggle" onclick="toggleMobileSocial()">
        <i class="fas fa-share-alt"></i>
    </div>

    <!-- Mobile Social Icons Popup -->
    <div class="social-icons-mobile">
        <a href="https://facebook.com" target="_blank" class="social-icon">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank" class="social-icon">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://wa.me/" target="_blank" class="social-icon">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
    @endif

    <main>
        @yield('content')
    </main>

    @sectionMissing('hideHeader')
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="{{ asset('images/assets/logo.png') }}" alt="IT Center Logo">
                </div>
                <p>{{ __('messages.footer_description') }}</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.quick_links') }}</h3>
                <ul>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="{{ route('products') }}">{{ __('messages.products') }}</a></li>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="{{ route('contact') }}">{{ __('messages.contact_us') }}</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.footer_categories') }}</h3>
                <ul>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="#">{{ __('messages.laptops') }}</a></li>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="#">{{ __('messages.desktops') }}</a></li>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="#">{{ __('messages.accessories') }}</a></li>
                    <li><i class="fas fa-chevron-{{ is_rtl() ? 'left' : 'right' }}"></i><a href="#">{{ __('messages.components') }}</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>{{ __('messages.contact_us') }}</h3>
                <ul>
                    <li><i class="fas fa-phone"></i><a href="tel:0595910045">0595910045</a></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:support@itcenter.vip">support@itcenter.vip</a></li>
                    <li><i class="fas fa-map-marker-alt"></i><span style="color: #4b5563;">{{ __('messages.location') }}</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} <a href="{{ route('home') }}">IT Center</a>. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </footer>
    @endif

    <script>
        // Global configuration variables
        const isRTL = {{ is_rtl() ? 'true' : 'false' }};
        
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

        // Mobile Social Icons Toggle Function
        function toggleMobileSocial() {
            const toggle = document.querySelector('.social-icons-toggle');
            const popup = document.querySelector('.social-icons-mobile');
            
            if (toggle && popup) {
                toggle.classList.toggle('active');
                popup.classList.toggle('active');
            }
        }

        // Close mobile social icons when clicking outside
        document.addEventListener('click', function(e) {
            const toggle = document.querySelector('.social-icons-toggle');
            const popup = document.querySelector('.social-icons-mobile');
            
            if (toggle && popup && !toggle.contains(e.target) && !popup.contains(e.target)) {
                toggle.classList.remove('active');
                popup.classList.remove('active');
            }
        });

        // Language and User dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Language Dropdown
            const languageDropdown = document.querySelector('.language-dropdown');
            const languageToggle = languageDropdown ? languageDropdown.querySelector('.language-toggle') : null;
            const languageMenu = languageDropdown ? languageDropdown.querySelector('.language-dropdown-menu') : null;

            // User Dropdown
            const userDropdown = document.querySelector('.user-dropdown');
            const userToggle = userDropdown ? userDropdown.querySelector('.user-toggle') : null;
            const userMenu = userDropdown ? userDropdown.querySelector('.user-dropdown-menu') : null;

            // Helper function to close all dropdowns
            function closeAllDropdowns() {
                // Close language dropdown
                if (languageDropdown) {
                    languageDropdown.classList.remove('active');
                }

                // Close user dropdown
                if (userDropdown && userMenu) {
                    userDropdown.classList.remove('active');
                    userMenu.style.opacity = '0';
                    userMenu.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        userMenu.style.display = 'none';
                    }, 300);
                }
            }

            // Language dropdown toggle
            if (languageToggle && languageMenu) {
                languageToggle.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Close user dropdown if open
                    if (userDropdown && userDropdown.classList.contains('active')) {
                        userDropdown.classList.remove('active');
                        if (userMenu) {
                            userMenu.style.opacity = '0';
                            userMenu.style.transform = 'translateY(-10px)';
                            setTimeout(() => {
                                userMenu.style.display = 'none';
                            }, 300);
                        }
                    }

                    // Toggle language dropdown
                    languageDropdown.classList.toggle('active');
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

                    // Close language dropdown if open
                    if (languageDropdown && languageDropdown.classList.contains('active')) {
                        languageDropdown.classList.remove('active');
                    }

                    // Toggle user dropdown
                    const isCurrentlyActive = userDropdown.classList.contains('active');
                    userDropdown.classList.toggle('active');

                    // Toggle menu visibility
                    if (!isCurrentlyActive) {
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

            // Global click-outside handler for all dropdowns
            document.addEventListener('click', function(e) {
                // Check if click is outside both dropdowns
                const isLanguageClick = languageDropdown && languageDropdown.contains(e.target);
                const isUserClick = userDropdown && userDropdown.contains(e.target);

                if (!isLanguageClick && !isUserClick) {
                    closeAllDropdowns();
                }
            });

            // Sync header counters on page load (server-side values already rendered)
            // This ensures any session changes are reflected
            refreshHeaderCounters();

            // Load favorites IDs for wishlist button states
            updateFavoritesCount();

            // Load cart product IDs for button states
            updateCartCount();

            // Initialize all wishlist buttons on the page
            initializeWishlistButtons();
        });

        // CSRF Token for AJAX requests
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        /**
         * GLOBAL FUNCTION: Refresh both cart and favorites counters
         * This is the single source of truth for updating header badges
         */
        async function refreshHeaderCounters() {
            try {
                const [cartRes, favRes] = await Promise.all([
                    fetch('/cart/count', { credentials: 'same-origin' }),
                    fetch('/favorites/count', { credentials: 'same-origin' }),
                ]);

                const cart = await cartRes.json();
                const fav = await favRes.json();

                const cartEl = document.getElementById('cart-count');
                const favEl = document.getElementById('favorites-count');

                // Update cart counter
                if (cartEl && typeof cart.count !== 'undefined') {
                    cartEl.textContent = cart.count;
                    if (cart.count > 0) {
                        cartEl.classList.remove('hidden');
                    } else {
                        cartEl.classList.add('hidden');
                    }
                }

                // Update favorites counter
                if (favEl && typeof fav.count !== 'undefined') {
                    favEl.textContent = fav.count;
                    if (fav.count > 0) {
                        favEl.classList.remove('hidden');
                    } else {
                        favEl.classList.add('hidden');
                    }
                }

                console.log('✅ Header counters updated - Cart:', cart.count, 'Favorites:', fav.count);
            } catch (error) {
                console.error('❌ Failed to refresh header counters:', error);
            }
        }

        /**
         * Update the favorites count in header
         */
        function updateFavoritesCount(skipButtonUpdate = false) {
            console.log('🔄 Updating favorites count...');
            fetch('/favorites/ids')
                .then(response => response.json())
                .then(data => {
                    console.log('✅ Favorites data received:', data);
                    const badge = document.getElementById('favorites-count');
                    const newCount = data.favoriteIds ? data.favoriteIds.length : 0;
                    console.log('📊 Favorites count:', newCount, 'Badge element:', badge);
                    
                    if (badge) {
                        // Update the badge text
                        badge.textContent = newCount;
                        
                        // Show/hide badge based on count (same as cart)
                        if (newCount > 0) {
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                        
                        console.log('✨ Badge updated successfully. Current text:', badge.textContent);
                    }
                    
                    // Store favorite IDs globally for quick checks
                    window.favoriteIds = data.favoriteIds || [];
                    
                    // ALWAYS update button states to ensure UI is in sync with server
                    // This fixes issues where visual state doesn't match actual state
                    updateWishlistButtonStates();
                })
                .catch(error => {
                    console.error('❌ Error updating favorites count:', error);
                });
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
                    
                    // Update badge count (same as cart counter logic)
                    if (badge) {
                        const newCount = window.favoriteIds.length;
                        badge.textContent = newCount;
                        
                        // Show/hide badge based on count
                        if (newCount > 0) {
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
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

            // Check if this is an icon-only button
            const isIconButton = button.classList.contains('add-to-cart-icon');

            if (isIconButton) {
                // For icon-only buttons, just show spinner
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            } else {
                // For text buttons, show spinner with text
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            }

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

                    if (isIconButton) {
                        // For icon-only buttons, just show check icon
                        button.innerHTML = '<i class="fas fa-check"></i>';
                        // Update title/aria-label for accessibility
                        button.setAttribute('title', addedText);
                        button.setAttribute('aria-label', addedText);
                    } else {
                        // For text buttons, show check icon with text
                        if (isRTL) {
                            button.innerHTML = addedText + ' <i class="fas fa-check"></i>';
                        } else {
                            button.innerHTML = '<i class="fas fa-check"></i> ' + addedText;
                        }
                    }

                    // Add product ID to global cart array
                    if (window.cartProductIds && !window.cartProductIds.includes(productId)) {
                        window.cartProductIds.push(productId);
                    }

                    // Update cart count using global refresh function
                    refreshHeaderCounters();

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
            const requestMsg = '{{ __t("messages.request_product") }}';
            const contactMsg = '{{ __t("messages.contact_us") }}';
            if (confirm(requestMsg + ': ' + productName + '?\n\n' + contactMsg + ': 0599-123456')) {
                showNotification(requestMsg + ': ' + productName);
            }
        }

        /**
         * Update cart count in header and load product IDs for button states
         */
        function updateCartCount() {
            console.log('� Loading cart products for button states...');
            
            // Store cart product IDs globally
            fetch('/cart/products')
                .then(response => response.json())
                .then(data => {
                    window.cartProductIds = data.productIds || [];
                    updateCartButtonStates();
                    console.log('✅ Cart product IDs loaded:', window.cartProductIds.length);
                })
                .catch(error => {
                    console.error('❌ Error loading cart products:', error);
                });
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
                        if (isRTL) {
                            button.innerHTML = addedText + ' <i class="fas fa-check"></i>';
                        } else {
                            button.innerHTML = '<i class="fas fa-check"></i> ' + addedText;
                        }
                    }
                } else {
                    button.classList.remove('in-cart');
                    // Restore original text
                    if (button.innerHTML.includes('check')) {
                        if (isRTL) {
                            button.innerHTML = originalText + ' <i class="fas fa-shopping-cart"></i>';
                        } else {
                            button.innerHTML = '<i class="fas fa-shopping-cart"></i> ' + originalText;
                        }
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
        
        // Mobile Sidebar Toggle - Simple and robust implementation
        (function() {
            'use strict';
            
            function initMobileSidebar() {
                var mobileMenuToggle = document.getElementById('mobileMenuToggle');
                var navMenu = document.getElementById('navMenu');
                var mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

                if (!mobileMenuToggle || !navMenu || !mobileMenuOverlay) {
                    return;
                }

                function openSidebar() {
                    navMenu.classList.add('active');
                    mobileMenuOverlay.classList.add('active');
                    mobileMenuToggle.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }

                function closeSidebar() {
                    navMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    document.body.style.overflow = '';
                }

                // Toggle button click
                mobileMenuToggle.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (navMenu.classList.contains('active')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                };

                // Overlay click to close
                mobileMenuOverlay.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeSidebar();
                };

                // Stop propagation on nav-menu for non-link clicks
                navMenu.onclick = function(e) {
                    if (e.target.tagName === 'A' || e.target.closest('a')) {
                        return true;
                    }
                    e.stopPropagation();
                };

                // Menu links - close sidebar and navigate
                var menuLinks = navMenu.querySelectorAll('.nav-menu-list a');
                menuLinks.forEach(function(link) {
                    link.onclick = function() {
                        navMenu.classList.remove('active');
                        mobileMenuOverlay.classList.remove('active');
                        mobileMenuToggle.classList.remove('active');
                        document.body.style.overflow = '';
                        return true;
                    };
                });

                // Close on Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                        closeSidebar();
                    }
                });

                // Close on window resize to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768 && navMenu.classList.contains('active')) {
                        closeSidebar();
                    }
                });
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMobileSidebar);
            } else {
                initMobileSidebar();
            }
        })();

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
    
    {{-- Horizontal Scroller JavaScript --}}
    <script src="{{ asset('js/horizontal-scroller.js') }}"></script>
    
    {{-- Search Autocomplete JavaScript --}}
    <script src="{{ asset('js/search-autocomplete.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
