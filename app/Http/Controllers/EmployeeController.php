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
use App\Models\EmployeeDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;
use App\Mail\WelcomeEmployeeMail;
use Illuminate\Support\Facades\Mail;

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

        $employees = $query->orderBy('id', 'desc')->paginate(10)->withQueryString(); // withQueryString() appends the current query string parameters to the pagination links, ensuring that the search filters remain applied when navigating through paginated results.

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
    /**
     * Store a newly created employee.
     */
    /**
     * Store a new employee and optionally upload their document.
     */
    public function store(StoreEmployeeRequest $request)
    {
        // 1. Get validated input.
        $data = $request->validated();

        // 2. Retrieve the uploaded file separately.
        $file = $request->file('document');
        // dd([
        //     'has_file' => $request->hasFile('document'),
        //     'file_name' => $file?->getClientOriginalName(),
        //     'validated_document' => isset($data['document']),
        //     'request_keys' => array_keys($request->all()),
        // ]);

        // 3. Extract project assignments.
        $projectIds = $data['project_ids'] ?? [];
        $projectRoles = $data['project_roles'] ?? [];

        // 4. Remove fields that do not belong in the employees table.
        unset(
            $data['project_ids'],
            $data['project_roles'],
            $data['document']
        );

        // 5. Prepare the employee_project pivot data.
        $syncData = [];

        foreach ($projectIds as $projectId) {
            $syncData[$projectId] = [
                'role' => $projectRoles[$projectId],
            ];
        }

        // Track the stored file so we can remove it if the DB transaction fails.
        $newPath = null;

        try {
            // 6. Create the employee, assign projects, and save document metadata.
            $employee = DB::transaction(function () use (
                $data,
                $syncData,
                $file,
                $request,
                &$newPath
            ) {
                // Create the employee first so we have their ID.
                $employee = Employee::create($data);
                // Send the welcome email to the new employee.
                // Mail::to($employee->email)
                //     ->send(new WelcomeEmployeeMail($employee));
                Mail::to($employee->email)
                    ->queue(new WelcomeEmployeeMail($employee));
                //Laravel will put the email job into a queue. A separate background worker will process the job and send the email.

                // Save project assignments.
                $employee->projects()->sync($syncData);

                // Upload the document only if the user selected one.
                if ($file) {
                    $newPath = $this->storeEmployeeDocumentFile(
                        $file,
                        $employee
                    );

                    // Insert the document's metadata into employee_documents.
                    $employee->documents()->create([
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $newPath,
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'uploaded_by' => $request->user()->id,
                    ]);
                }

                return $employee;
            });
        } catch (Throwable $exception) {
            // A DB failure must not leave an orphaned physical file.
            if ($newPath !== null) {
                Storage::disk('local')->delete($newPath);
            }

            throw $exception;
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee added successfully.');
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
        $employee = $employee->load('department', 'projects', 'documents');
        $departments = Department::all();
        $projects = Project::all();
        $roles = RoleMaster::all();

        // dd($employee);


        return view('employee.edit', ['employee' => $employee, 'departments' => $departments, 'projects' => $projects, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update an employee and optionally replace their document.
     */
    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee
    ) {
        // 1. Check whether the user is authorized to update this employee.
        Gate::authorize('update', $employee);

        // 2. Get validated input and the optional uploaded file.
        $data = $request->validated();
        $file = $request->file('document');

        // 3. Extract project assignments.
        $projectIds = $data['project_ids'] ?? [];
        $projectRoles = $data['project_roles'] ?? [];

        // 4. Remove fields that do not belong in the employees table.
        unset(
            $data['project_ids'],
            $data['project_roles'],
            $data['document']
        );

        // 5. Prepare project pivot data.
        $syncData = [];

        foreach ($projectIds as $projectId) {
            $syncData[$projectId] = [
                'role' => $projectRoles[$projectId],
            ];
        }

        // 6. Find the existing document, if one exists.
        $existingDocument = $employee->documents()->first();

        // Keep the old physical path until the update succeeds.
        $oldPath = $existingDocument?->path;

        // Track the newly uploaded file for error cleanup.
        $newPath = null;

        try {
            DB::transaction(function () use (
                $employee,
                $data,
                $syncData,
                $file,
                $request,
                $existingDocument,
                &$newPath
            ) {
                // Update employee details.
                $employee->update($data);

                // Synchronize project assignments.
                $employee->projects()->sync($syncData);

                // No new file selected: leave the document unchanged.
                if (!$file) {
                    return;
                }

                // Store the replacement file.
                $newPath = $this->storeEmployeeDocumentFile(
                    $file,
                    $employee
                );

                // Prepare the new document metadata.
                $documentData = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $newPath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => $request->user()->id,
                ];

                if ($existingDocument) {
                    // Update the existing row. Its document ID stays the same.
                    $existingDocument->update($documentData);
                } else {
                    // No document existed, so create the first record.
                    $employee->documents()->create($documentData);
                }
            });
        } catch (Throwable $exception) {
            // Remove the new physical file if the DB transaction fails.
            if ($newPath !== null) {
                Storage::disk('local')->delete($newPath);
            }

            throw $exception;
        }

        // Delete the old physical file only after the DB transaction succeeds.
        if (
            $file &&
            $oldPath &&
            $oldPath !== $newPath
        ) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee data updated successfully.');
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

    private function storeEmployeeDocumentFile(
        UploadedFile $file,
        Employee $employee
    ): string {
        // Get the original filename without its extension.
        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        // Replace spaces and special characters with underscores.
        $originalName = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '_',
            $originalName
        );

        $originalName = trim($originalName ?? '', '_');

        if ($originalName === '') {
            $originalName = 'document';
        }

        // Get the uploaded file extension.
        $extension = $file->getClientOriginalExtension();

        // Generate a timestamp including microseconds.
        $timestamp = now()->format('Y-m-d_H-i-s-u');

        // Create the custom filename.
        $fileName = $originalName
            . '_employee_' . $employee->id
            . '_' . $timestamp
            . '.' . $extension;

        // Save the file on Laravel's local disk.
        $path = $file->storeAs(
            'employee-documents',
            $fileName,
            'local'
        );

        if ($path === false) {
            throw new \RuntimeException(
                'Unable to store the employee document.'
            );
        }

        return $path;
    }
}
