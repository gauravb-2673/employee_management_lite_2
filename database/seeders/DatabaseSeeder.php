<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\Employee;
use App\Models\Project;
use App\Models\RoleMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test66666@example.com',
        // ]);
        Department::factory()->count(3)->create();
        Project::factory()->count(5)->create();
        Employee::factory()->count(20)->create();
        RoleMaster::factory()->count(5)->create();
        // Seed roles and permissions.
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        $this->call([
            EmployeeProjectSeeder::class,
        ]);
    }
}
