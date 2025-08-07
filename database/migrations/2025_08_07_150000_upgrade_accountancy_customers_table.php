<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CustomerStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accountancy_customers', function (Blueprint $table) {
            // Add new fields
            $table->uuid('accountancy_company_id')->after('id')->nullable()->index();
            $table->string('code')->after('accountancy_company_id')->nullable()->unique();
            $table->string('company_name')->after('name')->nullable();
            $table->string('npwp')->after('phone')->nullable();
            $table->decimal('credit_limit', 20, 2)->after('npwp')->default(0);
            $table->enum('status', CustomerStatus::values())->after('credit_limit')->default(CustomerStatus::ACTIVE->value);
            $table->string('contact_person')->after('status')->nullable();
            $table->string('payment_terms')->after('contact_person')->nullable();
        });

        // Update existing records with company ID (assign to first company)
        $firstCompany = \App\Models\AccountancyCompany::first();
        if ($firstCompany) {
            \App\Models\AccountancyCustomer::whereNull('accountancy_company_id')
                ->update(['accountancy_company_id' => $firstCompany->id]);
        }

        // Generate codes for existing customers
        $customers = \App\Models\AccountancyCustomer::whereNull('code')->get();
        foreach ($customers as $index => $customer) {
            $customer->update([
                'code' => 'CUST-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT)
            ]);
        }

        // Make company_id not nullable and add foreign key
        Schema::table('accountancy_customers', function (Blueprint $table) {
            $table->uuid('accountancy_company_id')->nullable(false)->change();
            $table->foreign('accountancy_company_id')->references('id')->on('accountancy_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accountancy_customers', function (Blueprint $table) {
            $table->dropForeign(['accountancy_company_id']);
            $table->dropColumn([
                'accountancy_company_id',
                'code',
                'company_name',
                'npwp',
                'credit_limit',
                'status',
                'contact_person',
                'payment_terms'
            ]);
        });
    }
}; 