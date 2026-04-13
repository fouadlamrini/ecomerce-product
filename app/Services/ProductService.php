<?php

namespace App\Services;

use App\Models\Product;

class ProductService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Product::class;
    }
}
