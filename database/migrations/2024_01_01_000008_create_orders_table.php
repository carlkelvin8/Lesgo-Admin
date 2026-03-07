<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('driver_profiles')->nullOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('pickup_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('dropoff_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('status', 50)->default('pending');
            $table->timestampTz('scheduled_at')->nullable();
            $table->timestampTz('accepted_at')->nullable();
            $table->timestampTz('picked_up_at')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->integer('estimated_distance_m')->nullable();
            $table->integer('actual_distance_m')->nullable();
            $table->decimal('estimated_fare', 10, 2)->nullable();
            $table->decimal('actual_fare', 10, 2)->nullable();
            $table->decimal('partner_share', 10, 2)->nullable();
            $table->decimal('driver_share', 10, 2)->nullable();
            $table->decimal('platform_fee', 10, 2)->nullable();
            $table->string('payment_method', 50)->default('cash');
            $table->string('payment_status', 50)->default('pending');
            $table->string('cancel_reason')->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestampsTz();

            $table->index('customer_id', 'idx_orders_customer');
            $table->index('partner_id', 'idx_orders_partner');
            $table->index('driver_id', 'idx_orders_driver');
            $table->index('status', 'idx_orders_status');
            $table->index('payment_status', 'idx_orders_payment_status');
            $table->index('completed_at', 'idx_orders_completed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
