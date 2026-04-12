<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    /**
     * Composite primary key.
     *
     * @var array<string>
     */
    protected $primaryKey = ['order_id', 'ticket_id'];

    /**
     * Disable auto-incrementing since the PK is composite.
     */
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'ticket_id',
        'event_ticket_type_id',
    ];

    /**
     * The order this detail line belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * The ticket associated with this detail line.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * The event-ticket-type configuration for this line item.
     */
    public function eventTicketType()
    {
        return $this->belongsTo(EventTicketType::class);
    }
}
