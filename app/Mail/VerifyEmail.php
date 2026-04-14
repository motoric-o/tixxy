<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerifyEmail extends Mailable
{
    private $user;
    private $verificationUrl;

    public function __construct(User $user, string $verificationUrl) {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Set the subject and sender details.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Email Anda — Tixxy'
        );
    }

    /**
     * Set the view and data.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify_email',
            with: [
                'user' => $this->user,
                'verificationUrl' => $this->verificationUrl,
            ],
        );
    }
}
