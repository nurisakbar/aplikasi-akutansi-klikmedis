<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superadminRole = Role::create(['name' => 'superadmin']);
        $companyAdminRole = Role::create(['name' => 'company-admin']);

        // Create permissions for Chart of Accounts
        $permissions = [
            'chart-of-accounts.view',
            'chart-of-accounts.create',
            'chart-of-accounts.edit',
            'chart-of-accounts.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to superadmin
        $superadminRole->givePermissionTo($permissions);

        // Assign company-specific permissions to company-admin
        $companyAdminRole->givePermissionTo([
            'chart-of-accounts.view',
            'chart-of-accounts.create',
            'chart-of-accounts.edit',
            'chart-of-accounts.delete',
        ]);
    }
}
