<?php

namespace App\Services;

use App\Models\Shipping;

class ShippingService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Shipping::class;
    }
}
