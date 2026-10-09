<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EmployeeProjectSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Retrieve existing employees.
        $employees = Employee::orderBy('id')->get();

        // 2. Retrieve existing projects.
        $projects = Project::orderBy('id')->get();

        // 3. Stop if employees or projects do not exist.
        if ($employees->isEmpty()) {
            throw new RuntimeException(
                'No employees found. Seed employees first.'
            );
        }

        if ($projects->isEmpty()) {
            throw new RuntimeException(
                'No projects found. Seed projects first.'
            );
        }

        // Retrieve all roles directly from role_master.
        $roles = DB::table('role_master')
            ->pluck('id');

        // Stop if no roles exist.
        if ($roles->isEmpty()) {
            throw new RuntimeException(
                'No roles found in role_master. Seed the roles first.'
            );
        }

        // 6. Assign each employee to a project with a valid role.
        foreach ($employees as $index => $employee) {

            // Select a project from the existing projects.
            $project = $projects[$index % $projects->count()];

            // Select a random role from the available roles.
            $roleId = $roles->random();


            // 7. Create the relationship without duplicating it.
            $employee->projects()->syncWithoutDetaching([
                $project->id => [
                    'role' => $roleId,
                ],
            ]);
        }
    }
}
