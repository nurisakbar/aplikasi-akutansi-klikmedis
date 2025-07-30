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
            UserSeeder::class,
            ChartOfAccountSeeder::class,
            JournalEntrySeeder::class,
            ExpenseSeeder::class,
            TaxSeeder::class,
            FixedAssetSeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
