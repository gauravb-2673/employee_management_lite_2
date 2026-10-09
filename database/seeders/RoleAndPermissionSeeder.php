<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear Spatie's cached permissions before changing them.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Step 1: Create roles if they do not already exist.
        $roles = [
            'guest',
            'employee',
            'hr',
            'admin',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        // Step 2: Create permissions if they do not already exist.
        $permissions = [
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        // Step 3: Assign all four permissions to admin.
        $adminRole = Role::findByName('admin', 'web');

        $adminRole->syncPermissions([
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
        ]);

        // Step 4: Assign three permissions to hr.
        $hrRole = Role::findByName('hr', 'web');

        $hrRole->syncPermissions([
            'view employees',
            'create employees',
            'edit employees',
        ]);

        // Step 5: Assign only the view permission to employee.
        $employeeRole = Role::findByName('employee', 'web');

        $employeeRole->syncPermissions([
            'view employees',
        ]);

        // Step 6: Give guest no permissions.
        $guestRole = Role::findByName('guest', 'web');

        $guestRole->syncPermissions([]);

        // Clear the cache after all role-permission changes.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
