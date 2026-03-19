<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'start_time',
        'end_time',
        'status',
        'quota',
        'user_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    /**
     * The organizer (user) who owns this event.
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Ticket type configurations for this event (price & capacity per type).
     */
    public function eventTicketTypes()
    {
        return $this->hasMany(EventTicketType::class);
    }

    /**
     * Orders placed for this event.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Queues for this event.
     */
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
