<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\Api\V2\EmployeeResource;
use App\Mail\WelcomeEmployeeMail;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\RoleMaster;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EmployeeController extends Controller
{
    /**
     * Display a paginated list of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'projects']);

        // Search employees by name or email.
        if ($request->filled('employeesearch')) {
            $search = $request->input('employeesearch');

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', $search.'%')
                    ->orWhere('email', 'like', $search.'%');
            });
        }

        // Filter employees by department.
        if ($request->filled('departmentsearch')) {
            $query->where(
                'department_id',
                $request->input('departmentsearch')
            );
        }

        // Employees with the employee role can see only their own record.
        if ($request->user()->hasRole('employee')) {
            $query->where('user_id', $request->user()->id);
        }

        // Paginate and preserve search filters in pagination links.
        $employees = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Collect the role IDs attached to projects on this page.
        $roleIds = $employees->getCollection()
            ->flatMap(function ($employee) {
                return $employee->projects->map(function ($project) {
                    return $project->pivot->role;
                });
            })
            ->filter()
            ->unique()
            ->values();

        // Retrieve the required role records in one query.
        $rolesById = $roleIds->isEmpty()
            ? collect()
            : RoleMaster::whereIn('id', $roleIds)
                ->get()
                ->keyBy('id');

        // Attach the already-retrieved role to each project pivot.
        $employees->getCollection()->each(function ($employee) use ($rolesById) {
            $employee->projects->each(function ($project) use ($rolesById) {
                $roleId = $project->pivot->role;

                $project->pivot->setRelation(
                    'roleMaster',
                    $rolesById->get((int) $roleId)
                );
            });
        });

        // Transform every employee using EmployeeResource.
        return EmployeeResource::collection($employees);
    }

    /**
     * Display one employee.
     */
    public function show(Employee $employee)
    {
        // Apply the same authorization rule as the web controller.
        Gate::authorize('view', $employee);

        // Load relationships needed by EmployeeResource.
        $employee->load([
            'department',
            'projects',
            'documents',
        ]);

        return new EmployeeResource($employee);
    }

    /**
     * Create an employee through the API.
     */
    public function store(StoreEmployeeRequest $request)
    {
        // 1. Retrieve validated input.
        $data = $request->validated();

        // 2. Retrieve the uploaded file separately.
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

        // 5. Prepare the employee_project pivot data.
        $syncData = [];

        foreach ($projectIds as $projectId) {
            $syncData[$projectId] = [
                'role' => $projectRoles[$projectId],
            ];
        }

        // Track the uploaded file for error cleanup.
        $newPath = null;

        try {
            // 6. Create the employee and related records transactionally.
            $employee = DB::transaction(function () use (
                $data,
                $syncData,
                $file,
                $request,
                &$newPath
            ) {
                // Create the employee.
                $employee = Employee::create($data);

                // Synchronize project assignments.
                $employee->projects()->sync($syncData);

                // Upload the document if one was provided.
                if ($file) {
                    $newPath = $this->storeEmployeeDocumentFile(
                        $file,
                        $employee
                    );

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
            // Remove the uploaded file if the database operation fails.
            if ($newPath !== null) {
                Storage::disk('local')->delete($newPath);
            }

            throw $exception;
        }

        // Queue the welcome email after the transaction succeeds.
        Mail::to($employee->email)
            ->queue(new WelcomeEmployeeMail($employee));

        // Load relationships for the API response.
        $employee->load(['department', 'projects']);

        // Return the created resource with HTTP 201.
        return (new EmployeeResource($employee))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an employee through the API.
     */
    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee
    ) {
        // 1. Authorize the update.
        Gate::authorize('update', $employee);

        // 2. Retrieve validated input and optional document.
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

        // 6. Find an existing document, if any.
        $existingDocument = $employee->documents()->first();

        $oldPath = $existingDocument?->path;
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

                // Keep the current document if no new file was uploaded.
                if (! $file) {
                    return;
                }

                // Store the replacement document.
                $newPath = $this->storeEmployeeDocumentFile(
                    $file,
                    $employee
                );

                $documentData = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $newPath,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => $request->user()->id,
                ];

                if ($existingDocument) {
                    $existingDocument->update($documentData);
                } else {
                    $employee->documents()->create($documentData);
                }
            });
        } catch (Throwable $exception) {
            // Clean up a newly uploaded file if the DB operation fails.
            if ($newPath !== null) {
                Storage::disk('local')->delete($newPath);
            }

            throw $exception;
        }

        // Delete the old physical file only after a successful transaction.
        if (
            $file &&
            $oldPath &&
            $oldPath !== $newPath
        ) {
            Storage::disk('local')->delete($oldPath);
        }

        // Refresh relationships so the Resource returns current data.
        $employee->refresh();
        $employee->load(['department', 'projects']);

        return new EmployeeResource($employee);
    }

    /**
     * Delete an employee through the API.
     */
    public function destroy(Employee $employee)
    {
        Gate::authorize('delete', $employee);

        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully.',
        ]);
    }

    /**
     * Store an employee document using the existing naming convention.
     */
    private function storeEmployeeDocumentFile(
        UploadedFile $file,
        Employee $employee
    ): string {
        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $originalName = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '_',
            $originalName
        );

        $originalName = trim($originalName ?? '', '_');

        if ($originalName === '') {
            $originalName = 'document';
        }

        $extension = $file->getClientOriginalExtension();

        $timestamp = now()->format('Y-m-d_H-i-s-u');

        $fileName = $originalName
            .'_employee_'.$employee->id
            .'_'.$timestamp
            .'.'.$extension;

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
