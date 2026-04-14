<?php

namespace App\Services;

use App\Models\Order;
use RuntimeException;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeCheckoutService
{
    public function createPaymentIntent(Order $order): PaymentIntent
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

        $metadata = [
            'order_id' => (string) $order->id,
            'order_number' => (string) $order->order_number,
            'user_id' => (string) $order->user_id,
        ];

        try {
            return PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
                'receipt_email' => $order->user?->email,
                'metadata' => $metadata,
            ]);
        } catch (ApiErrorException $exception) {
            throw new RuntimeException('Unable to create payment intent: '.$exception->getMessage(), previous: $exception);
        }
    }

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        $secret = (string) config('services.stripe.secret', '');
        if ($secret === '') {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        Stripe::setApiKey($secret);

        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $exception) {
            throw new RuntimeException('Unable to verify payment intent: '.$exception->getMessage(), previous: $exception);
        }
    }
}
