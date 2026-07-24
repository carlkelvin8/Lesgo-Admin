<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geofence extends Model
{
    protected $fillable = ['name', 'type', 'coordinates', 'radius', 'is_active'];
    protected $casts = ['coordinates' => 'array', 'is_active' => 'boolean'];
}
