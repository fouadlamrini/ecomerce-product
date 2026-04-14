@extends('admin.layouts.app')

@section('title', 'Edit Subcategory')

@section('content')
    <style>
        .container { max-width: 760px; }
        .field { margin-bottom: 14px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input[type="text"], input[type="file"], select { width: 100%; border: 1px solid #ddd; border-radius: 8px; padding: 10px; }
        .preview { margin-top: 8px; }
        .preview img { width: 180px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; }
        .btn { background: #f16743; color: #fff; border: 0; border-radius: 8px; padding: 9px 16px; cursor: pointer; }
        .link { margin-right: 12px; color: #666; text-decoration: none; }
        .error-list { margin-bottom: 12px; padding: 10px; background: #fff1f1; border: 1px solid #ffc8c8; color: #9a1a1a; border-radius: 8px; }
    </style>
    <div class="container">
        @if ($errors->any())
            <div class="error-list">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.subcategories.update', $subcategory) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $subcategory->category_id) === $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $subcategory->name) }}" required>
            </div>

            <div class="field">
                <label for="bg_image">Background Image</label>
                <input id="bg_image" type="file" name="bg_image" accept=".jpg,.jpeg,.png,.webp">
                @if ($subcategory->bg_image)
                    <div class="preview">
                        <img src="{{ asset('storage/'.$subcategory->bg_image) }}" alt="{{ $subcategory->name }}">
                    </div>
                @endif
            </div>

            <div class="field">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $subcategory->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <a class="link" href="{{ route('admin.subcategories.index') }}">Back</a>
            <button class="btn" type="submit">Update</button>
        </form>
    </div>
@endsection
