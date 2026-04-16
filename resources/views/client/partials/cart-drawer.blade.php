<div id="cartDrawerOverlay" class="fixed inset-0 z-1000 invisible bg-black/45 opacity-0 transition" aria-hidden="true"></div>
<aside id="cartDrawer" class="fixed right-0 top-0 z-1001 flex h-full w-full max-w-[400px] translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300" aria-hidden="true" aria-labelledby="cartDrawerTitle">
    <div class="flex h-full min-h-0 flex-col">
        <div class="flex shrink-0 items-start justify-between border-b border-slate-100 px-5 pb-4 pt-5">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-[#FF7F50]" aria-hidden="true">
                    <svg class="h-[22px] w-[22px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6h15l-1.5 9h-12z" />
                        <circle cx="9" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <circle cx="17" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <path d="M6 6 5 3H2" />
                    </svg>
                </div>
                <div>
                    <h2 id="cartDrawerTitle" class="m-0 text-xl font-extrabold text-slate-900">Cart</h2>
                    <p class="cart-drawer-sub mt-0.5 text-sm text-slate-500">{{ $cartCount }} {{ $cartCount === 1 ? 'item' : 'items' }}</p>
                </div>
            </div>
            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-2xl text-slate-700 shadow-xl shadow-slate-200/40 transition-all hover:bg-slate-50" id="cartDrawerClose" aria-label="Close cart">&times;</button>
        </div>

        <div class="flex min-h-0 flex-1 flex-col px-5 pb-5" id="cartDrawerBody">
            @include('client.partials.cart-drawer-body', ['cartDrawerItems' => $cartDrawerItems, 'cartDrawerSubtotal' => $cartDrawerSubtotal])
        </div>
    </div>
</aside>
