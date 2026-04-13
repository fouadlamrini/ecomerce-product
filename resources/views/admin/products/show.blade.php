@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
    <style>
        .wrap { display: grid; grid-template-columns: 420px 1fr; gap: 24px; }
        .gallery-main {
            width: 100%;
            height: 320px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gallery-main img { width: 100%; height: 100%; object-fit: cover; }
        .arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            border: 1px solid #e5e7eb;
            background: #ffffffd9;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .arrow-prev { left: 10px; }
        .arrow-next { right: 10px; }
        .thumbs {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .thumb {
            width: 72px;
            height: 72px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            overflow: hidden;
            cursor: pointer;
            background: #fff;
        }
        .thumb img { width: 100%; height: 100%; object-fit: cover; }
        .empty {
            color: #9ca3af;
            font-size: 14px;
        }
        .item { margin-bottom: 12px; }
        .label { color: #6b7280; font-size: 13px; }
        .value { font-size: 16px; }
        .btn { display: inline-block; margin-right: 8px; text-decoration: none; border-radius: 8px; padding: 8px 14px; }
        .btn-primary { background: #f16743; color: #fff; }
        .btn-muted { background: #efefef; color: #444; }
        @media (max-width: 980px) { .wrap { grid-template-columns: 1fr; } }
    </style>

    <div class="wrap">
        <div>
            <div class="gallery-main">
                <img id="mainImage" src="" alt="{{ $product->name }}" style="display:none;">
                <div id="emptyState" class="empty">No images available</div>
                <button id="prevBtn" class="arrow-btn arrow-prev" type="button">&#8592;</button>
                <button id="nextBtn" class="arrow-btn arrow-next" type="button">&#8594;</button>
            </div>
            <div id="thumbs" class="thumbs"></div>
        </div>

        <div>
            <div class="item">
                <div class="label">Name</div>
                <div class="value">{{ $product->name }}</div>
            </div>
            <div class="item">
                <div class="label">Category</div>
                <div class="value">{{ $product->category?->name ?? '-' }}</div>
            </div>
            <div class="item">
                <div class="label">Subcategory</div>
                <div class="value">{{ $product->subcategory?->name ?? '-' }}</div>
            </div>
            <div class="item">
                <div class="label">Price</div>
                <div class="value">{{ number_format((float) $product->price, 2) }}</div>
            </div>
            <div class="item">
                <div class="label">Stock</div>
                <div class="value">{{ $product->stock }}</div>
            </div>
            <div class="item">
                <div class="label">Status</div>
                <div class="value">{{ $product->is_active ? 'active' : 'inactive' }}</div>
            </div>
            <div class="item">
                <div class="label">Description</div>
                <div class="value">{{ $product->description ?: '-' }}</div>
            </div>

            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-muted">Back</a>
        </div>
    </div>

    <script>
        const images = @json($galleryImages);
        const mainImage = document.getElementById('mainImage');
        const emptyState = document.getElementById('emptyState');
        const thumbs = document.getElementById('thumbs');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        function swapToMain(index) {
            if (index <= 0 || index >= images.length) return;
            const temp = images[0];
            images[0] = images[index];
            images[index] = temp;
            renderGallery();
        }

        function nextImage() {
            if (images.length <= 1) return;
            images.push(images.shift());
            renderGallery();
        }

        function previousImage() {
            if (images.length <= 1) return;
            images.unshift(images.pop());
            renderGallery();
        }

        function renderGallery() {
            thumbs.innerHTML = '';

            if (!images.length) {
                mainImage.style.display = 'none';
                emptyState.style.display = 'block';
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                return;
            }

            mainImage.src = images[0].url;
            mainImage.alt = images[0].alt || 'main image';
            mainImage.style.display = 'block';
            emptyState.style.display = 'none';
            prevBtn.style.display = images.length > 1 ? 'inline-flex' : 'none';
            nextBtn.style.display = images.length > 1 ? 'inline-flex' : 'none';

            images.forEach((image, index) => {
                const thumb = document.createElement('button');
                thumb.type = 'button';
                thumb.className = 'thumb';
                thumb.title = 'Image ' + (index + 1);
                thumb.innerHTML = '<img src="' + image.url + '" alt="' + (image.alt || 'thumbnail') + '">';
                thumb.addEventListener('mouseenter', () => swapToMain(index));
                thumbs.appendChild(thumb);
            });
        }

        prevBtn.addEventListener('click', previousImage);
        nextBtn.addEventListener('click', nextImage);
        renderGallery();
    </script>
@endsection
