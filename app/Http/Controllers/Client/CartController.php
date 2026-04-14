<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\Concerns\InteractsWithCart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        $defaultAddressId = $addresses->firstWhere('is_default', true)?->id ?? $addresses->first()?->id;

        return view('client.checkout.show', [
            'items' => $items,
            'subtotal' => $subtotal,
            'addresses' => $addresses,
            'defaultAddressId' => $defaultAddressId,
        ]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'address_id' => [
                'required',
                'uuid',
                Rule::exists('addresses', 'id')->where(
                    fn ($query) => $query->where('user_id', $user->id)
                ),
            ],
        ]);

        $order = DB::transaction(function () use ($request, $user, $validated): Order {
            $cart = $this->activeCart($request);
            if (! $cart) {
                throw ValidationException::withMessages([
                    'address_id' => 'Your cart is empty.',
                ]);
            }

            $items = $cart->items()->orderBy('created_at')->get();
            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'address_id' => 'Your cart is empty.',
                ]);
            }

            $lockedProducts = Product::query()
                ->whereIn('id', $items->pluck('product_id')->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $product = $lockedProducts->get($item->product_id);
                $stock = (int) ($product?->stock ?? 0);
                if (! $product || $item->quantity > $stock) {
                    throw ValidationException::withMessages([
                        'address_id' => 'Some products are out of stock. Please update your cart and try again.',
                    ]);
                }
            }

            $subtotal = (float) $items->sum(
                fn (CartItem $item): float => $item->quantity * (float) $item->unit_price
            );

            $order = Order::query()->create([
                'user_id' => $user->id,
                'address_id' => $validated['address_id'],
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_amount' => 0,
                'total' => $subtotal,
                'payment_status' => 'unpaid',
            ]);

            foreach ($items as $item) {
                $lineTotal = (float) $item->quantity * (float) $item->unit_price;
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => (int) $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                    'line_total' => $lineTotal,
                ]);

                $lockedProducts->get($item->product_id)?->decrement('stock', (int) $item->quantity);
            }

            $cart->items()->delete();
            // carts has a unique index on (user_id, status), so keep a unique non-active status per order.
            $cart->update(['status' => 'completed-'.$order->id]);

            return $order;
        });

        return redirect()
            ->route('client.orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }

    public function showOrder(Request $request, Order $order): View
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load(['items.product.images', 'address']);

        return view('client.orders.show', [
            'order' => $order,
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

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.now()->format('Ymd').'-'.random_int(1000, 9999);
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
