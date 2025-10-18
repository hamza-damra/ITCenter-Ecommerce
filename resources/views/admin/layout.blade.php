<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - IT Center</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #0f172a;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            color: #1e293b;
            line-height: 1.6;
            min-height: 100vh;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(0, 0, 0, 0.3) 100%);
        }

        .sidebar-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-header h2 i {
            font-size: 24px;
            color: var(--primary-light);
        }

        .sidebar-menu {
            list-style: none;
            padding: 16px 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 24px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 15px;
            font-weight: 500;
            position: relative;
            margin: 4px 12px;
            border-radius: 10px;
        }

        .sidebar-menu li a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: var(--primary-light);
            border-radius: 2px;
            transition: height 0.3s ease;
        }

        .sidebar-menu li a:hover {
            background: rgba(37, 99, 235, 0.15);
            color: #fff;
            padding-left: 28px;
        }

        .sidebar-menu li a:hover::before {
            height: 60%;
        }

        .sidebar-menu li a.active {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.25) 0%, rgba(37, 99, 235, 0.15) 100%);
            color: white;
            border-left: 4px solid var(--primary-light);
            padding-left: 24px;
            font-weight: 700;
        }

        .sidebar-menu li a i {
            width: 20px;
            font-size: 16px;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 24px;
            overflow-y: auto;
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 32px 28px;
            border-radius: 16px;
            margin-bottom: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 6px solid var(--primary);
            border: none;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .page-header-content h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .page-header-content h1 i {
            color: var(--primary);
        }

        .page-header-content p {
            color: var(--secondary);
            font-size: 15px;
            font-weight: 500;
        }

        .page-actions {
            display: flex;
            gap: 12px;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: none;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-header {
            padding: 24px 28px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .card-body {
            padding: 28px;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: var(--secondary);
            border: 2px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--light);
            border-color: var(--secondary);
        }

        .btn-success {
            background: var(--success);
            color: white;
            border: 2px solid var(--success);
        }

        .btn-success:hover {
            background: #059669;
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            border: 2px solid var(--danger);
        }

        .btn-danger:hover {
            background: #dc2626;
            border-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 700;
            min-width: 80px;
            justify-content: center;
        }

        /* Ensure form buttons (like delete) match anchor buttons */
        form button.btn {
            cursor: pointer;
        }

        form button.btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }

        /* Forms */
        .form-layout {
            display: flex;
            flex-direction: column;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
        }

        .required {
            color: var(--danger);
            margin-left: 4px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: white;
        }

        /* Modern Select/Dropdown Styles */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
            padding-right: 40px;
            cursor: pointer;
            font-weight: 500;
        }

        select.form-control:hover {
            border-color: #94a3b8;
        }

        select.form-control:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%232563eb' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        }

        select.form-control option {
            padding: 12px;
            font-size: 14px;
            font-weight: 500;
            background: white;
            color: var(--dark);
        }

        select.form-control option:hover,
        select.form-control option:checked {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: var(--primary);
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #f0f9ff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background: #fef2f2;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-text {
            font-size: 13px;
            color: var(--secondary);
            margin-top: 6px;
        }

        .error-message {
            font-size: 13px;
            color: var(--danger);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .error-message:before {
            content: "⚠";
            font-size: 14px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-group:hover {
            background: var(--light);
            border-color: var(--primary);
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .checkbox-group label {
            cursor: pointer;
            font-weight: 500;
            margin: 0;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid;
            font-size: 14px;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-error {
            background: #fef2f2;
            color: #7f1d1d;
            border-left-color: var(--danger);
        }

        .alert-warning {
            background: #fefce8;
            color: #78350f;
            border-left-color: var(--warning);
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        table th {
            background: #f8fafc;
            font-weight: 700;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table tr:hover {
            background: #f8fafc;
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background: #fee2e2;
            color: #7f1d1d;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card h3 {
            font-size: 13px;
            color: var(--secondary);
            margin-bottom: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
        }

        /* Images */
        img.product-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            text-decoration: none;
            color: var(--primary);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: var(--primary);
            color: white;
        }

        .pagination .active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Language Switcher */
        .language-switcher {
            position: relative;
            padding: 12px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 16px;
        }

        .language-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            font-size: 14px;
            font-weight: 600;
            justify-content: space-between;
        }

        .language-btn .fa-chevron-down {
            margin-left: auto;
            margin-right: 0;
        }

        [dir="rtl"] .language-btn .fa-chevron-down {
            margin-left: 0;
            margin-right: auto;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
        }

        .language-btn i {
            width: 18px;
            font-size: 16px;
        }

        .language-dropdown {
            position: absolute;
            top: 100%;
            left: 20px;
            right: 20px;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 8px;
            margin-top: 12px;
            display: none;
            z-index: 1000;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        /* Global reset for all dropdown elements */
        .language-dropdown *,
        .language-dropdown *::before,
        .language-dropdown *::after {
            text-decoration: none !important;
            -webkit-text-decoration: none !important;
            border-bottom: none !important;
            text-underline-offset: 0 !important;
        }

        .language-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .language-dropdown a {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 12px 14px !important;
            color: #ffffff !important;
            text-decoration: none !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
            margin-bottom: 4px !important;
            border: none !important;
            outline: none !important;
            background: rgba(255, 255, 255, 0.05) !important;
        }

        .language-dropdown a:link,
        .language-dropdown a:visited,
        .language-dropdown a:focus,
        .language-dropdown a:active {
            text-decoration: none !important;
            border-bottom: none !important;
            color: #ffffff !important;
            outline: none !important;
        }

        .language-dropdown a:last-child {
            margin-bottom: 0 !important;
        }

        .language-dropdown a:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            transform: translateX(4px) !important;
            text-decoration: none !important;
            border-bottom: none !important;
        }

        .language-dropdown a.active {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4) !important;
            text-decoration: none !important;
            border-bottom: none !important;
        }

        .language-dropdown a.active:link,
        .language-dropdown a.active:visited,
        .language-dropdown a.active:focus,
        .language-dropdown a.active:active {
            color: white !important;
            text-decoration: none !important;
            border-bottom: none !important;
        }

        .language-dropdown a.active:hover {
            background: linear-gradient(135deg, #2980b9 0%, #3498db 100%) !important;
            text-decoration: none !important;
            color: white !important;
            border-bottom: none !important;
            transform: translateX(4px) !important;
        }

        .language-dropdown a span {
            text-decoration: none !important;
            color: inherit !important;
            border-bottom: none !important;
        }

        .language-dropdown a .lang-flag {
            font-size: 18px !important;
            line-height: 1 !important;
            text-decoration: none !important;
        }

        .lang-flag {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* RTL Support */
        [dir="rtl"] .sidebar {
            right: 0;
            left: auto;
        }

        [dir="rtl"] .main-content {
            margin-left: 0;
            margin-right: 260px;
        }

        [dir="rtl"] .sidebar-menu li a.active {
            border-left: none;
            border-right: 4px solid var(--primary-light);
            padding-right: 20px;
            padding-left: 12px;
        }

        [dir="rtl"] .sidebar-menu li a:hover {
            padding-left: 12px;
            padding-right: 24px;
        }

        [dir="rtl"] .page-header {
            border-left: none;
            border-right: 5px solid var(--primary);
        }

        @media (max-width: 768px) {
            [dir="rtl"] .main-content {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cube"></i> IT Center</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> {{ __('messages.dashboard') }}</a></li>
                <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="fas fa-box"></i> {{ __('messages.products') }}</a></li>
                <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="fas fa-folder"></i> {{ __('messages.categories') }}</a></li>
                <li><a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><i class="fas fa-tag"></i> {{ __('messages.brands') }}</a></li>
                <li><a href="{{ route('home') }}" target="_blank"><i class="fas fa-globe"></i> {{ __('messages.view') }} {{ __('messages.home') }}</a></li>
            </ul>
            
            <!-- Language Switcher -->
            <div class="language-switcher">
                <button class="language-btn" onclick="toggleLanguageDropdown()">
                    <i class="fas fa-globe"></i>
                    <span>{{ config('app.locale_names')[app()->getLocale()] ?? 'English' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="language-dropdown" id="languageDropdown">
                    <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        <span class="lang-flag">🇬🇧</span>
                        <span>English</span>
                    </a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="{{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                        <span class="lang-flag">🇸🇦</span>
                        <span>العربية</span>
                    </a>
                    <a href="{{ route('lang.switch', 'he') }}" class="{{ app()->getLocale() == 'he' ? 'active' : '' }}">
                        <span class="lang-flag">🇮🇱</span>
                        <span>עברית</span>
                    </a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Admin API Scripts -->
    <script src="{{ asset('js/admin/api-client.js') }}"></script>
    <script>
        function toggleLanguageDropdown() {
            const dropdown = document.getElementById('languageDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('languageDropdown');
            const languageBtn = document.querySelector('.language-btn');
            
            if (!languageBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
