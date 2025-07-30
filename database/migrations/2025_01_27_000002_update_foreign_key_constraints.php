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
        // Drop existing foreign key constraints
        Schema::table('akuntansi_journal_entry_lines', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropForeign(['chart_of_account_id']);
        });

        Schema::table('akuntansi_accounts_receivable', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::table('akuntansi_accounts_payable', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
        });

        Schema::table('akuntansi_accounts_receivable_payments', function (Blueprint $table) {
            $table->dropForeign(['accounts_receivable_id']);
        });

        Schema::table('akuntansi_accounts_payable_payments', function (Blueprint $table) {
            $table->dropForeign(['accounts_payable_id']);
        });

        Schema::table('akuntansi_journal_entries', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });

        // Add new foreign key constraints with updated table names
        Schema::table('akuntansi_journal_entry_lines', function (Blueprint $table) {
            $table->foreign('journal_entry_id')->references('id')->on('akuntansi_journal_entries')->onDelete('cascade');
            $table->foreign('chart_of_account_id')->references('id')->on('akuntansi_chart_of_accounts')->onDelete('restrict');
        });

        Schema::table('akuntansi_accounts_receivable', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('akuntansi_customers')->onDelete('restrict');
        });

        Schema::table('akuntansi_accounts_payable', function (Blueprint $table) {
            $table->foreign('supplier_id')->references('id')->on('akuntansi_suppliers')->onDelete('restrict');
        });

        Schema::table('akuntansi_accounts_receivable_payments', function (Blueprint $table) {
            $table->foreign('accounts_receivable_id')->references('id')->on('akuntansi_accounts_receivable')->onDelete('cascade');
        });

        Schema::table('akuntansi_accounts_payable_payments', function (Blueprint $table) {
            $table->foreign('accounts_payable_id')->references('id')->on('akuntansi_accounts_payable')->onDelete('cascade');
        });

        Schema::table('akuntansi_journal_entries', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new foreign key constraints
        Schema::table('akuntansi_journal_entry_lines', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropForeign(['chart_of_account_id']);
        });

        Schema::table('akuntansi_accounts_receivable', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::table('akuntansi_accounts_payable', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
        });

        Schema::table('akuntansi_accounts_receivable_payments', function (Blueprint $table) {
            $table->dropForeign(['accounts_receivable_id']);
        });

        Schema::table('akuntansi_accounts_payable_payments', function (Blueprint $table) {
            $table->dropForeign(['accounts_payable_id']);
        });

        Schema::table('akuntansi_journal_entries', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });

        // Add back original foreign key constraints
        Schema::table('journal_entry_lines', function (Blueprint $table) {
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('cascade');
            $table->foreign('chart_of_account_id')->references('id')->on('chart_of_accounts')->onDelete('restrict');
        });

        Schema::table('accounts_receivable', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
        });

        Schema::table('accounts_payable', function (Blueprint $table) {
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('restrict');
        });

        Schema::table('accounts_receivable_payments', function (Blueprint $table) {
            $table->foreign('accounts_receivable_id')->references('id')->on('accounts_receivable')->onDelete('cascade');
        });

        Schema::table('accounts_payable_payments', function (Blueprint $table) {
            $table->foreign('accounts_payable_id')->references('id')->on('accounts_payable')->onDelete('cascade');
        });

        Schema::table('journal_entries', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }
}; 