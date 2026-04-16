@extends('client.layouts.app')

@section('title', $title)

@section('content')
    <h1 class="mb-5.5 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">{{ $title }}</h1>

    @if (($showSkeleton ?? false) === true)
        <div class="grid grid-cols-[repeat(auto-fill,minmax(240px,1fr))] gap-4 sm:gap-5.5" aria-label="Loading products">
            @for ($i = 0; $i < 8; $i++)
                @include('client.products.partials.card-skeleton')
            @endfor
        </div>
    @else
        <div class="grid grid-cols-[repeat(auto-fill,minmax(240px,1fr))] gap-4 sm:gap-5.5">
            @forelse ($products as $product)
                @php
                    $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                    $categoryText = trim(($product->category?->name ?? 'Category').' / '.($product->subcategory?->name ?? 'Collection'));
                @endphp
                <article class="group overflow-hidden rounded-3xl bg-white shadow-xl shadow-slate-200/50 transition-all duration-300 hover:scale-[1.02]">
                    <div class="relative aspect-square overflow-hidden bg-slate-50">
                        @if ($mainImage)
                            <img class="h-full w-full object-cover transition duration-500 group-hover:scale-105" src="{{ asset('storage/'.$mainImage->path) }}" alt="{{ $product->name }}">
                        @else
                            <span class="grid h-full w-full place-items-center bg-linear-to-br from-slate-50 to-slate-100 text-[13px] font-semibold text-slate-400">No image</span>
                        @endif
                        <button class="absolute right-3 top-3 z-3 grid h-[34px] w-[34px] place-items-center rounded-full border border-white/50 bg-white/90 text-slate-700 shadow-lg backdrop-blur transition-all hover:bg-white" type="button" aria-label="Add {{ $product->name }} to wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.716-4.35-9.193-8.151C.7 9.785 2.04 6 5.88 6c2.033 0 3.13 1.18 4.12 2.55C10.99 7.18 12.087 6 14.12 6c3.84 0 5.18 3.785 3.073 6.849C18.716 16.65 12 21 12 21z" />
                            </svg>
                        </button>
                        <a href="{{ route('client.products.show', $product) }}" class="absolute right-[52px] top-3 z-3 grid h-[34px] w-[34px] -translate-y-1 place-items-center rounded-full border border-white/50 bg-white/90 text-slate-700 opacity-0 shadow-lg backdrop-blur transition group-hover:translate-y-0 group-hover:opacity-100" aria-label="View details for {{ $product->name }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </a>
                    </div>
                    <div class="p-3.5 pb-4">
                        <span class="inline-flex max-w-full items-center rounded-2xl border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-bold uppercase leading-none tracking-[0.04em] text-slate-500">{{ $categoryText }}</span>
                        <h3 class="mb-1.5 mt-2.5 text-[21px] font-extrabold leading-7 text-slate-900">{{ $product->name }}</h3>
                        <div class="mb-2.5 text-[21px] font-black text-[#FF7F50]">MAD {{ number_format((float) $product->price, 2) }}</div>
                        <div class="max-h-0 translate-y-2 overflow-hidden opacity-0 transition group-hover:max-h-20 group-hover:translate-y-0 group-hover:opacity-100">
                            <form method="POST" action="{{ route('client.products.add-to-cart', $product) }}">
                                @csrf
                                <button class="w-full rounded-xl bg-[#FF7F50] px-3 py-2.5 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition-all hover:bg-[#E66D43] active:scale-95" type="submit">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-slate-500">No products available right now.</p>
            @endforelse
        </div>
    @endif

    @if ($products->hasPages())
        <div class="mt-5.5 flex items-center gap-2.5 text-sm text-slate-500">
            @if ($products->onFirstPage())
                <span class="text-slate-400">Previous</span>
            @else
                <a class="font-semibold text-[#FF7F50]" href="{{ $products->previousPageUrl() }}">Previous</a>
            @endif
            <span>Page {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
            @if ($products->hasMorePages())
                <a class="font-semibold text-[#FF7F50]" href="{{ $products->nextPageUrl() }}">Next</a>
            @else
                <span class="text-slate-400">Next</span>
            @endif
        </div>
    @endif
@endsection
