<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Employee Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">

    <!-- Navigation -->

    <nav class="border-b border-gray-200 bg-white">

        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">

            <a href="{{ url('/') }}" class="text-xl font-bold">
                Employee Management
            </a>

            <div>

                @auth

                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-600">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-600">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-600">
                        Register
                    </a>
                @endauth



            </div>

        </div>

    </nav>


    <!-- Welcome Section -->

    <main>

        <section class="px-6 py-16">

            <div class="mx-auto max-w-3xl text-center">

                <p class="mt-4 text-lg text-gray-600">
                    Manage employees, departments and projects
                    in one simple application.
                </p>

                <div class="mt-6">

                    @auth

                        <a href="{{ route('dashboard') }}"
                            class="inline-block rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="inline-block rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                            Register
                        </a>

                    @endauth

                </div>

            </div>

        </section>


        <!-- Features -->

        <section class="px-6 pb-16">

            <div class="mx-auto grid max-w-6xl gap-6 md:grid-cols-3">

                <div class="rounded-lg border border-gray-200 bg-white p-6">

                    <h2 class="text-lg font-semibold">
                        Employees
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        Manage employee information and details. <a href="{{ route('user-roles.assign') }}"
                            class="text-sm font-medium text-indigo-700 hover:text-indigo-600">
                            Assign User Roles
                        </a>
                    </p>

                </div>


                <div class="rounded-lg border border-gray-200 bg-white p-6">

                    <h2 class="text-lg font-semibold">
                        Departments
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        Organize employees by department.
                    </p>

                </div>


                <div class="rounded-lg border border-gray-200 bg-white p-6">

                    <h2 class="text-lg font-semibold">
                        Projects
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        Manage employee project assignments.
                    </p>

                </div>

            </div>

        </section>

    </main>


    <!-- Footer -->

    <footer class="border-t border-gray-200 bg-white">

    </footer>

</body>

</html>
