<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => strtoupper(fake()->lexify('?????')).' '.fake()->word(),
            'owner_id' => User::inRandomOrder()->value('id'),
        ];
    }
}
