@extends('admin.layouts.app')

@section('title', 'Shipping')

@section('content')
    <div class="mb-5 flex items-center justify-between gap-3">
        <h2 class="m-0 text-xl font-bold">Shipping List</h2>
        <a href="{{ route('admin.shippings.create') }}" class="rounded-lg bg-[#f16743] px-3.5 py-2 text-sm font-bold text-white">+ Add Shipping</a>
    </div>

    @if (session('success'))
        <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="text-left">
                <tr>
                    <th class="border-b border-slate-200 px-2 py-2.5">Order</th>
                    <th class="border-b border-slate-200 px-2 py-2.5">Carrier</th>
                    <th class="border-b border-slate-200 px-2 py-2.5">Tracking</th>
                    <th class="border-b border-slate-200 px-2 py-2.5">Status</th>
                    <th class="border-b border-slate-200 px-2 py-2.5">Shipped At</th>
                    <th class="w-[200px] border-b border-slate-200 px-2 py-2.5">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shippings as $shipping)
                    <tr>
                        <td class="border-b border-slate-100 px-2 py-2.5">{{ $shipping->order?->order_number ?? '-' }}</td>
                        <td class="border-b border-slate-100 px-2 py-2.5">{{ $shipping->carrier ?: '-' }}</td>
                        <td class="border-b border-slate-100 px-2 py-2.5">{{ $shipping->tracking_number ?: '-' }}</td>
                        <td class="border-b border-slate-100 px-2 py-2.5">
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-700">{{ $shipping->status }}</span>
                        </td>
                        <td class="border-b border-slate-100 px-2 py-2.5">{{ $shipping->shipped_at?->format('Y-m-d H:i') ?: '-' }}</td>
                        <td class="border-b border-slate-100 px-2 py-2.5">
                            <div class="flex items-center gap-2">
                                <a class="inline-flex h-[34px] w-[34px] items-center justify-center rounded-lg border border-emerald-200 bg-white text-emerald-700" href="{{ route('admin.shippings.show', $shipping) }}" title="View">V</a>
                                <a class="inline-flex h-[34px] w-[34px] items-center justify-center rounded-lg border border-blue-200 bg-white text-blue-700" href="{{ route('admin.shippings.edit', $shipping) }}" title="Edit">E</a>
                                <form method="POST" action="{{ route('admin.shippings.destroy', $shipping) }}" onsubmit="return confirm('Delete this shipping record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex h-[34px] w-[34px] items-center justify-center rounded-lg border border-red-200 bg-white text-red-700" title="Delete">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-2 py-3 text-slate-500" colspan="6">No shipping records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex items-center gap-2.5 text-sm">
        @if ($shippings->onFirstPage())
            <span class="text-slate-400">Previous</span>
        @else
            <a href="{{ $shippings->previousPageUrl() }}" class="font-bold text-[#f16743]">Previous</a>
        @endif

        <span class="text-slate-500">Page {{ $shippings->currentPage() }} / {{ $shippings->lastPage() }}</span>

        @if ($shippings->hasMorePages())
            <a href="{{ $shippings->nextPageUrl() }}" class="font-bold text-[#f16743]">Next</a>
        @else
            <span class="text-slate-400">Next</span>
        @endif
    </div>
@endsection
