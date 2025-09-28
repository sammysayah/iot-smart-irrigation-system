<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'processed',
        'duration',
        'expires_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'processed' => 'boolean',
        'expires_at' => 'datetime'
    ];

    // Scope to get active, unprocessed requests that haven't expired
    public function scopePending($query)
    {
        return $query->where('active', true)
                    ->where('processed', false)
                    ->where(function($q) {
                        $q->where('expires_at', '>', now())
                          ->orWhereNull('expires_at');
                    });
    }
}