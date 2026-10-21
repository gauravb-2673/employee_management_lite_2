<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmployeeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The employee who will receive the welcome email.
     */
    public function __construct(
        public Employee $employee
    ) {
        //
    }

    /**
     * Define the email subject.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Our Company!'
        );
    }

    /**
     * Define the email content and Blade view.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-employee',
        );
    }

    /**
     * Define any email attachments.
     */
    public function attachments(): array
    {
        return [];
    }
}
