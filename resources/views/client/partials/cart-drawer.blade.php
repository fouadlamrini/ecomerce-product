<div id="cartDrawerOverlay" class="cart-drawer-overlay" aria-hidden="true"></div>
<aside id="cartDrawer" class="cart-drawer" aria-hidden="true" aria-labelledby="cartDrawerTitle">
    <div class="cart-drawer-inner">
        <div class="cart-drawer-head">
            <div class="cart-drawer-head-left">
                <div class="cart-drawer-icon-wrap" aria-hidden="true">
                    <svg class="cart-drawer-head-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6h15l-1.5 9h-12z" />
                        <circle cx="9" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <circle cx="17" cy="19" r="1.25" fill="currentColor" stroke="none" />
                        <path d="M6 6 5 3H2" />
                    </svg>
                </div>
                <div>
                    <h2 id="cartDrawerTitle" class="cart-drawer-title">Cart</h2>
                    <p class="cart-drawer-sub">{{ $cartCount }} {{ $cartCount === 1 ? 'item' : 'items' }}</p>
                </div>
            </div>
            <button type="button" class="cart-drawer-close" id="cartDrawerClose" aria-label="Close cart">&times;</button>
        </div>

        <div class="cart-drawer-body" id="cartDrawerBody">
            @include('client.partials.cart-drawer-body', ['cartDrawerItems' => $cartDrawerItems, 'cartDrawerSubtotal' => $cartDrawerSubtotal])
        </div>
    </div>
</aside>
