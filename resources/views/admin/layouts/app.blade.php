<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>
    <style>
        :root {
            --bg: #f4f6f8;
            --sidebar-bg: #ffffff;
            --content-bg: #ffffff;
            --line: #e8edf2;
            --text: #1f2937;
            --muted: #6b7280;
            --active-bg: #e7f8ec;
            --active-text: #1f7a42;
            --primary: #f16743;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 260px 1fr;
        }
        .sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid var(--line);
            padding: 18px 14px;
        }
        .brand {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 20px;
            color: var(--primary);
        }
        .menu-title {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--muted);
            margin: 18px 8px 8px;
            letter-spacing: 0.05em;
        }
        .menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text);
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 6px;
            font-size: 14px;
            transition: .2s ease;
        }
        .menu-link:hover { background: #f8fafc; }
        .menu-link.active {
            background: var(--active-bg);
            color: var(--active-text);
            font-weight: 700;
        }
        .main {
            padding: 24px;
        }
        .content-card {
            background: var(--content-bg);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 20px;
        }
        .top-title {
            margin: 0 0 18px;
            font-size: 22px;
            font-weight: 800;
        }
        @media (max-width: 900px) {
            .admin-shell { grid-template-columns: 1fr; }
            .sidebar { border-right: 0; border-bottom: 1px solid var(--line); }
        }
    </style>
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">Vendora</div>

            <div class="menu-title">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="menu-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <span>📊</span><span>Analytic</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="menu-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <span>🧾</span><span>Order</span>
            </a>

            <div class="menu-title">Product</div>
            <a href="{{ route('admin.products') }}" class="menu-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <span>📦</span><span>Management Product</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span>🗂️</span><span>Management Category</span>
            </a>
            <a href="{{ route('admin.promotions') }}" class="menu-link {{ request()->routeIs('admin.promotions') ? 'active' : '' }}">
                <span>🏷️</span><span>Discount & Promotion</span>
            </a>
        </aside>

        <main class="main">
            <div class="content-card">
                <h1 class="top-title">@yield('title', 'Admin Panel')</h1>
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
