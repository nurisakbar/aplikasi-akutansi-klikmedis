<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountancyChartOfAccount;
use App\Models\AccountancyCompany;
use App\Enums\AccountType;
use App\Enums\AccountCategory;
use Illuminate\Support\Str;

class AccountancyChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first company (or create a default one if none exists)
        $company = AccountancyCompany::first();

        if (!$company) {
            $this->command->warn('No companies found. Creating a default company first.');
            $company = AccountancyCompany::create([
                'id' => (string) Str::uuid(),
                'name' => 'Default Company',
                'email' => 'default@company.com',
                'address' => 'Default Address',
                'province' => 'Default Province',
                'city' => 'Default City',
                'district' => 'Default District',
                'postal_code' => '12345',
                'phone' => '0123456789',
            ]);
        }

        $this->command->info('Creating Chart of Accounts for: ' . $company->name);

        // Create basic Chart of Accounts
        $accounts = [
            [
                'code' => '1000',
                'name' => 'Cash in Hand',
                'type' => AccountType::ASSET,
                'category' => AccountCategory::CURRENT_ASSET,
                'description' => 'Cash available in hand'
            ],
            [
                'code' => '1100',
                'name' => 'Cash at Bank',
                'type' => AccountType::ASSET,
                'category' => AccountCategory::CURRENT_ASSET,
                'description' => 'Cash deposited in bank accounts'
            ],
            [
                'code' => '2000',
                'name' => 'Accounts Payable',
                'type' => AccountType::LIABILITY,
                'category' => AccountCategory::CURRENT_LIABILITY,
                'description' => 'Money owed to suppliers'
            ],
            [
                'code' => '3000',
                'name' => 'Owner Equity',
                'type' => AccountType::EQUITY,
                'category' => AccountCategory::EQUITY,
                'description' => 'Owner equity account'
            ],
            [
                'code' => '4000',
                'name' => 'Sales Revenue',
                'type' => AccountType::REVENUE,
                'category' => AccountCategory::OPERATING_REVENUE,
                'description' => 'Revenue from sales'
            ],
            [
                'code' => '5000',
                'name' => 'Office Expenses',
                'type' => AccountType::EXPENSE,
                'category' => AccountCategory::OPERATING_EXPENSE,
                'description' => 'General office expenses'
            ],
            [
                'code' => '1200',
                'name' => 'Accounts Receivable',
                'type' => AccountType::ASSET,
                'category' => AccountCategory::CURRENT_ASSET,
                'description' => 'Money owed by customers'
            ],
            [
                'code' => '1300',
                'name' => 'Inventory',
                'type' => AccountType::ASSET,
                'category' => AccountCategory::CURRENT_ASSET,
                'description' => 'Goods available for sale'
            ]
        ];

        foreach ($accounts as $accountData) {
            $account = AccountancyChartOfAccount::firstOrCreate(
                [
                    'code' => $accountData['code'],
                    'accountancy_company_id' => $company->id
                ],
                [
                    'id' => (string) Str::uuid(),
                    'accountancy_company_id' => $company->id,
                    'code' => $accountData['code'],
                    'name' => $accountData['name'],
                    'type' => $accountData['type'],
                    'category' => $accountData['category'],
                    'description' => $accountData['description'],
                    'is_active' => true,
                    'level' => 1,
                    'path' => null,
                    'parent_id' => null
                ]
            );

            // Update the path for this account
            $account->updatePath();

            $this->command->info('Created COA: ' . $account->code . ' - ' . $account->name);
        }

        $this->command->info('Chart of Accounts seeding completed! Created ' . count($accounts) . ' accounts.');
    }
}
