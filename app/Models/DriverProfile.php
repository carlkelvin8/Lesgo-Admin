<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_id',
        'status',
        'rating',
        'total_trips',
        'license_number',
        'license_expiry_date',
        'id_document_path',
        'last_latitude',
        'last_longitude',
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
        'rating' => 'decimal:2',
        'last_latitude' => 'decimal:7',
        'last_longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'driver_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'driver_id');
    }
}
