<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\ExpenseClaims;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpenseClaims>
 */
class ExpenseClaimsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => fake()->randomElement(
                Employee::pluck('id')->toArray()
            ),
            'category' => fake()->name(),
            'amount' => fake()->randomFloat(2, 30000, 100000),
            'expense_date' => fake()->date(),
            'description' => fake()->name(),
            'receipt_path' => fake()->name(),
            'status' => fake()->randomElement(
                ['pending', 'approved', 'rejected'],
            ),

            'reviewed_by' => fake()->randomElement(
                User::pluck('id')->toArray()
            ),
            'reviewed_at' => fake()->date(),
            'rejection_reason' => fake()->name(),
        ];
    }
}
