<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing unique constraint
        Schema::table('akuntansi_chart_of_accounts', function (Blueprint $table) {
            $table->dropUnique(['setting_id', 'code']);
        });

        // Add new unique constraint
        Schema::table('akuntansi_chart_of_accounts', function (Blueprint $table) {
            $table->unique(['setting_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new unique constraint
        Schema::table('akuntansi_chart_of_accounts', function (Blueprint $table) {
            $table->dropUnique(['setting_id', 'code']);
        });

        // Add back original unique constraint
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->unique(['setting_id', 'code']);
        });
    }
}; 