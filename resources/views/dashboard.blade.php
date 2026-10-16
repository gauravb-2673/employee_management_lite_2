<x-app-layout>

    {{-- Dashboard header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Main dashboard content --}}
    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="space-y-6">

                {{-- Assign User Roles card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            User Role Management
                        </h3>

                        <p class="text-sm text-gray-600">
                            To assign roles to users, click
                            <a href="{{ route('user-roles.assign') }}"
                                class="font-medium text-indigo-700 hover:text-indigo-900 underline">
                                here
                            </a>.
                        </p>

                    </div>
                </div>

                {{-- Employee Data card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Employee Management
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">
                            View and manage employee records.
                        </p>

                        <a href="{{ route('employees.index') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            View Employee Data
                        </a>

                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>
