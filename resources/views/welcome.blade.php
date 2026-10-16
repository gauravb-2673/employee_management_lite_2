<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Employee Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">

    {{-- Navigation --}}
    <nav class="border-b border-gray-200 bg-white">

        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">

            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">
                Employee Management
            </a>

            <div class="flex items-center gap-5">

                @auth

                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">
                        Dashboard
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">
                        Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">
                            Register
                        </a>
                    @endif

                @endauth

            </div>

        </div>

    </nav>

    {{-- Welcome Section --}}
    <main>

        <section class="px-6 py-16 sm:py-20">

            <div class="mx-auto max-w-3xl text-center">

                <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Welcome to Employee Management
                </h1>

                <p class="mt-4 text-lg leading-8 text-gray-600">
                    Manage employees, departments, and projects
                    in one simple application.
                </p>

                <div class="mt-8">

                    @auth

                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Login to Continue
                        </a>

                    @endauth

                </div>

            </div>

        </section>

        {{-- Features --}}
        <section class="px-6 pb-16">

            <div class="mx-auto grid max-w-6xl gap-6 md:grid-cols-3">

                {{-- Employees --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Employees
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Manage employee information and details.
                    </p>

                    @auth
                        @can('view employees')
                            <a href="{{ route('employees.index') }}"
                                class="mt-4 inline-block text-sm font-medium text-indigo-700 hover:text-indigo-900">
                                View Employees &rarr;
                            </a>
                        @endcan
                    @endauth

                </div>

                {{-- Departments --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Departments
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Organize employees by department.
                    </p>

                </div>

                {{-- Projects --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Projects
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Manage employee project assignments.
                    </p>

                </div>

            </div>

        </section>

    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 bg-white">

        <div class="mx-auto max-w-6xl px-6 py-5 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Employee Management. All rights reserved.
        </div>

    </footer>

</body>

</html>
