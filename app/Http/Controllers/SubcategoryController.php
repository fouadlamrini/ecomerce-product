<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subcategory\StoreSubcategoryRequest;
use App\Http\Requests\Subcategory\UpdateSubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function index(): View
    {
        $subcategories = Subcategory::query()
            ->with('category:id,name')
            ->latest()
            ->paginate(10);

        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create(Request $request): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        $selectedCategoryId = $request->query('category_id');

        return view('admin.subcategories.create', compact('categories', 'selectedCategoryId'));
    }

    public function store(StoreSubcategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $bgImagePath = $this->storeBgImage($request->file('bg_image'));

        Subcategory::query()->create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'bg_image' => $bgImagePath,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory created successfully.');
    }

    public function show(Subcategory $subcategory): View
    {
        $subcategory->load('category:id,name');

        return view('admin.subcategories.show', compact('subcategory'));
    }

    public function edit(Subcategory $subcategory): View
    {
        $categories = Category::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $validated = $request->validated();
        $bgImagePath = $subcategory->bg_image;
        $newImage = $request->file('bg_image');
        if ($newImage instanceof UploadedFile) {
            if ($subcategory->bg_image) {
                Storage::disk('public')->delete($subcategory->bg_image);
            }
            $bgImagePath = $this->storeBgImage($newImage);
        }

        $subcategory->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $subcategory->name !== $validated['name']
                ? $this->generateUniqueSlug($validated['name'], $subcategory->id)
                : $subcategory->slug,
            'bg_image' => $bgImagePath,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        if ($subcategory->bg_image) {
            Storage::disk('public')->delete($subcategory->bg_image);
        }

        $subcategory->delete();

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
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
        return Subcategory::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function storeBgImage(?UploadedFile $file): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        return $file->store('subcategory-bg', 'public');
    }
}
