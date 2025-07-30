<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            Supplier::create([
                'name' => 'Supplier ' . $i,
                'email' => 'supplier' . $i . '@example.com',
            ]);
        }
    }
} 