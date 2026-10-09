<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Add Employee</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .page-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
        }

        .page-title {
            color: #1e293b;
            font-weight: 600;
        }

        .page-description {
            color: #64748b;
        }

        .employee-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
        }

        .employee-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #ffffff;
        }

        .employee-card-body {
            padding: 24px;
        }

        .form-label {
            color: #334155;
            font-weight: 500;
        }

        .form-control,
        .form-select {
            border-color: #cbd5e1;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.10);
        }

        .section-title {
            color: #1e293b;
            font-weight: 600;
        }

        .project-row {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 14px 16px;
            background-color: #ffffff;
        }

        .project-row:hover {
            background-color: #f8fafc;
        }

        .project-name {
            color: #334155;
            font-weight: 500;
        }

        .form-check-input {
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
        }

        .form-actions {
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>

</head>


<body>

    <div class="container py-4">


        {{-- ====================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ====================================================== --}}

        <div class="page-header mb-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                <div>

                    <h4 class="page-title mb-1">
                        Add Employee
                    </h4>

                    <p class="page-description mb-0">
                        Enter the employee s information and assign projects.
                    </p>

                </div>


                <div class="mt-3 mt-md-0">

                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                        Back to Employees
                    </a>

                </div>

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ====================================================== --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <strong>Success:</strong>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

            </div>
        @endif


        {{-- ====================================================== --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ====================================================== --}}

        @if ($errors->any())

            <div class="alert alert-danger" role="alert">

                <strong>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ====================================================== --}}
        {{-- EMPLOYEE FORM --}}
        {{-- ====================================================== --}}

        <div class="employee-card">

            {{-- Card Header --}}

            <div class="employee-card-header">

                <h5 class="mb-1 fw-semibold">
                    Employee Information
                </h5>

                <small class="page-description">
                    Provide the basic details for the new employee.
                </small>

            </div>


            {{-- Card Body --}}

            <div class="employee-card-body">

                <form method="POST" action="{{ route('employees.store') }}">

                    @csrf


                    {{-- ================================================== --}}
                    {{-- BASIC INFORMATION --}}
                    {{-- ================================================== --}}

                    <div class="row g-4">


                        {{-- Name --}}

                        <div class="col-md-6">

                            <label for="name" class="form-label">
                                Name
                            </label>

                            <input type="text" name="name" id="name" maxlength="100"
                                value="{{ old('name') }}" class="form-control" required>

                        </div>


                        {{-- Email --}}

                        <div class="col-md-6">

                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input type="email" name="email" id="email" maxlength="100"
                                value="{{ old('email') }}" class="form-control" required>

                        </div>


                        {{-- Salary --}}

                        <div class="col-md-6">

                            <label for="Salary" class="form-label">
                                Salary
                            </label>

                            <input type="number" name="Salary" id="Salary" min="1.00" step="0.01"
                                value="{{ old('Salary') }}" class="form-control" required>

                        </div>


                        {{-- Joining Date --}}

                        <div class="col-md-6">

                            <label for="joining_date" class="form-label">
                                Joining Date
                            </label>

                            <input type="date" name="joining_date" id="joining_date"
                                max="{{ now()->format('Y-m-d') }}" value="{{ old('joining_date') }}"
                                class="form-control" required>

                        </div>


                        {{-- Department --}}

                        <div class="col-md-6">

                            <label for="department_id" class="form-label">
                                Department
                            </label>

                            <select name="department_id" id="department_id" class="form-select" required>

                                <option value="">
                                    Select department
                                </option>

                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                    </div>


                    {{-- ================================================== --}}
                    {{-- PROJECTS --}}
                    {{-- ================================================== --}}

                    <div class="mt-5">

                        <div class="mb-3">

                            <h6 class="section-title mb-1">
                                Project Assignments
                            </h6>

                            <small class="page-description">
                                Select the projects assigned to this employee
                                and choose their role.
                            </small>

                        </div>


                        @foreach ($projects as $project)
                            <div class="project-row mb-2">

                                <div class="row align-items-center g-3">


                                    {{-- Project Checkbox --}}

                                    <div class="col-md-7">

                                        <div class="form-check">

                                            <input type="checkbox" name="project_ids[]" value="{{ $project->id }}"
                                                id="project_{{ $project->id }}" class="form-check-input"
                                                @checked(in_array($project->id, old('project_ids', [])))>

                                            <label for="project_{{ $project->id }}"
                                                class="form-check-label project-name">
                                                {{ $project->project_name }}
                                            </label>

                                        </div>

                                    </div>


                                    {{-- Role --}}

                                    <div class="col-md-5">

                                        <select name="project_roles[{{ $project->id }}]" class="form-select">

                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">
                                                    {{ $role->role_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>


                    {{-- ================================================== --}}
                    {{-- ACTIVE STATUS --}}
                    {{-- ================================================== --}}

                    <div class="mt-4">

                        <div class="form-check">

                            <input type="hidden" name="is_active" value="0">

                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                class="form-check-input" @checked(old('is_active', true))>

                            <label for="is_active" class="form-check-label">
                                Active Employee
                            </label>

                        </div>

                    </div>


                    {{-- ================================================== --}}
                    {{-- FORM ACTIONS --}}
                    {{-- ================================================== --}}

                    <div class="form-actions mt-4">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Create Employee
                            </button>

                        </div>

                    </div>


                </form>

            </div>

        </div>

    </div>


    {{-- Bootstrap JavaScript --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
