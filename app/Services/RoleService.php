<?php

namespace App\Services;

use App\Models\Role;

class RoleService extends BaseCrudService
{
    public function __construct()
    {
        $this->modelClass = Role::class;
    }
}
