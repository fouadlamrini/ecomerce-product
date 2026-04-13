@extends('client.layouts.app')

@section('title', $title)

@section('content')
    <style>
        .title { margin: 0 0 14px; font-size: 28px; font-weight: 800; }
        .grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .thumb-wrap { height: 180px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; }
        .thumb { width: 100%; height: 100%; object-fit: cover; }
        .thumb-empty { color: #9ca3af; font-size: 13px; }
        .content { padding: 12px; }
        .name { margin: 0 0 6px; font-size: 18px; font-weight: 800; }
        .meta { margin: 0 0 8px; color: #6b7280; font-size: 13px; }
        .price { font-size: 18px; font-weight: 900; color: #111827; margin-bottom: 10px; }
        .actions { display: flex; gap: 8px; }
        .btn { border: 0; border-radius: 8px; padding: 8px 10px; cursor: pointer; text-decoration: none; font-weight: 700; font-size: 13px; }
        .btn-detail { background: #eef2ff; color: #3730a3; }
        .btn-cart { background: #f16743; color: #fff; }
        @media (max-width: 1100px) { .grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 840px) { .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 560px) { .grid { grid-template-columns: 1fr; } }
    </style>

    <h1 class="title">{{ $title }}</h1>

    <div class="grid">
        @forelse ($products as $product)
            @php $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
            <div class="card">
                <div class="thumb-wrap">
                    @if ($mainImage)
                        <img class="thumb" src="{{ asset('storage/'.$mainImage->path) }}" alt="{{ $product->name }}">
                    @else
                        <span class="thumb-empty">No image</span>
                    @endif
                </div>
                <div class="content">
                    <h3 class="name">{{ $product->name }}</h3>
                    <p class="meta">{{ $product->category?->name ?? '-' }} / {{ $product->subcategory?->name ?? '-' }}</p>
                    <div class="price">{{ number_format((float) $product->price, 2) }}</div>
                    <div class="actions">
                        <a href="{{ route('client.products.show', $product) }}" class="btn btn-detail">Detail</a>
                        <form method="POST" action="{{ route('client.products.add-to-cart', $product) }}">
                            @csrf
                            <button class="btn btn-cart" type="submit">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>No products available.</p>
        @endforelse
    </div>

    @if ($products->hasPages())
        <div style="margin-top: 16px; display:flex; gap:10px; align-items:center;">
            @if ($products->onFirstPage())
                <span style="color:#9ca3af;">Previous</span>
            @else
                <a href="{{ $products->previousPageUrl() }}" style="text-decoration:none;color:#f16743;">Previous</a>
            @endif
            <span style="color:#6b7280;">Page {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" style="text-decoration:none;color:#f16743;">Next</a>
            @else
                <span style="color:#9ca3af;">Next</span>
            @endif
        </div>
    @endif
@endsection
