<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if superadmin already exists
        $existingSuperAdmin = User::where('email', 'superadmin@klikmedis.com')->first();
        if ($existingSuperAdmin) {
            $this->command->info('Super Admin already exists. Skipping...');
            return;
        }

        // Get superadmin role
        $superadminRole = Role::where('name', 'superadmin')->first();
        if (!$superadminRole) {
            $this->command->error('Superadmin role not found. Please run RoleSeeder first.');
            return;
        }

        // Create superadmin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@klikmedis.com',
            'password' => Hash::make('password123'),
            'company_id' => null,
        ]);

        // Assign superadmin role
        $superAdmin->assignRole($superadminRole);

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: superadmin@klikmedis.com');
        $this->command->info('Password: password123');
    }
}
