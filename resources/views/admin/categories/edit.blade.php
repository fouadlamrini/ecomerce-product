@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <style>
        .container { max-width: 760px; }
        .field { margin-bottom: 14px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input[type="text"] { width: 100%; border: 1px solid #ddd; border-radius: 8px; padding: 10px; }
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

        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required>
            </div>

            <div class="field">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <a class="link" href="{{ route('admin.categories.index') }}">Back</a>
            <button class="btn" type="submit">Update</button>
        </form>
    </div>
@endsection
