@if ($cartDrawerItems->isEmpty())
    <div class="cart-drawer-empty">
        <div class="cart-drawer-empty-circle" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 6h15l-1.5 9h-12z" />
                <circle cx="9" cy="19" r="1.25" fill="currentColor" stroke="none" />
                <circle cx="17" cy="19" r="1.25" fill="currentColor" stroke="none" />
                <path d="M6 6 5 3H2" />
            </svg>
        </div>
        <p class="cart-drawer-empty-title">Cart Empty</p>
        <p class="cart-drawer-empty-text">Discover our products and start your order.</p>
        <a href="{{ route('client.categories.index') }}" class="cart-drawer-browse">Continue shopping</a>
    </div>
@else
    <div class="cart-drawer-scroll">
        <ul class="cart-drawer-list">
            @foreach ($cartDrawerItems as $item)
                @php
                    $product = $item->product;
                    $img = $product?->images->firstWhere('is_primary', true) ?? $product?->images->first();
                    $stock = (int) ($product?->stock ?? 0);
                    $atMax = $item->quantity >= $stock;
                @endphp
                <li class="cart-drawer-line">
                    <div class="cart-drawer-line-thumb">
                        @if ($img)
                            <img src="{{ asset('storage/'.$img->path) }}" alt="">
                        @else
                            <span class="cart-drawer-noimg">—</span>
                        @endif
                    </div>
                    <div class="cart-drawer-line-main">
                        <p class="cart-drawer-line-name">{{ $product->name }}</p>
                        <p class="cart-drawer-line-meta">{{ number_format((float) $item->unit_price, 2) }} each · max {{ $stock }}</p>
                        <div class="cart-drawer-qty">
                            <button type="button" class="cart-drawer-qbtn" data-cart-ajax="decrement" data-cart-url="{{ route('client.cart.items.decrement', $item) }}" aria-label="Decrease">&minus;</button>
                            <span class="cart-drawer-qval">{{ $item->quantity }}</span>
                            <button type="button" class="cart-drawer-qbtn" data-cart-ajax="increment" data-cart-url="{{ route('client.cart.items.increment', $item) }}" @if ($atMax) disabled title="Maximum stock reached" @endif aria-label="Increase">&plus;</button>
                        </div>
                    </div>
                    <div class="cart-drawer-line-total">
                        {{ number_format((float) $item->unit_price * $item->quantity, 2) }}
                        <div class="cart-drawer-remove-form">
                            <button type="button" class="cart-drawer-remove" data-cart-ajax="remove" data-cart-url="{{ route('client.cart.items.destroy', $item) }}" data-cart-confirm="Remove this item?">Remove</button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="cart-drawer-footer">
        <div class="cart-drawer-subtotal">
            <span>Subtotal</span>
            <strong>{{ number_format($cartDrawerSubtotal, 2) }}</strong>
        </div>
        <div class="cart-drawer-actions">
            <a href="{{ route('client.categories.index') }}" class="cart-drawer-btn cart-drawer-btn-secondary">Continue shopping</a>
            <a href="{{ route('client.checkout') }}" class="cart-drawer-btn cart-drawer-btn-primary">Checkout</a>
        </div>
    </div>
@endif
