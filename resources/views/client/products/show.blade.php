@extends('client.layouts.app')

@section('title', $product->name)

@section('content')
    <style>
        .wrap { display: grid; grid-template-columns: 480px 1fr; gap: 24px; }
        .gallery { position: relative; }
        .main-img { width: 100%; height: 360px; border-radius: 12px; object-fit: cover; border: 1px solid #e5e7eb; background: #f3f4f6; display: block; }
        .empty { width: 100%; height: 360px; border-radius: 12px; border: 1px dashed #d1d5db; display: flex; align-items: center; justify-content: center; color: #9ca3af; }
        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111827;
        }
        .nav-btn:hover { background: #fff; }
        .nav-btn:disabled { opacity: 0.35; cursor: not-allowed; }
        .nav-prev { left: 10px; }
        .nav-next { right: 10px; }
        .thumbs { margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap; }
        .thumbs button { padding: 0; border: 2px solid transparent; border-radius: 8px; background: none; cursor: pointer; }
        .thumbs button.is-active { border-color: #f16743; }
        .thumbs img { width: 78px; height: 78px; border-radius: 6px; object-fit: cover; border: 1px solid #e5e7eb; display: block; }
        .name { margin: 0 0 8px; font-size: 32px; font-weight: 900; }
        .meta { color: #6b7280; margin-bottom: 12px; }
        .price { font-size: 26px; font-weight: 900; margin-bottom: 10px; }
        .desc { color: #374151; line-height: 1.5; margin-bottom: 14px; }
        .btn { border: 0; border-radius: 8px; padding: 10px 14px; cursor: pointer; background: #f16743; color: #fff; font-weight: 700; }
        @media (max-width: 980px) { .wrap { grid-template-columns: 1fr; } }
    </style>

    <div class="wrap">
        <div>
            @if (count($galleryImages))
                <div class="gallery">
                    <img id="productMainImg" class="main-img" src="{{ $galleryImages[0]['url'] }}" alt="{{ $galleryImages[0]['alt'] }}">
                    @if (count($galleryImages) > 1)
                        <button type="button" id="productImgPrev" class="nav-btn nav-prev" aria-label="Previous image">&#8592;</button>
                        <button type="button" id="productImgNext" class="nav-btn nav-next" aria-label="Next image">&#8594;</button>
                    @endif
                </div>
                <div id="productThumbs" class="thumbs">
                    @foreach ($galleryImages as $idx => $img)
                        <button type="button" class="{{ $idx === 0 ? 'is-active' : '' }}" data-index="{{ $idx }}" aria-label="Image {{ $idx + 1 }}">
                            <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}">
                        </button>
                    @endforeach
                </div>
            @else
                <div class="empty">No image</div>
            @endif
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

    @if (count($galleryImages) > 1)
        <script>
            (function () {
                const gallery = @json($galleryImages);
                const main = document.getElementById('productMainImg');
                const prev = document.getElementById('productImgPrev');
                const next = document.getElementById('productImgNext');
                const thumbs = document.getElementById('productThumbs');
                if (!main || !prev || !next || !thumbs) return;

                let i = 0;
                const n = gallery.length;

                function render() {
                    const cur = gallery[i];
                    main.src = cur.url;
                    main.alt = cur.alt;
                    thumbs.querySelectorAll('button').forEach((btn, idx) => {
                        btn.classList.toggle('is-active', idx === i);
                    });
                }

                prev.addEventListener('click', function () {
                    i = (i - 1 + n) % n;
                    render();
                });
                next.addEventListener('click', function () {
                    i = (i + 1) % n;
                    render();
                });
                thumbs.addEventListener('click', function (e) {
                    const btn = e.target.closest('button[data-index]');
                    if (!btn) return;
                    i = parseInt(btn.getAttribute('data-index'), 10);
                    render();
                });
            })();
        </script>
    @endif
@endsection
