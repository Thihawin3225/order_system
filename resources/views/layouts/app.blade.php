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
            background-color: #ffffff;
            color: #111827;
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            background: #fff;
            z-index: 10;
        }
        .sidebar-header {
            padding: 20px;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid #e5e7eb;
        }
        .sidebar-nav {
            padding: 20px 10px;
            flex: 1;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            margin-bottom: 4px;
            transition: all 0.2s;
        }
        .sidebar-nav a:hover {
            background: #f3f4f6;
            color: #111827;
        }
        .sidebar-nav a.active {
            background: #111827;
            color: white;
        }
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .btn-logout {
            width: 100%;
            padding: 10px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-logout:hover {
            background: #f9fafb;
        }
        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 250px);
        }
        .topbar {
            padding: 16px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            font-size: 14px;
            color: #4b5563;
        }
        .content-wrap {
            padding: 32px;
            width: 100%;
            box-sizing: border-box;
        }
        .breadcrumb {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .page-title h1 {
            font-size: 28px;
            margin: 0 0 8px 0;
            font-weight: 600;
        }
        .page-title p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        .btn-new {
            background: #111827;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        /* Table Design */
        .table-container {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background: #f9fafb;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .btn-action {
            padding: 6px 12px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            color: #111827;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-action:hover {
            background: #f9fafb;
        }
        .btn-delete {
            color: #dc2626;
            border-color: #fca5a5;
        }
        .btn-delete:hover {
            background: #fef2f2;
        }
        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-bar input {
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            width: 300px;
            font-size: 14px;
        }
        .search-bar button {
            padding: 10px 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        
        /* Dashboard Card */
        .dash-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 32px;
            max-width: 600px;
        }
        .dash-info { margin-bottom: 20px; }
        .dash-label { font-size: 12px; color: #6b7280; text-transform: uppercase; margin-bottom: 4px; }
        .dash-value { font-size: 16px; font-weight: 500; }

        /* Form Styles */
        .form-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 32px;
            max-width: 600px;
            background: #fff;
        }
        .form-field { margin-bottom: 20px; }
        .form-field label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .form-field input, .form-field textarea {
            width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;
        }
        .form-field input:focus, .form-field textarea:focus {
            outline: none; border-color: #111827;
        }
        .form-actions { display: flex; gap: 12px; margin-top: 24px; }
        .btn-secondary { background: white; color: #374151; border: 1px solid #d1d5db; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 500; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">Laravel</div>
        <nav class="sidebar-nav">
            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                Orders
            </a>
            <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'active' : '' }}">
                Items
            </a>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" style="margin-top: 20px;">
                Dashboard
            </a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Log out</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            {{ auth()->user()->name }} ▾
        </header>
        <div class="content-wrap">
            @yield('content')
        </div>
    </main>
</body>
</html>