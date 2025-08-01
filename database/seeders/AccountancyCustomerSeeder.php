<?php

namespace Database\Seeders;

use App\Models\AccountancyCustomer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountancyCustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            AccountancyCustomer::create([
                'name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
            ]);
        }
    }
}
