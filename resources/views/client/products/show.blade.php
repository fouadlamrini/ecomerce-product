@extends('client.layouts.app')

@section('title', $product->name)

@section('content')
    <style>
        .wrap { display: grid; grid-template-columns: 480px 1fr; gap: 24px; }
        .main-img { width: 100%; height: 360px; border-radius: 12px; object-fit: cover; border: 1px solid #e5e7eb; background: #f3f4f6; }
        .empty { width: 100%; height: 360px; border-radius: 12px; border: 1px dashed #d1d5db; display: flex; align-items: center; justify-content: center; color: #9ca3af; }
        .thumbs { margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap; }
        .thumbs img { width: 78px; height: 78px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb; }
        .name { margin: 0 0 8px; font-size: 32px; font-weight: 900; }
        .meta { color: #6b7280; margin-bottom: 12px; }
        .price { font-size: 26px; font-weight: 900; margin-bottom: 10px; }
        .desc { color: #374151; line-height: 1.5; margin-bottom: 14px; }
        .btn { border: 0; border-radius: 8px; padding: 10px 14px; cursor: pointer; background: #f16743; color: #fff; font-weight: 700; }
        @media (max-width: 980px) { .wrap { grid-template-columns: 1fr; } }
    </style>

    <div class="wrap">
        <div>
            @php $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
            @if ($mainImage)
                <img class="main-img" src="{{ asset('storage/'.$mainImage->path) }}" alt="{{ $product->name }}">
            @else
                <div class="empty">No image</div>
            @endif

            <div class="thumbs">
                @foreach ($product->images as $image)
                    <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}">
                @endforeach
            </div>
        </div>
        <div>
            <h1 class="name">{{ $product->name }}</h1>
            <div class="meta">{{ $product->category?->name ?? '-' }} / {{ $product->subcategory?->name ?? '-' }}</div>
            <div class="price">{{ number_format((float) $product->price, 2) }}</div>
            <div class="desc">{{ $product->description ?: 'No description available.' }}</div>
            <form method="POST" action="{{ route('client.products.add-to-cart', $product) }}">
                @csrf
                <button class="btn" type="submit">Add to cart</button>
            </form>
        </div>
    </div>
@endsection
