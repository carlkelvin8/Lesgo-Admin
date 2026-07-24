<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'partner_id',
        'driver_id',
        'service_id',
        'pickup_address_id',
        'dropoff_address_id',
        'status',
        'scheduled_at',
        'accepted_at',
        'picked_up_at',
        'completed_at',
        'cancelled_at',
        'driver_arrived_at',
        'in_progress_at',
        'estimated_distance_m',
        'actual_distance_m',
        'estimated_fare',
        'actual_fare',
        'partner_share',
        'driver_share',
        'platform_fee',
        'platform_share',
        'payment_method',
        'payment_status',
        'cancel_reason',
        'pickup_picture',
        'dropoff_picture',
        'proof_pickup_image',
        'proof_delivery_image',
        'meta',
        'voucher_code',
        'voucher_discount',
        'scheduled_delivery_time',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'accepted_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'driver_arrived_at' => 'datetime',
        'in_progress_at' => 'datetime',
        'scheduled_delivery_time' => 'datetime',
        'estimated_fare' => 'decimal:2',
        'actual_fare' => 'decimal:2',
        'partner_share' => 'decimal:2',
        'driver_share' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'platform_share' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function driver()
    {
        return $this->belongsTo(DriverProfile::class, 'driver_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function pickupAddress()
    {
        return $this->belongsTo(Address::class, 'pickup_address_id');
    }

    public function dropoffAddress()
    {
        return $this->belongsTo(Address::class, 'dropoff_address_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackingEvents()
    {
        return $this->hasMany(OrderTrackingEvent::class);
    }
}
