<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop all old tables (tanpa prefix)
        Schema::dropIfExists('customers');
        Schema::dropIfExists('suppliers');
        // Schema::dropIfExists('chart_of_accounts'); // Already dropped by akuntansi_chart_of_accounts
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('cash_bank_transactions');
        Schema::dropIfExists('accounts_receivable');
        Schema::dropIfExists('accounts_payable');
        Schema::dropIfExists('accounts_receivable_payments');
        Schema::dropIfExists('accounts_payable_payments');
        Schema::dropIfExists('fixed_assets');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('taxes');

        // Drop all new tables (dengan prefix)
        Schema::dropIfExists('akuntansi_customers');
        Schema::dropIfExists('akuntansi_suppliers');
        Schema::dropIfExists('akuntansi_chart_of_accounts');
        Schema::dropIfExists('akuntansi_journal_entries');
        Schema::dropIfExists('akuntansi_journal_entry_lines');
        Schema::dropIfExists('akuntansi_cash_bank_transactions');
        Schema::dropIfExists('akuntansi_accounts_receivable');
        Schema::dropIfExists('akuntansi_accounts_payable');
        Schema::dropIfExists('akuntansi_accounts_receivable_payments');
        Schema::dropIfExists('akuntansi_accounts_payable_payments');
        Schema::dropIfExists('akuntansi_fixed_assets');
        Schema::dropIfExists('akuntansi_expenses');
        Schema::dropIfExists('akuntansi_taxes');
    }

    public function down(): void
    {
        // Tidak perlu implementasi down, karena ini hanya untuk drop table
    }
};
