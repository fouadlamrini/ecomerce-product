<?php

namespace App\Services;

use App\Models\Subcategory;

class SubcategoryService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Subcategory::class;
    }
}
