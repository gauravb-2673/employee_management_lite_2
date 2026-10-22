<?php

namespace App\Listeners;

use App\Events\UserRoleChanged;
use Illuminate\Support\Facades\Log;

class LogUserRoleChange
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRoleChanged $event): void
    {
        Log::info('User role changed', [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'old_role' => $event->oldRole,
            'new_role' => $event->newRole,
            'changed_at' => now()->toDateTimeString(),
        ]);
    }
}
