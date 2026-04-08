<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_hash',
        'is_scanned',
        'order_id',
    ];

    protected $casts = [
        'is_scanned' => 'boolean',
    ];

    /**
     * Auto-generate a secure qr_code_hash on ticket creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->qr_code_hash)) {
                $ticket->qr_code_hash = strtoupper(Str::random(8)) . '-' . bin2hex(random_bytes(16));
            }
        });
    }

    /**
     * The order this ticket belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
