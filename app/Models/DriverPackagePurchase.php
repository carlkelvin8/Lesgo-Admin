<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverPackagePurchase extends Model
{
    protected $fillable = ['driver_profile_id', 'package_tier', 'amount', 'payment_method', 'payment_reference', 'status', 'expires_at'];
    protected $casts = ['amount' => 'decimal:2', 'expires_at' => 'datetime'];

    public function driver() { return $this->belongsTo(DriverProfile::class, 'driver_profile_id'); }
}
