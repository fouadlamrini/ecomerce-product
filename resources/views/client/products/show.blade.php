@extends('client.layouts.app')

@section('title', $product->name)

@section('content')
    <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:gap-8">
        <div class="rounded-[28px] border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/5">
            @if (count($galleryImages))
                <div class="relative">
                    <img id="productMainImg" class="block aspect-square w-full cursor-zoom-in rounded-[20px] border border-slate-200 bg-slate-50 object-cover transition hover:scale-[1.015]" src="{{ $galleryImages[0]['url'] }}" alt="{{ $galleryImages[0]['alt'] }}">
                    @if (count($galleryImages) > 1)
                        <button type="button" id="productImgPrev" class="absolute left-2.5 top-1/2 inline-flex h-[42px] w-[42px] -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white/95 text-lg text-slate-900 shadow-lg hover:scale-105 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Previous image">&#8592;</button>
                        <button type="button" id="productImgNext" class="absolute right-2.5 top-1/2 inline-flex h-[42px] w-[42px] -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white/95 text-lg text-slate-900 shadow-lg hover:scale-105 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Next image">&#8594;</button>
                    @endif
                </div>
                <div id="productThumbs" class="mt-3 grid grid-cols-[repeat(auto-fill,minmax(70px,1fr))] gap-2">
                    @foreach ($galleryImages as $idx => $img)
                        <button type="button" class="rounded-xl border-2 {{ $idx === 0 ? 'border-[#f16743]' : 'border-transparent' }}" data-index="{{ $idx }}" aria-label="Image {{ $idx + 1 }}">
                            <img class="block aspect-square w-full rounded-[10px] border border-slate-200 object-cover" src="{{ $img['url'] }}" alt="{{ $img['alt'] }}">
                        </button>
                    @endforeach
                </div>
            @else
                <div class="flex aspect-square w-full items-center justify-center rounded-[20px] border border-dashed border-slate-300 bg-slate-50 text-slate-400">No image</div>
            @endif
        </div>
        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 lg:p-7">
            <div class="mb-2.5 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs font-bold uppercase tracking-[0.06em] text-slate-500">{{ $product->category?->name ?? 'Category' }} / {{ $product->subcategory?->name ?? 'Collection' }}</div>
            <h1 class="mb-3 text-4xl font-black leading-tight tracking-tight text-slate-900 sm:text-5xl">{{ $product->name }}</h1>
            <div class="mb-3.5 text-4xl font-black tracking-tight text-[#f16743] sm:text-5xl">MAD {{ number_format((float) $product->price, 2) }}</div>
            <div class="mb-5.5 text-[15px] leading-7 text-slate-600">{{ $product->description ?: 'No description available.' }}</div>
            @php $stock = (int) $product->stock; @endphp
            <form method="POST" action="{{ route('client.products.add-to-cart', $product) }}">
                @csrf
                <div class="grid grid-cols-1 items-center gap-2.5 sm:grid-cols-[124px_1fr_48px]">
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-1" aria-label="Quantity selector">
                        <button type="button" id="qtyMinus" class="h-[34px] w-[34px] rounded-lg bg-slate-100 text-lg font-bold text-slate-900 disabled:cursor-not-allowed disabled:opacity-45" aria-label="Decrease quantity" {{ $stock < 1 ? 'disabled' : '' }}>-</button>
                        <span class="min-w-6 text-center text-[15px] font-extrabold text-slate-900" id="qtyValue">1</span>
                        <button type="button" id="qtyPlus" class="h-[34px] w-[34px] rounded-lg bg-slate-100 text-lg font-bold text-slate-900 disabled:cursor-not-allowed disabled:opacity-45" aria-label="Increase quantity" {{ $stock < 1 ? 'disabled' : '' }}>+</button>
                    </div>
                    <input type="hidden" name="quantity" id="qtyInput" value="1">
                    <button class="rounded-2xl bg-linear-to-b from-[#ff996f] via-[#ff7f50] to-[#f16743] px-4 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-[#f16743]/25 disabled:cursor-not-allowed disabled:opacity-55 disabled:shadow-none" type="submit" {{ $stock < 1 ? 'disabled' : '' }}>{{ $stock < 1 ? 'Out of stock' : 'Add to cart' }}</button>
                    <button class="inline-flex h-12 w-full items-center justify-center rounded-2xl border border-slate-200 bg-white text-[#f16743] sm:w-12" type="button" aria-label="Add to wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.716-4.35-9.193-8.151C.7 9.785 2.04 6 5.88 6c2.033 0 3.13 1.18 4.12 2.55C10.99 7.18 12.087 6 14.12 6c3.84 0 5.18 3.785 3.073 6.849C18.716 16.65 12 21 12 21z" />
                        </svg>
                    </button>
                </div>
            </form>
            <div class="mt-4 grid gap-2.5">
                <div class="flex items-center gap-2.5 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-[13px] font-semibold text-slate-700">
                    <svg class="h-4 w-4 shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l5 5L20 7" />
                    </svg>
                    Free Shipping on eligible orders
                </div>
                <div class="flex items-center gap-2.5 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-[13px] font-semibold text-slate-700">
                    <svg class="h-4 w-4 shrink-0 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                    </svg>
                    Secure Checkout protected by encryption
                </div>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-1200 hidden items-center justify-center bg-slate-950/80 p-5" id="productLightbox" aria-hidden="true">
        <button type="button" class="absolute right-5 top-5 inline-flex h-[42px] w-[42px] items-center justify-center rounded-full border border-white/50 bg-white/20 text-2xl text-white" id="lightboxClose" aria-label="Close image preview">&times;</button>
        <img id="lightboxImage" class="max-h-[88vh] max-w-[min(94vw,1080px)] rounded-2xl border border-white/30 shadow-2xl" src="" alt="">
    </div>

    <script>
        (function () {
            const qtyInput = document.getElementById('qtyInput');
            const qtyValue = document.getElementById('qtyValue');
            const minus = document.getElementById('qtyMinus');
            const plus = document.getElementById('qtyPlus');
            if (!qtyInput || !qtyValue || !minus || !plus) return;
            const maxStock = Math.max(1, @json((int) $product->stock));
            let qty = 1;

            function renderQty() {
                qtyValue.textContent = String(qty);
                qtyInput.value = String(qty);
                minus.disabled = qty <= 1;
                plus.disabled = qty >= maxStock;
            }

            minus.addEventListener('click', function () {
                qty = Math.max(1, qty - 1);
                renderQty();
            });
            plus.addEventListener('click', function () {
                qty = Math.min(maxStock, qty + 1);
                renderQty();
            });
            renderQty();
        })();
    </script>

    @if (count($galleryImages))
        <script>
            (function () {
                const gallery = @json($galleryImages);
                const main = document.getElementById('productMainImg');
                const prev = document.getElementById('productImgPrev');
                const next = document.getElementById('productImgNext');
                const thumbs = document.getElementById('productThumbs');
                const lightbox = document.getElementById('productLightbox');
                const lightboxImage = document.getElementById('lightboxImage');
                const lightboxClose = document.getElementById('lightboxClose');
                if (!main) return;

                let i = 0;
                const n = gallery.length;

                function render() {
                    const cur = gallery[i];
                    main.src = cur.url;
                    main.alt = cur.alt;
                    thumbs.querySelectorAll('button').forEach((btn, idx) => {
                        btn.classList.toggle('border-[#f16743]', idx === i);
                        btn.classList.toggle('border-transparent', idx !== i);
                    });
                }

                main.addEventListener('click', function () {
                    if (!lightbox || !lightboxImage) return;
                    lightboxImage.src = main.src;
                    lightboxImage.alt = main.alt;
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                    lightbox.setAttribute('aria-hidden', 'false');
                });

                lightboxClose?.addEventListener('click', function () {
                    lightbox?.classList.remove('flex');
                    lightbox?.classList.add('hidden');
                    lightbox?.setAttribute('aria-hidden', 'true');
                });

                lightbox?.addEventListener('click', function (e) {
                    if (e.target === lightbox) {
                        lightbox.classList.remove('flex');
                        lightbox.classList.add('hidden');
                        lightbox.setAttribute('aria-hidden', 'true');
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        lightbox?.classList.remove('flex');
                        lightbox?.classList.add('hidden');
                        lightbox?.setAttribute('aria-hidden', 'true');
                    }
                });

                if (prev && next && thumbs) {
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
                }
            })();
        </script>
    @endif
@endsection
