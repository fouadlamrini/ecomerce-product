@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
    <style>
        .container { max-width: 860px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .field { margin-bottom: 14px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea, select { width: 100%; border: 1px solid #ddd; border-radius: 8px; padding: 10px; }
        .btn { background: #f16743; color: #fff; border: 0; border-radius: 8px; padding: 9px 16px; cursor: pointer; }
        .link { margin-right: 12px; color: #666; text-decoration: none; }
        .error-list { margin-bottom: 12px; padding: 10px; background: #fff1f1; border: 1px solid #ffc8c8; color: #9a1a1a; border-radius: 8px; }
        .preview-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
        .preview-card { width: 90px; border: 1px solid #e5e7eb; border-radius: 8px; padding: 5px; text-align: center; }
        .preview-card img { width: 100%; height: 70px; object-fit: cover; border-radius: 6px; margin-bottom: 4px; }
        .muted { color: #6b7280; font-size: 12px; }
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

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid">
                <div class="field">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="subcategory_id">Subcategory</label>
                    <select id="subcategory_id" name="subcategory_id">
                        <option value="">Select subcategory</option>
                        @foreach ($subcategories as $subcategory)
                            <option
                                value="{{ $subcategory->id }}"
                                data-category-id="{{ $subcategory->category_id }}"
                                {{ old('subcategory_id') === $subcategory->id ? 'selected' : '' }}
                            >
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="price">Price</label>
                    <input id="price" type="number" step="0.01" name="price" value="{{ old('price') }}" required>
                </div>
                <div class="field">
                    <label for="compare_price">Compare Price</label>
                    <input id="compare_price" type="number" step="0.01" name="compare_price" value="{{ old('compare_price') }}">
                </div>
            </div>

            <div class="field">
                <label for="stock">Stock</label>
                <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', 0) }}" required>
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="field">
                <label for="main_image">Main Image</label>
                <input id="main_image" type="file" name="main_image" accept=".jpg,.jpeg,.png,.webp" required>
                <div id="mainImagePreview" class="preview-list"></div>
            </div>

            <div class="field">
                <label for="secondary_images">Secondary Images (multiple)</label>
                <input id="secondary_images" type="file" name="secondary_images[]" multiple accept=".jpg,.jpeg,.png,.webp">
                <div id="secondaryCounter" class="muted">0 / 20 selected</div>
                <div id="secondaryPreview" class="preview-list"></div>
            </div>

            <div class="field">
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}> Active</label>
            </div>

            <a class="link" href="{{ route('admin.products.index') }}">Back</a>
            <button class="btn" type="submit">Create</button>
        </form>
    </div>

    <script>
        const categorySelect = document.getElementById('category_id');
        const subcategorySelect = document.getElementById('subcategory_id');
        const subcategoryOptions = Array.from(subcategorySelect.querySelectorAll('option[data-category-id]'));
        const mainImageInput = document.getElementById('main_image');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const secondaryInput = document.getElementById('secondary_images');
        const secondaryPreview = document.getElementById('secondaryPreview');
        const secondaryCounter = document.getElementById('secondaryCounter');
        let secondaryFiles = [];

        function filterSubcategories() {
            const selectedCategoryId = categorySelect.value;

            subcategoryOptions.forEach((option) => {
                const isVisible = !selectedCategoryId || option.dataset.categoryId === selectedCategoryId;
                option.hidden = !isVisible;
                option.disabled = !isVisible;
            });

            const current = subcategorySelect.selectedOptions[0];
            if (current && current.dataset.categoryId && current.dataset.categoryId !== selectedCategoryId) {
                subcategorySelect.value = '';
            }
        }

        categorySelect.addEventListener('change', filterSubcategories);
        filterSubcategories();

        mainImageInput.addEventListener('change', () => {
            mainImagePreview.innerHTML = '';
            const file = mainImageInput.files[0];
            if (!file) return;

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);

            const card = document.createElement('div');
            card.className = 'preview-card';
            card.appendChild(img);
            mainImagePreview.appendChild(card);
        });

        function renderSecondaryPreview() {
            secondaryPreview.innerHTML = '';
            secondaryCounter.textContent = secondaryFiles.length + ' / 20 selected';

            secondaryFiles.forEach((file, index) => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.textContent = 'x';
                remove.style.border = '0';
                remove.style.background = '#fff1f1';
                remove.style.color = '#b42318';
                remove.style.borderRadius = '6px';
                remove.style.cursor = 'pointer';
                remove.addEventListener('click', () => {
                    secondaryFiles = secondaryFiles.filter((_, i) => i !== index);
                    syncSecondaryInput();
                    renderSecondaryPreview();
                });

                const card = document.createElement('div');
                card.className = 'preview-card';
                card.appendChild(img);
                card.appendChild(remove);
                secondaryPreview.appendChild(card);
            });
        }

        function syncSecondaryInput() {
            const dataTransfer = new DataTransfer();
            secondaryFiles.forEach((file) => dataTransfer.items.add(file));
            secondaryInput.files = dataTransfer.files;
        }

        secondaryInput.addEventListener('change', () => {
            const incoming = Array.from(secondaryInput.files || []);
            secondaryFiles = secondaryFiles.concat(incoming).slice(0, 20);
            syncSecondaryInput();
            renderSecondaryPreview();
        });
    </script>
@endsection
