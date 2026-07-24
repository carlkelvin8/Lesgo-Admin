<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDutyAttendance extends Model
{
    protected $fillable = ['driver_profile_id', 'clock_in_at', 'clock_out_at', 'status', 'total_hours'];
    protected $casts = ['clock_in_at' => 'datetime', 'clock_out_at' => 'datetime', 'total_hours' => 'decimal:2'];

    public function driver() { return $this->belongsTo(DriverProfile::class, 'driver_profile_id'); }
}
