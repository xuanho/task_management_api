<?php

namespace Database\Seeders;

use App\Models\Auth\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'project.create', 'description' => 'Permission to create a project']);
        Permission::create(['name' => 'project.view', 'description' => 'Permission to view a project']);
        Permission::create(['name' => 'project.update', 'description' => 'Permission to update a project']);
        Permission::create(['name' => 'project.delete', 'description' => 'Permission to delete a project']);
        Permission::create(['name' => 'task.create', 'description' => 'Permission to create a task']);
        Permission::create(['name' => 'task.view', 'description' => 'Permission to view a task']);
        Permission::create(['name' => 'task.update', 'description' => 'Permission to update a task']);
        Permission::create(['name' => 'task.delete', 'description' => 'Permission to delete a task']);
        Permission::create(['name' => 'task.show', 'description' => 'Permission to show a task']);
    }
}
