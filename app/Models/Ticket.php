<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * The order this ticket belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
