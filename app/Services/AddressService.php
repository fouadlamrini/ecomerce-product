<?php

namespace App\Services;

use App\Models\Address;

class AddressService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Address::class;
    }
}
