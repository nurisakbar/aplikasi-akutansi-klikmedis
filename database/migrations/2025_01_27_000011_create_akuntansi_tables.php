<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Customers
        Schema::create('akuntansi_customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Suppliers
        Schema::create('akuntansi_suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Chart of Accounts
        Schema::create('akuntansi_chart_of_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->index();
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
            $table->foreign('parent_id')->references('id')->on('akuntansi_chart_of_accounts')->onDelete('restrict');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'code']);
        });

        // Journal Entries
        Schema::create('akuntansi_journal_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('journal_number')->unique()->nullable();
            $table->date('date');
            $table->string('description')->nullable();
            $table->string('reference')->nullable();
            $table->string('attachment')->nullable();
            $table->string('status')->nullable();
            $table->json('history')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        // Journal Entry Lines
        Schema::create('akuntansi_journal_entry_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('journal_entry_id');
            $table->uuid('chart_of_account_id');
            $table->decimal('debit', 20, 2)->default(0);
            $table->decimal('credit', 20, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('journal_entry_id')->references('id')->on('akuntansi_journal_entries')->onDelete('cascade');
            $table->foreign('chart_of_account_id')->references('id')->on('akuntansi_chart_of_accounts')->onDelete('restrict');
        });

        // Cash Bank Transactions
        Schema::create('akuntansi_cash_bank_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('type');
            $table->string('description')->nullable();
            $table->decimal('amount', 20, 2);
            $table->uuid('chart_of_account_id');
            $table->timestamps();
            $table->softDeletes();
        });

        // Accounts Receivable
        Schema::create('akuntansi_accounts_receivable', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->date('date');
            $table->date('due_date');
            $table->decimal('amount', 20, 2);
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('akuntansi_customers')->onDelete('restrict');
        });

        // Accounts Payable
        Schema::create('akuntansi_accounts_payable', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('supplier_id');
            $table->date('date');
            $table->date('due_date');
            $table->decimal('amount', 20, 2);
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('supplier_id')->references('id')->on('akuntansi_suppliers')->onDelete('restrict');
        });

        // Accounts Receivable Payments
        Schema::create('akuntansi_accounts_receivable_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('accounts_receivable_id');
            $table->date('date');
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('accounts_receivable_id', 'ar_payments_ar_id_fk')->references('id')->on('akuntansi_accounts_receivable')->onDelete('cascade');
        });

        // Accounts Payable Payments
        Schema::create('akuntansi_accounts_payable_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('accounts_payable_id');
            $table->date('date');
            $table->decimal('amount', 20, 2);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('accounts_payable_id', 'ap_payments_ap_id_fk')->references('id')->on('akuntansi_accounts_payable')->onDelete('cascade');
        });

        // Fixed Assets
        Schema::create('akuntansi_fixed_assets', function (Blueprint $table) {
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

        // Expenses
        Schema::create('akuntansi_expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('document_number')->nullable();
            $table->date('date');
            $table->decimal('amount', 20, 2);
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Taxes
        Schema::create('akuntansi_taxes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('document_number')->nullable();
            $table->date('date');
            $table->decimal('amount', 20, 2);
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
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
};
