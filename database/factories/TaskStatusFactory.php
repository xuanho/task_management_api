<?php

namespace Database\Factories;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskStatus>
 */
class TaskStatusFactory extends Factory
{
    protected $model = TaskStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->randomElement(['todo', 'in_progress', 'done', 'blocked']),
            'name' => fake()->randomElement(['To Do', 'In Progress', 'Done', 'Blocked']),
            'color' => fake()->randomElement(['#6c757d', '#0d6efd', '#198754', '#dc3545']),
            'order' => fake()->numberBetween(1, 4),
        ];
    }
}
