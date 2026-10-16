@extends('layouts.main')

@section('title', 'Add Employee')

@section('content')

    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

        <div class="mb-6 border-b border-slate-200 pb-5">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                Add Employee
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Enter the employees information and assign projects.
            </p>

            <a href="{{ route('employees.index') }}"
                class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                <span aria-hidden="true">&larr;</span>
                Back to Employees
            </a>
        </div>

        <x-employee-form :departments="$departments" :projects="$projects" :roles="$roles" />

    </div>

@endsection
