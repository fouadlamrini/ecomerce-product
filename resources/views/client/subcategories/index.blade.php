@extends('client.layouts.app')

@section('title', 'Subcategories')

@section('content')
    <style>
        .title { margin: 0 0 14px; font-size: 28px; font-weight: 800; }
        .subtitle { margin: 0 0 16px; color: #6b7280; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .card {
            position: relative;
            min-height: 160px;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            color: #fff;
            border: 1px solid #e5e7eb;
            background: linear-gradient(135deg, #0891b2, #2563eb);
            display: flex;
            align-items: flex-end;
        }
        .card img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .overlay { position: absolute; inset: 0; background: linear-gradient(180deg, transparent 20%, rgba(0,0,0,.55) 100%); }
        .content { position: relative; z-index: 2; padding: 14px; }
        .name { margin: 0; font-size: 28px; font-weight: 900; line-height: 1; text-transform: capitalize; }
        .meta { margin-top: 6px; font-size: 13px; opacity: .95; }
        @media (max-width: 960px) { .grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .grid { grid-template-columns: 1fr; } }
    </style>

    <h1 class="title">Choose Subcategory</h1>
    <p class="subtitle">Category: {{ $category->name }}</p>

    <div class="grid">
        @forelse ($subcategories as $subcategory)
            @php
                $cover = $subcategory->bg_image ?: optional($subcategory->products->first()?->images->first())->path;
            @endphp
            <a href="{{ route('client.subcategories.show', $subcategory) }}" class="card">
                @if ($cover)
                    <img src="{{ asset('storage/'.$cover) }}" alt="{{ $subcategory->name }}">
                @endif
                <div class="overlay"></div>
                <div class="content">
                    <p class="name">{{ $subcategory->name }}</p>
                    <div class="meta">{{ $subcategory->products->count() }} products</div>
                </div>
            </a>
        @empty
            <p>No subcategories available.</p>
        @endforelse
    </div>
@endsection
