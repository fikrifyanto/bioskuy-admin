<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theater extends Model
{
    protected $fillable = [
        'name',
        'address',
        'capacity',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
