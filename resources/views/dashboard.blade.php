<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} <div class="p-6 text-gray-900">
                <p>To assign user roles, click <a href="{{ route('user-roles.assign') }}"
                        class="text-sm font-medium text-indigo-700 hover:text-indigo-600">here</a>.</p>
            </div>
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('employees.index') }}">View Employee Data</a>
                </div>
            </div>
        </div>

    </div>



</x-app-layout>
