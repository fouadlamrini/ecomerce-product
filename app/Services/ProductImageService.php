<?php

namespace App\Services;

use App\Models\ProductImage;

class ProductImageService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = ProductImage::class;
    }
}
