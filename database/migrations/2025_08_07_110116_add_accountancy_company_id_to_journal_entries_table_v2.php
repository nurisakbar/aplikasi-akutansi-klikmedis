<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accountancy_journal_entries', function (Blueprint $table) {
            $table->uuid('accountancy_company_id')->after('id')->nullable()->index();
        });

        // Update existing records with company ID from chart of accounts
        $journalEntries = DB::table('accountancy_journal_entries')->get();
        foreach ($journalEntries as $entry) {
            $companyId = DB::table('accountancy_journal_entry_lines')
                ->join('accountancy_chart_of_accounts', 'accountancy_journal_entry_lines.chart_of_account_id', '=', 'accountancy_chart_of_accounts.id')
                ->where('accountancy_journal_entry_lines.journal_entry_id', $entry->id)
                ->value('accountancy_chart_of_accounts.accountancy_company_id');
            
            if ($companyId) {
                DB::table('accountancy_journal_entries')
                    ->where('id', $entry->id)
                    ->update(['accountancy_company_id' => $companyId]);
            }
        }

        // Make the column not nullable and add foreign key
        Schema::table('accountancy_journal_entries', function (Blueprint $table) {
            $table->uuid('accountancy_company_id')->nullable(false)->change();
            $table->foreign('accountancy_company_id')->references('id')->on('accountancy_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accountancy_journal_entries', function (Blueprint $table) {
            $table->dropForeign(['accountancy_company_id']);
            $table->dropColumn('accountancy_company_id');
        });
    }
};
