<?php

namespace App\Services;

use App\Models\Order;

class OrderService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Order::class;
    }
}
