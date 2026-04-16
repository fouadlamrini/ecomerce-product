@extends('client.layouts.app')

@section('title', 'Order Confirmation 2')
@section('content')
    @php
        $paymentStatus = strtoupper(trim((string) $order->payment_status));
        $canPayNow = strtolower(trim((string) $order->payment_status)) !== 'paid';
        $orderStatus = strtolower(trim((string) $order->status));
        $latestShipping = $order->shipping->sortByDesc('created_at')->first();
        $shippingStatus = strtolower(trim((string) ($latestShipping?->status ?? '')));

        $steps = ['Placed', 'Paid', 'Processing', 'Shipped', 'Delivered'];
        $currentStep = 0;
        if ($paymentStatus === 'PAID') {
            $currentStep = 1;
        }
        if ($orderStatus === 'processing' || in_array($shippingStatus, ['processing', 'shipped', 'delivered'], true)) {
            $currentStep = max($currentStep, 2);
        }
        if (in_array($shippingStatus, ['shipped', 'delivered'], true)) {
            $currentStep = max($currentStep, 3);
        }
        if ($shippingStatus === 'delivered') {
            $currentStep = 4;
        }

        $isCancelled = $orderStatus === 'cancelled' || $shippingStatus === 'cancelled';
        $progressPercent = $isCancelled
            ? 100
            : (int) round(($currentStep / (count($steps) - 1)) * 100);
    @endphp

    <h1 class="mb-2 text-[26px] font-extrabold">Order Confirmation </h1>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4.5">
        <div class="mb-3 flex items-center justify-between gap-2">
            <h2 class="text-sm font-extrabold text-slate-800">Order Tracking</h2>
            @if ($isCancelled)
                <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-700">Cancelled</span>
            @else
                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-700">{{ $steps[$currentStep] }}</span>
            @endif
        </div>

        <div class="mb-3 h-2 w-full rounded-full bg-slate-100">
            <div
                class="h-2 rounded-full {{ $isCancelled ? 'bg-red-500' : 'bg-[#f16743]' }}"
                style="width: {{ $progressPercent }}%;"
            ></div>
        </div>

        <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-500 sm:grid-cols-5">
            @foreach ($steps as $index => $step)
                @php
                    $isDone = !$isCancelled && $index <= $currentStep;
                @endphp
                <div class="inline-flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full {{ $isCancelled ? ($index === 0 ? 'bg-red-500' : 'bg-slate-300') : ($isDone ? 'bg-[#f16743]' : 'bg-slate-300') }}"></span>
                    <span class="{{ $isCancelled ? ($index === 0 ? 'font-bold text-red-700' : '') : ($isDone ? 'font-bold text-slate-700' : '') }}">{{ $step }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[1fr_340px]">
        <div class="rounded-xl border border-slate-200 bg-white p-4.5">
            <h2 class="mb-3.5 text-base font-extrabold">Order details</h2>
            <div class="grid grid-cols-1 gap-3 text-sm text-slate-700 sm:grid-cols-2">
                <div><span class="font-semibold">Order number:</span> {{ $order->order_number }}</div>
                <div><span class="font-semibold">Order status:</span> {{ $order->status }}</div>
                <div><span class="font-semibold">Shipping status:</span> {{ $latestShipping?->status ?? 'Not shipped yet' }}</div>
                <div>
                    <span class="font-semibold">Payment status:</span>
                    @if ($paymentStatus === 'PAID')
                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">Paid</span>
                    @else
                        <span class="uppercase">{{ $paymentStatus }}</span>
                    @endif
                </div>
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

            @if ($canPayNow)
                <form method="POST" action="{{ route('orders.checkout', $order) }}" class="mt-4">
                    @csrf
                    <button type="submit"
                            class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-600"
                            style="display:block;width:100%;background-color:#4338ca;color:#ffffff;border:1px solid #3730a3;border-radius:0.5rem;padding:0.7rem 1rem;font-weight:600;">
                        Buy Now
                    </button>
                </form>
            @endif

            <a class="mt-4 inline-block text-sm font-bold text-[#f16743]" href="{{ route('client.categories.index') }}">&larr; Continue shopping</a>
        </div>
    </div>
@endsection
