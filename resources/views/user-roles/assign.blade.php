<x-app-layout>

    {{-- Page header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign or Change User Role
        </h2>
    </x-slot>

    {{-- Main content --}}
    <div class="py-12">

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success message --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800"
                    role="status">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">

                    <p class="font-semibold">
                        Please check the following:
                    </p>

                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            {{-- Assignment form card --}}
            <div class="overflow-hidden rounded-lg bg-white shadow-sm">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h3 class="text-lg font-semibold text-gray-900">
                        User Role Management
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Assign a role to a user or replace their existing role.
                    </p>

                </div>

                <div class="p-6">

                    {{-- No users available --}}
                    @if ($users->isEmpty())

                        <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-800">
                            No users found. Create a user before assigning a role.
                        </div>

                        {{-- No roles available --}}
                    @elseif ($roles->isEmpty())
                        <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-800">
                            No roles found. Run your role and permission seeder first.
                        </div>

                        {{-- Display the form --}}
                    @else
                        <form action="{{ route('user-roles.store') }}" method="POST" class="space-y-6">

                            @csrf

                            {{-- Select user --}}
                            <div>

                                <label for="user_id" class="block text-sm font-medium text-gray-700">
                                    Select User
                                </label>

                                <select name="user_id" id="user_id" required
                                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                    <option value="">
                                        -- Select a User --
                                    </option>

                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>
                                            {{ $user->name }}
                                            ({{ $user->email }})
                                            — Current role:
                                            {{ $user->getRoleNames()->isEmpty() ? 'None' : $user->getRoleNames()->join(', ') }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('user_id')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            {{-- Select role --}}
                            <div>

                                <label for="role" class="block text-sm font-medium text-gray-700">
                                    Select Role
                                </label>

                                <select name="role" id="role" required
                                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                    <option value="">
                                        -- Select a Role --
                                    </option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('role')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                            {{-- Help text --}}
                            <div class="rounded-md bg-indigo-50 p-4">

                                <p class="text-sm text-indigo-800">
                                    <span class="font-semibold">Note:</span>
                                    If the selected user already has a role, use
                                    <strong>Change Existing Role</strong>
                                    to replace it.
                                </p>

                            </div>

                            {{-- Action buttons --}}
                            <div class="flex flex-col gap-3 border-t border-gray-200 pt-5 sm:flex-row">

                                <button type="submit" name="action" value="assign"
                                    class="inline-flex w-full items-center justify-center rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                                    Assign Role
                                </button>

                                <button type="submit" name="action" value="change"
                                    class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                                    Change Existing Role
                                </button>

                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex w-full items-center justify-center rounded-md px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 sm:ml-auto sm:w-auto">
                                    Back to Dashboard
                                </a>

                            </div>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
