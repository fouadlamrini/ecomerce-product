@extends('admin.layouts.app')

@section('title', 'Shipping Details')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[420px_1fr]">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-3 text-sm text-slate-500">Quick Actions</div>
            <a href="{{ route('admin.shippings.edit', $shipping) }}" class="mb-2 inline-block rounded-lg bg-[#f16743] px-3.5 py-2 text-sm font-bold text-white">Edit</a>
            <a href="{{ route('admin.shippings.index') }}" class="inline-block rounded-lg bg-slate-200 px-3.5 py-2 text-sm text-slate-800">Back</a>
        </div>

        <div>
            <div class="mb-3"><div class="text-xs text-slate-500">Order</div><div class="text-base">{{ $shipping->order?->order_number ?? '-' }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Carrier</div><div class="text-base">{{ $shipping->carrier ?: '-' }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Tracking Number</div><div class="text-base">{{ $shipping->tracking_number ?: '-' }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Status</div><div class="text-base">{{ ucfirst($shipping->status) }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Shipped At</div><div class="text-base">{{ $shipping->shipped_at?->format('Y-m-d H:i') ?: '-' }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Delivered At</div><div class="text-base">{{ $shipping->delivered_at?->format('Y-m-d H:i') ?: '-' }}</div></div>
            <div class="mb-3"><div class="text-xs text-slate-500">Created At</div><div class="text-base">{{ $shipping->created_at?->format('Y-m-d H:i') ?: '-' }}</div></div>
        </div>
    </div>
@endsection
