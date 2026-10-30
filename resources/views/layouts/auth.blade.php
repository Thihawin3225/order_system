<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel')</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #fafafa;
            color: #111827;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding-top: 80px;
        }
        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }
        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 40px;
            box-sizing: border-box;
        }
        h1 {
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        .subtitle {
            color: #6b7280;
            font-size: 14px;
            margin: 0 0 24px 0;
        }
        .field {
            margin-bottom: 16px;
        }
        .field label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
        }
        .field input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .field input:focus {
            outline: none;
            border-color: #111827;
        }
        .btn-primary {
            width: 100%;
            background: #111827;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background: #374151;
        }
        .footer-text {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-top: 24px;
        }
        .footer-text a {
            color: #111827;
            font-weight: 500;
            text-decoration: underline;
        }
        .error {
            color: #dc2626;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .error ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="brand-logo">Laravel</div>
    @yield('content')
</body>
</html>