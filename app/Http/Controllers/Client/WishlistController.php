<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->with(['product.images', 'product.category', 'product.subcategory'])
            ->latest()
            ->paginate(12);

        return view('client.wishlist.index', [
            'items' => $items,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        if (! $product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        Wishlist::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Product removed from wishlist.');
    }
}
