<!DOCTYPE html>
<html lang="<?php echo e(current_locale()); ?>" dir="<?php echo e(locale_direction()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(__t('404')); ?> - IT Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php if(is_rtl()): ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <?php endif; ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: <?php echo e(is_rtl() ? "'Cairo', sans-serif" : "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"); ?>;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            direction: <?php echo e(locale_direction()); ?>;
            overflow-x: hidden;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .error-animation {
            position: relative;
            margin-bottom: 2rem;
        }

        .error-code {
            font-size: clamp(8rem, 20vw, 15rem);
            font-weight: 900;
            color: rgba(255, 255, 255, 0.1);
            line-height: 1;
            text-shadow: 
                2px 2px 0 rgba(255, 255, 255, 0.2),
                4px 4px 0 rgba(255, 255, 255, 0.15),
                6px 6px 0 rgba(255, 255, 255, 0.1);
            animation: glitch 2s infinite;
            position: relative;
        }

        @keyframes glitch {
            0%, 100% {
                transform: translate(0);
            }
            20% {
                transform: translate(-2px, 2px);
            }
            40% {
                transform: translate(-2px, -2px);
            }
            60% {
                transform: translate(2px, 2px);
            }
            80% {
                transform: translate(2px, -2px);
            }
        }

        .error-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: clamp(4rem, 10vw, 8rem);
            color: white;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(-50%, -50%) translateY(0);
            }
            50% {
                transform: translate(-50%, -50%) translateY(-20px);
            }
        }

        .error-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin: 0 auto;
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

        .error-title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 700;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: #555;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: rise 10s infinite ease-in;
        }

        @keyframes rise {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        .particle:nth-child(1) {
            left: 10%;
            width: 10px;
            height: 10px;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            left: 30%;
            width: 8px;
            height: 8px;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            left: 40%;
            width: 12px;
            height: 12px;
            animation-delay: 1s;
        }

        .particle:nth-child(5) {
            left: 50%;
            width: 10px;
            height: 10px;
            animation-delay: 3s;
        }

        .particle:nth-child(6) {
            left: 60%;
            width: 14px;
            height: 14px;
            animation-delay: 5s;
        }

        .particle:nth-child(7) {
            left: 70%;
            width: 9px;
            height: 9px;
            animation-delay: 2.5s;
        }

        .particle:nth-child(8) {
            left: 80%;
            width: 11px;
            height: 11px;
            animation-delay: 4.5s;
        }

        .particle:nth-child(9) {
            left: 90%;
            width: 13px;
            height: 13px;
            animation-delay: 1.5s;
        }

        .particle:nth-child(10) {
            left: 95%;
            width: 8px;
            height: 8px;
            animation-delay: 3.5s;
        }

        @media (max-width: 768px) {
            .error-content {
                padding: 2rem 1.5rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="error-container">
        <div class="error-animation">
            <div class="error-code">404</div>
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>

        <div class="error-content">
            <h1 class="error-title">
                <?php if(current_locale() === 'ar'): ?>
                    عذراً! الصفحة غير موجودة
                <?php elseif(current_locale() === 'he'): ?>
                    מצטערים! הדף לא נמצא
                <?php else: ?>
                    Oops! Page Not Found
                <?php endif; ?>
            </h1>
            <p class="error-message">
                <?php if(current_locale() === 'ar'): ?>
                    الصفحة التي تبحث عنها غير متوفرة أو ربما تم نقلها إلى موقع آخر. يمكنك العودة إلى الصفحة الرئيسية أو استخدام زر الرجوع.
                <?php elseif(current_locale() === 'he'): ?>
                    הדף שאתה מחפש אינו זמין או אולי הועבר למיקום אחר. אתה יכול לחזור לדף הבית או להשתמש בכפתור חזור.
                <?php else: ?>
                    The page you're looking for is not available or may have been moved to another location. You can return to the home page or use the back button.
                <?php endif; ?>
            </p>
            <div class="error-actions">
                <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    <?php if(current_locale() === 'ar'): ?>
                        العودة للرئيسية
                    <?php elseif(current_locale() === 'he'): ?>
                        חזרה לדף הבית
                    <?php else: ?>
                        Go to Home
                    <?php endif; ?>
                </a>
                <button onclick="window.history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-<?php echo e(is_rtl() ? 'right' : 'left'); ?>"></i>
                    <?php if(current_locale() === 'ar'): ?>
                        الرجوع للخلف
                    <?php elseif(current_locale() === 'he'): ?>
                        חזור אחורה
                    <?php else: ?>
                        Go Back
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Add subtle rotation effect on mouse move
        document.addEventListener('mousemove', (e) => {
            const errorIcon = document.querySelector('.error-icon');
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            errorIcon.style.transform = `translate(-50%, -50%) translateX(${x}px) translateY(${y}px)`;
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\rashe\Desktop\it-center\laravel-app\resources\views/errors/404.blade.php ENDPATH**/ ?>