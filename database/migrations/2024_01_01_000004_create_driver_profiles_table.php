<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('status', 50)->default('pending');
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_trips')->default(0);
            $table->string('license_number', 100)->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('id_document_path')->nullable();
            $table->decimal('last_latitude', 10, 7)->nullable();
            $table->decimal('last_longitude', 10, 7)->nullable();
            $table->timestampsTz();

            $table->index('partner_id', 'idx_driver_partner');
            $table->index('status', 'idx_driver_status');
            $table->index(['last_latitude', 'last_longitude'], 'idx_driver_location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_profiles');
    }
};
