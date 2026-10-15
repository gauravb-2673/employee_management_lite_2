@extends('layouts.main')

@section('title', 'Add Employee')

@section('content')
    <div class="container py-4">
        <div class="page-header mb-4">
            <h4 class="page-title mb-1">Add Employee</h4>

            <p class="page-description mb-0">
                Enter the Employee&#39; s information and assign projects.
            </p>

            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary mt-3">
                Back to Employees
            </a>
        </div>

        <x-employee-form :departments="$departments" :projects="$projects" :roles="$roles" />
    </div>
@endsection
