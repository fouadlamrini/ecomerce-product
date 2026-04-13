@extends('client.layouts.app')

@section('title', 'Categories')

@section('content')
    <style>
        .title { margin: 0 0 14px; font-size: 28px; font-weight: 800; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .card {
            position: relative;
            min-height: 170px;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            color: #fff;
            border: 1px solid #e5e7eb;
            background: linear-gradient(135deg, #2563eb, #9333ea);
            display: flex;
            align-items: flex-end;
        }
        .card img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .overlay { position: absolute; inset: 0; background: linear-gradient(180deg, transparent 20%, rgba(0,0,0,.55) 100%); }
        .content { position: relative; z-index: 2; padding: 14px; }
        .name { margin: 0; font-size: 30px; font-weight: 900; line-height: 1; text-transform: capitalize; }
        .meta { margin-top: 6px; font-size: 13px; opacity: .95; }
        @media (max-width: 960px) { .grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .grid { grid-template-columns: 1fr; } }
    </style>

    <h1 class="title">Choose Category</h1>
    <div class="grid">
        @forelse ($categories as $category)
            @php
                $cover = $category->bg_image ?: optional($category->products->first()?->images->first())->path;
                $hasSubs = $category->subcategories->isNotEmpty();
                $meta = $hasSubs ? $category->subcategories->count().' subcategories' : $category->products->count().' products';
            @endphp
            <a href="{{ route('client.categories.show', $category) }}" class="card">
                @if ($cover)
                    <img src="{{ asset('storage/'.$cover) }}" alt="{{ $category->name }}">
                @endif
                <div class="overlay"></div>
                <div class="content">
                    <p class="name">{{ $category->name }}</p>
                    <div class="meta">{{ $meta }}</div>
                </div>
            </a>
        @empty
            <p>No categories available.</p>
        @endforelse
    </div>
@endsection
