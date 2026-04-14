@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
    <div class="max-w-[860px]">
        @if ($errors->any())
            <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="category_id">Category</label>
                    <select class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="category_id" name="category_id">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="subcategory_id">Subcategory</label>
                    <select class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="subcategory_id" name="subcategory_id">
                        <option value="">Select subcategory</option>
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}" {{ old('subcategory_id') === $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="mb-1.5 block text-sm font-bold" for="name">Name</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="name" type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="price">Price</label>
                    <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="price" type="number" step="0.01" name="price" value="{{ old('price') }}" required>
                </div>
                <div class="mb-3">
                    <label class="mb-1.5 block text-sm font-bold" for="compare_price">Compare Price</label>
                    <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="compare_price" type="number" step="0.01" name="compare_price" value="{{ old('compare_price') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="mb-1.5 block text-sm font-bold" for="stock">Stock</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="stock" type="number" min="0" name="stock" value="{{ old('stock', 0) }}" required>
            </div>

            <div class="mb-3">
                <label class="mb-1.5 block text-sm font-bold" for="description">Description</label>
                <textarea class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="mb-1.5 block text-sm font-bold" for="main_image">Main Image</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="main_image" type="file" name="main_image" accept=".jpg,.jpeg,.png,.webp" required>
                <div id="mainImagePreview" class="mt-2 flex flex-wrap gap-2.5"></div>
            </div>

            <div class="mb-4">
                <label class="mb-1.5 block text-sm font-bold" for="secondary_images">Secondary Images (multiple)</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="secondary_images" type="file" name="secondary_images[]" multiple accept=".jpg,.jpeg,.png,.webp">
                <div id="secondaryCounter" class="mt-1 text-xs text-slate-500">0 / 20 selected</div>
                <div id="secondaryPreview" class="mt-2 flex flex-wrap gap-2.5"></div>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input class="h-4 w-4 rounded border-slate-300 text-[#f16743] focus:ring-[#f16743]/30" type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <a class="mr-3 text-sm text-slate-600" href="{{ route('admin.products.index') }}">Back</a>
            <button class="rounded-lg bg-[#f16743] px-4 py-2 text-sm font-bold text-white" type="submit">Create</button>
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
                const visible = !selectedCategoryId || option.dataset.categoryId === selectedCategoryId;
                option.hidden = !visible;
                option.disabled = !visible;
            });
            const current = subcategorySelect.selectedOptions[0];
            if (current && current.dataset.categoryId && current.dataset.categoryId !== selectedCategoryId) subcategorySelect.value = '';
        }
        categorySelect.addEventListener('change', filterSubcategories);
        filterSubcategories();

        mainImageInput.addEventListener('change', () => {
            mainImagePreview.innerHTML = '';
            const file = mainImageInput.files[0];
            if (!file) return;
            const card = document.createElement('div');
            card.className = 'w-[90px] rounded-lg border border-slate-200 p-1';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'h-[70px] w-full rounded object-cover';
            card.appendChild(img);
            mainImagePreview.appendChild(card);
        });

        function syncSecondaryInput() {
            const dataTransfer = new DataTransfer();
            secondaryFiles.forEach((file) => dataTransfer.items.add(file));
            secondaryInput.files = dataTransfer.files;
        }

        function renderSecondaryPreview() {
            secondaryPreview.innerHTML = '';
            secondaryCounter.textContent = secondaryFiles.length + ' / 20 selected';

            secondaryFiles.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'w-[90px] rounded-lg border border-slate-200 p-1 text-center';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'mb-1 h-[70px] w-full rounded object-cover';

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.textContent = 'x';
                remove.className = 'rounded bg-red-50 px-1.5 py-0.5 text-xs text-red-700';
                remove.addEventListener('click', () => {
                    secondaryFiles = secondaryFiles.filter((_, i) => i !== index);
                    syncSecondaryInput();
                    renderSecondaryPreview();
                });

                card.appendChild(img);
                card.appendChild(remove);
                secondaryPreview.appendChild(card);
            });
        }

        secondaryInput.addEventListener('change', () => {
            const incoming = Array.from(secondaryInput.files || []);
            secondaryFiles = secondaryFiles.concat(incoming).slice(0, 20);
            syncSecondaryInput();
            renderSecondaryPreview();
        });
    </script>
@endsection
