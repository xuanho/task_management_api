<?php

namespace Database\Seeders;

use App\Models\Project\ProjectMember;
use Illuminate\Database\Seeder;

class ProjectMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectMember::insert([
            [
                'user_id' => 1,
                'project_id' => 4,
                'role_id' => 4,
            ],
            [
                'user_id' => 2,
                'project_id' => 5,
                'role_id' => 5,
            ],
            [
                'user_id' => 3,
                'project_id' => 6,
                'role_id' => 6,
            ],
            [
                'user_id' => 1,
                'project_id' => 5,
                'role_id' => 5,
            ],
        ]);
    }
}
