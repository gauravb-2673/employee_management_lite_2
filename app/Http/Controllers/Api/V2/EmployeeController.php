<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a paginated list of employees.
     */
    public function index()
    {
        $employees = Employee::with('department')
            ->latest()
            ->paginate(10);

        return EmployeeResource::collection($employees);
    }

    /**
     * Display a single employee.
     *
     * Laravel automatically resolves the employee using its ID.
     */
    public function show(Employee $employee)
    {
        $employee->load('department');

        return new EmployeeResource($employee);
    }
}
