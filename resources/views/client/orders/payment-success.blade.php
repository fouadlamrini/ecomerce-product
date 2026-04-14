@extends('client.layouts.app')

@section('title', 'Payment Success')

@section('content')
    <div class="mx-auto max-w-2xl rounded-xl border border-emerald-200 bg-white p-6">
        <h1 class="text-2xl font-extrabold text-emerald-700">Payment Success</h1>
        <p class="mt-3 text-sm text-slate-700">
            Thank you. We received your payment return from Stripe for order
            <span class="font-semibold">{{ $order->order_number }}</span>.
            We are confirming it from our server.
        </p>

        @if (strtolower((string) $order->payment_status) === 'paid')
            <p class="mt-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800">
                Payment confirmed.
            </p>
        @else
            <p class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                Payment is still processing. Refresh the order page in a few seconds.
            </p>
        @endif

        <a href="{{ route('client.orders.show', $order) }}" class="mt-5 inline-block text-sm font-bold text-[#f16743]">
            &larr; Back to order details
        </a>
    </div>
@endsection
