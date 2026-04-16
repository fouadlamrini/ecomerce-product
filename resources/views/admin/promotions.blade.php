@extends('admin.layouts.app')

@section('title', 'Discount & Promotion')

@section('content')
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1.15fr_1.85fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">
                {{ $editCoupon ? 'Edit Promotion' : 'Create Promotion' }}
            </h2>

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $editCoupon ? route('admin.promotions.update', $editCoupon) : route('admin.promotions.store') }}">
                @csrf
                @if ($editCoupon)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="code">Code</label>
                    <input
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                        id="code"
                        type="text"
                        name="code"
                        value="{{ old('code', $editCoupon?->code) }}"
                        placeholder="WELCOME10"
                        required
                    >
                </div>

                <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold" for="type">Type</label>
                        <select class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="type" name="type" required>
                            @php $selectedType = old('type', $editCoupon?->type ?? 'fixed'); @endphp
                            <option value="fixed" {{ $selectedType === 'fixed' ? 'selected' : '' }}>Fixed amount</option>
                            <option value="percentage" {{ $selectedType === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold" for="value">Value</label>
                        <input
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                            id="value"
                            type="number"
                            min="0.01"
                            step="0.01"
                            name="value"
                            value="{{ old('value', $editCoupon?->value) }}"
                            required
                        >
                    </div>
                </div>

                <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold" for="min_order_amount">Minimum order amount</label>
                        <input
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                            id="min_order_amount"
                            type="number"
                            min="0"
                            step="0.01"
                            name="min_order_amount"
                            value="{{ old('min_order_amount', $editCoupon?->min_order_amount) }}"
                            placeholder="Optional"
                        >
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold" for="usage_limit">Usage limit</label>
                        <input
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                            id="usage_limit"
                            type="number"
                            min="1"
                            step="1"
                            name="usage_limit"
                            value="{{ old('usage_limit', $editCoupon?->usage_limit) }}"
                            placeholder="Optional"
                        >
                    </div>
                </div>

                <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-bold" for="starts_at">Starts at</label>
                        <input
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                            id="starts_at"
                            type="datetime-local"
                            name="starts_at"
                            value="{{ old('starts_at', optional($editCoupon?->starts_at)->format('Y-m-d\TH:i')) }}"
                        >
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold" for="expires_at">Expires at</label>
                        <input
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                            id="expires_at"
                            type="datetime-local"
                            name="expires_at"
                            value="{{ old('expires_at', optional($editCoupon?->expires_at)->format('Y-m-d\TH:i')) }}"
                        >
                    </div>
                </div>

                @php
                    $activeOld = old('is_active');
                    $isActive = $activeOld !== null
                        ? ((string) $activeOld === '1')
                        : ($editCoupon?->is_active ?? true);
                @endphp

                <label class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <input class="h-4 w-4 rounded border-slate-300 text-[#f16743] focus:ring-[#f16743]" type="checkbox" name="is_active" value="1" {{ $isActive ? 'checked' : '' }}>
                    Active promotion
                </label>

                <div class="flex items-center gap-2.5">
                    @if ($editCoupon)
                        <a class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-semibold text-slate-700" href="{{ route('admin.promotions') }}">Cancel</a>
                        <button class="rounded-lg bg-[#f16743] px-4 py-2 text-sm font-bold text-white" type="submit">Update</button>
                    @else
                        <button class="rounded-lg bg-[#f16743] px-4 py-2 text-sm font-bold text-white" type="submit">Create</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="m-0 text-lg font-semibold text-slate-900">Promotion List</h2>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full border-collapse">
                    <thead class="text-left">
                        <tr>
                            <th class="border-b border-slate-200 px-3 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Code</th>
                            <th class="border-b border-slate-200 px-3 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Type</th>
                            <th class="border-b border-slate-200 px-3 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Value</th>
                            <th class="border-b border-slate-200 px-3 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Usage</th>
                            <th class="border-b border-slate-200 px-3 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Active</th>
                            <th class="border-b border-slate-200 px-3 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Validity</th>
                            <th class="w-35 border-b border-slate-200 px-3 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="border-b border-slate-100 px-3 py-3 text-sm font-semibold text-slate-800">{{ $coupon->code }}</td>
                                <td class="border-b border-slate-100 px-3 py-3 text-sm text-slate-600">{{ ucfirst($coupon->type) }}</td>
                                <td class="border-b border-slate-100 px-3 py-3 text-sm text-slate-700">
                                    @if ($coupon->type === 'percentage')
                                        {{ number_format((float) $coupon->value, 2) }}%
                                    @else
                                        {{ number_format((float) $coupon->value, 2) }}
                                    @endif
                                </td>
                                <td class="border-b border-slate-100 px-3 py-3 text-sm text-slate-600">
                                    {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }}
                                </td>
                                <td class="border-b border-slate-100 px-3 py-3">
                                    @if ($coupon->is_active)
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-700">Active</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="border-b border-slate-100 px-3 py-3 text-xs text-slate-500">
                                    <div>From: {{ $coupon->starts_at?->format('Y-m-d H:i') ?? '-' }}</div>
                                    <div>To: {{ $coupon->expires_at?->format('Y-m-d H:i') ?? '-' }}</div>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.promotions', ['edit' => $coupon->id]) }}" class="rounded-lg border border-blue-200 bg-white px-2.5 py-1.5 text-xs font-bold text-blue-700">Edit</a>
                                        <form method="POST" action="{{ route('admin.promotions.destroy', $coupon) }}" onsubmit="return confirm('Delete this promotion?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg border border-red-200 bg-white px-2.5 py-1.5 text-xs font-bold text-red-700" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-3 py-4 text-sm text-slate-500" colspan="7">No promotions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center gap-2.5 text-sm">
                @if ($coupons->onFirstPage())
                    <span class="text-slate-400">Previous</span>
                @else
                    <a href="{{ $coupons->previousPageUrl() }}" class="font-semibold text-[#f16743]">Previous</a>
                @endif

                <span class="text-slate-500">Page {{ $coupons->currentPage() }} / {{ $coupons->lastPage() }}</span>

                @if ($coupons->hasMorePages())
                    <a href="{{ $coupons->nextPageUrl() }}" class="font-semibold text-[#f16743]">Next</a>
                @else
                    <span class="text-slate-400">Next</span>
                @endif
            </div>
        </div>
    </div>
@endsection
