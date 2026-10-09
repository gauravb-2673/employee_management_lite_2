<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Assign or Change User Role</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 650px;
            margin: 40px auto;
            padding: 20px;
        }

        label {
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        select,
        button {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            margin-top: 15px;
            cursor: pointer;
        }

        .success {
            padding: 12px;
            background: #e6ffed;
            margin-bottom: 20px;
        }

        .error {
            padding: 12px;
            background: #ffecec;
            color: #b00020;
            margin-bottom: 20px;
        }

        .help {
            margin-top: 8px;
            color: #555;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <h1>Assign or Change User Role</h1>

    {{-- Display a success message. --}}
    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Display validation or role assignment errors. --}}
    @if ($errors->any())
        <div class="error">
            <strong>Please check the following:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($users->isEmpty())

        <p>No users found. Create a user before assigning a role.</p>
    @elseif ($roles->isEmpty())
        <p>No roles found. Run your role and permission seeder first.</p>
    @else
        <form action="{{ route('user-roles.store') }}" method="POST">

            @csrf

            {{-- Select an existing user. --}}
            <label for="user_id">Select User</label>

            <select name="user_id" id="user_id" required>

                <option value="">-- Select a User --</option>

                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>
                        {{ $user->name }}
                        ({{ $user->email }})
                        - Current role:
                        {{ $user->getRoleNames()->isEmpty() ? 'None' : $user->getRoleNames()->join(', ') }}
                    </option>
                @endforeach

            </select>

            {{-- Select the new role. --}}
            <label for="role">Select Role</label>

            <select name="role" id="role" required>

                <option value="">-- Select a Role --</option>

                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach

            </select>

            <p class="help">
                If the selected user already has a role, use
                Change Existing Role to replace it.
            </p>

            {{-- Assign a role only if the user has none. --}}
            <button type="submit" name="action" value="assign">
                Assign Role
            </button>

            {{-- Replace the user's existing role. --}}
            <button type="submit" name="action" value="change">
                Change Existing Role
            </button>

        </form>

    @endif

</body>

</html>
