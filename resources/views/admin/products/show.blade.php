@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[420px_1fr]">
        <div>
            <div class="relative flex h-[320px] items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                <img id="mainImage" src="" alt="{{ $product->name }}" class="hidden h-full w-full object-cover">
                <div id="emptyState" class="text-sm text-slate-400">No images available</div>
                <button id="prevBtn" class="absolute left-2.5 top-1/2 inline-flex h-[34px] w-[34px] -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white/90 font-bold" type="button">&#8592;</button>
                <button id="nextBtn" class="absolute right-2.5 top-1/2 inline-flex h-[34px] w-[34px] -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white/90 font-bold" type="button">&#8594;</button>
            </div>
            <div id="thumbs" class="mt-3 flex flex-wrap gap-2"></div>
        </div>

        <div>
            <div class="mb-3"><div class="text-xs text-slate-500">Name</div><div class="text-base">{{ $product->name }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Category</div><div class="text-base">{{ $product->category?->name ?? '-' }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Subcategory</div><div class="text-base">{{ $product->subcategory?->name ?? '-' }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Price</div><div class="text-base">{{ number_format((float) $product->price, 2) }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Stock</div><div class="text-base">{{ $product->stock }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Status</div><div class="text-base">{{ $product->is_active ? 'active' : 'inactive' }}</div></div>
            <div class="mb-4"><div class="text-xs text-slate-500">Description</div><div class="text-base">{{ $product->description ?: '-' }}</div></div>

            <a href="{{ route('admin.products.edit', $product) }}" class="mr-2 inline-block rounded-lg bg-[#f16743] px-3.5 py-2 text-sm font-bold text-white">Edit</a>
            <a href="{{ route('admin.products.index') }}" class="inline-block rounded-lg bg-slate-100 px-3.5 py-2 text-sm text-slate-700">Back</a>
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
                mainImage.classList.add('hidden');
                emptyState.classList.remove('hidden');
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
                return;
            }

            mainImage.src = images[0].url;
            mainImage.alt = images[0].alt || 'main image';
            mainImage.classList.remove('hidden');
            emptyState.classList.add('hidden');
            prevBtn.classList.toggle('hidden', images.length <= 1);
            nextBtn.classList.toggle('hidden', images.length <= 1);

            images.forEach((image, index) => {
                const thumb = document.createElement('button');
                thumb.type = 'button';
                thumb.className = 'h-[72px] w-[72px] overflow-hidden rounded-lg border-2 border-slate-200 bg-white';
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
