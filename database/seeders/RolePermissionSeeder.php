<?php

namespace Database\Seeders;

use App\Models\Auth\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RolePermission::insert([
            [
                'role_id' => 4, // admin role
                'permission_id' => 14,
            ],
            [
                'role_id' => 4, // admin role
                'permission_id' => 17,
            ],
            [
                'role_id' => 4, // admin role
                'permission_id' => 18,
            ],
            [
                'role_id' => 5, // user role
                'permission_id' => 15,
            ],
            [
                'role_id' => 6, // manager role
                'permission_id' => 16,
            ],
        ]);
    }
}
