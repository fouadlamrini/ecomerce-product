<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()->latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $bgImagePath = $this->storeBgImage($request->file('bg_image'));

        Category::query()->create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'bg_image' => $bgImagePath,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();
        $bgImagePath = $category->bg_image;
        $newImage = $request->file('bg_image');
        if ($newImage instanceof UploadedFile) {
            if ($category->bg_image) {
                Storage::disk('public')->delete($category->bg_image);
            }
            $bgImagePath = $this->storeBgImage($newImage);
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $category->name !== $validated['name']
                ? $this->generateUniqueSlug($validated['name'], $category->id)
                : $category->slug,
            'bg_image' => $bgImagePath,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->bg_image) {
            Storage::disk('public')->delete($category->bg_image);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
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
        return Category::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function storeBgImage(?UploadedFile $file): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        return $file->store('category-bg', 'public');
    }
}
