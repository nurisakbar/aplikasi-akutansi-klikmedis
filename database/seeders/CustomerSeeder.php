<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            Customer::create([
                'name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
            ]);
        }
    }
} 