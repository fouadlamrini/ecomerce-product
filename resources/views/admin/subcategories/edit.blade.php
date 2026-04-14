@extends('admin.layouts.app')

@section('title', 'Edit Subcategory')

@section('content')
    <div class="max-w-[760px]">
        @if ($errors->any())
            <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.subcategories.update', $subcategory) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3.5">
                <label class="mb-1.5 block text-sm font-bold" for="category_id">Category</label>
                <select class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $subcategory->category_id) === $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3.5">
                <label class="mb-1.5 block text-sm font-bold" for="name">Name</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="name" type="text" name="name" value="{{ old('name', $subcategory->name) }}" required>
            </div>

            <div class="mb-3.5">
                <label class="mb-1.5 block text-sm font-bold" for="bg_image">Background Image</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="bg_image" type="file" name="bg_image" accept=".jpg,.jpeg,.png,.webp">
                @if ($subcategory->bg_image)
                    <div class="mt-2">
                        <img class="h-[100px] w-[180px] rounded-lg border border-slate-200 object-cover" src="{{ asset('storage/'.$subcategory->bg_image) }}" alt="{{ $subcategory->name }}">
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input class="h-4 w-4 rounded border-slate-300 text-[#f16743] focus:ring-[#f16743]/30" type="checkbox" name="is_active" value="1" {{ old('is_active', $subcategory->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <a class="mr-3 text-sm text-slate-600" href="{{ route('admin.subcategories.index') }}">Back</a>
            <button class="rounded-lg bg-[#f16743] px-4 py-2 text-sm font-bold text-white" type="submit">Update</button>
        </form>
    </div>
@endsection
