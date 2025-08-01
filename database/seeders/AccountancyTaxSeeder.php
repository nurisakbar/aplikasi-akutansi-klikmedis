<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountancyTax;

class AccountancyTaxSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['PPN', 'PPh 21', 'PPh 22', 'PPh 23', 'PPh 25', 'PPh 4(2)', 'PPH Final', 'PPN Masukan', 'PPN Keluaran'];
        $statuses = ['unpaid', 'paid'];
        for ($i = 1; $i <= 30; $i++) {
            AccountancyTax::create([
                'type' => $types[array_rand($types)],
                'document_number' => 'TAX-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'date' => now()->subDays(rand(0, 90)),
                'amount' => rand(100000, 5000000),
                'status' => $statuses[array_rand($statuses)],
                'description' => 'Transaksi pajak dummy #' . $i,
            ]);
        }
    }
}
