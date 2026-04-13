<?php

namespace App\Http\Controllers\Client\Concerns;

use App\Models\Cart;
use Illuminate\Http\Request;

trait InteractsWithCart
{
    protected function activeCart(Request $request): ?Cart
    {
        $user = $request->user();
        if (! $user) {
            return null;
        }

        return Cart::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();
    }

    /** Total units in the active cart (sum of line quantities). */
    protected function cartQuantity(Request $request): int
    {
        $cart = $this->activeCart($request);
        if (! $cart) {
            return 0;
        }

        return (int) $cart->items()->sum('quantity');
    }
}
