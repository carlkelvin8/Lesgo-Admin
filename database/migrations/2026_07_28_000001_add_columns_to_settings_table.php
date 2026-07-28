<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'type')) {
                    $table->string('type')->default('string');
                }
                if (!Schema::hasColumn('settings', 'group')) {
                    $table->string('group')->default('general');
                }
                if (!Schema::hasColumn('settings', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }
    }

    public function down(): void {}
};
