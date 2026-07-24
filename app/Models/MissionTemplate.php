<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissionTemplate extends Model
{
    protected $fillable = ['title', 'description', 'type', 'goal_type', 'goal_target', 'reward_amount', 'reward_currency', 'service_code', 'is_active', 'target_audience'];
    protected $casts = ['reward_amount' => 'decimal:2', 'is_active' => 'boolean'];

    public function driverMissions() { return $this->hasMany(DriverMission::class); }
    public function customerMissions() { return $this->hasMany(CustomerMission::class); }
}
