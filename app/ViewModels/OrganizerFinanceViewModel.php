<?php

namespace App\ViewModels;

use App\Models\Order;
use App\Models\Event;
use App\Models\Ticket;

class OrganizerFinanceViewModel extends FinanceViewModel
{
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Scope orders to only those belonging to the organizer's events.
     */
    protected function baseOrderQuery()
    {
        return Order::whereHas('event', fn($q) => $q->where('user_id', $this->userId));
    }

    /**
     * Scope events to only those owned by this organizer.
     */
    protected function baseEventQuery()
    {
        return Event::where('user_id', $this->userId);
    }

    /**
     * Scope tickets to only those from the organizer's events.
     */
    protected function baseTicketQuery()
    {
        return Ticket::whereHas('order.event', fn($q) => $q->where('user_id', $this->userId));
    }

    /**
     * Return the user ID for ticket-type-breakdown scoping.
     */
    protected function scopeUserId(): ?int
    {
        return $this->userId;
    }
}
