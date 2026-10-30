<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DepartmentResource;
use App\Mail\WelcomeEmployeeMail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\RoleMaster;
use Illuminate\Http\Request;
use Throwable;

class DepartmentController extends Controller
{
    /**
     * Display a paginated list of employees.
     */

    public function departments(Department $department)
    {
        //Add GET /api/v1/departments/{department}/employees, protected by Sanctum, returning paginated employees (10 per page) through an API Resource. Support an optional ?active=1 filter.
        // $department = Department::with([
        //     'employees',
        // ])->get();
        $employees = Employee::with('department')->where('id', $department)->paginate(10)->withQueryString();

        // $department = Department::with([
        //     'employees',
        // ])->where('id', $department)->paginate(10)->withQueryString();
        //dd($department);

        return DepartmentResource::collection($employees);
    }
}
