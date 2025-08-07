<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AccountancyCompany;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Users...');

        // Get or create roles
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $companyAdminRole = Role::firstOrCreate(['name' => 'company-admin']);

        // Get companies
        $companies = AccountancyCompany::all();

        if ($companies->count() > 0) {
            // Create company admin users for each company
            foreach ($companies as $company) {
                $email = 'admin@' . strtolower(str_replace(' ', '', $company->name)) . '.com';
                
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'id' => (string) Str::uuid(),
                        'name' => 'Admin ' . $company->name,
                        'email' => $email,
                        'password' => Hash::make('password123'),
                        'accountancy_company_id' => $company->id,
                        'email_verified_at' => now(),
                    ]
                );

                $user->assignRole($companyAdminRole);
                $this->command->info('Created user: ' . $user->name . ' for company: ' . $company->name);
            }
        }

        // Create a default superadmin user
        $superadminEmail = 'superadmin@klikmedis.com';
        $superadmin = User::firstOrCreate(
            ['email' => $superadminEmail],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Super Admin',
                'email' => $superadminEmail,
                'password' => Hash::make('password123'),
                'accountancy_company_id' => null,
                'email_verified_at' => now(),
            ]
        );

        $superadmin->assignRole($superadminRole);
        $this->command->info('Created superadmin: ' . $superadmin->name);

        $this->command->info('Users created successfully!');
    }
}
