<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerStaff extends Model
{
    use HasFactory;

    protected $table = 'partner_staff';

    protected $fillable = [
        'partner_id',
        'user_id',
        'role',
        'status',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
