<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('driver_profiles')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PHP');
            $table->string('method', 50);
            $table->string('status', 50)->default('pending');
            $table->string('provider', 100)->nullable();
            $table->string('provider_reference')->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestampsTz();

            $table->index('order_id', 'idx_payments_order');
            $table->index('status', 'idx_payments_status');
            $table->index('method', 'idx_payments_method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
