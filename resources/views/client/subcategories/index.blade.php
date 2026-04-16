@extends('client.layouts.app')

@section('title', 'Subcategories')

@section('content')
    <nav class="mb-3.5 flex items-center gap-2 text-[13px] text-slate-400" aria-label="Breadcrumb">
        <a class="transition hover:text-slate-500" href="{{ route('client.categories.index') }}">Categories</a>
        <span class="opacity-80">/</span>
        <span>{{ $category->name }}</span>
    </nav>

    <h1 class="m-0 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">Choose Subcategory</h1>
    <p class="mb-7 mt-2.5 max-w-[720px] text-[15px] leading-7 text-slate-500">Explore curated picks inside <strong>{{ $category->name }}</strong> and discover the collection that matches your style.</p>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(260px,320px))] justify-center gap-5.5">
        @forelse ($subcategories as $subcategory)
            @php
                $cover = $subcategory->bg_image ?: optional($subcategory->products->first()?->images->first())->path;
                $productsCount = $subcategory->products->count();
            @endphp
            <a href="{{ route('client.subcategories.show', $subcategory) }}" class="group relative flex min-h-[230px] items-end overflow-hidden rounded-3xl bg-slate-50 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:scale-[1.02]">
                @if ($cover)
                    <img class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105" src="{{ asset('storage/'.$cover) }}" alt="{{ $subcategory->name }}">
                @else
                    <div class="absolute inset-0 bg-linear-to-br from-slate-100 to-slate-200"></div>
                @endif
                <div class="relative z-10 m-3.5 flex w-[calc(100%-28px)] items-end justify-between gap-2 rounded-2xl border border-white/60 bg-white/70 p-3.5 backdrop-blur-md">
                    <div>
                        <h2 class="m-0 text-[27px] font-extrabold capitalize leading-tight tracking-tight text-slate-900">{{ $subcategory->name }}</h2>
                        <div class="mt-1.5 text-[13px] text-slate-600">{{ $productsCount > 0 ? $productsCount.' products' : 'Explore collection' }}</div>
                    </div>
                    <svg class="h-[26px] w-[26px] shrink-0 -translate-x-2 text-slate-600 opacity-0 transition group-hover:translate-x-0 group-hover:opacity-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6l8 6-8 6" />
                    </svg>
                </div>
            </a>
        @empty
            <p class="mt-1.5 text-center text-[15px] text-slate-500">No subcategories available right now.</p>
        @endforelse
    </div>
@endsection
