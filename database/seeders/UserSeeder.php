<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get companies
        $companies = Company::all();

        if ($companies->count() > 0) {
            // Create admin users for each company
            foreach ($companies as $company) {
                User::create([
                    'name' => 'Admin ' . $company->name,
                    'email' => 'admin@' . strtolower(str_replace(' ', '', $company->name)) . '.com',
                    'password' => Hash::make('password123'),
                    'company_id' => $company->id,
                    'role' => 'admin',
                ]);
            }
        }

        // Create a default admin user if no companies exist
        if ($companies->count() === 0) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@klikmedis.com',
                'password' => Hash::make('password123'),
                'company_id' => null,
                'role' => 'admin',
            ]);
        }
    }
}
