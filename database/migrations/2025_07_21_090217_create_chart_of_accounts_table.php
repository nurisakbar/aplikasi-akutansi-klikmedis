<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('setting_id')->index();
            $table->string('code', 20)->index();
            $table->string('name', 100);
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('category', [
                'current_asset', 'fixed_asset', 'other_asset',
                'current_liability', 'long_term_liability',
                'equity',
                'operating_revenue', 'other_revenue',
                'operating_expense', 'other_expense'
            ]);
            $table->uuid('parent_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('level')->default(1);
            $table->string('path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Constraints
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('chart_of_accounts')
                  ->onDelete('restrict');
            
            // Unique combination for code within same setting
            $table->unique(['setting_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
