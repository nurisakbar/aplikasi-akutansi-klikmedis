<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, drop foreign key constraints
        Schema::table('accountancy_cash_bank_transactions', function (Blueprint $table) {
            $table->dropForeign('cash_bank_created_by_foreign');
        });

        // Change ID column from integer to UUID
        Schema::table('accountancy_cash_bank_transactions', function (Blueprint $table) {
            // Drop primary key first
            $table->dropPrimary();
            
            // Change ID column to UUID
            $table->uuid('id')->change();
            
            // Re-add primary key
            $table->primary('id');
        });

        // Update existing records to have UUID IDs
        $transactions = DB::table('accountancy_cash_bank_transactions')->get();
        foreach ($transactions as $transaction) {
            $newId = Str::uuid()->toString();
            DB::table('accountancy_cash_bank_transactions')
                ->where('id', $transaction->id)
                ->update(['id' => $newId]);
        }

        // Re-add foreign key constraint
        Schema::table('accountancy_cash_bank_transactions', function (Blueprint $table) {
            $table->foreign('created_by', 'cash_bank_created_by_foreign')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be safely reversed
        // as it would require converting UUIDs back to integers
        // which could cause data loss
    }
};
