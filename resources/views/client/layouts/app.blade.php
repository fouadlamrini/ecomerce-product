<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Shop')</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f6f7fb; color: #111827; }
        .topbar { background: #fff; border-bottom: 1px solid #e5e7eb; }
        .top-inner { max-width: 1200px; margin: 0 auto; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; }
        .brand { color: #f16743; font-size: 24px; font-weight: 800; text-decoration: none; }
        .nav { display: flex; gap: 14px; align-items: center; }
        .nav a { text-decoration: none; color: #374151; font-weight: 600; }
        .badge { background: #111827; color: #fff; border-radius: 999px; padding: 4px 8px; font-size: 12px; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 18px; }
        .alert { margin-bottom: 14px; padding: 12px; border-radius: 10px; font-size: 14px; }
        .alert-success { background: #effff3; border: 1px solid #b6efc1; color: #176029; }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="top-inner">
            <a href="{{ route('client.categories.index') }}" class="brand">Vendora Shop</a>
            <div class="nav">
                <a href="{{ route('client.categories.index') }}">Categories</a>
                <span>Cart <span class="badge">{{ $cartCount ?? 0 }}</span></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button style="border:0;background:#fff1f1;color:#b42318;padding:8px 10px;border-radius:8px;cursor:pointer;">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
