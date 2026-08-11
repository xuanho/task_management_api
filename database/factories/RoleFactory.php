<?php

namespace Database\Factories;

use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Member',
            'description' => 'Member role with limited permissions',
        ];
    }

    public function admin(): self
    {
        return $this->state([
            'name' => 'Admin',
            'description' => 'Admin role with full permissions',
        ]
        );
    }

    public function manager(): self
    {
        return $this->state([
            'name' => 'Manager',
            'description' => 'Manager role with elevated permissions',
        ]
        );
    }
}
