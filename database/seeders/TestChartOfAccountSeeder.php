<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;
use App\Models\Company;
use Illuminate\Support\Str;

class TestChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create Global System company for superadmin
        $globalCompany = Company::firstOrCreate(
            ['name' => 'Global System'],
            [
                'id' => (string) Str::uuid(),
                'email' => 'system@global.com',
                'name' => 'Global System'
            ]
        );

        $this->command->info('Global System company: ' . $globalCompany->name);

        // Create test COA for Global System
        $testCoa = ChartOfAccount::firstOrCreate(
            ['code' => 'TEST001', 'company_id' => $globalCompany->id],
            [
                'id' => (string) Str::uuid(),
                'company_id' => $globalCompany->id,
                'code' => 'TEST001',
                'name' => 'Test Account',
                'type' => 'asset',
                'category' => 'current_asset',
                'description' => 'Test account for superadmin',
                'is_active' => true,
                'level' => 1,
                'path' => null
            ]
        );

        $this->command->info('Test COA created: ' . $testCoa->name . ' - ' . $testCoa->code);

        // Create sample COA for each existing company
        $companies = Company::where('name', '!=', 'Global System')->get();

        foreach ($companies as $company) {
            $sampleCoa = ChartOfAccount::firstOrCreate(
                ['code' => 'SAMPLE001', 'company_id' => $company->id],
                [
                    'id' => (string) Str::uuid(),
                    'company_id' => $company->id,
                    'code' => 'SAMPLE001',
                    'name' => 'Sample Account for ' . $company->name,
                    'type' => 'asset',
                    'category' => 'current_asset',
                    'description' => 'Sample account for testing',
                    'is_active' => true,
                    'level' => 1,
                    'path' => null
                ]
            );

            $this->command->info('Sample COA created for ' . $company->name . ': ' . $sampleCoa->name);
        }

        $this->command->info('Test Chart of Accounts seeding completed!');
    }
}
