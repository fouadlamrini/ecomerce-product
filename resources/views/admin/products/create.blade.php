@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
    <style>
        .field { margin-bottom: 14px; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        label { display:block; margin-bottom:6px; font-weight:700; }
        input, textarea, select { width:100%; border:1px solid #ddd; border-radius:8px; padding:10px; }
        .preview-list { display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
        .preview-card { width:90px; border:1px solid #e5e7eb; border-radius:8px; padding:5px; text-align:center; }
        .preview-card img { width:100%; height:70px; object-fit:cover; border-radius:6px; margin-bottom:4px; }
        .btn { background:#f16743; color:#fff; border:0; border-radius:8px; padding:9px 16px; cursor:pointer; }
        .error-list { margin-bottom:12px; padding:10px; background:#fff1f1; border:1px solid #ffc8c8; color:#9a1a1a; border-radius:8px; }
        .muted { color:#6b7280; font-size:12px; }
    </style>

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
                <label>Category</label>
                <select id="category_id" name="category_id">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Subcategory</label>
                <select id="subcategory_id" name="subcategory_id">
                    <option value="">Select subcategory</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}">{{ $subcategory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="field">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="grid">
            <div class="field"><label>Price</label><input type="number" step="0.01" name="price" required></div>
            <div class="field"><label>Compare Price</label><input type="number" step="0.01" name="compare_price"></div>
        </div>
        <div class="field"><label>Stock</label><input type="number" min="0" name="stock" value="0" required></div>
        <div class="field"><label>Description</label><textarea name="description" rows="4"></textarea></div>
        <div class="field"><label>Main Image</label><input id="main_image" type="file" name="main_image" required></div>
        <div id="mainImagePreview" class="preview-list"></div>
        <div class="field"><label>Secondary Images (max 20)</label><input id="secondary_images" type="file" name="secondary_images[]" multiple></div>
        <div id="secondaryCounter" class="muted">0 / 20 selected</div>
        <div id="secondaryPreview" class="preview-list"></div>
        <div class="field"><label><input type="checkbox" name="is_active" value="1"> Active</label></div>
        <button class="btn" type="submit">Create</button>
    </form>

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
                const visible = !selectedCategoryId || option.dataset.categoryId === selectedCategoryId;
                option.hidden = !visible;
                option.disabled = !visible;
            });
            if (subcategorySelect.selectedOptions[0]?.dataset.categoryId !== selectedCategoryId) subcategorySelect.value = '';
        }
        categorySelect.addEventListener('change', filterSubcategories);
        filterSubcategories();

        mainImageInput.addEventListener('change', () => {
            mainImagePreview.innerHTML = '';
            const file = mainImageInput.files[0];
            if (!file) return;
            const card = document.createElement('div');
            card.className = 'preview-card';
            card.innerHTML = '<img src="' + URL.createObjectURL(file) + '">';
            mainImagePreview.appendChild(card);
        });

        function syncSecondaryInput() {
            const dt = new DataTransfer();
            secondaryFiles.forEach((f) => dt.items.add(f));
            secondaryInput.files = dt.files;
        }

        function renderSecondaryPreview() {
            secondaryPreview.innerHTML = '';
            secondaryCounter.textContent = secondaryFiles.length + ' / 20 selected';
            secondaryFiles.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'preview-card';
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = 'x';
                btn.onclick = () => {
                    secondaryFiles = secondaryFiles.filter((_, i) => i !== index);
                    syncSecondaryInput();
                    renderSecondaryPreview();
                };
                card.innerHTML = '<img src="' + URL.createObjectURL(file) + '">';
                card.appendChild(btn);
                secondaryPreview.appendChild(card);
            });
        }

        secondaryInput.addEventListener('change', () => {
            secondaryFiles = secondaryFiles.concat(Array.from(secondaryInput.files || [])).slice(0, 20);
            syncSecondaryInput();
            renderSecondaryPreview();
        });
    </script>
@endsection
