<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'description',
        'latitude',
        'longitude',
        'meta',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
