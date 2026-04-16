<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\Concerns\InteractsWithCart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\StripeCheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;
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
            'coupon_code' => ['nullable', 'string', 'max:50'],
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

            $coupon = null;
            $discountAmount = 0.0;
            $couponCode = strtoupper(trim((string) ($validated['coupon_code'] ?? '')));
            if ($couponCode !== '') {
                $coupon = Coupon::query()
                    ->lockForUpdate()
                    ->whereRaw('UPPER(code) = ?', [$couponCode])
                    ->first();

                if (! $coupon) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'Promo code is invalid.',
                    ]);
                }

                if (! $coupon->is_active) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'This promo code is not active.',
                    ]);
                }

                $now = now();
                if ($coupon->starts_at && $coupon->starts_at->gt($now)) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'This promo code is not active yet.',
                    ]);
                }
                if ($coupon->expires_at && $coupon->expires_at->lt($now)) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'This promo code has expired.',
                    ]);
                }
                if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'This promo code has reached its usage limit.',
                    ]);
                }
                if ($coupon->min_order_amount !== null && $subtotal < (float) $coupon->min_order_amount) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'Minimum order amount for this code is '.number_format((float) $coupon->min_order_amount, 2).'.',
                    ]);
                }

                if ($coupon->type === 'percentage') {
                    $discountAmount = round($subtotal * ((float) $coupon->value / 100), 2);
                } else {
                    $discountAmount = min((float) $coupon->value, $subtotal);
                }
            }

            $total = max(0, round($subtotal - $discountAmount, 2));

            $order = Order::query()->create([
                'user_id' => $user->id,
                'address_id' => $validated['address_id'],
                'coupon_id' => $coupon?->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_amount' => 0,
                'total' => $total,
                'payment_status' => 'UNPAID',
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

            if ($coupon) {
                $coupon->increment('used_count');
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
        $this->ensureOwnedOrder($request, $order);

        $order->load(['items.product.images', 'address', 'shipping']);

        return view('client.orders.show', [
            'order' => $order,
        ]);
    }

    public function orders(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->latest()
            ->paginate(12);

        return view('client.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function pay(Request $request, Order $order, StripeCheckoutService $stripeCheckout): View|RedirectResponse
    {
        $this->ensureOwnedOrder($request, $order);

        if (strtolower((string) $order->payment_status) === 'paid') {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('success', 'This order is already paid.');
        }

        $order->loadMissing(['items.product', 'user']);

        try {
            $paymentIntent = $stripeCheckout->createPaymentIntent($order);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('error', $exception->getMessage());
        }

        $stripePublishableKey = (string) config('services.stripe.key', '');
        if ($stripePublishableKey === '') {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('error', 'Stripe publishable key is not configured.');
        }

        return view('client.orders.payment', [
            'order' => $order,
            'stripePublishableKey' => $stripePublishableKey,
            'paymentIntentClientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function confirmPayment(Request $request, Order $order, StripeCheckoutService $stripeCheckout): JsonResponse
    {
        $this->ensureOwnedOrder($request, $order);

        if (strtolower((string) $order->payment_status) === 'paid') {
            return response()->json([
                'ok' => true,
                'redirect_url' => route('client.orders.payment.success', $order),
            ]);
        }

        $validated = $request->validate([
            'payment_intent_id' => ['required', 'string', 'max:255'],
        ]);

        try {
            $paymentIntent = $stripeCheckout->retrievePaymentIntent($validated['payment_intent_id']);
        } catch (RuntimeException $exception) {
            return response()->json(['ok' => false, 'message' => $exception->getMessage()], 422);
        }

        $metadataOrderId = (string) data_get($paymentIntent, 'metadata.order_id', '');
        if ($metadataOrderId !== (string) $order->id) {
            return response()->json(['ok' => false, 'message' => 'Payment does not belong to this order.'], 403);
        }

        if ((string) $paymentIntent->status !== 'succeeded') {
            return response()->json(['ok' => false, 'message' => 'Payment has not been completed yet.'], 422);
        }

        $order->payment_status = 'paid';
        if ($order->status === 'pending') {
            $order->status = 'processing';
        }
        $order->save();

        return response()->json([
            'ok' => true,
            'redirect_url' => route('client.orders.payment.success', $order),
        ]);
    }

    public function paymentSuccess(Request $request, Order $order): View
    {
        $this->ensureOwnedOrder($request, $order);

        return view('client.orders.payment-success', [
            'order' => $order->fresh(),
        ]);
    }

    public function paymentCancel(Request $request, Order $order): View
    {
        $this->ensureOwnedOrder($request, $order);

        return view('client.orders.payment-cancel', [
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

    private function ensureOwnedOrder(Request $request, Order $order): void
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
