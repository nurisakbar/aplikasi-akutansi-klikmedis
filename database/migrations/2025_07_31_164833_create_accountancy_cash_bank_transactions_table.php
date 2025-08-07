<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CashBankTransactionType;
use App\Enums\CashBankTransactionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accountancy_cash_bank_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('accountancy_chart_of_account_id'); // relasi ke chart_of_accounts (kas/bank)
            $table->uuid('accountancy_company_id'); // relasi ke companies
            $table->date('date');
            $table->enum('type', CashBankTransactionType::values());
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->enum('status', CashBankTransactionStatus::values())->default(CashBankTransactionStatus::DRAFT->value);
            $table->string('bukti')->nullable(); // file attachment
            $table->string('reference')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('accountancy_chart_of_account_id', 'cash_bank_chart_account_foreign')->references('id')->on('accountancy_chart_of_accounts')->onDelete('restrict');
            $table->foreign('accountancy_company_id', 'cash_bank_company_foreign')->references('id')->on('accountancy_companies')->onDelete('restrict');
            $table->foreign('created_by', 'cash_bank_created_by_foreign')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountancy_cash_bank_transactions');
    }
};
