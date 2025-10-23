<!DOCTYPE html>
<html lang="{{ current_locale() }}" dir="{{ locale_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __t('errors.db_down.title') }} - IT Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(is_rtl())
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: {{ is_rtl() ? "'Cairo', sans-serif" : "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif" }};
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            direction: {{ locale_direction() }};
            overflow-x: hidden;
            position: relative;
        }

        /* Animated background pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.05) 35px, rgba(255,255,255,.05) 70px);
            animation: slideBackground 20s linear infinite;
        }

        @keyframes slideBackground {
            0% { transform: translateX(0); }
            100% { transform: translateX(70px); }
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
            max-width: 800px;
            width: 100%;
        }

        .error-animation {
            position: relative;
            margin-bottom: 2rem;
            perspective: 1000px;
        }

        /* Database icon animation */
        .db-icon-container {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }

        .db-icon {
            font-size: 8rem;
            color: rgba(255, 255, 255, 0.9);
            animation: floatDatabase 3s ease-in-out infinite;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.3));
        }

        @keyframes floatDatabase {
            0%, 100% {
                transform: translateY(0) rotateY(0deg);
            }
            50% {
                transform: translateY(-20px) rotateY(10deg);
            }
        }

        .warning-badge {
            position: absolute;
            top: -10px;
            {{ is_rtl() ? 'left' : 'right' }}: -10px;
            background: #ef4444;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            animation: pulse 2s ease-in-out infinite;
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.5);
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 4px 20px rgba(239, 68, 68, 0.5);
            }
            50% {
                transform: scale(1.1);
                box-shadow: 0 4px 30px rgba(239, 68, 68, 0.8);
            }
        }

        .error-code {
            font-size: clamp(4rem, 12vw, 6rem);
            font-weight: 900;
            color: rgba(255, 255, 255, 0.15);
            line-height: 1;
            text-shadow: 
                2px 2px 0 rgba(255, 255, 255, 0.2),
                4px 4px 0 rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .error-code::after {
            content: '503';
            position: absolute;
            top: 0;
            left: 0;
            color: white;
            font-size: inherit;
            animation: glitch 2s infinite;
            text-shadow: 
                -2px 0 #ff00de,
                2px 0 #00fff2;
            clip-path: inset(0 100% 0 0);
        }

        @keyframes glitch {
            0%, 90%, 100% {
                clip-path: inset(0 100% 0 0);
            }
            92% {
                clip-path: inset(0 0 0 0);
            }
            94% {
                clip-path: inset(0 100% 0 0);
            }
            96% {
                clip-path: inset(0 0 0 0);
            }
            98% {
                clip-path: inset(0 100% 0 0);
            }
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.5);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-weight: 900;
            line-height: 1.2;
        }

        .subtitle {
            font-size: clamp(1.1rem, 3vw, 1.3rem);
            color: #3b82f6;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .message {
            font-size: clamp(1rem, 2.5vw, 1.1rem);
            color: #64748b;
            line-height: 1.8;
            margin-bottom: 2.5rem;
            padding: 0 1rem;
        }

        .info-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-{{ is_rtl() ? 'right' : 'left' }}: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: {{ is_rtl() ? 'right' : 'left' }};
        }

        .info-box-title {
            font-size: 1.1rem;
            color: #1e40af;
            font-weight: 700;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            {{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}
        }

        .info-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-box li {
            color: #1e40af;
            padding: 0.5rem 0;
            padding-{{ is_rtl() ? 'right' : 'left' }}: 1.5rem;
            position: relative;
        }

        .info-box li::before {
            content: '•';
            position: absolute;
            {{ is_rtl() ? 'right' : 'left' }}: 0;
            color: #3b82f6;
            font-size: 1.5rem;
            line-height: 1;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fee2e2;
            color: #991b1b;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            margin: 1rem 0;
            animation: statusPulse 2s ease-in-out infinite;
        }

        @keyframes statusPulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background: #dc2626;
            border-radius: 50%;
            animation: blink 1s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .btn-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #3b82f6;
            border: 2px solid #3b82f6;
        }

        .btn-secondary:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .language-switcher {
            position: fixed;
            top: 2rem;
            {{ is_rtl() ? 'left' : 'right' }}: 2rem;
            display: flex;
            gap: 0.5rem;
            z-index: 100;
            background: rgba(255, 255, 255, 0.9);
            padding: 0.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .lang-btn {
            padding: 0.5rem 1rem;
            border: 2px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: #3b82f6;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .lang-btn:hover {
            background: #eff6ff;
            border-color: #3b82f6;
        }

        .lang-btn.active {
            background: #3b82f6;
            color: white;
        }

        .footer {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e5e7eb;
            color: #64748b;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .content-card {
                padding: 2rem 1.5rem;
            }

            .language-switcher {
                top: 1rem;
                {{ is_rtl() ? 'left' : 'right' }}: 1rem;
                flex-direction: column;
            }

            .btn-container {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        /* RTL specific adjustments */
        @if(is_rtl())
        .info-box li::before {
            right: 0;
            left: auto;
        }
        @endif
    </style>
</head>
<body>
    <!-- Language Switcher -->
    <div class="language-switcher">
        <a href="?lang=en" class="lang-btn {{ current_locale() === 'en' ? 'active' : '' }}">EN</a>
        <a href="?lang=ar" class="lang-btn {{ current_locale() === 'ar' ? 'active' : '' }}">AR</a>
        <a href="?lang=he" class="lang-btn {{ current_locale() === 'he' ? 'active' : '' }}">HE</a>
    </div>

    <div class="error-container">
        <!-- Database Icon Animation -->
        <div class="error-animation">
            <div class="db-icon-container">
                <i class="fas fa-database db-icon"></i>
                <div class="warning-badge">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="error-code">503</div>
        </div>

        <!-- Content Card -->
        <div class="content-card">
            <!-- Status Indicator -->
            <div class="status-indicator">
                <span class="status-dot"></span>
                <span>{{ __t('errors.db_down.status') }}</span>
            </div>

            <h1>{{ __t('errors.db_down.heading') }}</h1>
            <p class="subtitle">{{ __t('errors.db_down.subtitle') }}</p>
            <p class="message">{{ __t('errors.db_down.message') }}</p>

            <!-- Information Box -->
            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ __t('errors.db_down.info_title') }}</span>
                </div>
                <ul>
                    <li>{{ __t('errors.db_down.info_1') }}</li>
                    <li>{{ __t('errors.db_down.info_2') }}</li>
                    <li>{{ __t('errors.db_down.info_3') }}</li>
                    <li>{{ __t('errors.db_down.info_4') }}</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="btn-container">
                <button onclick="location.reload()" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                    <span>{{ __t('errors.db_down.retry') }}</span>
                </button>
                <a href="mailto:support@itcenter.com" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i>
                    <span>{{ __t('errors.db_down.contact') }}</span>
                </a>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>{{ __t('errors.db_down.footer') }}</p>
                @if(config('app.debug') && isset($exception))
                <details style="margin-top: 1rem; text-align: {{ is_rtl() ? 'right' : 'left' }};">
                    <summary style="cursor: pointer; color: #ef4444; font-weight: 600;">
                        <i class="fas fa-bug"></i> Debug Information
                    </summary>
                    <pre style="background: #f8fafc; padding: 1rem; border-radius: 8px; overflow-x: auto; margin-top: 0.5rem; font-size: 0.85rem; text-align: left;">{{ $exception->getMessage() }}</pre>
                </details>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-retry logic (optional)
        let retryCount = 0;
        const maxRetries = 3;
        const retryDelay = 5000; // 5 seconds

        function autoRetry() {
            if (retryCount < maxRetries) {
                retryCount++;
                setTimeout(() => {
                    console.log(`Auto-retry attempt ${retryCount}/${maxRetries}`);
                    location.reload();
                }, retryDelay);
            }
        }

        // Uncomment to enable auto-retry
        // autoRetry();

        // Handle locale change
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Allow default behavior to navigate with ?locale parameter
            });
        });
    </script>
</body>
</html>
