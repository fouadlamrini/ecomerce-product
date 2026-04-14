@extends('admin.layouts.app')

@section('title', 'Subcategory Details')

@section('content')
    <div class="max-w-[760px]">
        <div class="mb-3">
            <div class="text-xs text-slate-500">Name</div>
            <div class="text-base">{{ $subcategory->name }}</div>
        </div>

        <div class="mb-3">
            <div class="text-xs text-slate-500">Category</div>
            <div class="text-base">{{ $subcategory->category?->name ?? '-' }}</div>
        </div>

        <div class="mb-3">
            <div class="text-xs text-slate-500">Slug</div>
            <div class="text-base">{{ $subcategory->slug }}</div>
        </div>

        <div class="mb-4">
            <div class="text-xs text-slate-500">Status</div>
            <div class="text-base">{{ $subcategory->is_active ? 'active' : 'inactive' }}</div>
        </div>

        <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="mr-2 inline-block rounded-lg bg-[#f16743] px-3.5 py-2 text-sm font-bold text-white">Edit</a>
        <a href="{{ route('admin.subcategories.index') }}" class="inline-block rounded-lg bg-slate-100 px-3.5 py-2 text-sm text-slate-700">Back</a>
    </div>
@endsection
