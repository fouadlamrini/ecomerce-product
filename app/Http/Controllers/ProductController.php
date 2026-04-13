<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with(['category:id,name', 'subcategory:id,name', 'images'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $subcategories = Subcategory::query()->orderBy('name')->get(['id', 'name', 'category_id']);

        return view('admin.products.create', compact('categories', 'subcategories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request): void {
            $product = Product::query()->create([
                'category_id' => $validated['category_id'] ?? null,
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'compare_price' => $validated['compare_price'] ?? null,
                'stock' => $validated['stock'],
                'is_active' => (bool) ($validated['is_active'] ?? false),
            ]);

            $secondaryFiles = $this->normalizeUploadedFiles($request->file('secondary_images', []));
            if ($secondaryFiles->count() > 20) {
                throw ValidationException::withMessages([
                    'secondary_images' => 'Maximum 20 secondary images are allowed.',
                ]);
            }

            $this->storeMainImage($product, $request->file('main_image'));
            $this->storeSecondaryImages($product, $secondaryFiles->all());
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $product->load(['category:id,name', 'subcategory:id,name', 'images']);

        $galleryImages = $product->images
            ->sortBy('sort_order')
            ->values()
            ->map(fn (ProductImage $image): array => [
                'id' => $image->id,
                'url' => asset('storage/'.$image->path),
                'alt' => $image->alt_text ?: $product->name,
            ])
            ->all();

        return view('admin.products.show', compact('product', 'galleryImages'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $subcategories = Subcategory::query()->orderBy('name')->get(['id', 'name', 'category_id']);
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $product): void {
            $product->update([
                'category_id' => $validated['category_id'] ?? null,
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'name' => $validated['name'],
                'slug' => $product->name !== $validated['name']
                    ? $this->generateUniqueSlug($validated['name'], $product->id)
                    : $product->slug,
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'compare_price' => $validated['compare_price'] ?? null,
                'stock' => $validated['stock'],
                'is_active' => (bool) ($validated['is_active'] ?? false),
            ]);

            $removeImageIds = collect($validated['remove_image_ids'] ?? []);
            if ($removeImageIds->isNotEmpty()) {
                $this->removeImages($product, $removeImageIds);
            }

            $secondaryFiles = $this->normalizeUploadedFiles($request->file('secondary_images', []));
            $secondaryCountAfterUpdate = $product->images()->where('is_primary', false)->count() + $secondaryFiles->count();
            if ($secondaryCountAfterUpdate > 20) {
                throw ValidationException::withMessages([
                    'secondary_images' => 'Maximum 20 secondary images are allowed per product.',
                ]);
            }

            $newMainImage = $request->file('main_image');
            if ($newMainImage instanceof UploadedFile) {
                $this->replaceMainImage($product, $newMainImage);
            }

            $this->storeSecondaryImages($product, $secondaryFiles->all());
            $this->ensurePrimaryImage($product);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product): void {
            $product->load('images');

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            $product->delete();
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function generateUniqueSlug(string $name, ?string $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?string $ignoreId = null): bool
    {
        return Product::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function storeMainImage(Product $product, ?UploadedFile $mainImage): void
    {
        if (! $mainImage instanceof UploadedFile) {
            return;
        }

        $path = $mainImage->store($this->productImageFolder($product), 'public');
        $product->images()->create([
            'path' => $path,
            'alt_text' => $product->name.' main image',
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }

    private function storeSecondaryImages(Product $product, array|UploadedFile|null $secondaryImages): void
    {
        $images = collect(is_array($secondaryImages) ? $secondaryImages : [$secondaryImages])
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->values();

        if ($images->isEmpty()) {
            return;
        }

        $currentMaxSort = (int) $product->images()->max('sort_order');

        foreach ($images as $index => $imageFile) {
            $path = $imageFile->store($this->productImageFolder($product), 'public');
            $product->images()->create([
                'path' => $path,
                'alt_text' => $product->name.' secondary image',
                'is_primary' => false,
                'sort_order' => $currentMaxSort + $index + 1,
            ]);
        }
    }

    private function replaceMainImage(Product $product, UploadedFile $newMainImage): void
    {
        $currentMain = $product->images()->where('is_primary', true)->first();
        if ($currentMain) {
            Storage::disk('public')->delete($currentMain->path);
            $currentMain->delete();
        }

        $this->storeMainImage($product, $newMainImage);
    }

    private function normalizeUploadedFiles(array|UploadedFile|null $files): Collection
    {
        return collect(is_array($files) ? $files : [$files])
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->values();
    }

    private function productImageFolder(Product $product): string
    {
        $categoryName = $product->category?->name ?? 'uncategorized';
        $categorySlug = Str::slug($categoryName) ?: 'uncategorized';

        return 'product/'.$categorySlug;
    }

    private function removeImages(Product $product, Collection $removeImageIds): void
    {
        $images = $product->images()
            ->whereIn('id', $removeImageIds)
            ->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    private function ensurePrimaryImage(Product $product): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        if ($hasPrimary) {
            return;
        }

        $firstImage = $product->images()->orderBy('sort_order')->first();
        if ($firstImage) {
            $firstImage->update(['is_primary' => true]);
        }
    }
}
