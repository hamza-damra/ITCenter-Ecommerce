<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Missing - IT Center</title>
    <link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            max-width: 600px;
            text-align: center;
        }

        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #cbd5e1;
            line-height: 1;
            margin-bottom: 20px;
        }

        .error-icon {
            font-size: 80px;
            color: #f59e0b;
            margin-bottom: 30px;
        }

        .error-title {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .error-message {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">503</div>
        <div class="error-icon">
            <i class="fas fa-database"></i>
        </div>
        <h1 class="error-title">Database Schema Missing</h1>
        <p class="error-message">
            The database server is reachable, but the target database schema does not exist.
            Please use Bootstrap Mode to restore your database.
        </p>
        <div>
            <a href="{{ route('admin.bootstrap.login') }}" class="btn">
                <i class="fas fa-wrench"></i> Open Bootstrap Mode
            </a>
        </div>
    </div>
</body>

</html>
