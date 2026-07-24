<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LesbuyItem extends Model
{
    protected $fillable = ['order_id', 'name', 'quantity', 'estimated_price', 'is_checklist_item', 'status'];
    protected $casts = ['estimated_price' => 'decimal:2', 'is_checklist_item' => 'boolean'];

    public function order() { return $this->belongsTo(Order::class); }
}
