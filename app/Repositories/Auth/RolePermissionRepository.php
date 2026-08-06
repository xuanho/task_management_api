<?php

namespace App\Repositories\Auth;

use App\Enums\Auth\PermissionEnum;
use App\Interfaces\Auth\RolePermissionRepositoryInterface;
use App\Models\Auth\RolePermission;
use Illuminate\Support\Facades\Cache;

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function roleHasPermission(int $roleId, PermissionEnum $permission): bool
    {
        return Cache::remember("role_permissions_{$roleId}_{$permission->value}", 60, function () use ($roleId, $permission) {
            return RolePermission::query()
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $roleId)
                ->where('permissions.name', $permission->value)
                ->exists();
        });

    }
}
