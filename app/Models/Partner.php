<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'legal_name',
        'slug',
        'business_type',
        'tax_id',
        'support_email',
        'support_phone',
        'status',
        'logo_url',
        'cover_image_url',
        'description',
        'category',
        'tags',
        'cuisine_types',
        'rating',
        'total_reviews',
        'delivery_fee',
        'min_order_amount',
        'estimated_delivery_minutes',
        'is_open',
        'is_featured',
        'accepts_online_payment',
        'opening_hours',
        'documents',
    ];

    protected $casts = [
        'tags' => 'array',
        'cuisine_types' => 'array',
        'opening_hours' => 'array',
        'documents' => 'array',
        'rating' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'is_open' => 'boolean',
        'is_featured' => 'boolean',
        'accepts_online_payment' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branches()
    {
        return $this->hasMany(PartnerBranch::class);
    }

    public function drivers()
    {
        return $this->hasMany(DriverProfile::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function staff()
    {
        return $this->hasMany(PartnerStaff::class);
    }
}
