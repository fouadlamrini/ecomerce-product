@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
    <style>
        .wrap { display:grid; grid-template-columns:420px 1fr; gap:24px; }
        .main-img { width:100%; height:320px; border-radius:12px; object-fit:cover; border:1px solid #e5e7eb; }
        .thumbs { margin-top:12px; display:flex; gap:8px; flex-wrap:wrap; }
        .thumb { width:72px; height:72px; border-radius:8px; object-fit:cover; border:1px solid #e5e7eb; cursor:pointer; }
        .item { margin-bottom:10px; }
        .label { color:#6b7280; font-size:13px; }
        .value { font-size:16px; }
        .arrow { border:1px solid #ddd; border-radius:999px; width:34px; height:34px; background:#fff; cursor:pointer; }
        .arrows { margin-top:10px; display:flex; gap:8px; }
    </style>

    <div class="wrap">
        <div>
            @php $main = $galleryImages[0]['url'] ?? null; @endphp
            @if ($main)
                <img id="mainImage" class="main-img" src="{{ $main }}" alt="{{ $product->name }}">
            @endif
            <div class="arrows">
                <button id="prevBtn" class="arrow" type="button">&#8592;</button>
                <button id="nextBtn" class="arrow" type="button">&#8594;</button>
            </div>
            <div id="thumbs" class="thumbs"></div>
        </div>
        <div>
            <div class="item"><div class="label">Name</div><div class="value">{{ $product->name }}</div></div>
            <div class="item"><div class="label">Category</div><div class="value">{{ $product->category?->name ?? '-' }}</div></div>
            <div class="item"><div class="label">Subcategory</div><div class="value">{{ $product->subcategory?->name ?? '-' }}</div></div>
            <div class="item"><div class="label">Price</div><div class="value">{{ number_format((float) $product->price, 2) }}</div></div>
            <div class="item"><div class="label">Stock</div><div class="value">{{ $product->stock }}</div></div>
        </div>
    </div>

    <script>
        const images = @json($galleryImages);
        const mainImage = document.getElementById('mainImage');
        const thumbs = document.getElementById('thumbs');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        function render() {
            if (!images.length) return;
            mainImage.src = images[0].url;
            thumbs.innerHTML = '';
            images.forEach((img, index) => {
                const t = document.createElement('img');
                t.className = 'thumb';
                t.src = img.url;
                t.addEventListener('mouseenter', () => {
                    if (index === 0) return;
                    const temp = images[0];
                    images[0] = images[index];
                    images[index] = temp;
                    render();
                });
                thumbs.appendChild(t);
            });
        }

        prevBtn.addEventListener('click', () => {
            if (images.length <= 1) return;
            images.unshift(images.pop());
            render();
        });
        nextBtn.addEventListener('click', () => {
            if (images.length <= 1) return;
            images.push(images.shift());
            render();
        });
        render();
    </script>
@endsection
