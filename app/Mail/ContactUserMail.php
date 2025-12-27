<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public string $emailSubject;

    public string $message;

    public string $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $subject, string $message, string $adminName)
    {
        $this->user = $user;
        $this->emailSubject = $subject;
        $this->message = $message;
        $this->adminName = $adminName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->emailSubject)
            ->markdown('emails.admin.contact-user')
            ->with([
                'user' => $this->user,
                'subject' => $this->emailSubject,
                'message' => $this->message,
                'adminName' => $this->adminName,
            ]);
    }
}
