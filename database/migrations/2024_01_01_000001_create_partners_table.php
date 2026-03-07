<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('business_type', 100)->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->string('support_email')->nullable();
            $table->string('support_phone', 100)->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestampsTz();

            $table->index('status', 'idx_partners_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
