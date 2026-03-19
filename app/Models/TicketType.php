<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Event-specific configurations for this ticket type.
     */
    public function eventTicketTypes()
    {
        return $this->hasMany(EventTicketType::class);
    }
}
