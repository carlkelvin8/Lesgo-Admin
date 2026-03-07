<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('driver_profiles')->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('type', 50);
            $table->string('plate_number', 100);
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->smallInteger('year')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->string('status', 50)->default('active');
            $table->timestampsTz();

            $table->index('driver_id', 'idx_vehicles_driver');
            $table->index('partner_id', 'idx_vehicles_partner');
            $table->index('plate_number', 'idx_vehicles_plate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
