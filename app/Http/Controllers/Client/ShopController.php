<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
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
            ->where('is_active', true)
            ->with([
                'subcategories' => fn ($query) => $query->where('is_active', true),
                'products.images',
            ])
            ->orderBy('name')
            ->get();

        return view('client.categories.index', [
            'categories' => $categories,
            'cartCount' => $this->cartCount($request),
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
                'cartCount' => $this->cartCount($request),
            ]);
        }

        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['images', 'category', 'subcategory'])
            ->paginate(12);

        return view('client.products.index', [
            'title' => 'Products - '.$category->name,
            'products' => $products,
            'cartCount' => $this->cartCount($request),
        ]);
    }

    public function showSubcategory(Request $request, Subcategory $subcategory): View
    {
        $products = Product::query()
            ->where('subcategory_id', $subcategory->id)
            ->where('is_active', true)
            ->with(['images', 'category', 'subcategory'])
            ->paginate(12);

        return view('client.products.index', [
            'title' => 'Products - '.$subcategory->name,
            'products' => $products,
            'cartCount' => $this->cartCount($request),
        ]);
    }

    public function showProduct(Request $request, Product $product): View
    {
        $product->load(['images', 'category', 'subcategory']);

        return view('client.products.show', [
            'product' => $product,
            'cartCount' => $this->cartCount($request),
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        $cart = Cart::query()->firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['session_id' => $request->session()->getId()]
        );

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $product->price,
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    private function cartCount(Request $request): int
    {
        $user = $request->user();
        if (! $user) {
            return 0;
        }

        return Cart::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->withCount('items')
            ->first()
            ?->items_count ?? 0;
    }
}
