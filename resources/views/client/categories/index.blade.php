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

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @forelse ($categories as $category)
            @php
                $cover = $category->bg_image ?: optional($category->products->first()?->images->first())->path;
                $hasSubs = $category->subcategories->isNotEmpty();
                $productsCount = $category->products->count();
                $subCount = $category->subcategories->count();
                $meta = $hasSubs
                    ? ($subCount > 0 ? $subCount.' subcategories' : 'Explore collection')
                    : ($productsCount > 0 ? $productsCount.' products' : 'Explore collection');
                $featuredClass = $loop->index === 0
                    ? 'sm:col-span-2 sm:row-span-2 sm:aspect-[2/1.35] xl:col-span-2'
                    : ($loop->index === 3 ? 'sm:col-span-2 xl:col-span-2' : '');
                $cardBase = 'group relative isolate flex aspect-square items-end overflow-hidden rounded-3xl bg-slate-50 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:scale-[1.02]';
            @endphp
            <a href="{{ route('client.categories.show', $category) }}" class="{{ $cardBase }} {{ $featuredClass }}">
                @if ($cover)
                    <img class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105" src="{{ asset('storage/'.$cover) }}" alt="{{ $category->name }}">
                @else
                    <div class="absolute inset-0 bg-linear-to-br from-slate-100 to-slate-200"></div>
                @endif
                <div class="relative z-10 m-4 w-[calc(100%-2rem)] rounded-2xl border border-white/60 bg-white/70 p-4 backdrop-blur-md">
                    <h2 class="m-0 text-2xl font-extrabold capitalize leading-tight tracking-tight text-slate-900 sm:text-3xl">{{ $category->name }}</h2>
                    <div class="mt-1.5 text-sm font-medium text-slate-600">{{ $meta }}</div>
                </div>
            </a>
        @empty
            <p class="mt-2.5 text-[15px] text-slate-500">No categories available right now.</p>
        @endforelse
    </div>
@endsection
