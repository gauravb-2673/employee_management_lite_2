<?php

namespace App\Listeners;

use App\Events\UserRoleChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendRoleChangeEmail implements ShouldQueue
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
        $user = $event->user;

        Mail::raw(
            "Hello {$user->name},\n\n"
                ."Your role has been changed.\n"
                .'Previous role: '.($event->oldRole ?? 'None')."\n"
                ."New role: {$event->newRole}\n\n"
                .'If you have questions, please contact your administrator.',
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your Role Has Been Updated');
            }
        );
    }
}
