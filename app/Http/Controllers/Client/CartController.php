<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\Concerns\InteractsWithCart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CartController extends Controller
{
    use InteractsWithCart;

    public function increment(Request $request, CartItem $cartItem): JsonResponse|RedirectResponse
    {
        if (! $this->ownsCartItem($request, $cartItem)) {
            return $this->forbiddenCartResponse($request);
        }

        $cartItem->load('product');
        $product = $cartItem->product;
        $stock = (int) $product->stock;

        if ($cartItem->quantity >= $stock) {
            $message = 'You cannot add more than available stock ('.$stock.').';
            if ($this->shouldReturnJson($request)) {
                return response()->json(['ok' => false, 'message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $cartItem->increment('quantity');

        if ($this->shouldReturnJson($request)) {
            return $this->jsonCartState($request);
        }

        return back();
    }

    public function decrement(Request $request, CartItem $cartItem): JsonResponse|RedirectResponse
    {
        if (! $this->ownsCartItem($request, $cartItem)) {
            return $this->forbiddenCartResponse($request);
        }

        $removedLine = $cartItem->quantity <= 1;
        if ($removedLine) {
            $cartItem->delete();
        } else {
            $cartItem->decrement('quantity');
        }

        if ($this->shouldReturnJson($request)) {
            return $this->jsonCartState($request);
        }

        return $removedLine
            ? back()->with('success', 'Item removed from cart.')
            : back();
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse|RedirectResponse
    {
        if (! $this->ownsCartItem($request, $cartItem)) {
            return $this->forbiddenCartResponse($request);
        }

        $cartItem->delete();

        if ($this->shouldReturnJson($request)) {
            return $this->jsonCartState($request);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cart = $this->activeCart($request);
        $items = $cart
            ? $cart->items()->with(['product'])->orderBy('created_at')->get()
            : collect();

        if ($items->isEmpty()) {
            return redirect()
                ->route('client.categories.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = $items->sum(fn (CartItem $item): float => $item->quantity * (float) $item->unit_price);
        $addresses = $request->user()
            ->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('client.checkout.show', [
            'items' => $items,
            'subtotal' => $subtotal,
            'addresses' => $addresses,
        ]);
    }

    private function ownsCartItem(Request $request, CartItem $cartItem): bool
    {
        $cart = $this->activeCart($request);

        return $cart !== null && $cartItem->cart_id === $cart->id;
    }

    private function shouldReturnJson(Request $request): bool
    {
        return $request->expectsJson() || $request->ajax();
    }

    private function forbiddenCartResponse(Request $request): JsonResponse|RedirectResponse
    {
        if ($this->shouldReturnJson($request)) {
            return response()->json(['ok' => false, 'message' => 'Forbidden.'], 403);
        }

        abort(403);
    }

    private function jsonCartState(Request $request): JsonResponse
    {
        $state = $this->cartSnapshot($request);

        return response()->json([
            'ok' => true,
            'cartCount' => $state['cartCount'],
            'drawerBodyHtml' => view('client.partials.cart-drawer-body', [
                'cartDrawerItems' => $state['items'],
                'cartDrawerSubtotal' => $state['subtotal'],
            ])->render(),
        ]);
    }

    /** @return array{items: Collection, subtotal: float, cartCount: int} */
    private function cartSnapshot(Request $request): array
    {
        $cart = $this->activeCart($request);
        $items = $cart
            ? $cart->items()->with(['product.images'])->orderBy('created_at')->get()
            : collect();

        return [
            'items' => $items,
            'subtotal' => (float) $items->sum(fn (CartItem $item): float => $item->quantity * (float) $item->unit_price),
            'cartCount' => (int) $items->sum('quantity'),
        ];
    }
}
