<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Categories</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f7f7f7; }
        .container { max-width: 980px; margin: 30px auto; background: #fff; border-radius: 12px; padding: 24px; }
        .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { display: inline-block; background: #f16743; color: #fff; text-decoration: none; padding: 8px 14px; border-radius: 8px; border: 0; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        .badge { padding: 3px 8px; border-radius: 999px; font-size: 12px; }
        .badge-on { background: #e7f8ec; color: #17733a; }
        .badge-off { background: #ffe9e9; color: #9f1d1d; }
        .actions { display: flex; gap: 8px; align-items: center; }
        .icon-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; text-decoration: none; border: 1px solid #e9e9e9; background: #fff; color: #444; }
        .icon-btn svg { width: 18px; height: 18px; }
        .icon-btn:hover { background: #f9f9f9; }
        .icon-btn.edit { color: #1f5fbf; border-color: #cfe0fb; }
        .icon-btn.view { color: #197857; border-color: #cceee1; }
        .icon-btn.delete { color: #b00020; border-color: #f1c5ce; cursor: pointer; }
        .icon-btn.delete:hover { background: #fff4f6; }
        .success { margin-bottom: 12px; padding: 10px; background: #effff3; border: 1px solid #b6efc1; color: #176029; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="top">
            <h2>Category Management</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn">+ New Category</a>
        </div>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th style="width: 240px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'badge-on' : 'badge-off' }}">
                                {{ $category->is_active ? 'active' : 'inactive' }}
                            </span>
                        </td>
                        <td class="actions">
                            <a class="icon-btn view" title="View" href="{{ route('admin.categories.show', $category) }}" aria-label="View category">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                            <a class="icon-btn edit" title="Edit" href="{{ route('admin.categories.edit', $category) }}" aria-label="Edit category">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn delete" title="Delete" aria-label="Delete category">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6l-1 14H6L5 6"></path>
                                        <path d="M10 11v6"></path>
                                        <path d="M14 11v6"></path>
                                        <path d="M9 6V4h6v2"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 16px;">
            {{ $categories->links() }}
        </div>
    </div>
</body>
</html>
