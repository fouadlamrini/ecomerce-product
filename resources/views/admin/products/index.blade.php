@extends('admin.layouts.app')

@section('title', 'Management Product')

@section('content')
    <style>
        .top { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
        .btn { background:#f16743; color:#fff; text-decoration:none; border:0; border-radius:8px; padding:8px 14px; }
        table { width:100%; border-collapse: collapse; }
        th,td { padding:10px; border-bottom:1px solid #eee; text-align:left; }
        .thumb { width:52px; height:52px; border-radius:8px; object-fit:cover; border:1px solid #e5e7eb; }
        .status { padding:3px 8px; border-radius:999px; font-size:12px; }
        .on { background:#e7f8ec; color:#17733a; }
        .off { background:#ffe9e9; color:#9f1d1d; }
        .actions { display:flex; gap:8px; }
        .icon-btn { display:inline-flex; width:34px; height:34px; align-items:center; justify-content:center; border:1px solid #e9e9e9; border-radius:8px; text-decoration:none; background:#fff; }
        .icon-btn svg { width:18px; height:18px; }
        .success { margin-bottom:12px; padding:10px; border-radius:8px; background:#effff3; border:1px solid #b6efc1; color:#176029; }
    </style>

    <div class="top">
        <h2 style="margin:0;">Product List</h2>
        <a href="{{ route('admin.products.create') }}" class="btn">+ Add Product</a>
    </div>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                @php $main = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
                <tr>
                    <td>
                        @if ($main)
                            <img class="thumb" src="{{ asset('storage/'.$main->path) }}" alt="{{ $product->name }}">
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ number_format((float) $product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td><span class="status {{ $product->is_active ? 'on' : 'off' }}">{{ $product->is_active ? 'active' : 'inactive' }}</span></td>
                    <td class="actions">
                        <a class="icon-btn" href="{{ route('admin.products.show', $product) }}" title="View">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>
                        <a class="icon-btn" href="{{ route('admin.products.edit', $product) }}" title="Edit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn" title="Delete">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#b00020" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
