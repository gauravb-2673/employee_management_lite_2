<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    /**
     * Display the user role assignment form.
     */
    public function index()
    {
        // Retrieve users and their existing roles.
        $users = User::with('roles')
            ->orderBy('name')
            ->get();

        // Retrieve roles configured for the web guard.
        $roles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        // Display the form.
        return view('user-roles.assign', compact('users', 'roles'));
    }

    /**
     * Assign a role or explicitly change an existing role.
     */
    public function store(Request $request)
    {
        // Validate the submitted values.
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')
                    ->where('guard_name', 'web'),
            ],

            'action' => [
                'required',
                'in:assign,change',
            ],
        ]);

        // Find the selected user.
        $user = User::findOrFail($validated['user_id']);

        // Get the user's current roles.
        $existingRoles = $user->getRoleNames();

        // Find the selected role.
        $role = Role::where('name', $validated['role'])
            ->where('guard_name', 'web')
            ->firstOrFail();

        /*
         * OPTION 1:
         * Assign a role only if the user has no existing role.
         */
        if ($validated['action'] === 'assign') {

            if ($existingRoles->isNotEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'role' => 'This user already has the role(s): '
                            . $existingRoles->join(', ')
                            . '. Remove the existing role or choose '
                            . 'Change Existing Role.',
                    ]);
            }

            // Assign the role to a user who has no role.
            $user->assignRole($role);

            return redirect()
                ->route('user-roles.assign')
                ->with('success', 'Role assigned successfully.');
        }

        /*
         * OPTION 2:
         * Replace the user's existing role with the selected role.
         */
        if ($validated['action'] === 'change') {

            if ($existingRoles->isEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'role' => 'This user has no existing role. '
                            . 'Choose Assign Role instead.',
                    ]);
            }

            // Remove existing role assignments and assign only this role.
            $user->syncRoles([$role]);

            return redirect()
                ->route('user-roles.assign')
                ->with(
                    'success',
                    'Existing role changed successfully.'
                );
        }
    }
}
