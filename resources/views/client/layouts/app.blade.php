<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop')</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f6f7fb; color: #111827; }
        body.cart-drawer-open { overflow: hidden; }
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
            border: 0;
            background: transparent;
            cursor: pointer;
            font: inherit;
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
        .alert-error { background: #fff1f1; border: 1px solid #f5c2c2; color: #7f1d1d; }
        .alert-errors { background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; }
        .alert-errors ul { margin: 0; padding-left: 18px; }

        /* Cart drawer (off-canvas) */
        .cart-drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        .cart-drawer-overlay.is-open {
            opacity: 1;
            visibility: visible;
        }
        .cart-drawer {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: 100%;
            max-width: 400px;
            z-index: 1001;
            background: #fff;
            box-shadow: -8px 0 32px rgba(0, 0, 0, 0.12);
            transform: translateX(100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        .cart-drawer.is-open {
            transform: translateX(0);
        }
        .cart-drawer-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 0;
        }
        .cart-drawer-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 20px 20px 16px;
            border-bottom: 1px solid #eef2f7;
            flex-shrink: 0;
        }
        .cart-drawer-head-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .cart-drawer-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-drawer-head-svg {
            width: 22px;
            height: 22px;
        }
        .cart-drawer-title {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.02em;
        }
        .cart-drawer-sub {
            margin: 2px 0 0;
            font-size: 13px;
            color: #6b7280;
        }
        .cart-drawer-close {
            width: 40px;
            height: 40px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fff;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            color: #374151;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .cart-drawer-close:hover {
            background: #f9fafb;
        }
        .cart-drawer-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            padding: 0 20px 20px;
        }
        .cart-drawer-scroll {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
        }
        .cart-drawer-empty {
            text-align: center;
            padding: 48px 12px 32px;
        }
        .cart-drawer-empty-circle {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #eff6ff;
            color: #93c5fd;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-drawer-empty-circle svg {
            width: 56px;
            height: 56px;
        }
        .cart-drawer-empty-title {
            margin: 0 0 8px;
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }
        .cart-drawer-empty-text {
            margin: 0 0 20px;
            font-size: 14px;
            color: #9ca3af;
            line-height: 1.5;
            max-width: 260px;
            margin-left: auto;
            margin-right: auto;
        }
        .cart-drawer-browse {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
        }
        .cart-drawer-browse:hover {
            background: #1d4ed8;
        }
        .cart-drawer-list {
            list-style: none;
            margin: 0;
            padding: 16px 0 0;
        }
        .cart-drawer-line {
            display: grid;
            grid-template-columns: 52px 1fr auto;
            gap: 10px;
            padding: 14px 0;
            border-bottom: 1px solid #f3f4f6;
            align-items: start;
        }
        .cart-drawer-line-thumb {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            overflow: hidden;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }
        .cart-drawer-line-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .cart-drawer-noimg {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 12px;
            color: #9ca3af;
        }
        .cart-drawer-line-name {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
        }
        .cart-drawer-line-meta {
            margin: 0 0 8px;
            font-size: 12px;
            color: #6b7280;
        }
        .cart-drawer-qty {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .cart-drawer-qbtn {
            width: 30px;
            height: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            padding: 0;
            color: #111827;
        }
        .cart-drawer-qbtn:hover:not(:disabled) {
            background: #f9fafb;
        }
        .cart-drawer-qbtn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }
        .cart-drawer-qval {
            min-width: 22px;
            text-align: center;
            font-weight: 700;
            font-size: 14px;
        }
        .cart-drawer-line-total {
            font-size: 14px;
            font-weight: 800;
            text-align: right;
            white-space: nowrap;
        }
        .cart-drawer-remove-form {
            margin-top: 6px;
        }
        .cart-drawer-remove {
            border: 0;
            background: none;
            color: #b42318;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }
        .cart-drawer-footer {
            flex-shrink: 0;
            padding-top: 16px;
            margin-top: 8px;
            border-top: 1px solid #eef2f7;
        }
        .cart-drawer-subtotal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            margin-bottom: 12px;
        }
        .cart-drawer-subtotal strong {
            font-size: 18px;
        }
        .cart-drawer-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .cart-drawer-btn {
            display: block;
            text-align: center;
            padding: 12px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            border: 1px solid transparent;
        }
        .cart-drawer-btn-primary {
            background: #f16743;
            color: #fff;
        }
        .cart-drawer-btn-primary:hover {
            filter: brightness(0.95);
        }
        .cart-drawer-btn-secondary {
            background: #fff;
            color: #374151;
            border-color: #e5e7eb;
        }
        .cart-drawer-btn-secondary:hover {
            background: #f9fafb;
        }
    </style>
</head>
<body>
    @php
        $cartCount = $cartCount ?? 0;
        $cartDrawerItems = $cartDrawerItems ?? collect();
        $cartDrawerSubtotal = $cartDrawerSubtotal ?? 0.0;
    @endphp
    <header class="topbar">
        <div class="top-inner">
            <a href="{{ route('client.categories.index') }}" class="brand">Vendora Shop</a>
            <div class="nav">
                <a href="{{ route('client.categories.index') }}">Categories</a>
                <button type="button" class="cart-wrap" id="cartDrawerOpen" title="{{ __('Cart') }} ({{ $cartCount }})" aria-label="{{ __('Open shopping cart') }}" aria-expanded="false" aria-controls="cartDrawer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 6h15l-1.5 9h-12z" />
                        <circle cx="9" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <circle cx="17" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <path d="M6 6 5 3H2" />
                    </svg>
                    <span class="cart-badge">{{ $cartCount }}</span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button style="border:0;background:#fff1f1;color:#b42318;padding:8px 10px;border-radius:8px;cursor:pointer;">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        <div id="cartAjaxError" class="alert alert-error" style="display:none;" role="status"></div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-errors">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    @include('client.partials.cart-drawer')

    <script>
        (function () {
            var openBtn = document.getElementById('cartDrawerOpen');
            var closeBtn = document.getElementById('cartDrawerClose');
            var drawer = document.getElementById('cartDrawer');
            var overlay = document.getElementById('cartDrawerOverlay');
            if (!openBtn || !drawer || !overlay) return;

            function setOpen(open) {
                drawer.classList.toggle('is-open', open);
                overlay.classList.toggle('is-open', open);
                drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
                overlay.setAttribute('aria-hidden', open ? 'false' : 'true');
                openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                document.body.classList.toggle('cart-drawer-open', open);
                if (open) closeBtn.focus();
            }

            openBtn.addEventListener('click', function () { setOpen(true); });
            closeBtn.addEventListener('click', function () { setOpen(false); });
            overlay.addEventListener('click', function () { setOpen(false); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && drawer.classList.contains('is-open')) setOpen(false);
            });
        })();
    </script>
    <script>
        (function () {
            function csrfToken() {
                var m = document.querySelector('meta[name="csrf-token"]');
                return m ? m.getAttribute('content') : '';
            }
            function showCartAjaxError(msg) {
                var bar = document.getElementById('cartAjaxError');
                if (!bar) return;
                bar.textContent = msg;
                bar.style.display = 'block';
                clearTimeout(bar._hideT);
                bar._hideT = setTimeout(function () { bar.style.display = 'none'; }, 5000);
            }
            function updateCartFromJson(data) {
                if (!data || !data.ok) return;
                document.querySelectorAll('.cart-badge').forEach(function (el) {
                    el.textContent = data.cartCount;
                });
                var sub = document.querySelector('.cart-drawer-sub');
                if (sub) {
                    sub.textContent = data.cartCount + (data.cartCount === 1 ? ' item' : ' items');
                }
                var drawerBody = document.getElementById('cartDrawerBody');
                if (drawerBody && typeof data.drawerBodyHtml === 'string') {
                    drawerBody.innerHTML = data.drawerBodyHtml;
                }
            }
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-cart-ajax]');
                if (!btn) return;
                e.preventDefault();
                var url = btn.getAttribute('data-cart-url');
                if (!url) return;
                var confirmMsg = btn.getAttribute('data-cart-confirm');
                if (confirmMsg && !window.confirm(confirmMsg)) return;

                var action = btn.getAttribute('data-cart-ajax');
                var method = action === 'remove' ? 'DELETE' : 'POST';
                var headers = {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken()
                };
                var opts = { method: method, headers: headers, credentials: 'same-origin' };
                if (method === 'POST') {
                    headers['Content-Type'] = 'application/x-www-form-urlencoded';
                    opts.body = '_token=' + encodeURIComponent(csrfToken());
                }

                btn.disabled = true;
                fetch(url, opts)
                    .then(function (r) {
                        var ct = r.headers.get('Content-Type') || '';
                        if (ct.indexOf('application/json') !== -1) {
                            return r.json().then(function (data) {
                                return { ok: r.ok, status: r.status, data: data };
                            });
                        }
                        return r.text().then(function () {
                            return { ok: false, status: r.status, data: { ok: false, message: 'Unexpected response.' } };
                        });
                    })
                    .then(function (res) {
                        btn.disabled = false;
                        if (res.status === 422 && res.data && res.data.message) {
                            showCartAjaxError(res.data.message);
                            return;
                        }
                        if (res.status === 403 && res.data && res.data.message) {
                            showCartAjaxError(res.data.message);
                            return;
                        }
                        if (!res.ok || !res.data || !res.data.ok) {
                            showCartAjaxError((res.data && res.data.message) ? res.data.message : 'Something went wrong.');
                            return;
                        }
                        updateCartFromJson(res.data);
                    })
                    .catch(function () {
                        btn.disabled = false;
                        showCartAjaxError('Network error.');
                    });
            });
        })();
    </script>
</body>
</html>
