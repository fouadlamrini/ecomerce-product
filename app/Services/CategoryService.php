<?php

namespace App\Services;

use App\Models\Category;

class CategoryService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Category::class;
    }
}
