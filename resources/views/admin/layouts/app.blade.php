<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .admin-fade-in {
            animation: adminFadeIn .35s ease-out both;
        }
        @keyframes adminFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="m-0 bg-slate-100/50 text-slate-800" style="font-family: 'Inter', sans-serif;">
    @php
        $brand = 'text-[#f16743]';
        $linkBase = 'group mb-1.5 flex items-center gap-2.5 rounded-lg border-l-2 border-transparent px-3 py-2.5 text-sm font-medium text-slate-600 transition-all duration-200 hover:bg-slate-100 hover:text-slate-900';
        $linkActive = 'border-l-[#f16743] bg-orange-50 text-[#f16743]';
    @endphp
    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-[260px_1fr]">
        <aside class="flex border-b border-slate-200 bg-slate-50 px-3.5 py-4 lg:border-b-0 lg:border-r">
            <div class="flex min-h-full w-full flex-col">
                <div class="mb-5 text-[22px] font-extrabold text-[#f16743]">Nexus</div>

                <div class="mb-2 mt-2 px-2 text-[11px] uppercase tracking-[0.08em] text-slate-400">Overview</div>
                <a href="{{ route('admin.dashboard') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.dashboard') ? $linkActive : '' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('admin.dashboard') ? $brand : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 9.5 12 3l9 6.5"></path>
                        <path d="M5 10.5V20h14v-9.5"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.shippings.index') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.shippings.*') ? $linkActive : '' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('admin.shippings.*') ? $brand : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 7h11v8H3z"></path>
                        <path d="M14 10h3l4 3v2h-7z"></path>
                        <circle cx="7.5" cy="18" r="1.5"></circle>
                        <circle cx="17.5" cy="18" r="1.5"></circle>
                    </svg>
                    <span>Shipping</span>
                </a>

                <div class="mb-2 mt-4 px-2 text-[11px] uppercase tracking-[0.08em] text-slate-400">Catalog</div>
                <a href="{{ route('admin.products.index') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.products.*') ? $linkActive : '' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('admin.products.*') ? $brand : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 8a2 2 0 0 0-1.1-1.8l-6-3a2 2 0 0 0-1.8 0l-6 3A2 2 0 0 0 5 8v8a2 2 0 0 0 1.1 1.8l6 3a2 2 0 0 0 1.8 0l6-3A2 2 0 0 0 21 16Z"></path>
                        <path d="m5.3 7 6.7 3.4L18.7 7"></path>
                        <path d="M12 21V10.4"></path>
                    </svg>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.categories.*') ? $linkActive : '' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('admin.categories.*') ? $brand : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 5a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    </svg>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.subcategories.index') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.subcategories.*') ? $linkActive : '' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('admin.subcategories.*') ? $brand : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="4" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="4" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="15" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="15" width="7" height="7" rx="1.5"></rect>
                    </svg>
                    <span>Subcategories</span>
                </a>
                <a href="{{ route('admin.promotions') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.promotions') ? $linkActive : '' }}">
                    <svg class="h-4 w-4 {{ request()->routeIs('admin.promotions') ? $brand : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2v20"></path>
                        <path d="M17 5H9a3 3 0 0 0 0 6h6a3 3 0 0 1 0 6H6"></path>
                    </svg>
                    <span>Promotions</span>
                </a>

                <div class="mt-auto border-t border-slate-200 pt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="group inline-flex items-center gap-2 rounded-lg px-2 py-2 text-sm text-slate-500 transition-colors hover:text-[#f16743]">
                            <svg class="h-4 w-4 text-slate-500 group-hover:text-[#f16743]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <path d="m16 17 5-5-5-5"></path>
                                <path d="M21 12H9"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="p-4 lg:p-6">
            <div class="admin-fade-in">
                <h1 class="mb-4 text-2xl font-extrabold text-slate-900">@yield('title', 'Admin Panel')</h1>
                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')
</body>
</html>
