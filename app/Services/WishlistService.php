<?php

namespace App\Services;

use App\Models\Wishlist;

class WishlistService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Wishlist::class;
    }
}
