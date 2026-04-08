<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'user_id',
        'event_id',
        'payment_proof',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expired_at' => 'datetime',
    ];

    /**
     * The customer who placed this order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The event this order belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Order detail lines (which ticket types and quantities).
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Physical tickets generated for this order.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all possible order statuses.
     */
    public static function getStatuses(): array
    {
        return [
            'pending'   => 'Pending',
            'completed' => 'Completed',
            'canceled'  => 'Canceled',
        ];
    }
}
