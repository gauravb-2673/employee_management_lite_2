<?php

namespace Database\Factories;

use App\Models\RoleMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoleMaster>
 */
class RoleMasterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_name' => fake()->unique()->randomLetter(20),
        ];
    }
}
