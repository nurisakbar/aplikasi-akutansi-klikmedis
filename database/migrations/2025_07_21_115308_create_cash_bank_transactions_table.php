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
        Schema::create('cash_bank_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->uuid('account_id'); // relasi ke chart_of_accounts (kas/bank)
            $table->enum('type', ['in', 'out', 'transfer']);
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->string('status')->default('posted');
            $table->string('reference')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('chart_of_accounts')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_bank_transactions');
    }
};
