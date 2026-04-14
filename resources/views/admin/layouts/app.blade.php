<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="m-0 bg-slate-100 text-slate-800">
    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-[260px_1fr]">
        <aside class="border-b border-slate-200 bg-white px-3.5 py-4 lg:border-b-0 lg:border-r">
            <div class="mb-5 text-[22px] font-extrabold text-[#f16743]">Vendora</div>

            <div class="mb-2 mt-4 px-2 text-xs uppercase tracking-[0.05em] text-slate-500">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="mb-1.5 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 font-bold text-emerald-700' : 'text-slate-800 hover:bg-slate-50' }}">
                <span>🏠</span><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="mb-1.5 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs('admin.analytics') ? 'bg-emerald-50 font-bold text-emerald-700' : 'text-slate-800 hover:bg-slate-50' }}">
                <span>📊</span><span>Analytic</span>
            </a>
            <a href="{{ route('admin.shippings.index') }}" class="mb-1.5 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs('admin.shippings.*') ? 'bg-emerald-50 font-bold text-emerald-700' : 'text-slate-800 hover:bg-slate-50' }}">
                <span>🚚</span><span>Shipping</span>
            </a>

            <div class="mb-2 mt-4 px-2 text-xs uppercase tracking-[0.05em] text-slate-500">Product</div>
            <a href="{{ route('admin.products.index') }}" class="mb-1.5 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs('admin.products.*') ? 'bg-emerald-50 font-bold text-emerald-700' : 'text-slate-800 hover:bg-slate-50' }}">
                <span>📦</span><span>Management Product</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="mb-1.5 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-50 font-bold text-emerald-700' : 'text-slate-800 hover:bg-slate-50' }}">
                <span>🗂️</span><span>Management Category</span>
            </a>
            <a href="{{ route('admin.subcategories.index') }}" class="mb-1.5 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs('admin.subcategories.*') ? 'bg-emerald-50 font-bold text-emerald-700' : 'text-slate-800 hover:bg-slate-50' }}">
                <span>🧩</span><span>Management Subcategory</span>
            </a>
            <a href="{{ route('admin.promotions') }}" class="mb-1.5 flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm {{ request()->routeIs('admin.promotions') ? 'bg-emerald-50 font-bold text-emerald-700' : 'text-slate-800 hover:bg-slate-50' }}">
                <span>🏷️</span><span>Discount & Promotion</span>
            </a>

            <div class="mt-4 border-t border-slate-200 pt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-sm font-bold text-red-700 hover:bg-red-100">
                        <span>↩</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="p-4 lg:p-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <h1 class="mb-4 text-2xl font-extrabold">@yield('title', 'Admin Panel')</h1>
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
