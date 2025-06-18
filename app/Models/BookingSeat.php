<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSeat extends Model
{
    protected $fillable = [
        'booking_id',
        'seat_id',
    ];

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
