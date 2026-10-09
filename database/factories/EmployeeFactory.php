<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => fake()->randomElement(
                Department::pluck('id')->toArray()
            ),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'salary' => fake()->randomFloat(2, 30000, 100000),
            'joining_date' => fake()->date(),
            'is_active' => fake()->boolean(),
        ];
    }
}
