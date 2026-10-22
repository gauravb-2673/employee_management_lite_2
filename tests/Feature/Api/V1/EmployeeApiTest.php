<?php

use App\Mail\WelcomeEmployeeMail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

// Reset the test database state between tests.
// uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Test 1: List employees
|--------------------------------------------------------------------------
*/

it('lists employees successfully', function () {
    // Arrange: create a department and employees.
    $department = Department::factory()->create([
        'department_name' => 'Testing',
    ]);

    Employee::factory()->count(3)->create([
        'department_id' => $department->id,
    ]);

    // Authenticate the request.
    Sanctum::actingAs(User::factory()->create());

    // Act: request the employee list.
    $response = $this->getJson('/api/v1/employees');

    // Assert: HTTP 200 and a paginated JSON collection.
    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'salary',
                    'joining_date',
                    'department_id',
                    'is_active',
                    'department',
                    'projects',
                ],
            ],
            'links',
            'meta',
        ]);

    // Assert that all three employees are returned.
    $this->assertCount(3, $response->json('data'));
});

/*
|--------------------------------------------------------------------------
| Test 2: Create an employee
|--------------------------------------------------------------------------
*/

it('creates an employee successfully', function () {
    $department = Department::factory()->create([
        'department_name' => 'Testing',
    ]);

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    // Prevent real email delivery while testing queued mail.
    Mail::fake();

    $data = [
        'name' => 'Feature Test Employee',
        'email' => 'feature.employee@example.com',
        'department_id' => $department->id,
        'salary' => '52000.55',
        'joining_date' => now()->toDateString(),
        'is_active' => true,
        'project_ids' => [],
        'project_roles' => [],
    ];

    $response = $this->postJson('/api/v1/employees', $data);

    // Assert that the API returns HTTP 201 Created.
    $response->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'salary',
                'department_id',
                'department',
                'projects',
            ],
        ]);

    // Assert that the employee was persisted.
    $this->assertDatabaseHas('employees', [
        'email' => 'feature.employee@example.com',
        'department_id' => $department->id,
        'salary' => '52000.55',
    ]);

    // Assert that the welcome email was queued.
    Mail::assertQueued(WelcomeEmployeeMail::class);
});

/*
|--------------------------------------------------------------------------
| Test 3: Invalid employee creation
|--------------------------------------------------------------------------
*/

it('returns validation errors for invalid employee data', function () {
    Sanctum::actingAs(User::factory()->create());

    // Send an empty request.
    $response = $this->postJson('/api/v1/employees', []);

    // Laravel should return HTTP 422.
    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name',
            'email',
            'department_id',
            'salary',
            'joining_date',
            'is_active',
        ]);

    // No employee should have been created.
    $this->assertDatabaseCount('employees', 0);
});

/*
|--------------------------------------------------------------------------
| Test 4: Show an employee
|--------------------------------------------------------------------------
*/

it('shows an individual employee', function () {
    $department = Department::factory()->create([
        'department_name' => 'Engineering',
    ]);

    $employee = Employee::factory()->create([
        'department_id' => $department->id,
        'name' => 'Show Test Employee',
    ]);

    // Create the admin role if it does not already exist.
    Role::findOrCreate('admin', 'web');

    $user = User::factory()->create();
    $user->assignRole('admin');

    Sanctum::actingAs($user);

    $response = $this->getJson(
        "/api/v1/employees/{$employee->id}"
    );

    $response->assertOk()
        ->assertJsonPath('data.id', $employee->id)
        ->assertJsonPath('data.email', $employee->email);
});

/*
|--------------------------------------------------------------------------
| Test 5: Update an employee
|--------------------------------------------------------------------------
*/

it('updates an employee successfully', function () {
    $department = Department::factory()->create([
        'department_name' => 'Engineering',
    ]);

    $employee = Employee::factory()->create([
        'department_id' => $department->id,
        'name' => 'Old Employee Name',
        'email' => 'old.employee@example.com',
    ]);

    Role::findOrCreate('admin', 'web');

    $user = User::factory()->create();
    $user->assignRole('admin');

    Sanctum::actingAs($user);

    $data = [
        'name' => 'Updated Employee',
        'email' => 'updated.employee@example.com',
        'department_id' => $department->id,
        'salary' => '65000.00',
        'joining_date' => now()->toDateString(),
        'is_active' => true,
        'project_ids' => [],
        'project_roles' => [],
    ];

    $response = $this->putJson(
        "/api/v1/employees/{$employee->id}",
        $data
    );

    $response->assertOk()
        ->assertJsonPath(
            'data.email',
            'updated.employee@example.com'
        );

    // Verify the database was updated.
    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'email' => 'updated.employee@example.com',
        'salary' => '65000.00',
    ]);

    // Verify the old email is no longer assigned to this employee.
    $this->assertDatabaseMissing('employees', [
        'id' => $employee->id,
        'email' => 'old.employee@example.com',
    ]);
});

/*
|--------------------------------------------------------------------------
| Test 6: Invalid employee update
|--------------------------------------------------------------------------
*/

it('returns validation errors for invalid employee updates', function () {
    $department = Department::factory()->create([
        'department_name' => 'Engineering',
    ]);

    $employee = Employee::factory()->create([
        'department_id' => $department->id,
    ]);

    Role::findOrCreate('admin', 'web');

    $user = User::factory()->create();
    $user->assignRole('admin');

    Sanctum::actingAs($user);

    // The salary has fewer than two decimal places.
    $data = [
        'name' => 'Invalid Update Employee',
        'email' => 'invalid.update@example.com',
        'department_id' => $department->id,
        'salary' => '65000.5',
        'joining_date' => now()->toDateString(),
        'is_active' => true,
        'project_ids' => [],
        'project_roles' => [],
    ];

    $response = $this->putJson(
        "/api/v1/employees/{$employee->id}",
        $data
    );

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['salary']);

    // The original employee email should remain unchanged.
    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'email' => $employee->email,
    ]);
});

/*
|--------------------------------------------------------------------------
| Test 7: Delete an employee
|--------------------------------------------------------------------------
*/

it('soft deletes an employee successfully', function () {
    $department = Department::factory()->create([
        'department_name' => 'Engineering',
    ]);

    $employee = Employee::factory()->create([
        'department_id' => $department->id,
    ]);

    Role::findOrCreate('admin', 'web');

    $user = User::factory()->create();
    $user->assignRole('admin');

    Sanctum::actingAs($user);

    $response = $this->deleteJson(
        "/api/v1/employees/{$employee->id}"
    );

    $response->assertOk()
        ->assertJson([
            'message' => 'Employee deleted successfully.',
        ]);

    // The row should remain, but deleted_at should be populated.
    $this->assertSoftDeleted('employees', [
        'id' => $employee->id,
    ]);
});

/*
|--------------------------------------------------------------------------
| Test 8: Unauthenticated access
|--------------------------------------------------------------------------
*/

it('rejects unauthenticated requests', function () {
    // Do not authenticate a user.
    $response = $this->getJson('/api/v1/employees');

    $response->assertUnauthorized();
});

/*
|--------------------------------------------------------------------------
| Test 9: Employee cannot view another employee
|--------------------------------------------------------------------------
*/

it('forbids an employee from viewing another employees record', function () {
    $department = Department::factory()->create([
        'department_name' => 'Engineering',
    ]);

    // Create the employee role.
    Role::findOrCreate('employee', 'web');

    // The currently authenticated employee.
    $firstUser = User::factory()->create();
    $firstUser->assignRole('employee');

    // Another employee with a different user account.
    $secondUser = User::factory()->create();

    $firstEmployee = Employee::factory()->create([
        'department_id' => $department->id,
        'user_id' => $firstUser->id,
    ]);

    $secondEmployee = Employee::factory()->create([
        'department_id' => $department->id,
        'user_id' => $secondUser->id,
    ]);

    // Authenticate as the first employee.
    Sanctum::actingAs($firstUser);

    // Attempt to view the second employee's record.
    $response = $this->getJson(
        "/api/v1/employees/{$secondEmployee->id}"
    );

    // EmployeePolicy should deny access with HTTP 403.
    $response->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| Test 10: Verify the API Resource structure
|--------------------------------------------------------------------------
*/

it('returns the expected employee and department resource structure', function () {
    $department = Department::factory()->create([
        'department_name' => 'Quality Assurance',
    ]);

    $employee = Employee::factory()->create([
        'department_id' => $department->id,
        'name' => 'Resource Test Employee',
    ]);

    Role::findOrCreate('admin', 'web');

    $user = User::factory()->create();
    $user->assignRole('admin');

    Sanctum::actingAs($user);

    $response = $this->getJson(
        "/api/v1/employees/{$employee->id}"
    );

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'salary',
                'joining_date',
                'department_id',
                'is_active',
                'department' => [
                    'id',
                    'name',
                ],
                'projects',
            ],
        ])
        ->assertJsonPath(
            'data.department.id',
            $department->id
        )
        ->assertJsonPath(
            'data.department.name',
            'Quality Assurance'
        );
});
