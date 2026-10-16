@extends('layouts.main')

@section('title', 'Employees')

@section('content')

    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8">

        {{-- PAGE HEADER --}}
        <div class="mb-6 border-b border-slate-200 pb-6">

            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">

                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        Employees
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Manage employee information, departments and projects.
                    </p>
                </div>

                {{-- HEADER ACTIONS --}}
                <div class="flex flex-wrap gap-2">

                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Back to Dashboard
                    </a>

                    @can('create employees')
                        <a href="{{ route('employees.create') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            + Add Employee
                        </a>
                    @endcan

                </div>

            </div>

            {{-- SEARCH AND FILTER --}}
            <form action="{{ route('employees.index') }}" method="GET"
                class="mt-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 xl:items-end">

                    {{-- Employee Search --}}
                    <div>
                        <label for="employeesearch" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Search employees
                        </label>

                        <input type="text" name="employeesearch" id="employeesearch"
                            value="{{ request('employeesearch') }}" placeholder="Name or email"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    {{-- Department Filter --}}
                    <div>
                        <label for="departmentsearch" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Filter by department
                        </label>

                        <select name="departmentsearch" id="departmentsearch"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            <option value="">All Departments</option>

                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @selected((string) request('departmentsearch') === (string) $department->id)>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Search Button --}}
                    <div>
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Search
                        </button>
                    </div>

                    {{-- Clear Button --}}
                    <div>
                        <a href="{{ route('employees.index') }}"
                            class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400">
                            Clear Filters
                        </a>
                    </div>

                </div>

            </form>

        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="mb-5 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800"
                role="status">

                <div class="min-w-0 flex-1">
                    <p class="font-semibold">Success</p>
                    <p class="mt-1">{{ session('success') }}</p>
                </div>

            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">

                <p class="font-semibold">
                    Please correct the following errors:
                </p>

                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        {{-- EMPLOYEE TABLE CARD --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div
                class="flex flex-col gap-1 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                <h2 class="text-base font-semibold text-slate-900">
                    Employee Directory
                </h2>

                <p class="text-sm text-slate-500">
                    {{ $employees->total() }} employees found
                </p>

            </div>

            {{-- Horizontal scrolling keeps the table usable on mobile. --}}
            <div class="overflow-x-auto">

                <table class="min-w-[1100px] w-full divide-y divide-slate-200 text-left text-sm">

                    <thead class="bg-slate-50">

                        <tr>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Name
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Email
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Salary
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Joining Date
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Department
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Project / Role
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Actions
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($employees as $employee)

                            <tr class="transition hover:bg-slate-50/80">

                                {{-- NAME --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-semibold text-slate-900">
                                        {{ $employee->name }}
                                    </span>
                                </td>

                                {{-- EMAIL --}}
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $employee->email }}
                                </td>

                                {{-- SALARY --}}
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-700">
                                    ₹{{ number_format((float) $employee->salary, 2) }}
                                </td>

                                {{-- JOINING DATE --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ $employee->joining_date?->format('d M Y') ?? '—' }}
                                </td>

                                {{-- DEPARTMENT --}}
                                <td class="px-5 py-4">
                                    @if ($employee->department)
                                        <span
                                            class="inline-flex whitespace-nowrap rounded-md border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                            {{ $employee->department->department_name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">
                                            No department
                                        </span>
                                    @endif
                                </td>

                                {{-- PROJECTS AND ROLES --}}
                                <td class="px-5 py-4">
                                    @forelse ($employee->projects as $project)
                                        <div class="mb-2 last:mb-0">
                                            <p class="font-medium text-slate-700">
                                                {{ $project->project_name }}
                                            </p>

                                            <span
                                                class="mt-1 inline-flex rounded-md border border-indigo-100 bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">
                                                {{ $project->pivot->roleMaster?->role_name ?? 'Unknown role' }}
                                            </span>
                                        </div>

                                    @empty
                                        <span class="text-slate-400">
                                            No projects
                                        </span>
                                    @endforelse
                                </td>

                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($employee->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTIONS --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        @can('view employees')
                                            <a href="{{ route('employees.show', $employee) }}"
                                                class="rounded-md border border-indigo-200 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 transition hover:bg-indigo-50">
                                                View
                                            </a>
                                        @endcan

                                        @can('edit employees')
                                            <a href="{{ route('employees.edit', $employee) }}"
                                                class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-100">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete employees')
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="rounded-md border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">

                                    <p class="text-base font-medium text-slate-700">
                                        No employees found
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Try changing your search or department filter.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- PAGINATION --}}
        <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-sm text-slate-500">
                Showing
                <span class="font-medium text-slate-800">
                    {{ $employees->firstItem() ?? 0 }}
                </span>
                to
                <span class="font-medium text-slate-800">
                    {{ $employees->lastItem() ?? 0 }}
                </span>
                of
                <span class="font-medium text-slate-800">
                    {{ $employees->total() }}
                </span>
                employees
            </p>

            <div>
                {{ $employees->withQueryString()->links() }}
            </div>

        </div>

    </div>

@endsection
