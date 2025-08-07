<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AccountancyCompanySeeder::class,
            UserSeeder::class,
            AccountancyChartOfAccountSeeder::class,
            AccountancyExpenseSeeder::class,
            AccountancyTaxSeeder::class,
            AccountancyFixedAssetSeeder::class,
            AccountancyCustomerSeeder::class,
            AccountancySupplierSeeder::class,
            AccountancyJournalEntrySeeder::class,
            AccountancyCashBankTransactionSeeder::class,
        ]);
    }
}
