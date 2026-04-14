<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="m-0 bg-slate-50 text-slate-900" style="font-family: Inter, Arial, sans-serif;">
    @php
        $cartCount = $cartCount ?? 0;
        $cartDrawerItems = $cartDrawerItems ?? collect();
        $cartDrawerSubtotal = $cartDrawerSubtotal ?? 0.0;
        $currentUser = auth()->user();
    @endphp
    <header class="sticky top-0 z-50 border-b border-white/30 bg-white/70 backdrop-blur">
        <div class="mx-auto flex max-w-[1240px] items-center justify-between px-4 py-3.5">
            <a href="{{ route('client.categories.index') }}" class="text-2xl font-black uppercase tracking-[0.08em] text-slate-900">Nexus</a>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('client.categories.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Categories</a>
                <button type="button" class="cart-wrap relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white/90 text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" id="cartDrawerOpen" title="{{ __('Cart') }} ({{ $cartCount }})" aria-label="{{ __('Open shopping cart') }}" aria-expanded="false" aria-controls="cartDrawer">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 6h15l-1.5 9h-12z" />
                        <circle cx="9" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <circle cx="17" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <path d="M6 6 5 3H2" />
                    </svg>
                    <span class="cart-badge absolute -right-1 -top-1 min-w-[18px] rounded-full bg-[#f16743] px-1.5 text-center text-[11px] font-bold leading-[18px] text-white">{{ $cartCount }}</span>
                </button>
                <div class="relative">
                    <button type="button" id="userMenuButton" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white/90 text-slate-900 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" aria-haspopup="true" aria-expanded="false" aria-controls="userDropdown" title="Open profile menu">
                        <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.85" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21a8 8 0 0 0-16 0" />
                            <circle cx="12" cy="8" r="4" />
                        </svg>
                    </button>
                    <div class="hidden absolute right-0 top-[calc(100%+10px)] w-[210px] rounded-xl border border-slate-200 bg-white p-2 shadow-xl" id="userDropdown" role="menu">
                        <div class="mb-1.5 border-b border-slate-100 px-2.5 pb-2.5 pt-2">
                            <p class="truncate text-[13px] font-bold text-slate-900">{{ $currentUser?->name ?? 'Client' }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">Account</p>
                        </div>
                        <a href="{{ route('client.profile') }}" class="block rounded-lg px-2.5 py-2 text-left text-[13px] font-semibold text-slate-700 hover:bg-slate-50" role="menuitem">Profile</a>
                        <a href="{{ route('client.orders.index') }}" class="block rounded-lg px-2.5 py-2 text-left text-[13px] font-semibold text-slate-700 hover:bg-slate-50" role="menuitem">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full rounded-lg px-2.5 py-2 text-left text-[13px] font-semibold text-red-700 hover:bg-slate-50" role="menuitem">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto my-7 max-w-[1240px] px-4">
        <div id="cartAjaxError" class="hidden mb-3.5 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-900" role="status"></div>
        @if (session('success'))
            <div class="mb-3.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-3.5 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-900">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-3.5 rounded-xl border border-orange-200 bg-orange-50 px-3 py-2.5 text-sm text-orange-800">
                <ul class="list-disc pl-5">
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
            var menuBtn = document.getElementById('userMenuButton');
            var dropdown = document.getElementById('userDropdown');
            if (!menuBtn || !dropdown) return;

            function setOpen(open) {
                dropdown.classList.toggle('hidden', !open);
                menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            menuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                setOpen(dropdown.classList.contains('hidden'));
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && e.target !== menuBtn) {
                    setOpen(false);
                }
            });
        })();
    </script>
    <script>
        (function () {
            var openBtn = document.getElementById('cartDrawerOpen');
            var closeBtn = document.getElementById('cartDrawerClose');
            var drawer = document.getElementById('cartDrawer');
            var overlay = document.getElementById('cartDrawerOverlay');
            if (!openBtn || !drawer || !overlay) return;

            function setOpen(open) {
                drawer.classList.toggle('translate-x-full', !open);
                overlay.classList.toggle('opacity-0', !open);
                overlay.classList.toggle('invisible', !open);
                drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
                overlay.setAttribute('aria-hidden', open ? 'false' : 'true');
                openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                document.body.classList.toggle('overflow-hidden', open);
                if (open) closeBtn.focus();
            }

            openBtn.addEventListener('click', function () { setOpen(true); });
            closeBtn.addEventListener('click', function () { setOpen(false); });
            overlay.addEventListener('click', function () { setOpen(false); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !drawer.classList.contains('translate-x-full')) setOpen(false);
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
                bar.classList.remove('hidden');
                clearTimeout(bar._hideT);
                bar._hideT = setTimeout(function () { bar.classList.add('hidden'); }, 5000);
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
