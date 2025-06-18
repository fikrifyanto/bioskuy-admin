<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'movie_id',
        'theater_id',
        'start_time',
        'end_time',
        'price',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'float',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function theater(): BelongsTo
    {
        return $this->belongsTo(Theater::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
