<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Each page can provide its own title --}}
    <title>@yield('title', 'Employee Management')</title>

    {{-- Bootstrap CSS shared by all pages using this layout --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Page-specific CSS will be inserted here --}}
    @yield('styles')
</head>

<body>

    {{-- Common application header and navigation --}}
    <header class="bg-white border-bottom">
        <div class="container-fluid px-4 py-3">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <a href="{{ route('employees.index') }}" class="text-decoration-none text-dark fw-semibold fs-5">
                    Employee Management
                </a>

                <nav class="d-flex flex-wrap gap-2">

                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm">
                        Employees
                    </a>

                    @can('create employees')
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                            Add Employee
                        </a>
                    @endcan

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            Dashboard
                        </a>
                    @endauth

                </nav>

            </div>

        </div>
    </header>

    {{-- Individual pages supply their content here --}}
    <main>
        @yield('content')
    </main>

    {{-- Bootstrap JavaScript shared by all pages using this layout --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page-specific JavaScript will be inserted here --}}
    @yield('scripts')

</body>

</html>
