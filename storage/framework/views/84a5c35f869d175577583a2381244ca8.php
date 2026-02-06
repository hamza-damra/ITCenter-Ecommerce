<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(in_array(app()->getLocale(), ['ar', 'he']) ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - IT Center</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <?php if(in_array(app()->getLocale(), ['ar', 'he'])): ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <?php endif; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Prevent Font Awesome from affecting select options */
        select option::before,
        select option::after,
        option::before,
        option::after {
            content: none !important;
            display: none !important;
            font-family: inherit !important;
        }

        :root {
            /* Existing base colors */
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
            
            /* Unified accent colors */
            --accent-blue: #0ea5e9;
            --accent-indigo: #6366f1;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-violet: #8b5cf6;
            
            /* Hero/Header gradients */
            --hero-gradient-start: #0f172a;
            --hero-gradient-mid: #1e293b;
            --hero-gradient-end: #334155;
            
            /* Background colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            
            /* Unified shadows */
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-card-hover: 0 12px 40px rgba(0, 0, 0, 0.12);
            
            /* Border radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
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

        [dir="rtl"] body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(0, 0, 0, 0.3) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .sidebar-header img {
            max-width: 85%;
            height: auto;
            max-height: 45px;
            object-fit: contain;
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

        [dir="rtl"] .sidebar-menu li a::before {
            left: auto;
            right: 0;
        }

        .sidebar-menu li a:hover {
            background: rgba(37, 99, 235, 0.15);
            color: #fff;
            padding-left: 28px;
        }

        [dir="rtl"] .sidebar-menu li a:hover {
            padding-left: 24px;
            padding-right: 28px;
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

        /* Logout Button - Match sidebar menu link styles */
        .sidebar-logout-btn {
            width: 100%;
            background: none;
            border: none;
            text-align: inherit;
            cursor: pointer;
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
            font-family: inherit;
        }

        .sidebar-logout-btn::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: var(--danger);
            border-radius: 2px;
            transition: height 0.3s ease;
        }

        .sidebar-logout-btn:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #fff;
            padding-left: 28px;
        }

        .sidebar-logout-btn:hover::before {
            height: 60%;
        }

        .sidebar-logout-btn i {
            width: 20px;
            font-size: 16px;
        }

        [dir="rtl"] .sidebar-logout-btn:hover {
            padding-left: 24px;
            padding-right: 28px;
        }

        [dir="rtl"] .sidebar-logout-btn::before {
            left: auto;
            right: 0;
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
            font-family: inherit;
        }

        /* Remove text transformation for RTL languages */
        [dir="rtl"] .btn {
            text-transform: none;
            letter-spacing: normal;
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

        [dir="rtl"] .required {
            margin-left: 0;
            margin-right: 4px;
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

        /* Select reset (no custom arrow) - prevents repeated arrow backgrounds */
        select.form-control {
            -webkit-appearance: auto;
            -moz-appearance: auto;
            appearance: auto;
            background-color: #ffffff;
            background-image: none !important;
            background: #ffffff none !important;
            padding: 12px 16px; /* match .form-control */
            cursor: pointer;
            font-weight: 500;
        }

        /* Ensure legacy IE/Edge don't paint extra dropdown glyph */
        select.form-control::-ms-expand { display: none; }

        select.form-control:hover {
            border-color: #94a3b8;
        }

        select.form-control:focus { background-image: none !important; }

        /* Style options - CRITICAL: Remove all markers and backgrounds */
        select.form-control option {
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 500;
            background-color: white;
            background-image: none !important;
            background: white;
            color: var(--dark);
            list-style: none;
            list-style-type: none;
        }

        /* Remove any ::before or ::after that might add markers */
        select.form-control option::before,
        select.form-control option::after {
            content: none !important;
            display: none !important;
        }

        select.form-control option:hover,
        select.form-control option:checked {
            background-color: #eff6ff;
            background-image: none !important;
            background: #eff6ff;
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

        [dir="rtl"] .alert {
            border-left: none;
            border-right: 4px solid;
            flex-direction: row-reverse;
            text-align: right;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left-color: var(--success);
        }

        [dir="rtl"] .alert-success {
            border-right-color: var(--success);
        }

        .alert-error {
            background: #fef2f2;
            color: #7f1d1d;
            border-left-color: var(--danger);
        }

        [dir="rtl"] .alert-error {
            border-right-color: var(--danger);
        }

        .alert-warning {
            background: #fefce8;
            color: #78350f;
            border-left-color: var(--warning);
        }

        [dir="rtl"] .alert-warning {
            border-right-color: var(--warning);
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

        [dir="rtl"] table th,
        [dir="rtl"] table td {
            text-align: right;
        }

        table th {
            background: #f8fafc;
            font-weight: 700;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        [dir="rtl"] table th {
            text-transform: none;
            letter-spacing: normal;
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

        [dir="rtl"] .badge {
            text-transform: none;
            letter-spacing: normal;
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

        [dir="rtl"] .stat-card h3 {
            text-transform: none;
            letter-spacing: normal;
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

        /* Mobile Hamburger Menu Button */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1100;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 12px;
            width: 48px;
            height: 48px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-menu-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        .mobile-menu-toggle .hamburger-icon {
            display: flex;
            flex-direction: column;
            gap: 5px;
            width: 22px;
        }

        .mobile-menu-toggle .hamburger-icon span {
            display: block;
            width: 100%;
            height: 2.5px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s ease;
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

        [dir="rtl"] .mobile-menu-toggle {
            left: auto;
            right: 16px;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }


        /* Responsive - Tablet */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }

            .main-content {
                margin-left: 240px;
                padding: 20px;
            }

            .page-header {
                padding: 24px 20px;
            }

            .page-header-content h1 {
                font-size: 26px;
            }
        }

        /* Responsive - Mobile */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                width: 280px;
                max-width: 85vw;
            }

            [dir="rtl"] .sidebar {
                transform: translateX(100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
                padding-top: 80px;
            }

            [dir="rtl"] .main-content {
                margin-right: 0;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
                padding: 20px 16px;
                margin-bottom: 20px;
            }

            .page-header-content h1 {
                font-size: 22px;
            }

            .page-header-content h1 i {
                font-size: 20px;
            }

            .page-header-content p {
                font-size: 14px;
            }

            .page-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .page-actions .btn {
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            /* Cards responsive */
            .admin-card {
                padding: 16px;
            }

            /* Alert responsive */
            .alert {
                padding: 12px 16px;
                font-size: 14px;
            }
        }

        /* Responsive - Small Mobile */
        @media (max-width: 480px) {
            .mobile-menu-toggle {
                top: 12px;
                left: 12px;
                width: 44px;
                height: 44px;
            }

            [dir="rtl"] .mobile-menu-toggle {
                left: auto;
                right: 12px;
            }

            .main-content {
                padding: 12px;
                padding-top: 72px;
            }

            .page-header {
                padding: 16px;
                border-radius: 12px;
            }

            .page-header-content h1 {
                font-size: 20px;
                gap: 10px;
            }

            .page-actions .btn {
                padding: 10px 16px;
                font-size: 13px;
            }

            .sidebar {
                width: 100%;
                max-width: 100%;
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

        /* ========================================
           GLOBAL RTL SUPPORT FOR ALL ADMIN PAGES
           ======================================== */

        /* General text alignment */
        [dir="rtl"] .main-content {
            text-align: right;
            direction: rtl;
        }

        /* Form elements */
        [dir="rtl"] .form-group {
            text-align: right;
        }

        [dir="rtl"] .form-label {
            text-align: right;
        }

        [dir="rtl"] .form-control {
            text-align: right;
            direction: rtl;
        }

        [dir="rtl"] select.form-control {
            text-align: right;
            direction: rtl;
        }

        /* Error messages */
        [dir="rtl"] .error-message {
            flex-direction: row-reverse;
            text-align: right;
        }

        /* Form text/help */
        [dir="rtl"] .form-text {
            text-align: right;
        }

        /* Page header in RTL */
        [dir="rtl"] .page-header-content h1 {
            flex-direction: row;
        }

        [dir="rtl"] .page-header-content p {
            text-align: right;
        }

        [dir="rtl"] .page-actions {
            flex-direction: row;
        }

        /* Card header */
        [dir="rtl"] .card-header {
            text-align: right;
        }

        /* Action buttons */
        [dir="rtl"] .action-buttons {
            direction: rtl;
        }

        /* Checkbox groups */
        [dir="rtl"] .checkbox-group {
            flex-direction: row;
            text-align: right;
        }

        [dir="rtl"] .checkbox-group span {
            text-align: right;
        }

        [dir="rtl"] .checkbox-group strong {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
        }

        /* Stat cards text */
        [dir="rtl"] .stat-card {
            text-align: right;
        }

        /* Section titles */
        [dir="rtl"] .section-title {
            text-align: right;
        }

        /* Form row */
        [dir="rtl"] .form-row {
            direction: rtl;
        }

        /* Language dropdown hover direction */
        [dir="rtl"] .language-dropdown a:hover {
            transform: translateX(-4px) !important;
        }

        [dir="rtl"] .language-dropdown a.active:hover {
            transform: translateX(-4px) !important;
        }

        /* ========================================
           UNIFIED ADMIN COMPONENTS
           ======================================== */

        /* Admin Hero Component - Unified Page Header */
        .admin-hero {
            background: #1e293b; /* Fallback for older browsers */
            background: linear-gradient(135deg, var(--hero-gradient-start) 0%, var(--hero-gradient-mid) 50%, var(--hero-gradient-end) 100%);
            border-radius: var(--radius-xl);
            padding: 2rem 2.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .admin-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .admin-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .admin-hero-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .admin-hero-text {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .admin-hero-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-indigo) 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.3);
        }

        .admin-hero h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .admin-hero p {
            font-size: 0.9375rem;
            color: #94a3b8;
            margin: 0.25rem 0 0 0;
        }

        .admin-hero .page-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* RTL Support for Admin Hero */
        [dir="rtl"] .admin-hero::before {
            right: auto;
            left: -10%;
        }

        [dir="rtl"] .admin-hero::after {
            left: auto;
            right: -5%;
        }

        [dir="rtl"] .admin-hero-text {
            flex-direction: row-reverse;
            text-align: right;
        }

        /* Responsive Admin Hero */
        @media (max-width: 768px) {
            .admin-hero {
                padding: 1.5rem;
            }

            .admin-hero-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .admin-hero-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }

            .admin-hero h1 {
                font-size: 1.375rem;
            }

            .admin-hero .page-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }

        /* Admin Stats Grid Component */
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        /* Admin Stat Card Component */
        .admin-stat-card {
            background: var(--bg-primary, #ffffff);
            border-radius: var(--radius-lg, 16px);
            padding: 1.5rem;
            box-shadow: var(--shadow-card, 0 4px 20px rgba(0, 0, 0, 0.08));
            border-top: 4px solid var(--accent-blue, #0ea5e9);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .admin-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.08) 0%, transparent 70%);
            border-radius: 0 0 0 100%;
            pointer-events: none;
        }

        .admin-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-card-hover, 0 12px 40px rgba(0, 0, 0, 0.12));
        }

        /* Semantic color variants */
        .admin-stat-card.stat-success {
            border-top-color: var(--accent-emerald, #10b981);
        }

        .admin-stat-card.stat-success::before {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
        }

        .admin-stat-card.stat-warning {
            border-top-color: var(--accent-amber, #f59e0b);
        }

        .admin-stat-card.stat-warning::before {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
        }

        .admin-stat-card.stat-danger {
            border-top-color: var(--accent-rose, #f43f5e);
        }

        .admin-stat-card.stat-danger::before {
            background: linear-gradient(135deg, rgba(244, 63, 94, 0.08) 0%, transparent 70%);
        }

        .admin-stat-card.stat-info {
            border-top-color: var(--accent-blue, #0ea5e9);
        }

        .admin-stat-card.stat-violet {
            border-top-color: var(--accent-violet, #8b5cf6);
        }

        .admin-stat-card.stat-violet::before {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.08) 0%, transparent 70%);
        }

        .admin-stat-card.stat-indigo {
            border-top-color: var(--accent-indigo, #6366f1);
        }

        .admin-stat-card.stat-indigo::before {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
        }

        .admin-stat-card h4 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--secondary, #64748b);
            margin: 0 0 0.75rem 0;
            font-weight: 700;
        }

        [dir="rtl"] .admin-stat-card h4 {
            text-transform: none;
            letter-spacing: normal;
        }

        .admin-stat-card .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark, #0f172a);
            line-height: 1.2;
        }

        .admin-stat-card.stat-success .stat-value {
            color: var(--accent-emerald, #10b981);
        }

        .admin-stat-card.stat-warning .stat-value {
            color: var(--accent-amber, #f59e0b);
        }

        .admin-stat-card.stat-danger .stat-value {
            color: var(--accent-rose, #f43f5e);
        }

        .admin-stat-card.stat-info .stat-value {
            color: var(--accent-blue, #0ea5e9);
        }

        .admin-stat-card.stat-violet .stat-value {
            color: var(--accent-violet, #8b5cf6);
        }

        .admin-stat-card.stat-indigo .stat-value {
            color: var(--accent-indigo, #6366f1);
        }

        .admin-stat-card .stat-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm, 8px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-indigo) 100%);
        }

        .admin-stat-card.stat-success .stat-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .admin-stat-card.stat-warning .stat-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .admin-stat-card.stat-danger .stat-icon {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        }

        .admin-stat-card.stat-violet .stat-icon {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .admin-stat-card.stat-indigo .stat-icon {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        /* RTL Support for Stat Cards */
        [dir="rtl"] .admin-stat-card::before {
            right: auto;
            left: 0;
            border-radius: 0 0 100% 0;
        }

        [dir="rtl"] .admin-stat-card .stat-icon {
            right: auto;
            left: 1rem;
        }

        /* Responsive Stats Grid */
        @media (max-width: 768px) {
            .admin-stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .admin-stat-card {
                padding: 1.25rem;
            }

            .admin-stat-card .stat-value {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .admin-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Admin Table Container Component */
        .admin-table-container {
            background: var(--bg-primary, #ffffff);
            border-radius: var(--radius-lg, 16px);
            box-shadow: var(--shadow-card, 0 4px 20px rgba(0, 0, 0, 0.08));
            overflow: hidden;
        }

        .admin-table-header {
            background: linear-gradient(135deg, var(--bg-secondary, #f8fafc) 0%, var(--bg-tertiary, #f1f5f9) 100%);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border, #e2e8f0);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-table-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark, #0f172a);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-table-header h3 i {
            color: var(--accent-blue, #0ea5e9);
        }

        .admin-table-body {
            padding: 0;
        }

        /* Admin Table Styles */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table thead {
            background: var(--bg-secondary, #f8fafc);
        }

        .admin-table th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-weight: 700;
            color: var(--secondary, #64748b);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border, #e2e8f0);
        }

        [dir="rtl"] .admin-table th {
            text-align: right;
            text-transform: none;
            letter-spacing: normal;
        }

        .admin-table tbody tr {
            border-bottom: 1px solid var(--bg-tertiary, #f1f5f9);
            transition: all 0.2s ease;
        }

        .admin-table tbody tr:last-child {
            border-bottom: none;
        }

        .admin-table tbody tr:hover {
            background: var(--bg-secondary, #f8fafc);
        }

        .admin-table td {
            padding: 1.25rem;
            color: var(--dark, #0f172a);
            vertical-align: middle;
        }

        [dir="rtl"] .admin-table td {
            text-align: right;
        }

        /* Table Action Buttons */
        .admin-table .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
        }

        [dir="rtl"] .admin-table .action-buttons {
            justify-content: flex-end;
        }

        /* Table Status Badges */
        .admin-table .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .admin-table .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .admin-table .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .admin-table .badge-danger {
            background: rgba(244, 63, 94, 0.1);
            color: #e11d48;
        }

        .admin-table .badge-info {
            background: rgba(14, 165, 233, 0.1);
            color: #0284c7;
        }

        /* Responsive Table */
        @media (max-width: 1024px) {
            .admin-table th,
            .admin-table td {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            .admin-table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .admin-table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin: 0 -1rem;
                padding: 0 1rem;
            }

            .admin-table {
                min-width: 600px;
            }

            .admin-table th,
            .admin-table td {
                padding: 0.75rem 0.875rem;
                font-size: 0.8125rem;
                white-space: nowrap;
            }

            .admin-table .action-buttons {
                flex-wrap: nowrap;
                gap: 0.25rem;
            }

            .admin-table .action-buttons .btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }

            /* Card-style table for mobile */
            .admin-table.mobile-cards {
                min-width: 100%;
            }

            .admin-table.mobile-cards thead {
                display: none;
            }

            .admin-table.mobile-cards tbody tr {
                display: block;
                margin-bottom: 1rem;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                padding: 1rem;
            }

            .admin-table.mobile-cards tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #f1f5f9;
                white-space: normal;
            }

            .admin-table.mobile-cards tbody td:last-child {
                border-bottom: none;
            }

            .admin-table.mobile-cards tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                flex-shrink: 0;
                margin-right: 1rem;
            }

            [dir="rtl"] .admin-table.mobile-cards tbody td::before {
                margin-right: 0;
                margin-left: 1rem;
            }
        }

        @media (max-width: 480px) {
            .admin-table th,
            .admin-table td {
                padding: 0.625rem 0.75rem;
                font-size: 0.75rem;
            }

            .admin-table .badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }
        }

        /* Responsive Forms */
        @media (max-width: 768px) {
            .form-group {
                margin-bottom: 1rem;
            }

            .form-group label {
                font-size: 0.875rem;
                margin-bottom: 0.375rem;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 0.625rem 0.875rem;
                font-size: 0.875rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Responsive Buttons */
        @media (max-width: 768px) {
            .btn {
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
            }

            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }

            .btn i {
                font-size: 0.875rem;
            }
        }

        /* Responsive Stats Cards */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .stat-card .stat-value {
                font-size: 1.75rem;
            }

            .stat-card .stat-label {
                font-size: 0.8125rem;
            }
        }

        /* Responsive Pagination */
        @media (max-width: 768px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.25rem;
            }

            .pagination .page-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.8125rem;
            }
        }

        /* Responsive Filters */
        @media (max-width: 768px) {
            .filters-row {
                flex-direction: column;
                gap: 0.75rem;
            }

            .filters-row > * {
                width: 100%;
            }

            .search-box {
                width: 100%;
            }

            .search-box input {
                width: 100%;
            }
        }

        /* Responsive Image Thumbnails */
        @media (max-width: 768px) {
            .product-thumbnail,
            .category-thumbnail,
            .brand-thumbnail {
                width: 50px;
                height: 50px;
            }
        }

        /* Responsive Modal */
        @media (max-width: 768px) {
            .modal-content {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
                border-radius: 16px;
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer {
                padding: 1rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }

        /* Responsive Tabs */
        @media (max-width: 768px) {
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                gap: 0.25rem;
            }

            .nav-tabs .nav-link {
                white-space: nowrap;
                padding: 0.625rem 1rem;
                font-size: 0.8125rem;
            }
        }

        /* Admin Empty State Component */
        .admin-empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .admin-empty-state-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-blue, #0ea5e9) 0%, var(--accent-indigo, #6366f1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);
        }

        .admin-empty-state-icon i {
            font-size: 2rem;
            color: white;
        }

        .admin-empty-state h3 {
            font-size: 1.25rem;
            color: var(--dark, #0f172a);
            margin: 0 0 0.5rem 0;
            font-weight: 700;
        }

        .admin-empty-state p {
            color: var(--secondary, #64748b);
            margin: 0 0 1.5rem 0;
            font-size: 0.9375rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .admin-empty-state .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Responsive Empty State */
        @media (max-width: 768px) {
            .admin-empty-state {
                padding: 3rem 1.5rem;
            }

            .admin-empty-state-icon {
                width: 64px;
                height: 64px;
            }

            .admin-empty-state-icon i {
                font-size: 1.5rem;
            }

            .admin-empty-state h3 {
                font-size: 1.125rem;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="<?php echo e(__('messages.toggle_menu') ?? 'Toggle Menu'); ?>">
        <div class="hamburger-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </button>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="admin-container">
        <aside class="sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <img src="<?php echo e(asset('images/assets/logo.png')); ?>" alt="IT Center Logo">
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>"><i class="fas fa-chart-line"></i> <?php echo e(__('messages.dashboard')); ?></a></li>
                <li><a href="<?php echo e(route('admin.orders.index')); ?>" class="<?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>"><i class="fas fa-shopping-bag"></i> <?php echo e(__t('messages.orders')); ?></a></li>
                <li><a href="<?php echo e(route('admin.contacts.index')); ?>" class="<?php echo e(request()->routeIs('admin.contacts.*') ? 'active' : ''); ?>"><i class="fas fa-envelope"></i> <?php echo e(__('messages.contact_messages')); ?></a></li>
                <li><a href="<?php echo e(route('admin.promotional-offers.index')); ?>" class="<?php echo e(request()->routeIs('admin.promotional-offers.*') ? 'active' : ''); ?>"><i class="fas fa-bullhorn"></i> <?php echo e(__('messages.promotional_management')); ?></a></li>
                <li><a href="<?php echo e(route('admin.banners.index')); ?>" class="<?php echo e(request()->routeIs('admin.banners.*') ? 'active' : ''); ?>"><i class="fas fa-images"></i> <?php echo e(__('messages.banner_management')); ?></a></li>
                <li><a href="<?php echo e(route('admin.promotional-ads.index')); ?>" class="<?php echo e(request()->routeIs('admin.promotional-ads.*') ? 'active' : ''); ?>"><i class="fas fa-ad"></i> <?php echo e(__('messages.promotional_ads')); ?></a></li>
                <li><a href="<?php echo e(route('admin.products.index')); ?>" class="<?php echo e(request()->routeIs('admin.products.*') ? 'active' : ''); ?>"><i class="fas fa-box"></i> <?php echo e(__('messages.products')); ?></a></li>
                <li><a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>"><i class="fas fa-folder"></i> <?php echo e(__('messages.categories')); ?></a></li>
                <li><a href="<?php echo e(route('admin.brands.index')); ?>" class="<?php echo e(request()->routeIs('admin.brands.*') ? 'active' : ''); ?>"><i class="fas fa-tag"></i> <?php echo e(__('messages.brands')); ?></a></li>
                <li><a href="<?php echo e(route('admin.tags.index')); ?>" class="<?php echo e(request()->routeIs('admin.tags.*') ? 'active' : ''); ?>"><i class="fas fa-tags"></i> <?php echo e(__('messages.tags_management')); ?></a></li>
                <li><a href="<?php echo e(route('admin.spec-templates.index')); ?>" class="<?php echo e(request()->routeIs('admin.spec-templates.*') ? 'active' : ''); ?>"><i class="fas fa-clipboard-list"></i> <?php echo e(__('messages.specification_templates')); ?></a></li>
                <li><a href="<?php echo e(route('admin.backup.index')); ?>" class="<?php echo e(request()->routeIs('admin.backup.*') ? 'active' : ''); ?>"><i class="fas fa-database"></i> <?php echo e(__('messages.Database Backup Management')); ?></a></li>
                <li><a href="<?php echo e(route('admin.reviews.index')); ?>" class="<?php echo e(request()->routeIs('admin.reviews.*') ? 'active' : ''); ?>"><i class="fas fa-star"></i> <?php echo e(__('messages.reviews')); ?></a></li>

                <li><a href="<?php echo e(route('home')); ?>" target="_blank"><i class="fas fa-globe"></i> <?php echo e(__('messages.view')); ?> <?php echo e(__('messages.home')); ?></a></li>
                <li>
                    <form method="POST" action="<?php echo e(route('admin.logout')); ?>" style="margin: 0;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="sidebar-logout-btn">
                            <i class="fas fa-sign-out-alt"></i> <?php echo e(__('messages.logout')); ?>

                        </button>
                    </form>
                </li>
            </ul>

            <!-- Language Switcher -->
            <div class="language-switcher">
                <button class="language-btn" onclick="toggleLanguageDropdown()">
                    <i class="fas fa-globe"></i>
                    <span><?php echo e(config('app.locale_names')[app()->getLocale()] ?? 'English'); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="language-dropdown" id="languageDropdown">
                    <a href="<?php echo e(route('lang.switch', 'en')); ?>" class="<?php echo e(app()->getLocale() == 'en' ? 'active' : ''); ?>">
                        <span class="lang-flag">🇬🇧</span>
                        <span>English</span>
                    </a>
                    <a href="<?php echo e(route('lang.switch', 'ar')); ?>" class="<?php echo e(app()->getLocale() == 'ar' ? 'active' : ''); ?>">
                        <span class="lang-flag">🇸🇦</span>
                        <span>العربية</span>
                    </a>
                    <a href="<?php echo e(route('lang.switch', 'he')); ?>" class="<?php echo e(app()->getLocale() == 'he' ? 'active' : ''); ?>">
                        <span class="lang-flag">🇮🇱</span>
                        <span>עברית</span>
                    </a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div><?php echo e(session('success')); ?></div>
                </div>
            <?php elseif(session('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><?php echo e(session('error')); ?></div>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

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

        // Mobile Sidebar Toggle
        (function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const adminSidebar = document.getElementById('adminSidebar');

            function openSidebar() {
                adminSidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                mobileMenuToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                adminSidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                document.body.style.overflow = '';
            }

            function toggleSidebar() {
                if (adminSidebar.classList.contains('active')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }

            // Event Listeners
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && adminSidebar.classList.contains('active')) {
                    closeSidebar();
                }
            });

            // Close sidebar when clicking on a menu link (on mobile)
            const menuLinks = adminSidebar ? adminSidebar.querySelectorAll('.sidebar-menu a') : [];
            menuLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        closeSidebar();
                    }
                }, 250);
            });
        })();
    </script>

    <!-- Global Confirmation Modal -->
    <?php echo $__env->make('components.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Confirmation Helper -->
    <script src="<?php echo e(asset('js/confirm-helper.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Hamza Damra\Documents\ITCenter-Ecommerce\resources\views/admin/layout.blade.php ENDPATH**/ ?>