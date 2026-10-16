@props([
    'employee' => null,
    'departments' => [],
    'projects' => [],
    'roles' => [],
])

@php
    // If an employee exists, the form is in edit mode.
    $isEditing = $employee !== null;

    // Keep the submitted project selections after validation fails.
    // Otherwise, use the employee's existing project assignments.
$selectedProjectIds = old('project_ids', $employee?->projects->pluck('id')->toArray() ?? []);
@endphp


{{-- SUCCESS MESSAGE --}}
@if (session('success'))
    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="status">

        <p class="font-semibold">Success</p>

        <p class="mt-1">
            {{ session('success') }}
        </p>

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


{{-- EMPLOYEE FORM CARD --}}
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- CARD HEADER --}}
    <div class="border-b border-slate-200 bg-slate-50/70 px-5 py-5 sm:px-6">

        <h2 class="text-lg font-semibold text-slate-900">
            Employee Information
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Provide the basic details for the employee.
        </p>

    </div>


    {{-- CARD BODY --}}
    <div class="p-5 sm:p-6">

        <form method="POST" action="{{ $isEditing ? route('employees.update', $employee) : route('employees.store') }}"
            enctype="multipart/form-data">

            @csrf

            @if ($isEditing)
                @method('PUT')
            @endif


            {{-- BASIC INFORMATION --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- NAME --}}
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Name
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="name" id="name" maxlength="100"
                        value="{{ old('name', $employee?->name) }}" required autocomplete="name"
                        aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                        class="block w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:outline-none focus:ring-2
                        {{ $errors->has('name')
                            ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20'
                            : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}"
                        placeholder="Enter employee name">

                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- EMAIL --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Email
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="email" name="email" id="email" maxlength="100"
                        value="{{ old('email', $employee?->email) }}" required autocomplete="email"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        class="block w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:outline-none focus:ring-2
                        {{ $errors->has('email')
                            ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20'
                            : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}"
                        placeholder="employee@example.com">

                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- SALARY --}}
                <div>
                    <label for="salary" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Salary
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500">
                            ₹
                        </span>

                        <input type="number" name="salary" id="salary" min="0.01" step="0.01"
                            value="{{ old('salary', $employee?->salary) }}" required
                            aria-invalid="{{ $errors->has('salary') ? 'true' : 'false' }}"
                            class="block w-full rounded-lg border py-2.5 pl-8 pr-3 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2
                            {{ $errors->has('salary')
                                ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20'
                                : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}"
                            placeholder="50000.00">
                    </div>

                    @error('salary')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- JOINING DATE --}}
                <div>
                    <label for="joining_date" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Joining Date
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="date" name="joining_date" id="joining_date" max="{{ now()->format('Y-m-d') }}"
                        value="{{ old('joining_date', $employee?->joining_date?->format('Y-m-d')) }}" required
                        aria-invalid="{{ $errors->has('joining_date') ? 'true' : 'false' }}"
                        class="block w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2
                        {{ $errors->has('joining_date')
                            ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20'
                            : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">

                    @error('joining_date')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- DEPARTMENT --}}
                <div class="sm:col-span-2">
                    <label for="department_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Department
                        <span class="text-red-500">*</span>
                    </label>

                    <select name="department_id" id="department_id" required
                        aria-invalid="{{ $errors->has('department_id') ? 'true' : 'false' }}"
                        class="block w-full rounded-lg border bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2
                        {{ $errors->has('department_id')
                            ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20'
                            : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">

                        <option value="">Select department</option>

                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) old('department_id', $employee?->department_id) === (string) $department->id)>
                                {{ $department->department_name }}
                            </option>
                        @endforeach

                    </select>

                    @error('department_id')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMPLOYEE DOCUMENT --}}
                <div class="mt-8 border-t border-slate-200 pt-6">

                    <h3 class="text-base font-semibold text-slate-900">
                        Employee Document
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Upload a resume, certificate, or other employee document.
                    </p>

                    <div class="mt-4">
                        <label for="document" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Select Document
                        </label>

                        <input type="file" name="document" id="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                        {{-- Show the existing document when editing --}}
                        @if ($isEditing && $employee->documents->isNotEmpty())
                            <div class="mt-4">
                                <p class="mb-2 text-sm font-medium text-slate-700">
                                    Current Document
                                </p>

                                @foreach ($employee->documents as $document)
                                    <div
                                        class="flex items-center justify-between rounded-lg border border-slate-200 p-3">
                                        <span class="text-sm text-slate-700">
                                            {{ $document->original_name }}
                                        </span>

                                        <a href="{{ route('employee-documents.download', $document) }}"
                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @error('document')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1.5 text-xs text-slate-500">
                            Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum size: 10 MB.
                        </p>
                    </div>

                </div>

            </div>


            {{-- PROJECT ASSIGNMENTS --}}
            <div class="mt-8 border-t border-slate-200 pt-6">

                <div class="mb-4">
                    <h3 class="text-base font-semibold text-slate-900">
                        Project Assignments
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Select the projects assigned to this employee and choose their role.
                    </p>
                </div>


                @error('project_ids')
                    <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('project_roles')
                    <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                @enderror


                @forelse ($projects as $project)

                    @php
                        // Find the employee's existing assignment for this project.
$existingProjectRole = $employee?->projects->firstWhere('id', $project->id)?->pivot?->role;

                        // Prefer submitted values after a validation failure.
                        $selectedProjectRole = old("project_roles.{$project->id}", $existingProjectRole);
                    @endphp

                    <div
                        class="mb-3 rounded-lg border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50/70">

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:items-center">

                            {{-- PROJECT CHECKBOX --}}
                            <div>
                                <div class="flex items-start gap-3">

                                    <input type="checkbox" name="project_ids[]" value="{{ $project->id }}"
                                        id="project_{{ $project->id }}" @checked(in_array($project->id, $selectedProjectIds))
                                        class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500">

                                    <label for="project_{{ $project->id }}"
                                        class="cursor-pointer text-sm font-medium text-slate-800">
                                        {{ $project->project_name }}

                                        <span class="mt-1 block text-xs font-normal text-slate-500">
                                            Assign this project to the employee
                                        </span>
                                    </label>

                                </div>
                            </div>


                            {{-- PROJECT ROLE --}}
                            <div>
                                <label for="project_role_{{ $project->id }}"
                                    class="mb-1.5 block text-xs font-medium text-slate-600">
                                    Project Role
                                </label>

                                <select name="project_roles[{{ $project->id }}]"
                                    id="project_role_{{ $project->id }}"
                                    class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected((string) $selectedProjectRole === (string) $role->id)>
                                            {{ $role->role_name }}
                                        </option>
                                    @endforeach

                                </select>

                                @error("project_roles.{$project->id}")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center">

                        <p class="text-sm font-medium text-slate-700">
                            No projects available
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Create a project before assigning it to an employee.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- ACTIVE STATUS --}}
            <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 p-4">

                <div class="flex items-start gap-3">

                    {{-- Hidden input ensures unchecked checkboxes submit 0. --}}
                    <input type="hidden" name="is_active" value="0">

                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        @checked((bool) old('is_active', $employee?->is_active ?? true))
                        class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500">

                    <div>
                        <label for="is_active" class="cursor-pointer text-sm font-medium text-slate-800">
                            Active Employee
                        </label>

                        <p class="mt-1 text-xs text-slate-500">
                            Uncheck this option to mark the employee as inactive.
                        </p>
                    </div>

                </div>

            </div>


            {{-- FORM ACTIONS --}}
            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                <a href="{{ route('employees.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400">
                    Cancel
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ $isEditing ? 'Update Employee' : 'Create Employee' }}
                </button>

            </div>

        </form>

    </div>

</div>
