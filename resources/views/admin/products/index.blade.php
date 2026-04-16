@extends('admin.layouts.app')

@section('title', 'Management Product')

@section('content')
    <div class="admin-fade-in rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:p-6">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h2 class="m-0 text-xl font-semibold text-slate-900">Product List</h2>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-orange-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                <span>Add Product</span>
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full border-collapse">
                <thead class="text-left">
                    <tr>
                        <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Image</th>
                        <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Name</th>
                        <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Category</th>
                        <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Price</th>
                        <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Stock</th>
                        <th class="border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Status</th>
                        <th class="w-[220px] border-b border-slate-200 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                            $inStock = $product->is_active && ((int) $product->stock > 0);
                            $statusLabel = $inStock ? 'In Stock' : ((int) $product->stock <= 0 ? 'Out of Stock' : 'Inactive');
                            $statusClass = $inStock
                                ? 'bg-green-100 text-green-700'
                                : ((int) $product->stock <= 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700');
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="border-b border-slate-100 px-4 py-3.5">
                                @if ($mainImage)
                                    <img class="h-[54px] w-[54px] rounded-lg border border-slate-200 object-cover" src="{{ asset('storage/'.$mainImage->path) }}" alt="{{ $product->name }}">
                                @else
                                    <span class="inline-flex h-[54px] w-[54px] items-center justify-center rounded-lg border border-dashed border-slate-300 text-xs text-slate-400">No img</span>
                                @endif
                            </td>
                            <td class="border-b border-slate-100 px-4 py-3.5 text-sm font-medium text-slate-700">{{ $product->name }}</td>
                            <td class="border-b border-slate-100 px-4 py-3.5 text-sm text-slate-600">{{ $product->category?->name ?? '-' }}</td>
                            <td class="border-b border-slate-100 px-4 py-3.5 text-sm text-slate-700">{{ number_format((float) $product->price, 2) }}</td>
                            <td class="border-b border-slate-100 px-4 py-3.5 text-sm text-slate-700">{{ $product->stock }}</td>
                            <td class="border-b border-slate-100 px-4 py-3.5">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="border-b border-slate-100 px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a class="inline-flex h-[34px] w-[34px] items-center justify-center rounded-lg border border-emerald-200 bg-white text-emerald-700" href="{{ route('admin.products.show', $product) }}" title="View">
                                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    <a class="inline-flex h-[34px] w-[34px] items-center justify-center rounded-lg border border-blue-200 bg-white text-blue-700" href="{{ route('admin.products.edit', $product) }}" title="Edit">
                                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-[34px] w-[34px] items-center justify-center rounded-lg border border-red-200 bg-white text-red-700" title="Delete">
                                            <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-4 text-sm text-slate-500" colspan="7">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center gap-2.5 text-sm">
            @if ($products->onFirstPage())
                <span class="text-slate-400">Previous</span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="font-semibold text-[#f16743]">Previous</a>
            @endif

            <span class="text-slate-500">Page {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>

            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="font-semibold text-[#f16743]">Next</a>
            @else
                <span class="text-slate-400">Next</span>
            @endif
        </div>
    </div>
@endsection
