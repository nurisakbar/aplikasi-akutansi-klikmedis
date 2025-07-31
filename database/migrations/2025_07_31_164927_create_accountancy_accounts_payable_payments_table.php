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
        Schema::create('accountancy_accounts_payable_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('accounts_payable_id');
            $table->date('date');
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('accounts_payable_id', 'accountancy_ap_payments_ap_id_fk')->references('id')->on('accountancy_accounts_payable')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountancy_accounts_payable_payments');
    }
};
