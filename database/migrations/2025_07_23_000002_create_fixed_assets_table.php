<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->date('acquisition_date');
            $table->decimal('acquisition_value', 20, 2);
            $table->integer('useful_life');
            $table->string('depreciation_method');
            $table->decimal('residual_value', 20, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
}; 