<?php

namespace App\Mail;

use App\Models\Queue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WaitlistTicketAvailable extends Mailable
{
    private Queue $queueEntry;

    public function __construct(Queue $queueEntry)
    {
        $this->queueEntry = $queueEntry;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A Ticket is Available! — ' . ($this->queueEntry->event->title ?? 'Tixxy')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.waitlist_available',
            with: [
                'queueEntry' => $this->queueEntry,
                'event'      => $this->queueEntry->event,
                'user'       => $this->queueEntry->user,
                'claimUrl'   => url("/queue/claim/{$this->queueEntry->event_id}"),
                'expiresAt'  => $this->queueEntry->expires_at,
            ],
        );
    }
}
