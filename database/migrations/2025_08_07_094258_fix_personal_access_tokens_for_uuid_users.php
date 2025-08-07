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
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['tokenable_id', 'tokenable_type']);
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Recreate columns with proper UUID support
            $table->uuidMorphs('tokenable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Drop UUID morphs columns
            $table->dropColumn(['tokenable_id', 'tokenable_type']);
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Recreate original morphs columns
            $table->morphs('tokenable');
        });
    }
};
