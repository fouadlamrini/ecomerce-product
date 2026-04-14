@extends('client.layouts.app')

@section('title', 'My Orders')

@section('content')
    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
            <h1 class="m-0 text-3xl font-extrabold">My Orders</h1>
            <p class="m-0 text-sm text-slate-500">Track all your placed orders and payment status.</p>
        </div>
    </div>

    <section class="rounded-xl border border-slate-200 bg-white p-4.5">
        @if ($orders->isEmpty())
            <p class="m-0 text-sm text-slate-500">You have not placed any orders yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase text-slate-500">
                        <tr class="border-b border-slate-200">
                            <th class="px-2 py-3">Order #</th>
                            <th class="px-2 py-3">Date</th>
                            <th class="px-2 py-3">Status</th>
                            <th class="px-2 py-3">Payment</th>
                            <th class="px-2 py-3">Total</th>
                            <th class="px-2 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @php
                                $paymentStatus = strtoupper(trim((string) $order->payment_status));
                            @endphp
                            <tr class="border-b border-slate-100 last:border-b-0">
                                <td class="px-2 py-3 font-semibold">{{ $order->order_number }}</td>
                                <td class="px-2 py-3 text-slate-600">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="px-2 py-3">{{ ucfirst($order->status) }}</td>
                                <td class="px-2 py-3">
                                    @if ($paymentStatus === 'PAID')
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">Paid</span>
                                    @elseif ($paymentStatus === 'FAILED')
                                        <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-700">Failed</span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-700">Unpaid</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 font-semibold">{{ number_format((float) $order->total, 2) }}</td>
                                <td class="px-2 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($paymentStatus === 'UNPAID')
                                            <form method="POST" action="{{ route('orders.checkout', $order) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-500">
                                                    Pay
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('client.orders.show', $order) }}"
                                           class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50">
                                            View details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </section>
@endsection
