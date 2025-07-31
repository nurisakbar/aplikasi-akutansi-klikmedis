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
        Schema::create('accountancy_fixed_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 20)->index();
            $table->string('name', 100);
            $table->string('category');
            $table->date('acquisition_date');
            $table->decimal('acquisition_value', 20, 2);
            $table->integer('useful_life');
            $table->string('depreciation_method');
            $table->decimal('residual_value', 20, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountancy_fixed_assets');
    }
};
