<?php

namespace App\Services;

use App\Models\OrderItem;

class OrderItemService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = OrderItem::class;
    }
}
