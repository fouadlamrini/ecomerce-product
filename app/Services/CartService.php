<?php

namespace App\Services;

use App\Models\Cart;

class CartService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Cart::class;
    }
}
