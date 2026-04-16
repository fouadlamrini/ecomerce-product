@extends('client.layouts.app')

@section('title', 'My Wishlist')

@section('content')
    <div class="mb-5.5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <span class="mb-2 block text-xs font-bold uppercase tracking-[0.2em] text-orange-600">My Wishlist</span>
            <h1 class="m-0 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">Saved products</h1>
            <p class="mt-2 text-sm text-slate-500">{{ $items->total() }} saved products</p>
        </div>
        <a href="{{ route('client.categories.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
            Continue shopping
        </a>
    </div>

    @if ($items->count())
        <div class="grid grid-cols-[repeat(auto-fill,minmax(240px,1fr))] gap-4 sm:gap-5.5">
            @foreach ($items as $item)
                @php
                    $product = $item->product;
                    $mainImage = $product?->images?->firstWhere('is_primary', true) ?? $product?->images?->first();
                    $categoryText = trim(($product?->category?->name ?? 'Category').' / '.($product?->subcategory?->name ?? 'Collection'));
                @endphp
                @if ($product)
                    <article class="group overflow-hidden rounded-3xl bg-white shadow-xl shadow-slate-200/50 transition-all duration-300 hover:scale-[1.02]">
                        <div class="relative aspect-square overflow-hidden bg-slate-50">
                            @if ($mainImage)
                                <img class="h-full w-full object-cover transition duration-500 group-hover:scale-105" src="{{ asset('storage/'.$mainImage->path) }}" alt="{{ $product->name }}">
                            @else
                                <span class="grid h-full w-full place-items-center bg-linear-to-br from-slate-50 to-slate-100 text-[13px] font-semibold text-slate-400">No image</span>
                            @endif
                            <form class="absolute right-3 top-3 z-3" method="POST" action="{{ route('client.wishlist.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button class="grid h-[34px] w-[34px] place-items-center rounded-full border border-white/50 bg-white/90 text-[#f16743] shadow-lg backdrop-blur transition-all hover:bg-white" type="submit" aria-label="Remove {{ $product->name }} from wishlist">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="p-3.5 pb-4">
                            <span class="inline-flex max-w-full items-center rounded-2xl border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-bold uppercase leading-none tracking-[0.04em] text-slate-500">{{ $categoryText }}</span>
                            <h3 class="mb-1.5 mt-2.5 text-[21px] font-extrabold leading-7 text-slate-900">{{ $product->name }}</h3>
                            <div class="mb-2.5 text-[21px] font-black text-[#FF7F50]">MAD {{ number_format((float) $product->price, 2) }}</div>
                            <div class="mt-2.5 grid grid-cols-2 gap-2">
                                <form method="POST" action="{{ route('client.products.add-to-cart', $product) }}">
                                    @csrf
                                    <button class="w-full rounded-xl border border-orange-500 bg-orange-500 px-3 py-2.5 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition-all hover:bg-orange-600 active:scale-95" type="submit">
                                        Add to cart
                                    </button>
                                </form>
                                <a href="{{ route('client.products.show', $product) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                    Details
                                </a>
                            </div>
                        </div>
                    </article>
                @endif
            @endforeach
        </div>
    @else
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-xl shadow-slate-200/60">
            <p class="text-lg font-semibold text-slate-900">Your wishlist is empty.</p>
            <p class="mt-1.5 text-sm text-slate-500">Save products you love and find them quickly later.</p>
            <a href="{{ route('client.categories.index') }}" class="mt-4 inline-flex items-center rounded-xl bg-[#FF7F50] px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#f26f41]">
                Explore products
            </a>
        </div>
    @endif

    @if ($items->hasPages())
        <div class="mt-5.5 flex items-center gap-2.5 text-sm text-slate-500">
            @if ($items->onFirstPage())
                <span class="text-slate-400">Previous</span>
            @else
                <a class="font-semibold text-[#FF7F50]" href="{{ $items->previousPageUrl() }}">Previous</a>
            @endif
            <span>Page {{ $items->currentPage() }} / {{ $items->lastPage() }}</span>
            @if ($items->hasMorePages())
                <a class="font-semibold text-[#FF7F50]" href="{{ $items->nextPageUrl() }}">Next</a>
            @else
                <span class="text-slate-400">Next</span>
            @endif
        </div>
    @endif
@endsection
