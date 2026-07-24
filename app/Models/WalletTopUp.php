<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTopUp extends Model
{
    protected $fillable = ['user_id', 'wallet_id', 'amount', 'currency', 'status', 'payment_method', 'xendit_invoice_id', 'external_id', 'invoice_url', 'paid_at', 'meta', 'convenience_fee', 'gateway_fee', 'total_charged', 'gateway_name', 'gateway_reference'];
    protected $casts = ['amount' => 'decimal:2', 'convenience_fee' => 'decimal:2', 'gateway_fee' => 'decimal:2', 'total_charged' => 'decimal:2', 'paid_at' => 'datetime', 'meta' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function wallet() { return $this->belongsTo(Wallet::class); }
}
