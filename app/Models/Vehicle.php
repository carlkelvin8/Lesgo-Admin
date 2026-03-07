<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'partner_id',
        'type',
        'plate_number',
        'brand',
        'model',
        'color',
        'year',
        'is_primary',
        'status',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'year' => 'integer',
    ];

    public function driver()
    {
        return $this->belongsTo(DriverProfile::class, 'driver_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
