<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superadminRole = Role::where('name', 'superadmin')->first();
        $companyAdminRole = Role::where('name', 'company-admin')->first();

        // Get companies
        $companies = Company::all();

        if ($companies->count() > 0) {
            // Create company admin users for each company
            foreach ($companies as $company) {
                $user = User::create([
                    'name' => 'Admin ' . $company->name,
                    'email' => 'admin@' . strtolower(str_replace(' ', '', $company->name)) . '.com',
                    'password' => Hash::make('password123'),
                    'company_id' => $company->id,
                ]);
                
                $user->assignRole($companyAdminRole);
            }
        }

        // Create a default superadmin user if no companies exist
        if ($companies->count() === 0) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@klikmedis.com',
                'password' => Hash::make('password123'),
                'company_id' => null,
            ]);
            
            $user->assignRole($superadminRole);
        }
    }
}
