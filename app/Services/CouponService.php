<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Coupon::class;
    }
}
