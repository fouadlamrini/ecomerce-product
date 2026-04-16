@extends('client.layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="mb-6">
        <span class="mb-4 block text-xs font-bold uppercase tracking-[0.2em] text-orange-600">Nexus Collections</span>
        <h1 class="m-0 text-5xl font-extrabold tracking-tight text-slate-950 md:text-6xl">Discover premium categories</h1>
        <p class="mt-2.5 max-w-[760px] text-[15px] leading-7 text-slate-500">
            Curated collections with elevated essentials, seasonal drops, and trending picks designed to upgrade your everyday shopping.
        </p>
        <p class="mt-2 text-sm font-medium text-slate-400">{{ $categories->count() }} active categories</p>
    </section>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        @forelse ($categories as $category)
            @php
                $cover = $category->bg_image ?: optional($category->products->first()?->images->first())->path;
                $subCount = $category->subcategories->count();
                $meta = $subCount.' '.\Illuminate\Support\Str::plural('subcategory', $subCount);
                $cardBase = 'relative overflow-hidden rounded-[2.5rem] bg-white shadow-sm border border-slate-100 aspect-[4/5]';
            @endphp
            <a href="{{ route('client.categories.show', $category) }}" class="group block">
                <div class="{{ $cardBase }}">
                    @if ($cover)
                        <img
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            src="{{ asset('storage/'.$cover) }}"
                            alt="{{ $category->name }}"
                        >
                    @else
                        <div class="h-full w-full bg-linear-to-br from-slate-100 via-slate-50 to-slate-200"></div>
                    @endif
                    <div class="absolute inset-0 bg-linear-to-t from-black/15 to-transparent"></div>
                </div>
                <div class="px-1 pt-3">
                    <h2 class="text-base font-bold text-slate-900">{{ $category->name }}</h2>
                    <div class="text-sm text-slate-500">{{ $meta }}</div>
                </div>
            </a>
        @empty
            <p class="mt-2.5 text-[15px] text-slate-500">No categories available right now.</p>
        @endforelse
    </div>
@endsection
