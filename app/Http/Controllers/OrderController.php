<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function checkout(Request $request, Order $order): RedirectResponse
    {
        $this->ensureOwnedOrder($request, $order);

        if (strtoupper(trim((string) $order->payment_status)) !== 'UNPAID') {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('success', 'This order is already paid.');
        }

        try {
            $session = $this->createCheckoutSession($order);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('error', $exception->getMessage());
        }

        return redirect()->away((string) $session->url);
    }

    public function success(Request $request): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');
        $orderId = (string) $request->query('order_id', '');

        if ($sessionId === '' || $orderId === '') {
            return redirect()
                ->route('client.orders.index')
                ->with('error', 'Missing Stripe payment session information.');
        }

        /** @var Order|null $order */
        $order = $request->user()
            ->orders()
            ->whereKey($orderId)
            ->first();

        if (! $order) {
            abort(404);
        }

        try {
            $session = $this->retrieveCheckoutSession($sessionId);
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('error', $exception->getMessage());
        }

        $sessionOrderId = (string) data_get($session, 'metadata.order_id', '');
        if ($sessionOrderId === '' || $sessionOrderId !== (string) $order->id) {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('error', 'Payment session does not belong to this order.');
        }

        if (strtolower((string) $session->payment_status) !== 'paid') {
            return redirect()
                ->route('client.orders.show', $order)
                ->with('error', 'Payment is not completed yet.');
        }

        $order->payment_status = 'PAID';
        if ($order->status === 'pending') {
            $order->status = 'processing';
        }
        $order->save();

        $activeCart = Cart::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->first();
        $activeCart?->items()->delete();

        return redirect()
            ->route('client.orders.show', $order)
            ->with('success', 'Payment completed successfully.');
    }

    private function createCheckoutSession(Order $order): CheckoutSession
    {
        $secret = (string) config('services.stripe.secret', '');
        $currency = strtolower((string) config('services.stripe.currency', 'mad'));
        if ($secret === '') {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        Stripe::setApiKey($secret);

        $amount = (int) round((float) $order->total * 100);
        if ($amount <= 0) {
            throw new RuntimeException('Order amount must be greater than zero.');
        }

        $successUrl = route('payment.success').'?session_id={CHECKOUT_SESSION_ID}&order_id='.rawurlencode((string) $order->id);

        try {
            return CheckoutSession::create([
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'unit_amount' => $amount,
                        'product_data' => [
                            'name' => 'Order '.$order->order_number,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'customer_email' => $order->user?->email,
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'order_number' => (string) $order->order_number,
                ],
                'success_url' => $successUrl,
                'cancel_url' => route('client.orders.show', $order),
            ]);
        } catch (ApiErrorException $exception) {
            throw new RuntimeException('Unable to create Stripe checkout session: '.$exception->getMessage(), previous: $exception);
        }
    }

    private function retrieveCheckoutSession(string $sessionId): CheckoutSession
    {
        $secret = (string) config('services.stripe.secret', '');
        if ($secret === '') {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        Stripe::setApiKey($secret);

        try {
            return CheckoutSession::retrieve($sessionId);
        } catch (ApiErrorException $exception) {
            throw new RuntimeException('Unable to retrieve Stripe session: '.$exception->getMessage(), previous: $exception);
        }
    }

    private function ensureOwnedOrder(Request $request, Order $order): void
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
