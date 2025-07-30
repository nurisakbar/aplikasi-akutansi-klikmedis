<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts_payable_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('accounts_payable_id');
            $table->date('date');
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('accounts_payable_id')->references('id')->on('accounts_payable')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts_payable_payments');
    }
}; 