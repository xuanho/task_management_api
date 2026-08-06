<?php

namespace App\Services\Auth;

use App\Models\Auth\Role;
use Illuminate\Support\Facades\Cache;

class RoleService
{
    public function __construct()
    {
        //
    }

    public function getAdminRoleId()
    {
        return Cache::rememberForever('admin_role_id', function () {
            return Role::where('name', 'Admin')->value('id');
        });
    }
}
