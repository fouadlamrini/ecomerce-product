@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-400">Unpaid Orders</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $unpaidOrdersCount }}</p>
            <p class="mt-1 text-sm text-amber-600">Waiting payment</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-400">Paid Orders</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $paidOrdersCount }}</p>
            <p class="mt-1 text-sm text-emerald-600">Payment completed</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-400">Total Products</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalProductsCount }}</p>
            <p class="mt-1 text-sm text-slate-500">Catalog items</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-400">Stock = 0</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $outOfStockProductsCount }}</p>
            <p class="mt-1 text-sm text-amber-600">Need replenishment</p>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Orders Payment Status</h2>
            <div class="mt-4">
                <canvas id="paymentOrdersChart" height="140"></canvas>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Products Stock</h2>
            <div class="mt-4">
                <canvas id="stockProductsChart" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-4 xl:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-slate-900">Latest Orders</h2>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full border-collapse">
                    <thead class="text-left">
                        <tr>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Order</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">User</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Payment</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Order Status</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Shipping</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            @php
                                $paymentUpper = strtoupper((string) $order->payment_status);
                                $isPaid = $paymentUpper === 'PAID';
                                $paymentClass = $isPaid ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700';
                                $paymentLabel = $isPaid ? 'Paid' : 'Unpaid';

                                $shippingLatest = $order->shipping
                                    ? $order->shipping->sortByDesc('created_at')->first()
                                    : null;

                                $shippingStatus = $shippingLatest?->status;

                                $shippingClass = 'bg-slate-100 text-slate-700';
                                if ($shippingStatus === 'delivered') {
                                    $shippingClass = 'bg-emerald-100 text-emerald-700';
                                } elseif ($shippingStatus === 'shipped') {
                                    $shippingClass = 'bg-blue-100 text-blue-700';
                                } elseif ($shippingStatus === 'cancelled') {
                                    $shippingClass = 'bg-red-100 text-red-700';
                                } elseif ($shippingStatus === 'processing' || $shippingStatus === 'pending') {
                                    $shippingClass = 'bg-amber-100 text-amber-700';
                                }

                                $orderStatus = (string) $order->status;
                                $orderStatusClass = $orderStatus === 'processing'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700';
                            @endphp

                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="border-b border-slate-100 px-4 py-3.5 text-sm font-medium text-slate-700">
                                    {{ $order->order_number }}
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5 text-sm text-slate-600">
                                    {{ $order->user?->email ?? '-' }}
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $paymentClass }}">
                                        {{ $paymentLabel }}
                                    </span>
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $orderStatusClass }}">
                                        {{ ucfirst($orderStatus) }}
                                    </span>
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $shippingClass }}">
                                        {{ $shippingStatus ?? 'Not shipped' }}
                                    </span>
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5 text-sm text-slate-600">
                                    {{ $order->created_at?->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-4 text-sm text-slate-500" colspan="6">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-lg font-semibold text-slate-900">Products Stock = 0</h2>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full border-collapse">
                    <thead class="text-left">
                        <tr>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Product</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Category</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Stock</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outOfStockProducts as $product)
                            @php
                                $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="border-b border-slate-100 px-4 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-13.5 w-13.5 overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                                            @if ($mainImage)
                                                <img class="h-full w-full object-cover" src="{{ asset('storage/'.$mainImage->path) }}" alt="{{ $product->name }}">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-xs text-slate-400">No img</div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="truncate text-sm font-medium text-slate-700">{{ $product->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5 text-sm text-slate-600">
                                    {{ $product->category?->name ?? '-' }}
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5 text-sm text-slate-700">
                                    {{ (int) $product->stock }}
                                </td>
                                <td class="border-b border-slate-100 px-4 py-3.5">
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                        Out of Stock
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-4 text-sm text-slate-500" colspan="4">No out-of-stock products.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const paymentCtx = document.getElementById('paymentOrdersChart');
        if (paymentCtx) {
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Unpaid'],
                    datasets: [{
                        data: [{{ $paidOrdersCount }}, {{ $unpaidOrdersCount }}],
                        backgroundColor: ['#10b981', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        const stockCtx = document.getElementById('stockProductsChart');
        if (stockCtx) {
            new Chart(stockCtx, {
                type: 'doughnut',
                data: {
                    labels: ['In Stock', 'Stock = 0'],
                    datasets: [{
                        data: [{{ $inStockProductsCount }}, {{ $outOfStockProductsCount }}],
                        backgroundColor: ['#22c55e', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    </script>
@endsection
