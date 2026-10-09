<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserRoleController;

Route::get('/', function () {
    return view('welcome');
});
// Display the role assignment form.
Route::get('/user-roles/assign', [UserRoleController::class, 'index'])
    ->name('user-roles.assign');

// Process the role assignment form.
Route::post('/user-roles/assign', [UserRoleController::class, 'store'])
    ->name('user-roles.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'active'])->name('dashboard');

Route::middleware('auth', 'active')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/employees', [EmployeeController::class, 'index'])
        ->middleware('permission:view employees')
        ->name('employees.index');

    Route::get('/employees/create', [EmployeeController::class, 'create'])
        ->middleware('permission:create employees')
        ->name('employees.create');

    Route::post('/employees', [EmployeeController::class, 'store'])
        ->middleware('permission:create employees')
        ->name('employees.store');

    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])
        ->middleware('permission:view employees')
        ->name('employees.show');

    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->middleware('permission:edit employees')->name('employees.edit');

    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
        ->middleware('permission:edit employees')
        ->name('employees.update');

    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
        ->middleware('permission:delete employees')
        ->name('employees.destroy');
});

require __DIR__ . '/auth.php';
