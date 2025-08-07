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
        // Get all companies
        $companies = AccountancyCompany::all();

        if ($companies->count() === 0) {
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
            $companies = collect([$company]);
        }

        $this->command->info('Creating Chart of Accounts for ' . $companies->count() . ' companies');

        // Create comprehensive Chart of Accounts for each company
        $accounts = [
            // ASSETS
            ['code' => '1000', 'name' => 'ASSETS', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Aset perusahaan', 'parent_id' => null],
            
            // Current Assets
            ['code' => '1100', 'name' => 'CURRENT ASSETS', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Aset lancar', 'parent_code' => '1000'],
            ['code' => '1110', 'name' => 'CASH & BANK', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Kas dan bank', 'parent_code' => '1100'],
            ['code' => '1111', 'name' => 'Cash on Hand', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Kas di tangan', 'parent_code' => '1110'],
            ['code' => '1112', 'name' => 'Bank BCA', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Rekening Bank BCA', 'parent_code' => '1110'],
            ['code' => '1113', 'name' => 'Bank Mandiri', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Rekening Bank Mandiri', 'parent_code' => '1110'],
            
            ['code' => '1120', 'name' => 'ACCOUNTS RECEIVABLE', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Piutang usaha', 'parent_code' => '1100'],
            ['code' => '1121', 'name' => 'Trade Receivable', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Piutang dagang', 'parent_code' => '1120'],
            ['code' => '1122', 'name' => 'Other Receivable', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Piutang lainnya', 'parent_code' => '1120'],
            
            ['code' => '1130', 'name' => 'INVENTORY', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Persediaan', 'parent_code' => '1100'],
            ['code' => '1131', 'name' => 'Raw Materials', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Bahan baku', 'parent_code' => '1130'],
            ['code' => '1132', 'name' => 'Work in Process', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Barang dalam proses', 'parent_code' => '1130'],
            ['code' => '1133', 'name' => 'Finished Goods', 'type' => AccountType::ASSET, 'category' => AccountCategory::CURRENT_ASSET, 'description' => 'Barang jadi', 'parent_code' => '1130'],
            
            // Fixed Assets
            ['code' => '2000', 'name' => 'FIXED ASSETS', 'type' => AccountType::ASSET, 'category' => AccountCategory::FIXED_ASSET, 'description' => 'Aset tetap', 'parent_code' => '1000'],
            ['code' => '2100', 'name' => 'Equipment', 'type' => AccountType::ASSET, 'category' => AccountCategory::FIXED_ASSET, 'description' => 'Peralatan', 'parent_code' => '2000'],
            ['code' => '2200', 'name' => 'Buildings', 'type' => AccountType::ASSET, 'category' => AccountCategory::FIXED_ASSET, 'description' => 'Bangunan', 'parent_code' => '2000'],
            ['code' => '2300', 'name' => 'Vehicles', 'type' => AccountType::ASSET, 'category' => AccountCategory::FIXED_ASSET, 'description' => 'Kendaraan', 'parent_code' => '2000'],
            
            // LIABILITIES
            ['code' => '3000', 'name' => 'LIABILITIES', 'type' => AccountType::LIABILITY, 'category' => AccountCategory::CURRENT_LIABILITY, 'description' => 'Kewajiban perusahaan', 'parent_id' => null],
            
            // Current Liabilities
            ['code' => '3100', 'name' => 'CURRENT LIABILITIES', 'type' => AccountType::LIABILITY, 'category' => AccountCategory::CURRENT_LIABILITY, 'description' => 'Kewajiban lancar', 'parent_code' => '3000'],
            ['code' => '3110', 'name' => 'Accounts Payable', 'type' => AccountType::LIABILITY, 'category' => AccountCategory::CURRENT_LIABILITY, 'description' => 'Hutang usaha', 'parent_code' => '3100'],
            ['code' => '3120', 'name' => 'Tax Payable', 'type' => AccountType::LIABILITY, 'category' => AccountCategory::CURRENT_LIABILITY, 'description' => 'Hutang pajak', 'parent_code' => '3100'],
            
            // Long Term Liabilities
            ['code' => '3200', 'name' => 'LONG TERM LIABILITIES', 'type' => AccountType::LIABILITY, 'category' => AccountCategory::LONG_TERM_LIABILITY, 'description' => 'Kewajiban jangka panjang', 'parent_code' => '3000'],
            ['code' => '3210', 'name' => 'Bank Loans', 'type' => AccountType::LIABILITY, 'category' => AccountCategory::LONG_TERM_LIABILITY, 'description' => 'Pinjaman bank', 'parent_code' => '3200'],
            
            // EQUITY
            ['code' => '4000', 'name' => 'EQUITY', 'type' => AccountType::EQUITY, 'category' => AccountCategory::EQUITY, 'description' => 'Ekuitas pemilik', 'parent_id' => null],
            ['code' => '4100', 'name' => 'Owner Capital', 'type' => AccountType::EQUITY, 'category' => AccountCategory::EQUITY, 'description' => 'Modal pemilik', 'parent_code' => '4000'],
            ['code' => '4200', 'name' => 'Retained Earnings', 'type' => AccountType::EQUITY, 'category' => AccountCategory::EQUITY, 'description' => 'Laba ditahan', 'parent_code' => '4000'],
            
            // REVENUE
            ['code' => '5000', 'name' => 'REVENUE', 'type' => AccountType::REVENUE, 'category' => AccountCategory::OPERATING_REVENUE, 'description' => 'Pendapatan', 'parent_id' => null],
            ['code' => '5100', 'name' => 'Sales Revenue', 'type' => AccountType::REVENUE, 'category' => AccountCategory::OPERATING_REVENUE, 'description' => 'Pendapatan penjualan', 'parent_code' => '5000'],
            ['code' => '5200', 'name' => 'Service Revenue', 'type' => AccountType::REVENUE, 'category' => AccountCategory::OPERATING_REVENUE, 'description' => 'Pendapatan jasa', 'parent_code' => '5000'],
            ['code' => '5300', 'name' => 'Other Revenue', 'type' => AccountType::REVENUE, 'category' => AccountCategory::OTHER_REVENUE, 'description' => 'Pendapatan lainnya', 'parent_code' => '5000'],
            
            // EXPENSE
            ['code' => '6000', 'name' => 'EXPENSE', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OPERATING_EXPENSE, 'description' => 'Beban', 'parent_id' => null],
            
            // Operating Expenses
            ['code' => '6100', 'name' => 'OPERATING EXPENSES', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OPERATING_EXPENSE, 'description' => 'Beban operasional', 'parent_code' => '6000'],
            ['code' => '6110', 'name' => 'Salary Expense', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OPERATING_EXPENSE, 'description' => 'Beban gaji', 'parent_code' => '6100'],
            ['code' => '6120', 'name' => 'Rent Expense', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OPERATING_EXPENSE, 'description' => 'Beban sewa', 'parent_code' => '6100'],
            ['code' => '6130', 'name' => 'Utilities Expense', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OPERATING_EXPENSE, 'description' => 'Beban utilitas', 'parent_code' => '6100'],
            ['code' => '6140', 'name' => 'Office Supplies', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OPERATING_EXPENSE, 'description' => 'Perlengkapan kantor', 'parent_code' => '6100'],
            
            // Other Expenses
            ['code' => '6200', 'name' => 'OTHER EXPENSES', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OTHER_EXPENSE, 'description' => 'Beban lainnya', 'parent_code' => '6000'],
            ['code' => '6210', 'name' => 'Interest Expense', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OTHER_EXPENSE, 'description' => 'Beban bunga', 'parent_code' => '6200'],
            ['code' => '6220', 'name' => 'Depreciation Expense', 'type' => AccountType::EXPENSE, 'category' => AccountCategory::OTHER_EXPENSE, 'description' => 'Beban penyusutan', 'parent_code' => '6200'],
        ];

        foreach ($companies as $company) {
            $this->command->info('Creating Chart of Accounts for: ' . $company->name);

            // Create accounts with proper hierarchy
            $createdAccounts = [];
            
            foreach ($accounts as $accountData) {
                $parentId = null;
                
                // Find parent if specified
                if (isset($accountData['parent_code'])) {
                    $parentAccount = collect($createdAccounts)->firstWhere('code', $accountData['parent_code']);
                    if ($parentAccount) {
                        $parentId = $parentAccount['id'];
                    }
                }
                
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
                        'parent_id' => $parentId,
                        'is_active' => true,
                        'level' => 1,
                        'path' => null
                    ]
                );

                // Update the path for this account
                $account->updatePath();
                
                // Store for parent reference
                $createdAccounts[] = [
                    'id' => $account->id,
                    'code' => $account->code
                ];

                $this->command->info('Created COA: ' . $account->code . ' - ' . $account->name);
            }
        }

        $this->command->info('Chart of Accounts seeding completed! Created ' . (count($accounts) * $companies->count()) . ' accounts across ' . $companies->count() . ' companies.');
    }
}
