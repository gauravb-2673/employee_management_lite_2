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

        // DB::listen(function ($query) use (&$queries) {
        //     $queries[] = [
        //         'sql' => $query->sql,
        //         'bindings' => $query->bindings,
        //     ];
        // });
        //dd($queries);
        //     $employees = Employee::with('department', 'projects')->paginate(10);




        $query = Employee::with('department', 'projects');

        $employeesearch = $request->input('employeesearch');

        if ($employeesearch !== '') {
            $query->where('name', 'like', '%' . $employeesearch . '%')->orWhere('email', 'like', '%' . $employeesearch . '%');
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

        $employee = $employee->load('department', 'projects');
        $departments = Department::all();
        $projects = Project::all();
        $roles = RoleMaster::all();

        // dd($employee);


        return view('employee.show', ['employee' => $employee, 'departments' => $departments, 'projects' => $projects, 'roles' => $roles]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //dd($employee);
        Gate::authorize('update', $employee);
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
        // Step 1: Check whether the authenticated user can update this employee.
        Gate::authorize('update', $employee);

        // Step 2: Retrieve only the data that passed validation.
        $data = $request->validated();

        // Step 3: Extract project IDs and their corresponding role IDs.
        $projectIds = $data['project_ids'] ?? [];
        $projectRoles = $data['project_roles'] ?? [];

        // Step 4: Remove project-specific data from the employee's attributes.
        unset($data['project_ids'], $data['project_roles']);

        // Step 5: Prepare the pivot-table data before starting the transaction.
        $syncData = [];

        foreach ($projectIds as $projectId) {
            $syncData[$projectId] = [
                'role' => $projectRoles[$projectId],
            ];
        }

        // Step 6: Update the employee and synchronize projects atomically.
        DB::transaction(function () use ($employee, $data, $syncData) {

            // Update the employee record in the employees table.
            $employee->update($data);

            // Synchronize project assignments in the employee_project table.
            $employee->projects()->sync($syncData);
        });

        // Step 7: Redirect after the transaction has completed successfully.
        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {

        Gate::authorize('delete', $employee);

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}
