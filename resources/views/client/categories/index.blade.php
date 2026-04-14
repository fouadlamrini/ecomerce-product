@extends('client.layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="mb-6">
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-[#f16743]">Nexus Collections</p>
        <h1 class="m-0 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">Discover premium categories</h1>
        <p class="mt-2.5 max-w-[760px] text-[15px] leading-7 text-slate-500">
            Curated collections with elevated essentials, seasonal drops, and trending picks designed to upgrade your everyday shopping.
        </p>
    </section>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 xl:grid-cols-4">
        @forelse ($categories as $category)
            @php
                $cover = $category->bg_image ?: optional($category->products->first()?->images->first())->path;
                $hasSubs = $category->subcategories->isNotEmpty();
                $productsCount = $category->products->count();
                $subCount = $category->subcategories->count();
                $meta = $hasSubs
                    ? ($subCount > 0 ? $subCount.' subcategories' : 'Explore collection')
                    : ($productsCount > 0 ? $productsCount.' products' : 'Explore collection');
                $featured = $loop->index < 2;
                $cardBase = 'group relative isolate flex aspect-square items-end overflow-hidden rounded-3xl border border-white/10 text-white shadow-xl transition hover:-translate-y-1 hover:scale-[1.02] hover:shadow-2xl';
                $featuredClass = $featured ? 'sm:col-span-2 sm:aspect-[2/1] xl:col-span-2 xl:aspect-[2/1]' : '';
                $bgFallback = $cover ? '' : 'bg-linear-to-br from-slate-900 to-slate-800';
            @endphp
            <a href="{{ route('client.categories.show', $category) }}" class="{{ $cardBase }} {{ $featuredClass }} {{ $bgFallback }}">
                @if ($cover)
                    <img class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-110" src="{{ asset('storage/'.$cover) }}" alt="{{ $category->name }}">
                @endif
                <div class="absolute inset-0 z-1 bg-linear-to-b from-black/10 via-black/20 to-black/60"></div>
                <div class="pointer-events-none absolute inset-[-120%_-40%] z-2 rotate-12 bg-linear-to-r from-transparent via-white/20 to-transparent transition duration-700 group-hover:translate-x-[78%]"></div>
                <div class="relative z-3 p-5">
                    <h2 class="m-0 text-3xl font-extrabold capitalize leading-tight tracking-tight sm:text-4xl">{{ $category->name }}</h2>
                    <div class="mt-2 text-sm font-medium text-white/90">{{ $meta }}</div>
                </div>
            </a>
        @empty
            <p class="mt-2.5 text-[15px] text-slate-500">No categories available right now.</p>
        @endforelse
    </div>
@endsection
