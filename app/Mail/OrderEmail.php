<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderEmail extends Mailable
{
    private $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    /**
     * Set the subject and sender details.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order #' . $this->order->id . ' - Tixxy'
        );
    }

    /**
     * Set the view and data.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_email',
            with: [
                'order' => $this->order,
            ],
        );
    }
}
