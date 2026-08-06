<?php

namespace App\Interfaces\Auth;

use App\Enums\Auth\PermissionEnum;

interface RolePermissionRepositoryInterface
{
    public function roleHasPermission(int $roleId, PermissionEnum $permission): bool;
}
