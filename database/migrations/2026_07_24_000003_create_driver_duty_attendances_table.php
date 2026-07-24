<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('driver_duty_attendances')) {
            Schema::create('driver_duty_attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_profile_id')->constrained('driver_profiles')->cascadeOnDelete();
                $table->timestamp('clock_in_at')->nullable();
                $table->timestamp('clock_out_at')->nullable();
                $table->string('status')->default('on_duty');
                $table->decimal('total_hours', 8, 2)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_duty_attendances');
    }
};
