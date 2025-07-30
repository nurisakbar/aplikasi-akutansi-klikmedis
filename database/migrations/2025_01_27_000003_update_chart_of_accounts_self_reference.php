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
        // Drop existing self-referencing foreign key constraint
        Schema::table('akuntansi_chart_of_accounts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });

        // Add new self-referencing foreign key constraint
        Schema::table('akuntansi_chart_of_accounts', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('akuntansi_chart_of_accounts')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new self-referencing foreign key constraint
        Schema::table('akuntansi_chart_of_accounts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });

        // Add back original self-referencing foreign key constraint
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('chart_of_accounts')
                  ->onDelete('restrict');
        });
    }
}; 