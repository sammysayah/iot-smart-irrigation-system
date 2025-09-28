<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'water_used',
        'duration_seconds', // Changed to match database
        'trigger_type',
        'notes'
    ];

    protected $casts = [
        'water_used' => 'float',
        'duration_seconds' => 'integer' // Changed to match database
    ];

    // Optional: Add an accessor if you want to use 'duration' in your code
    public function getDurationAttribute()
    {
        return $this->duration_seconds;
    }

    // Optional: Add a mutator if you want to set 'duration' in your code
    public function setDurationAttribute($value)
    {
        $this->attributes['duration_seconds'] = $value;
    }
}