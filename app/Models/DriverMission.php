<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverMission extends Model
{
    protected $fillable = ['driver_profile_id', 'mission_template_id', 'current_progress', 'goal_target', 'is_completed', 'completed_at', 'reward_claimed', 'claimed_at', 'mission_date'];
    protected $casts = ['is_completed' => 'boolean', 'reward_claimed' => 'boolean', 'completed_at' => 'datetime', 'claimed_at' => 'datetime', 'mission_date' => 'date'];

    public function driver() { return $this->belongsTo(DriverProfile::class, 'driver_profile_id'); }
    public function template() { return $this->belongsTo(MissionTemplate::class, 'mission_template_id'); }
}
