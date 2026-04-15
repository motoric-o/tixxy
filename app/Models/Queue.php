<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    const STATUS_QUEUED     = 'queued';
    const STATUS_ACTIVE     = 'active';
    const STATUS_WAITLISTED = 'waitlisted';
    const STATUS_NOTIFIED   = 'notified';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PURCHASED  = 'purchased';
    const STATUS_EXPIRED    = 'expired';
    const STATUS_CANCELED   = 'canceled';

    const HOLDING_STATUSES = [self::STATUS_ACTIVE, self::STATUS_PROCESSING];
    const ACTIVE_MINUTES = 15;
    const NOTIFIED_MINUTES = 60;

    protected $fillable = ['event_id', 'user_id', 'status', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeHolding($query)
    {
        return $query->whereIn('status', self::HOLDING_STATUSES);
    }

    public function scopeQueued($query)
    {
        return $query->where('status', self::STATUS_QUEUED);
    }

    public function scopeWaitlisted($query)
    {
        return $query->where('status', self::STATUS_WAITLISTED);
    }

    public function scopeNotified($query)
    {
        return $query->where('status', self::STATUS_NOTIFIED);
    }

    public function scopePromotable($query)
    {
        return $query->whereIn('status', [self::STATUS_QUEUED, self::STATUS_WAITLISTED])
                     ->orderBy('created_at', 'asc');
    }

    public function scopeExpirable($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_NOTIFIED])
                     ->whereNotNull('expires_at')
                     ->where('expires_at', '<=', now());
    }
}
