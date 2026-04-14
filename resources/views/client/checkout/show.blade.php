@extends('client.layouts.app')

@section('title', 'Checkout')

@section('content')
    <style>
        .page-title { margin: 0 0 8px; font-size: 26px; font-weight: 800; }
        .grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
        .card h2 { margin: 0 0 14px; font-size: 16px; font-weight: 800; }
        .address-list { display: grid; gap: 12px; margin-bottom: 18px; }
        .address-item { border: 1px solid #e5e7eb; border-radius: 10px; background: #fcfcff; padding: 12px; }
        .address-item.default { border-color: #f16743; box-shadow: 0 0 0 1px rgba(241, 103, 67, 0.18); }
        .address-head { margin: 0 0 10px; font-size: 13px; color: #6b7280; }
        .badge { display: inline-block; margin-left: 8px; background: #fff1ed; color: #c2410c; border: 1px solid #fed7aa; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 700; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        @media (max-width: 700px) { .grid-2 { grid-template-columns: 1fr; } }
        .field { margin-bottom: 12px; }
        .field label { display: block; font-size: 12px; font-weight: 700; color: #6b7280; margin-bottom: 4px; }
        .field input { width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: #fff; color: #111827; }
        .checkbox-wrap { display: flex; align-items: center; gap: 8px; margin: 6px 0 12px; }
        .btn-save { border: 0; border-radius: 8px; padding: 10px 12px; background: #111827; color: #fff; font-weight: 700; cursor: pointer; font-size: 13px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .summary-row:last-of-type { border-bottom: 0; }
        .summary-total { font-weight: 900; font-size: 18px; margin-top: 12px; padding-top: 12px; border-top: 2px solid #e5e7eb; display: flex; justify-content: space-between; }
        .btn-pay { width: 100%; margin-top: 16px; border: 0; border-radius: 8px; padding: 12px; background: #f16743; color: #fff; font-weight: 800; cursor: pointer; font-size: 15px; }
        .btn-back { display: inline-block; margin-top: 14px; color: #f16743; font-weight: 700; text-decoration: none; font-size: 14px; }
    </style>

    <h1 class="page-title">Checkout</h1>

    <div class="grid">
        <div class="card">
            <h2>Shipping addresses</h2>

            @if ($addresses->isEmpty())
                <p style="margin:0 0 16px; color:#6b7280; font-size:14px;">
                    You have no saved address yet. Add one below to speed up your checkout.
                </p>
            @else
                <div class="address-list">
                    @foreach ($addresses as $address)
                        <div class="address-item {{ $address->is_default ? 'default' : '' }}">
                            <p class="address-head">
                                <strong>{{ $address->label ?: 'Address' }}</strong>
                                @if ($address->is_default)
                                    <span class="badge">Default</span>
                                @endif
                                <br>
                                {{ $address->full_name }} - {{ $address->phone }}<br>
                                {{ $address->line1 }}{{ $address->line2 ? ', '.$address->line2 : '' }},
                                {{ $address->city }}{{ $address->state ? ', '.$address->state : '' }},
                                {{ $address->postal_code }}, {{ $address->country }}
                            </p>

                            <form method="POST" action="{{ route('client.addresses.update', $address) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="redirect_to" value="checkout">

                                <div class="grid-2">
                                    <div class="field">
                                        <label>Label</label>
                                        <input type="text" name="label" value="{{ old('label', $address->label) }}" placeholder="Home, Office...">
                                    </div>
                                    <div class="field">
                                        <label>Full name</label>
                                        <input type="text" name="full_name" value="{{ old('full_name', $address->full_name) }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Phone</label>
                                        <input type="text" name="phone" value="{{ old('phone', $address->phone) }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Address line 1</label>
                                        <input type="text" name="line1" value="{{ old('line1', $address->line1) }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Address line 2</label>
                                        <input type="text" name="line2" value="{{ old('line2', $address->line2) }}">
                                    </div>
                                    <div class="field">
                                        <label>City</label>
                                        <input type="text" name="city" value="{{ old('city', $address->city) }}" required>
                                    </div>
                                    <div class="field">
                                        <label>State</label>
                                        <input type="text" name="state" value="{{ old('state', $address->state) }}">
                                    </div>
                                    <div class="field">
                                        <label>Postal code</label>
                                        <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Country</label>
                                        <input type="text" name="country" value="{{ old('country', $address->country) }}" required>
                                    </div>
                                </div>

                                <div class="checkbox-wrap">
                                    <input id="checkout-default-{{ $address->id }}" type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }}>
                                    <label for="checkout-default-{{ $address->id }}">Set as default address</label>
                                </div>

                                <button type="submit" class="btn-save">Update address</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            <h2 style="margin-top:18px;">Add new address</h2>
            <form method="POST" action="{{ route('client.addresses.store') }}">
                @csrf
                <input type="hidden" name="redirect_to" value="checkout">
                <div class="grid-2">
                    <div class="field">
                        <label>Label</label>
                        <input type="text" name="label" value="{{ old('label') }}" placeholder="Home, Office...">
                    </div>
                    <div class="field">
                        <label>Full name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="field">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required>
                    </div>
                    <div class="field">
                        <label>Address line 1</label>
                        <input type="text" name="line1" value="{{ old('line1') }}" required>
                    </div>
                    <div class="field">
                        <label>Address line 2</label>
                        <input type="text" name="line2" value="{{ old('line2') }}">
                    </div>
                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" value="{{ old('city') }}" required>
                    </div>
                    <div class="field">
                        <label>State</label>
                        <input type="text" name="state" value="{{ old('state') }}">
                    </div>
                    <div class="field">
                        <label>Postal code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required>
                    </div>
                    <div class="field">
                        <label>Country</label>
                        <input type="text" name="country" value="{{ old('country', 'MA') }}" required>
                    </div>
                </div>

                <div class="checkbox-wrap">
                    <input id="checkout-new-default" type="checkbox" name="is_default" value="1">
                    <label for="checkout-new-default">Set as default address</label>
                </div>

                <button type="submit" class="btn-save">Save new address</button>
            </form>

            <h2 style="margin-top:22px;">Payment (demo)</h2>
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
