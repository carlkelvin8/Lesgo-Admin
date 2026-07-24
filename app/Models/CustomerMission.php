<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMission extends Model
{
    protected $fillable = ['user_id', 'mission_template_id', 'current_progress', 'goal_target', 'is_completed', 'completed_at', 'reward_claimed', 'claimed_at', 'mission_date'];
    protected $casts = ['is_completed' => 'boolean', 'reward_claimed' => 'boolean', 'completed_at' => 'datetime', 'claimed_at' => 'datetime', 'mission_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
    public function template() { return $this->belongsTo(MissionTemplate::class, 'mission_template_id'); }
}
