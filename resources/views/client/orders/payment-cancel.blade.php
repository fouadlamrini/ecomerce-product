@extends('client.layouts.app')

@section('title', 'Payment Canceled')

@section('content')
    <div class="mx-auto max-w-2xl rounded-xl border border-orange-200 bg-white p-6">
        <h1 class="text-2xl font-extrabold text-orange-700">Payment Canceled</h1>
        <p class="mt-3 text-sm text-slate-700">
            The payment was canceled for order
            <span class="font-semibold">{{ $order->order_number }}</span>.
            You can try again whenever you are ready.
        </p>

        <a href="{{ route('client.orders.show', $order) }}" class="mt-5 inline-block text-sm font-bold text-[#f16743]">
            &larr; Back to order details
        </a>
    </div>
@endsection
