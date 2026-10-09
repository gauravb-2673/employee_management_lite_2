<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Employees</title>

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

        .employee-table th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
            border-bottom: 1px solid #e2e8f0;
        }

        .employee-table td {
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .employee-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .employee-name {
            color: #1e293b;
            font-weight: 600;
        }

        .employee-email {
            color: #64748b;
        }

        .department-badge {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 500;
        }

        .project-role {
            margin-bottom: 5px;
        }

        .project-name {
            color: #334155;
            font-weight: 500;
        }

        .role-badge {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            font-weight: 500;
        }

        .status-active {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-weight: 500;
        }

        .status-inactive {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            font-weight: 500;
        }

        .empty-text {
            color: #94a3b8;
        }

        .pagination-wrapper {
            margin-top: 20px;
        }

        .custom-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .custom-page-item {
            display: inline-flex;
        }

        .custom-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background-color: #ffffff;
            color: #475569;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .custom-page-item:not(.disabled):not(.active) .custom-page-link:hover {
            background-color: #eff6ff;
            border-color: #bfdbfe;
            color: #2563eb;
        }

        .custom-page-item.active .custom-page-link {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        .custom-page-item.disabled .custom-page-link {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .table-responsive {
            border-radius: 0 0 8px 8px;
        }
    </style>

</head>


<body>

    <div class="container-fluid px-4 py-4">

        {{-- ====================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ====================================================== --}}

        <div class="page-header mb-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                <div>

                    <h4 class="page-title mb-1">
                        Employees
                    </h4>

                    <p class="page-description mb-0">
                        Manage employee information, departments and projects.
                    </p>

                    <div class="d-flex gap-2 mt-3 mt-md-0">
                        <form action="{{ route('employees.index') }}" method="GET">
                            @method('GET')
                            @csrf


                            <label for="employeesearch" placeholder="Search By Employee Name or Email">Search</label>
                            <input type="text" name="employeesearch" id="employeesearch">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Search
                            </button>

                        </form>

                        <form action="{{ route('employees.index') }}" method="GET">

                            @csrf

                            @method('GET')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Clear Search
                            </button>

                        </form>
                    </div>
                </div>


                <div class="d-flex gap-2 mt-3 mt-md-0">

                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        Back to Dashboard
                    </a>


                    @can('create employees')
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            Add Employee
                        </a>
                    @endcan

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
        {{-- EMPLOYEE TABLE --}}
        {{-- ====================================================== --}}

        <div class="employee-card">

            <div class="table-responsive">

                <table class="table employee-table table-hover mb-0">

                    <thead>

                        <tr>

                            <th class="px-4 py-3">
                                Name
                            </th>

                            <th class="py-3">
                                Email
                            </th>

                            <th class="py-3">
                                Salary
                            </th>

                            <th class="py-3">
                                Joining Date
                            </th>

                            <th class="py-3">
                                Department
                            </th>

                            <th class="py-3">
                                Project / Role
                            </th>

                            <th class="py-3">
                                Status
                            </th>

                            <th class="text-end px-4 py-3">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($employees as $employee)

                            <tr>

                                {{-- Name --}}

                                <td class="px-4">

                                    <span class="employee-name">
                                        {{ $employee->name }}
                                    </span>

                                </td>


                                {{-- Email --}}

                                <td>

                                    <span class="employee-email">
                                        {{ $employee->email }}
                                    </span>

                                </td>


                                {{-- Salary --}}

                                <td>

                                    ₹{{ number_format($employee->Salary, 2) }}

                                </td>


                                {{-- Joining Date --}}

                                <td>

                                    {{ $employee->joining_date->format('d M Y') }}

                                </td>


                                {{-- Department --}}

                                <td>

                                    <span class="badge department-badge">

                                        {{ $employee->department->department_name }}

                                    </span>

                                </td>


                                {{-- Projects / Roles --}}

                                <td>

                                    @forelse ($employee->projects as $project)
                                        <div class="project-role">

                                            <span class="project-name">
                                                {{ $project->project_name }}
                                            </span>

                                            <span class="badge role-badge ms-1">
                                                {{ $project->pivot->roleMaster->role_name }}
                                            </span>

                                        </div>

                                    @empty

                                        <span class="empty-text">
                                            No projects
                                        </span>
                                    @endforelse

                                </td>


                                {{-- Status --}}

                                <td>

                                    @if ($employee->is_active == 1)
                                        <span class="badge status-active">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge status-inactive">
                                            Inactive
                                        </span>
                                    @endif

                                </td>


                                {{-- Actions --}}

                                <td class="text-end px-4">

                                    <div class="d-inline-flex gap-2">

                                        @can('edit employees')
                                            <a href="{{ route('employees.edit', ['employee' => $employee->id]) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                        @endcan


                                        @can('delete employees')
                                            <form action="{{ route('employees.destroy', ['employee' => $employee->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this employee?')">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>

                                            </form>
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-5">

                                    <span class="empty-text">
                                        No employees found.
                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- PAGINATION --}}
        {{-- ====================================================== --}}

        <div class="pagination-wrapper d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

            <div class="text-secondary small">

                Showing
                <strong>{{ $employees->firstItem() ?? 0 }}</strong>
                to
                <strong>{{ $employees->lastItem() ?? 0 }}</strong>
                of
                <strong>{{ $employees->total() }}</strong>
                employees

            </div>


            <div>

                {{ $employees->links('pagination.custom') }}

            </div>

        </div>

    </div>


    {{-- Bootstrap JavaScript --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
