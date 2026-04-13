@extends('admin.layouts.app')

@section('title', 'Category Details')

@section('content')
    <style>
        .container { max-width: 760px; }
        .item { margin-bottom: 12px; }
        .label { color: #666; font-size: 13px; }
        .value { font-size: 16px; }
        .btn { display: inline-block; margin-right: 8px; text-decoration: none; border-radius: 8px; padding: 8px 14px; }
        .btn-primary { background: #f16743; color: #fff; }
        .btn-muted { background: #efefef; color: #444; }
    </style>
    <div class="container">

        <div class="item">
            <div class="label">Name</div>
            <div class="value">{{ $category->name }}</div>
        </div>

        <div class="item">
            <div class="label">Slug</div>
            <div class="value">{{ $category->slug }}</div>
        </div>

        <div class="item">
            <div class="label">Status</div>
            <div class="value">{{ $category->is_active ? 'active' : 'inactive' }}</div>
        </div>

        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-muted">Back</a>
    </div>
@endsection
