<?php

namespace App\Services;

use App\Models\CartItem;

class CartItemService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = CartItem::class;
    }
}
