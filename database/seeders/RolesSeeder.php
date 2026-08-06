<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin', 'description' => 'Administrator role with full access']);
        Role::create(['name' => 'User', 'description' => 'Regular user role']);
        Role::create(['name' => 'Manager', 'description' => 'Manager role with limited access']);
    }
}
