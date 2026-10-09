<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\RoleMaster;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // dd($request);

        DB::listen(function ($query) use (&$queries) {
            $queries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
            ];
        });
        //dd($queries);
        //     $employees = Employee::with('department', 'projects')->paginate(10);




        $query = Employee::with('department', 'projects');

        $employeesearch = $request->input('employeesearch');

        if ($employeesearch !== '') {
            $query->where('name', 'like', '%' . $employeesearch . '%');
        }

        if ($request->user()->hasRole('employee')) {
            $query->where('user_id', $request->user()->id);
        }

        $employees = $query->paginate(10)->withQueryString();;

        //dd($queries);

        return view('employee.index', [
            'employees' => $employees
        ]);
    }
    // public function index()
    // {
    //     $queries = [];

    //     DB::listen(function ($query) use (&$queries) {
    //         $queries[] = [
    //             'sql' => $query->sql,
    //             'bindings' => $query->bindings,
    //         ];
    //     });

    //     $employees = Employee::with('department', 'projects')->paginate(10);


        //dd($queries);
        //dd($employees->toArray());


        //dd($employees);
        // dd($employees->toSql());
    //     return view('employee.index', ['employees' => $employees]);
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $projects = Project::all();
        $roles = RoleMaster::all();

        return view('employee.create', [
            'departments' => $departments,
            'projects' => $projects,
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        $projectIds = $data['project_ids'] ?? [];
        $projectRoles = $data['project_roles'] ?? [];

        unset($data['project_ids'], $data['project_roles']);

        $employee = Employee::create($data);

        $syncData = [];

        foreach ($projectIds as $projectId) {
            $syncData[$projectId] = [
                'role' => $projectRoles[$projectId],
            ];
        }

        $employee->projects()->sync($syncData);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        Gate::authorize('view', $employee);

        return view('employee.show', [
            'employee' => $employee,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //dd($employee);

        $employee = $employee->load('department', 'projects');
        $departments = Department::all();
        $projects = Project::all();
        $roles = RoleMaster::all();

        // dd($employee);


        return view('employee.edit', ['employee' => $employee, 'departments' => $departments, 'projects' => $projects, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {

        // dd($employee->all());
        $data = $request->validated();

        $projectIds = $data['project_ids'] ?? [];
        $projectRoles = $data['project_roles'] ?? [];

        unset($data['project_ids'], $data['project_roles']);

        $employee->update($data);

        $syncData = [];

        foreach ($projectIds as $projectId) {
            $syncData[$projectId] = [
                'role' => $projectRoles[$projectId],
            ];
        }

        $employee->projects()->sync($syncData);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}
