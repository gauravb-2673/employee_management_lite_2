@php
    // True when this component receives an existing Employee model.
    $editing = isset($employee) && $employee->exists;

    // On validation failure, submitted selections take priority.
    // Otherwise, show the employee's saved project assignments.
$selectedProjects = old('project_ids', $editing ? $employee->projects->pluck('id')->all() : []);
@endphp

{{-- BASIC EMPLOYEE INFORMATION --}}
<div class="section-title mb-3">
    Basic Information
</div>

<div class="row g-4">

    {{-- Employee Name --}}
    <div class="col-md-6">
        <label for="name" class="form-label">Name</label>

        <input type="text" name="name" id="name" maxlength="100" value="{{ old('name', $employee->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror" required>

        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Employee Email --}}
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>

        <input type="email" name="email" id="email" maxlength="100"
            value="{{ old('email', $employee->email ?? '') }}" class="form-control @error('email') is-invalid @enderror"
            required>

        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Salary --}}
    <div class="col-md-6">
        <label for="salary" class="form-label">Salary</label>

        <input type="number" name="salary" id="salary" min="0" step="0.01"
            value="{{ old('salary', $employee->salary ?? '') }}"
            class="form-control @error('salary') is-invalid @enderror" required>

        @error('salary')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Joining Date --}}
    <div class="col-md-6">
        <label for="joining_date" class="form-label">Joining Date</label>

        <input type="date" name="joining_date" id="joining_date" max="{{ now()->format('Y-m-d') }}"
            value="{{ old('joining_date', $employee?->joining_date?->format('Y-m-d') ?? '') }}"
            class="form-control @error('joining_date') is-invalid @enderror" required>

        @error('joining_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Department --}}
    <div class="col-md-6">
        <label for="department_id" class="form-label">Department</label>

        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror"
            required>
            <option value="">Select department</option>

            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected((string) old('department_id', $employee->department_id ?? '') === (string) $department->id)>
                    {{ $department->department_name }}
                </option>
            @endforeach
        </select>

        @error('department_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<hr class="my-4">

{{-- PROJECT ASSIGNMENTS --}}
<div class="section-title mb-1">
    Project Assignments
</div>

<p class="text-muted small mb-3">
    Select the projects assigned to this employee and choose a role.
</p>

@error('project_ids')
    <div class="text-danger mb-2">{{ $message }}</div>
@enderror

@error('project_roles')
    <div class="text-danger mb-2">{{ $message }}</div>
@enderror

@foreach ($projects as $project)
    @php
        // Find this employee's existing assignment, if any.
$assignedProject = $editing ? $employee->projects->firstWhere('id', $project->id) : null;

        // Preserve the submitted role after validation fails.
        // Otherwise, use the saved pivot role, or the first available role.
        $selectedRole = old("project_roles.{$project->id}", $assignedProject?->pivot?->role ?? $roles->first()?->id);
    @endphp

    <div class="project-row">

        <div class="row align-items-center g-3">

            {{-- Project Checkbox --}}
            <div class="col-md-6">
                <div class="form-check">

                    <input type="checkbox" name="project_ids[]" value="{{ $project->id }}"
                        id="project_{{ $project->id }}" class="form-check-input" @checked(in_array($project->id, $selectedProjects))>

                    <label for="project_{{ $project->id }}" class="form-check-label project-name">
                        {{ $project->project_name }}
                    </label>

                </div>
            </div>

            {{-- Project Role --}}
            <div class="col-md-6">
                <label for="project_role_{{ $project->id }}" class="form-label">
                    Role
                </label>

                <select name="project_roles[{{ $project->id }}]" id="project_role_{{ $project->id }}"
                    class="form-select @error('project_roles.' . $project->id) is-invalid @enderror">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected((string) $selectedRole === (string) $role->id)>
                            {{ $role->role_name }}
                        </option>
                    @endforeach
                </select>

                @error('project_roles.' . $project->id)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

    </div>
@endforeach

{{-- ACTIVE STATUS --}}
<hr class="my-4">

<div class="section-title mb-3">
    Employee Status
</div>

<input type="hidden" name="is_active" value="0">

<div class="form-check">
    <input type="checkbox" name="is_active" id="is_active" value="1"
        class="form-check-input @error('is_active') is-invalid @enderror" @checked((bool) old('is_active', $employee->is_active ?? true))>

    <label for="is_active" class="form-check-label">
        Active Employee
    </label>

    @error('is_active')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
