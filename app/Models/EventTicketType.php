<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'ticket_type_id',
        'price',
        'capacity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * The event this configuration belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * The ticket type for this configuration.
     */
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    /**
     * Order detail lines that reference this event-ticket-type.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
