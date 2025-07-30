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
        Schema::table('akuntansi_journal_entries', function (Blueprint $table) {
            $table->dropUnique(['journal_number']);
        });

        // Add new unique constraint
        Schema::table('akuntansi_journal_entries', function (Blueprint $table) {
            $table->unique('journal_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new unique constraint
        Schema::table('akuntansi_journal_entries', function (Blueprint $table) {
            $table->dropUnique(['journal_number']);
        });

        // Add back original unique constraint
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->unique('journal_number');
        });
    }
}; 