@extends('client.layouts.app')

@section('title', 'Order Confirmation')

@section('content')
    <h1 class="mb-2 text-[26px] font-extrabold">Order Confirmation</h1>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_340px]">
        <div class="rounded-xl border border-slate-200 bg-white p-4.5">
            <h2 class="mb-3.5 text-base font-extrabold">Order details</h2>
            <div class="grid grid-cols-1 gap-3 text-sm text-slate-700 sm:grid-cols-2">
                <div><span class="font-semibold">Order number:</span> {{ $order->order_number }}</div>
                <div><span class="font-semibold">Order status:</span> {{ $order->status }}</div>
                <div><span class="font-semibold">Payment status:</span> {{ $order->payment_status }}</div>
                <div><span class="font-semibold">Placed at:</span> {{ $order->created_at?->format('Y-m-d H:i') }}</div>
            </div>

            <h3 class="mb-2 mt-5 text-sm font-extrabold">Shipping address</h3>
            <p class="text-sm text-slate-600">
                {{ $order->address?->full_name }} - {{ $order->address?->phone }}<br>
                {{ $order->address?->line1 }}{{ $order->address?->line2 ? ', '.$order->address?->line2 : '' }},
                {{ $order->address?->city }}{{ $order->address?->state ? ', '.$order->address?->state : '' }},
                {{ $order->address?->postal_code }}, {{ $order->address?->country }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4.5">
            <h2 class="mb-3.5 text-base font-extrabold">Order summary</h2>
            @foreach ($order->items as $item)
                <div class="flex items-center justify-between border-b border-slate-100 py-2 text-sm">
                    <span>{{ $item->product?->name ?? 'Product' }} &times; {{ $item->quantity }}</span>
                    <span>{{ number_format((float) $item->line_total, 2) }}</span>
                </div>
            @endforeach
            <div class="mt-3 space-y-1 border-t border-slate-200 pt-3 text-sm text-slate-700">
                <div class="flex items-center justify-between"><span>Subtotal</span><span>{{ number_format((float) $order->subtotal, 2) }}</span></div>
                <div class="flex items-center justify-between"><span>Discount</span><span>-{{ number_format((float) $order->discount_amount, 2) }}</span></div>
                <div class="flex items-center justify-between"><span>Shipping</span><span>{{ number_format((float) $order->shipping_amount, 2) }}</span></div>
            </div>
            <div class="mt-3 flex items-center justify-between border-t-2 border-slate-200 pt-3 text-lg font-black">
                <span>Total</span>
                <span>{{ number_format((float) $order->total, 2) }}</span>
            </div>

            <a class="mt-4 inline-block text-sm font-bold text-[#f16743]" href="{{ route('client.categories.index') }}">&larr; Continue shopping</a>
        </div>
    </div>
@endsection
