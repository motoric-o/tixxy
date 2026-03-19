<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'status',
    ];

    /**
     * The event this queue entry belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
