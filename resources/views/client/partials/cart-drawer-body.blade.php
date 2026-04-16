@if ($cartDrawerItems->isEmpty())
    <div class="px-3 pb-8 pt-12 text-center">
        <div class="mx-auto mb-5 flex h-[120px] w-[120px] items-center justify-center rounded-full bg-orange-50 text-orange-300" aria-hidden="true">
            <svg class="h-14 w-14" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 6h15l-1.5 9h-12z" />
                <circle cx="9" cy="19" r="1.25" fill="currentColor" stroke="none" />
                <circle cx="17" cy="19" r="1.25" fill="currentColor" stroke="none" />
                <path d="M6 6 5 3H2" />
            </svg>
        </div>
        <p class="mb-2 text-lg font-extrabold text-slate-900">Cart Empty</p>
        <p class="mx-auto mb-5 max-w-[260px] text-sm leading-6 text-slate-400">Discover our products and start your order.</p>
        <a href="{{ route('client.categories.index') }}" class="inline-block rounded-xl bg-[#FF7F50] px-4.5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition-all hover:bg-[#E66D43] active:scale-95">Continue shopping</a>
    </div>
@else
    <div class="min-h-0 flex-1 overflow-y-auto">
        <ul class="m-0 list-none px-0 pb-0 pt-4">
            @foreach ($cartDrawerItems as $item)
                @php
                    $product = $item->product;
                    $img = $product?->images->firstWhere('is_primary', true) ?? $product?->images->first();
                    $stock = (int) ($product?->stock ?? 0);
                    $atMax = $item->quantity >= $stock;
                @endphp
                <li class="grid grid-cols-[52px_1fr_auto] items-start gap-2.5 border-b border-slate-100 py-3.5">
                    <div class="h-[52px] w-[52px] overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                        @if ($img)
                            <img class="block h-full w-full object-cover" src="{{ asset('storage/'.$img->path) }}" alt="">
                        @else
                            <span class="flex h-full items-center justify-center text-xs text-slate-400">—</span>
                        @endif
                    </div>
                    <div>
                        <p class="mb-1 text-sm font-bold leading-5 text-slate-900">{{ $product->name }}</p>
                        <p class="mb-2 text-xs text-slate-500">{{ number_format((float) $item->unit_price, 2) }} each · max {{ $stock }}</p>
                        <div class="inline-flex items-center gap-1">
                            <button type="button" class="h-[30px] w-[30px] rounded-lg border border-slate-200 bg-white p-0 text-base text-slate-900 transition-all hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40" data-cart-ajax="decrement" data-cart-url="{{ route('client.cart.items.decrement', $item) }}" aria-label="Decrease">&minus;</button>
                            <span class="min-w-[22px] text-center text-sm font-bold">{{ $item->quantity }}</span>
                            <button type="button" class="h-[30px] w-[30px] rounded-lg border border-slate-200 bg-white p-0 text-base text-slate-900 transition-all hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40" data-cart-ajax="increment" data-cart-url="{{ route('client.cart.items.increment', $item) }}" @if ($atMax) disabled title="Maximum stock reached" @endif aria-label="Increase">&plus;</button>
                        </div>
                    </div>
                    <div class="whitespace-nowrap text-right text-sm font-extrabold">
                        {{ number_format((float) $item->unit_price * $item->quantity, 2) }}
                        <div class="mt-1.5">
                            <button type="button" class="p-0 text-xs font-semibold text-red-700 underline" data-cart-ajax="remove" data-cart-url="{{ route('client.cart.items.destroy', $item) }}" data-cart-confirm="Remove this item?">Remove</button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="mt-2 border-t border-slate-100 pt-4">
        <div class="mb-3 flex items-center justify-between text-[15px]">
            <span>Subtotal</span>
            <strong class="text-lg">{{ number_format($cartDrawerSubtotal, 2) }}</strong>
        </div>
        <div class="flex flex-col gap-2">
            <a href="{{ route('client.categories.index') }}" class="block rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-center text-sm font-semibold text-slate-700 transition-all hover:bg-slate-50">Continue shopping</a>
            <a href="{{ route('client.checkout') }}" class="block rounded-xl bg-[#FF7F50] px-3.5 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition-all hover:bg-[#E66D43] active:scale-95">Checkout</a>
        </div>
    </div>
@endif
