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
        .cart-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            padding: 4px;
        }
        .cart-wrap svg { width: 26px; height: 26px; display: block; }
        .cart-badge {
            position: absolute;
            top: -2px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #f16743;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            box-sizing: border-box;
        }
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
                <span class="cart-wrap" title="{{ __('Cart') }} ({{ $cartCount ?? 0 }})" aria-label="{{ __('Shopping cart') }}, {{ $cartCount ?? 0 }} {{ __('items') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 6h15l-1.5 9h-12z" />
                        <circle cx="9" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <circle cx="17" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <path d="M6 6 5 3H2" />
                    </svg>
                    <span class="cart-badge">{{ $cartCount ?? 0 }}</span>
                </span>
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
