@extends('client.layouts.app')

@section('title', 'Pay Order')

@section('content')
    <div class="mx-auto max-w-2xl rounded-xl border border-slate-200 bg-white p-6">
        <h1 class="text-2xl font-extrabold">Pay Order {{ $order->order_number }}</h1>
        <p class="mt-2 text-sm text-slate-600">
            Complete your payment securely by card.
        </p>

        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-slate-600">Total</span>
                <span class="font-bold">{{ number_format((float) $order->total, 2) }} {{ strtoupper((string) config('services.stripe.currency', 'mad')) }}</span>
            </div>
        </div>

        <div id="paymentError" class="mt-4 hidden rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"></div>

        <form id="paymentForm" class="mt-4 space-y-4">
            @csrf
            <div id="cardElement" class="rounded-lg border border-slate-300 px-3 py-3"></div>

            <div class="flex items-center gap-3">
                <button id="payButton" type="submit" class="rounded-lg bg-[#635bff] px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90">
                    Pay now
                </button>
                <a href="{{ route('client.orders.show', $order) }}" class="text-sm font-semibold text-[#f16743] hover:opacity-80">
                    Order details
                </a>
                <a href="{{ route('client.orders.payment.cancel', $order) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (function () {
            var publishableKey = @json($stripePublishableKey);
            var clientSecret = @json($paymentIntentClientSecret);
            var confirmUrl = @json(route('client.orders.payment.confirm', $order));
            var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            var errorBox = document.getElementById('paymentError');
            var form = document.getElementById('paymentForm');
            var payButton = document.getElementById('payButton');

            function showError(message) {
                errorBox.textContent = message;
                errorBox.classList.remove('hidden');
            }

            if (!publishableKey || !clientSecret) {
                showError('Stripe payment is not configured.');
                return;
            }

            var stripe = Stripe(publishableKey);
            var elements = stripe.elements({ clientSecret: clientSecret });
            var paymentElement = elements.create('payment');
            paymentElement.mount('#cardElement');

            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                payButton.disabled = true;
                errorBox.classList.add('hidden');

                var result = await stripe.confirmPayment({
                    elements: elements,
                    confirmParams: {
                        return_url: window.location.href,
                    },
                    redirect: 'if_required'
                });

                if (result.error) {
                    showError(result.error.message || 'Payment failed.');
                    payButton.disabled = false;
                    return;
                }

                var paymentIntent = result.paymentIntent;
                if (!paymentIntent) {
                    showError('Payment confirmation failed.');
                    payButton.disabled = false;
                    return;
                }

                var response = await fetch(confirmUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ payment_intent_id: paymentIntent.id })
                });

                var data = await response.json();
                if (!response.ok || !data.ok) {
                    showError(data.message || 'Unable to validate payment.');
                    payButton.disabled = false;
                    return;
                }

                window.location.href = data.redirect_url;
            });
        })();
    </script>
@endsection
