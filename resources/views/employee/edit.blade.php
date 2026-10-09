<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Employee</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
        }

        .page-wrapper {
            max-width: 1000px;
            margin: 40px auto;
        }

        .page-header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .form-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 28px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 16px;
        }

        .project-row {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px 14px;
            margin-bottom: 10px;
            background: #fafafa;
        }

        .project-row:hover {
            background: #f8f9fa;
        }

        .project-name {
            font-weight: 500;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="page-wrapper">

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-1">Edit Employee</h4>

                <p class="text-muted mb-0">
                    Update employee information and project assignments.
                </p>
            </div>

            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                Back to Employees
            </a>

        </div>


        <!-- Validation Errors -->
        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <!-- Edit Form -->
        <div class="form-card">

            <form method="POST" action="{{ route('employees.update', ['employee' => $employee->id]) }}">

                @csrf

                @method('PUT')


                <!-- Basic Information -->
                <div class="section-title">
                    Basic Information
                </div>


                <div class="row g-3">

                    <!-- Name -->
                    <div class="col-md-6">

                        <label for="name" class="form-label">
                            Name
                        </label>

                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $employee->name) }}" maxlength="100" required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- Email -->
                    <div class="col-md-6">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $employee->email) }}" maxlength="100" required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- salary -->
                    <div class="col-md-6">

                        <label for="salary" class="form-label">
                            salary
                        </label>

                        <input type="number" name="salary" id="salary"
                            class="form-control @error('salary') is-invalid @enderror"
                            value="{{ old('salary', $employee->salary) }}" min="0" step="0.01" required>

                        @error('salary')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- Joining Date -->
                    <div class="col-md-6">

                        <label for="joining_date" class="form-label">
                            Joining Date
                        </label>

                        <input type="date" name="joining_date" id="joining_date"
                            class="form-control @error('joining_date') is-invalid @enderror"
                            value="{{ old('joining_date', $employee->joining_date->format('Y-m-d')) }}"
                            max="{{ now()->format('Y-m-d') }}" required>

                        @error('joining_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- Department -->
                    <div class="col-md-6">

                        <label for="department_id" class="form-label">
                            Department
                        </label>

                        <select name="department_id" id="department_id"
                            class="form-select @error('department_id') is-invalid @enderror" required>

                            <option value="">
                                Select department
                            </option>

                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id', $employee->department_id) == $department->id)>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach

                        </select>

                        @error('department_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <hr class="my-4">


                <!-- Project Assignment -->
                <div class="section-title">
                    Project Assignments
                </div>

                <p class="text-muted small mb-3">
                    Select the projects assigned to this employee and choose a role.
                </p>


                @foreach ($projects as $project)
                    @php

                        /*
                         * Find out whether this employee is already
                         * assigned to the current project.
                         */
                        $assignedProject = $employee->projects->firstWhere('id', $project->id);

                        /*
                         * If validation failed, use the user's submitted
 * project selection instead of the database value.
 */
$selectedProjects = old('project_ids', $employee->projects->pluck('id')->toArray());

/*
                         * Determine the currently selected role.
                         *
                         * For an already assigned project, use the pivot role.
                         * If validation failed, old() takes priority.
 */
                        $selectedRole = old("project_roles.{$project->id}", $assignedProject?->pivot?->role);

                    @endphp


                    <div class="project-row">

                        <div class="row align-items-center g-3">

                            <!-- Project Checkbox -->
                            <div class="col-md-6">

                                <div class="form-check">

                                    <input type="checkbox" name="project_ids[]" value="{{ $project->id }}"
                                        id="project_{{ $project->id }}" class="form-check-input"
                                        @checked(in_array($project->id, $selectedProjects))>

                                    <label for="project_{{ $project->id }}" class="form-check-label project-name">
                                        {{ $project->project_name }}
                                    </label>

                                </div>

                            </div>


                            <!-- Project Role -->
                            <div class="col-md-6">

                                <select name="project_roles[{{ $project->id }}]" class="form-select">

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected($selectedRole == $role->id)>
                                            {{ $role->role_name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>
                @endforeach


                <hr class="my-4">


                <!-- Employee Status -->
                <div class="section-title">
                    Employee Status
                </div>

                <input type="hidden" name="is_active" value="0">

                <div class="form-check">

                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                        @checked(old('is_active', $employee->is_active))>

                    <label for="is_active" class="form-check-label">
                        Active Employee
                    </label>

                </div>


                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Employee
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
