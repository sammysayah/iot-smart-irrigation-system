<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'moisture_level',
        'pump_status',
        'water_used',
        'reading_type'
    ];

    protected $casts = [
        'pump_status' => 'boolean',
        'moisture_level' => 'float',
        'water_used' => 'float'
    ];
}