<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class ExpenseClaimPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employee $employee): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hr')) {
            return true;
        }

        if ($user->hasRole('employee')) {
            return $employee->user_id === $user->id;
        }

        return false;
    }
}
