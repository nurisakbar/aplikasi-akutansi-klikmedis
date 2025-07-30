<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts_receivable', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->date('date');
            $table->date('due_date');
            $table->decimal('amount', 20, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts_receivable');
    }
}; 