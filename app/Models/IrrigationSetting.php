<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrrigationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'moisture_threshold',
        'pump_duration',
        'auto_mode',
        'system_enabled'
    ];

    protected $casts = [
        'auto_mode' => 'boolean',
        'system_enabled' => 'boolean',
        'moisture_threshold' => 'float',
        'pump_duration' => 'integer'
    ];
}