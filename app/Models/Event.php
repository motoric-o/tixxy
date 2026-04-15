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
        'location',
        'start_time',
        'end_time',
        'status',
        'quota',
        'category_id',
        'user_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    /**
     * The category this event belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

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
     * Mendapatkan semua tiket melalui relasi Order.
     */
    public function tickets()
    {
        // Relasi: Event has many Tickets through Orders
        return $this->hasManyThrough(
            Ticket::class, 
            Order::class
        );
    }

    /**
     * Queues for this event.
     */
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    /**
     * Get all possible event statuses.
     */
    public static function getStatuses(): array
    {
        return [
            'pending'     => 'Pending',
            'preparation' => 'Preparation',
            'ongoing'     => 'Ongoing',
            'completed'   => 'Completed',
            'canceled'    => 'Canceled',
        ];
    }
}
