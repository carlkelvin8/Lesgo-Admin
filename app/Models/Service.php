<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'code',
        'name',
        'description',
        'base_fare',
        'per_km_rate',
        'per_minute_rate',
        'minimum_fare',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_fare' => 'decimal:2',
        'per_km_rate' => 'decimal:2',
        'per_minute_rate' => 'decimal:2',
        'minimum_fare' => 'decimal:2',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
