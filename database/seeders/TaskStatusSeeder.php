<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskStatus::insert([
            ['code' => 'todo', 'name' => 'To Do', 'color' => '#6c757d', 'order' => 1],
            ['code' => 'in_progress', 'name' => 'In Progress', 'color' => '#0d6efd', 'order' => 2],
            ['code' => 'done', 'name' => 'Done', 'color' => '#198754', 'order' => 3],
            ['code' => 'blocked', 'name' => 'Blocked', 'color' => '#dc3545', 'order' => 4],
        ]);
    }
}
