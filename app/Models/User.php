<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role',
        'email_verified_at',
        'is_banned',
        'banned_at',
        'ban_reason',
        'banned_by',
        'date_of_birth',
        'address_line1',
        'address_line2',
        'profile_photo_url',
        'referral_code',
        'referred_by',
        'points',
        'google_id',
        'fcm_token',
        'profile_picture',
        'account_status',
        'deactivated_at',
        'deactivation_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
            'date_of_birth' => 'date',
            'deactivated_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->is_banned) {
            return false;
        }

        return in_array($this->role, ['admin', 'staff']);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function driverProfile()
    {
        return $this->hasOne(DriverProfile::class);
    }

    public function partner()
    {
        return $this->hasOne(Partner::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function bannedUsers()
    {
        return $this->hasMany(User::class, 'banned_by');
    }
}
