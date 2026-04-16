<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Subcategory;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function home(Request $request): RedirectResponse
    {
        return redirect()->route('client.categories.index');
    }

    public function categories(Request $request): View
    {
        $categories = Category::query()
            ->whereRaw('COALESCE(is_active, 0) <> 0')
            ->with([
                'subcategories' => fn ($query) => $query->where('is_active', true),
                'products.images',
            ])
            ->orderBy('name')
            ->get();

        return view('client.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function showCategory(Request $request, Category $category): View
    {
        $category->load([
            'subcategories' => fn ($query) => $query->where('is_active', true)->with('products.images'),
            'products' => fn ($query) => $query->where('is_active', true)->with('images'),
        ]);

        $activeSubcategories = $category->subcategories;

        if ($activeSubcategories->isNotEmpty()) {
            return view('client.subcategories.index', [
                'category' => $category,
                'subcategories' => $activeSubcategories,
            ]);
        }

        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['images', 'category', 'subcategory'])
            ->paginate(12);
        $wishlistProductIds = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('product_id', $products->pluck('id')->all())
            ->pluck('product_id')
            ->all();

        return view('client.products.index', [
            'title' => 'Products - '.$category->name,
            'products' => $products,
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }

    public function showSubcategory(Request $request, Subcategory $subcategory): View
    {
        $products = Product::query()
            ->where('subcategory_id', $subcategory->id)
            ->where('is_active', true)
            ->with(['images', 'category', 'subcategory'])
            ->paginate(12);
        $wishlistProductIds = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('product_id', $products->pluck('id')->all())
            ->pluck('product_id')
            ->all();

        return view('client.products.index', [
            'title' => 'Products - '.$subcategory->name,
            'products' => $products,
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }

    public function showProduct(Request $request, Product $product): View
    {
        $product->load(['images', 'category', 'subcategory']);
        $galleryImages = $product->images
            ->sortBy('sort_order')
            ->values()
            ->map(fn (ProductImage $image) => [
                'id' => $image->id,
                'url' => asset('storage/'.$image->path),
                'alt' => $image->alt_text ?: $product->name,
            ])->all();

        return view('client.products.show', [
            'product' => $product,
            'galleryImages' => $galleryImages,
            'isWishlisted' => Wishlist::query()
                ->where('user_id', $request->user()->id)
                ->where('product_id', $product->id)
                ->exists(),
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        if (! $product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        $stock = (int) $product->stock;
        if ($stock < 1) {
            return back()->with('error', 'This product is out of stock.');
        }
        $requestedQty = max(1, (int) $request->input('quantity', 1));
        if ($requestedQty > $stock) {
            return back()->with('error', 'You cannot add more than available stock ('.$stock.').');
        }

        $user = $request->user();

        $cart = Cart::query()->firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['session_id' => $request->session()->getId()]
        );

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $nextQty = (int) $item->quantity + $requestedQty;
            if ($nextQty > $stock) {
                return back()->with('error', 'You cannot add more than available stock ('.$stock.').');
            }
            $item->update(['quantity' => $nextQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $requestedQty,
                'unit_price' => $product->price,
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }
}
