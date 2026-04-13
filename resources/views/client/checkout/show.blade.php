@extends('client.layouts.app')

@section('title', 'Checkout (demo)')

@section('content')
    <style>
        .page-title { margin: 0 0 8px; font-size: 26px; font-weight: 800; }
        .demo-banner { background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
        .card h2 { margin: 0 0 14px; font-size: 16px; font-weight: 800; }
        .field { margin-bottom: 12px; }
        .field label { display: block; font-size: 12px; font-weight: 700; color: #6b7280; margin-bottom: 4px; }
        .field input { width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #f9fafb; color: #6b7280; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .summary-row:last-of-type { border-bottom: 0; }
        .summary-total { font-weight: 900; font-size: 18px; margin-top: 12px; padding-top: 12px; border-top: 2px solid #e5e7eb; display: flex; justify-content: space-between; }
        .btn-pay { width: 100%; margin-top: 16px; border: 0; border-radius: 8px; padding: 12px; background: #f16743; color: #fff; font-weight: 800; cursor: pointer; font-size: 15px; }
        .btn-back { display: inline-block; margin-top: 14px; color: #f16743; font-weight: 700; text-decoration: none; font-size: 14px; }
    </style>

    <p class="demo-banner">
        This page is a <strong>static checkout simulation</strong> for demo purposes only. No payment is processed and no order is saved.
    </p>

    <h1 class="page-title">Checkout</h1>

    <div class="grid">
        <div class="card">
            <h2>Shipping (read-only demo)</h2>
            <div class="field">
                <label for="demo-name">Full name</label>
                <input id="demo-name" type="text" value="Demo Customer" readonly>
            </div>
            <div class="field">
                <label for="demo-address">Address</label>
                <input id="demo-address" type="text" value="123 Example Street, Casablanca" readonly>
            </div>
            <div class="field">
                <label for="demo-phone">Phone</label>
                <input id="demo-phone" type="text" value="+212 600 000 000" readonly>
            </div>
            <h2 style="margin-top:22px;">Payment (read-only demo)</h2>
            <div class="field">
                <label for="demo-card">Card number</label>
                <input id="demo-card" type="text" value="4242 4242 4242 4242" readonly>
            </div>
            <div class="field">
                <label for="demo-exp">Expiry</label>
                <input id="demo-exp" type="text" value="12 / 28" readonly>
            </div>
            <button type="button" class="btn-pay" id="checkout-demo-pay">Place order (demo)</button>
            <script>
                document.getElementById('checkout-demo-pay')?.addEventListener('click', function () {
                    alert('Demo: no payment was taken.');
                    window.location.href = @json(route('client.categories.index'));
                });
            </script>
            <a class="btn-back" href="{{ route('client.categories.index') }}">&larr; Continue shopping</a>
        </div>

        <div class="card">
            <h2>Order summary</h2>
            @foreach ($items as $item)
                <div class="summary-row">
                    <span>{{ $item->product->name }} &times; {{ $item->quantity }}</span>
                    <span>{{ number_format((float) $item->unit_price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
            <div class="summary-total">
                <span>Total</span>
                <span>{{ number_format($subtotal, 2) }}</span>
            </div>
        </div>
    </div>
@endsection
