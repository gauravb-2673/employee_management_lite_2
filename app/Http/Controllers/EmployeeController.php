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
        //dd($request->input('departmentsearch'));
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
        $departments = Department::all();
        $employeesearch = $request->input('employeesearch');
        $departmentsearch = $request->input('departmentsearch');

        // Apply employee name/email search only when a value is provided.
        if ($request->filled('employeesearch')) { // filled() checks if the input field is present and not empty. It returns true if the field has a value, and false if it's empty or not present.
            $query->where(function ($subQuery) use ($employeesearch) { // where() adds a condition to the query. In this case, it adds a condition that will filter employees based on their name or email. The closure allows for grouping multiple conditions together. // use ($employeesearch) allows the closure to access the $employeesearch variable from the parent scope. if you didn't use use ($employeesearch), the closure wouldn't have access to that variable, and you'd get an error of undefined variable.
                $subQuery->where('name', 'like',  $employeesearch . '%')
                    ->orWhere('email', 'like',  $employeesearch . '%'); //$subQuery is used to build a subquery that checks if the employee's name or email starts with the value of $employeesearch. The 'like' operator is used for pattern matching, and '%' is a wildcard that matches any sequence of characters.
            });
        }

        // Filter employees by department when a department is selected.
        if ($request->filled('departmentsearch')) {
            $query->where('department_id', $departmentsearch);
        }

        if ($request->user()->hasRole('employee')) {
            $query->where('user_id', $request->user()->id);
        }

        $employees = $query->paginate(10)->withQueryString(); // withQueryString() appends the current query string parameters to the pagination links, ensuring that the search filters remain applied when navigating through paginated results.

        //dd($employees->getCollection());

        // Collect the role IDs from all project assignments
        // belonging to the employees on the current page.
        $roleIds = $employees->getCollection()
            ->flatMap(function ($employee) {
                return $employee->projects->map(function ($project) {
                    return $project->pivot->role;
                });
            })
            ->filter()
            ->unique()
            ->values(); // getCollection() retrieves the underlying collection of Employee models from the paginated result. flatMap() is used to transform each employee into a collection of their project role IDs, and then flatten those collections into a single collection. filter() removes any null or empty values, unique() ensures that each role ID appears only once, and values() reindexes the collection to have sequential keys.

        // Retrieve all required roles using one query.
        // If no roles exist on this page, skip the query.
        $rolesById = $roleIds->isEmpty()
            ? collect()
            : RoleMaster::whereIn('id', $roleIds)
            ->get()
            ->keyBy('id');
        // $rolesById is a collection of RoleMaster models, keyed by their ID. This allows for quick access to role details when attaching them to the pivot models later. If there are no role IDs, an empty collection is created to avoid unnecessary database queries.
        // Attach each already-retrieved role to its pivot model.

        $employees->getCollection()->each(function ($employee) use ($rolesById) {
            $employee->projects->each(function ($project) use ($rolesById) {
                $roleId = $project->pivot->role;

                $project->pivot->setRelation(
                    'roleMaster',
                    $rolesById->get((int) $roleId)
                );
            });
        });
        // $employees->getCollection() retrieves the collection of Employee models from the paginated result. each() iterates over each employee, and for each employee, it iterates over their associated projects. For each project, it retrieves the role ID from the pivot table and uses it to fetch the corresponding RoleMaster model from $rolesById. The setRelation() method attaches the RoleMaster model to the pivot model under the name 'roleMaster', allowing easy access to role details when rendering views.

        //dd($query->toRawSql());
        // dd($queries);

        return view('employee.index', [
            'employees' => $employees,
            'departments' => $departments
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
