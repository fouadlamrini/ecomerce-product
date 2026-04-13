<?php

namespace App\View\Composers;

use App\Models\Cart;
use Illuminate\View\View;

class ClientShopComposer
{
    public function compose(View $view): void
    {
        $user = request()->user();
        if (! $user) {
            $view->with([
                'cartCount' => 0,
                'cartDrawerItems' => collect(),
                'cartDrawerSubtotal' => 0.0,
            ]);

            return;
        }

        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (! $cart) {
            $view->with([
                'cartCount' => 0,
                'cartDrawerItems' => collect(),
                'cartDrawerSubtotal' => 0.0,
            ]);

            return;
        }

        $items = $cart->items()->with(['product.images'])->orderBy('created_at')->get();
        $subtotal = $items->sum(fn ($item) => $item->quantity * (float) $item->unit_price);

        $view->with([
            'cartCount' => (int) $items->sum('quantity'),
            'cartDrawerItems' => $items,
            'cartDrawerSubtotal' => (float) $subtotal,
        ]);
    }
}
