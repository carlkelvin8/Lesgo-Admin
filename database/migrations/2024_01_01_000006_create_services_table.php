<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('code', 100)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('base_fare', 10, 2)->default(0);
            $table->decimal('per_km_rate', 10, 2)->default(0);
            $table->decimal('per_minute_rate', 10, 2)->default(0);
            $table->decimal('minimum_fare', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();

            $table->index('partner_id', 'idx_services_partner');
            $table->index('is_active', 'idx_services_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
