@extends('layouts.main')

@section('title', 'Edit Employee')

@section('content')
    <div class="container py-4">
        <div class="page-header mb-4">
            <h4 class="page-title mb-1">Edit Employee</h4>

            <p class="page-description mb-0">
                Update the employee's information and project assignments.
            </p>

            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary mt-3">
                Back to Employees
            </a>
        </div>

        <x-employee-form :employee="$employee" :departments="$departments" :projects="$projects" :roles="$roles" />
    </div>
@endsection
