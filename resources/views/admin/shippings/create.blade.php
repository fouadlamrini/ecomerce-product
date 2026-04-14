@extends('admin.layouts.app')

@section('title', 'Create Shipping')

@section('content')
    <div class="max-w-[860px]">
        @if ($errors->any())
            <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.shippings.store') }}">
            @csrf

            <div class="mb-3">
                <label class="mb-1.5 block text-sm font-bold" for="order_id">Order</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="order_id" name="order_id" required>
                    <option value="">Select order</option>
                    @foreach ($orders as $order)
                        <option value="{{ $order['id'] }}" {{ old('order_id') === $order['id'] ? 'selected' : '' }}>{{ $order['order_number'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="carrier">Carrier</label>
                    <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="carrier" type="text" name="carrier" value="{{ old('carrier') }}">
                </div>
                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="tracking_number">Tracking Number</label>
                    <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="tracking_number" type="text" name="tracking_number" value="{{ old('tracking_number') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="status">Status</label>
                    <select class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="status" name="status" required>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', 'pending') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="shipped_at">Shipped At</label>
                    <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="shipped_at" type="datetime-local" name="shipped_at" value="{{ old('shipped_at') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-1.5 block text-sm font-bold" for="delivered_at">Delivered At</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="delivered_at" type="datetime-local" name="delivered_at" value="{{ old('delivered_at') }}">
            </div>

            <a class="mr-3 text-sm text-slate-600" href="{{ route('admin.shippings.index') }}">Back</a>
            <button class="rounded-lg bg-[#f16743] px-4 py-2 text-sm font-bold text-white" type="submit">Create</button>
        </form>
    </div>
@endsection
