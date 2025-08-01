<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountancySupplier;

class AccountancySupplierSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            AccountancySupplier::create([
                'name' => 'Supplier ' . $i,
                'email' => 'supplier' . $i . '@example.com',
            ]);
        }
    }
}
