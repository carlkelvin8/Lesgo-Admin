<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'name',
        'phone_number',
        'address_line1',
        'address_line2',
        'city',
        'region',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'is_primary',
        'opening_hours',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'opening_hours' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
