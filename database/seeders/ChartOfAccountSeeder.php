<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChartOfAccountSeeder extends Seeder
{
    private $settingId;
    private $accounts = [];
    private $batchSize = 10;

    public function run(): void
    {
        $this->settingId = '11111111-1111-1111-1111-111111111111';
        
        // Cek apakah data sudah ada
        if (ChartOfAccount::where('setting_id', $this->settingId)->count() > 0) {
            $this->command->info('Data Chart of Accounts sudah ada. Skipping seeder.');
            return;
        }
        
        $this->command->info('Creating Chart of Accounts...');

        // Generate root accounts
        $this->generateRootAccounts();
        
        // Generate child accounts in batches
        $this->generateAssetAccounts();
        $this->generateLiabilityAccounts();
        $this->generateEquityAccounts();
        $this->generateRevenueAccounts();
        $this->generateExpenseAccounts();

        // Insert remaining accounts
        if (!empty($this->accounts)) {
            DB::table('akuntansi_chart_of_accounts')->insert($this->accounts);
        }

        $this->command->info('Chart of Accounts created successfully!');
        
        // Update paths
        $this->command->info('Updating account paths...');
        ChartOfAccount::where('setting_id', $this->settingId)
            ->whereNotNull('parent_id')
            ->chunk(100, function($accounts) {
                foreach($accounts as $account) {
                    $account->updatePath();
                }
            });
        
        $this->command->info('All paths updated successfully!');
    }

    private function addAccount($data)
    {
        $data['id'] = (string) Str::uuid();
        $data['setting_id'] = $this->settingId;
        $data['created_at'] = now();
        $data['updated_at'] = now();
        
        $this->accounts[] = $data;
        
        // Batch insert when reaching batch size
        if (count($this->accounts) >= $this->batchSize) {
            DB::table('akuntansi_chart_of_accounts')->insert($this->accounts);
            $this->accounts = [];
        }
        
        return $data['id'];
    }

    private function generateRootAccounts()
    {
        // Asset Root
        $assetId = $this->addAccount([
            'code' => '1000',
            'name' => 'ASSET',
            'type' => 'asset',
            'category' => 'current_asset',
            'parent_id' => null,
            'description' => 'Aset perusahaan',
            'is_active' => true,
            'level' => 1,
            'path' => null
        ]);

        // Liability Root
        $liabilityId = $this->addAccount([
            'code' => '3000',
            'name' => 'LIABILITY',
            'type' => 'liability',
            'category' => 'current_liability',
            'parent_id' => null,
            'description' => 'Kewajiban perusahaan',
            'is_active' => true,
            'level' => 1,
            'path' => null
        ]);

        // Equity Root
        $equityId = $this->addAccount([
            'code' => '4000',
            'name' => 'EQUITY',
            'type' => 'equity',
            'category' => 'equity',
            'parent_id' => null,
            'description' => 'Ekuitas pemilik',
            'is_active' => true,
            'level' => 1,
            'path' => null
        ]);

        // Revenue Root
        $revenueId = $this->addAccount([
            'code' => '5000',
            'name' => 'REVENUE',
            'type' => 'revenue',
            'category' => 'operating_revenue',
            'parent_id' => null,
            'description' => 'Pendapatan',
            'is_active' => true,
            'level' => 1,
            'path' => null
        ]);

        // Expense Root
        $expenseId = $this->addAccount([
            'code' => '6000',
            'name' => 'EXPENSE',
            'type' => 'expense',
            'category' => 'operating_expense',
            'parent_id' => null,
            'description' => 'Beban',
            'is_active' => true,
            'level' => 1,
            'path' => null
        ]);

        return compact('assetId', 'liabilityId', 'equityId', 'revenueId', 'expenseId');
    }

    private function generateAssetAccounts()
    {
        $assetId = ChartOfAccount::where('setting_id', $this->settingId)
            ->where('code', '1000')
            ->value('id');

        // Current Assets
        $currentAssetId = $this->addAccount([
            'code' => '1100',
            'name' => 'CURRENT ASSETS',
            'type' => 'asset',
            'category' => 'current_asset',
            'parent_id' => $assetId,
            'description' => 'Aset lancar',
            'is_active' => true,
            'level' => 2,
            'path' => $assetId
        ]);

        // Cash & Bank
        $cashBankId = $this->addAccount([
            'code' => '1110',
            'name' => 'CASH & BANK',
            'type' => 'asset',
            'category' => 'current_asset',
            'parent_id' => $currentAssetId,
            'description' => 'Kas dan bank',
            'is_active' => true,
            'level' => 3,
            'path' => "$assetId/$currentAssetId"
        ]);

        // Cash & Bank Children
        $cashAccounts = [
            ['code' => '1111', 'name' => 'Cash on Hand', 'description' => 'Kas di tangan'],
            ['code' => '1112', 'name' => 'Bank BCA', 'description' => 'Rekening Bank BCA'],
            ['code' => '1113', 'name' => 'Bank Mandiri', 'description' => 'Rekening Bank Mandiri']
        ];

        foreach ($cashAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'asset',
                'category' => 'current_asset',
                'parent_id' => $cashBankId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 4,
                'path' => "$assetId/$currentAssetId/$cashBankId"
            ]);
        }

        // Accounts Receivable
        $arId = $this->addAccount([
            'code' => '1120',
            'name' => 'ACCOUNTS RECEIVABLE',
            'type' => 'asset',
            'category' => 'current_asset',
            'parent_id' => $currentAssetId,
            'description' => 'Piutang usaha',
            'is_active' => true,
            'level' => 3,
            'path' => "$assetId/$currentAssetId"
        ]);

        // Accounts Receivable Children
        $arAccounts = [
            ['code' => '1121', 'name' => 'Trade Receivable', 'description' => 'Piutang dagang'],
            ['code' => '1122', 'name' => 'Other Receivable', 'description' => 'Piutang lainnya']
        ];

        foreach ($arAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'asset',
                'category' => 'current_asset',
                'parent_id' => $arId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 4,
                'path' => "$assetId/$currentAssetId/$arId"
            ]);
        }

        // Inventory
        $inventoryId = $this->addAccount([
            'code' => '1130',
            'name' => 'INVENTORY',
            'type' => 'asset',
            'category' => 'current_asset',
            'parent_id' => $currentAssetId,
            'description' => 'Persediaan',
            'is_active' => true,
            'level' => 3,
            'path' => "$assetId/$currentAssetId"
        ]);

        // Inventory Children
        $inventoryAccounts = [
            ['code' => '1131', 'name' => 'Raw Materials', 'description' => 'Bahan baku'],
            ['code' => '1132', 'name' => 'Work in Process', 'description' => 'Barang dalam proses'],
            ['code' => '1133', 'name' => 'Finished Goods', 'description' => 'Barang jadi']
        ];

        foreach ($inventoryAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'asset',
                'category' => 'current_asset',
                'parent_id' => $inventoryId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 4,
                'path' => "$assetId/$currentAssetId/$inventoryId"
            ]);
        }

        // Fixed Assets
        $fixedAssetId = $this->addAccount([
            'code' => '2000',
            'name' => 'FIXED ASSETS',
            'type' => 'asset',
            'category' => 'fixed_asset',
            'parent_id' => $assetId,
            'description' => 'Aset tetap',
            'is_active' => true,
            'level' => 2,
            'path' => $assetId
        ]);

        // Fixed Assets Children
        $fixedAssetAccounts = [
            ['code' => '2100', 'name' => 'Equipment', 'description' => 'Peralatan'],
            ['code' => '2200', 'name' => 'Buildings', 'description' => 'Bangunan'],
            ['code' => '2300', 'name' => 'Vehicles', 'description' => 'Kendaraan']
        ];

        foreach ($fixedAssetAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'asset',
                'category' => 'fixed_asset',
                'parent_id' => $fixedAssetId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 3,
                'path' => "$assetId/$fixedAssetId"
            ]);
        }
    }

    private function generateLiabilityAccounts()
    {
        $liabilityId = ChartOfAccount::where('setting_id', $this->settingId)
            ->where('code', '3000')
            ->value('id');

        // Current Liabilities
        $currentLiabilityId = $this->addAccount([
            'code' => '3100',
            'name' => 'CURRENT LIABILITIES',
            'type' => 'liability',
            'category' => 'current_liability',
            'parent_id' => $liabilityId,
            'description' => 'Kewajiban lancar',
            'is_active' => true,
            'level' => 2,
            'path' => $liabilityId
        ]);

        // Current Liability Children
        $currentLiabilityAccounts = [
            ['code' => '3110', 'name' => 'Accounts Payable', 'description' => 'Hutang usaha'],
            ['code' => '3120', 'name' => 'Tax Payable', 'description' => 'Hutang pajak']
        ];

        foreach ($currentLiabilityAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'liability',
                'category' => 'current_liability',
                'parent_id' => $currentLiabilityId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 3,
                'path' => "$liabilityId/$currentLiabilityId"
            ]);
        }

        // Long Term Liabilities
        $longTermLiabilityId = $this->addAccount([
            'code' => '3200',
            'name' => 'LONG TERM LIABILITIES',
            'type' => 'liability',
            'category' => 'long_term_liability',
            'parent_id' => $liabilityId,
            'description' => 'Kewajiban jangka panjang',
            'is_active' => true,
            'level' => 2,
            'path' => $liabilityId
        ]);

        // Long Term Liability Children
        $longTermLiabilityAccounts = [
            ['code' => '3210', 'name' => 'Bank Loans', 'description' => 'Pinjaman bank']
        ];

        foreach ($longTermLiabilityAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'liability',
                'category' => 'long_term_liability',
                'parent_id' => $longTermLiabilityId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 3,
                'path' => "$liabilityId/$longTermLiabilityId"
            ]);
        }
    }

    private function generateEquityAccounts()
    {
        $equityId = ChartOfAccount::where('setting_id', $this->settingId)
            ->where('code', '4000')
            ->value('id');

        $equityAccounts = [
            ['code' => '4100', 'name' => 'Owner Capital', 'description' => 'Modal pemilik'],
            ['code' => '4200', 'name' => 'Retained Earnings', 'description' => 'Laba ditahan']
        ];

        foreach ($equityAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'equity',
                'category' => 'equity',
                'parent_id' => $equityId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 2,
                'path' => $equityId
            ]);
        }
    }

    private function generateRevenueAccounts()
    {
        $revenueId = ChartOfAccount::where('setting_id', $this->settingId)
            ->where('code', '5000')
            ->value('id');

        $revenueAccounts = [
            ['code' => '5100', 'name' => 'Sales Revenue', 'description' => 'Pendapatan penjualan', 'category' => 'operating_revenue'],
            ['code' => '5200', 'name' => 'Service Revenue', 'description' => 'Pendapatan jasa', 'category' => 'operating_revenue'],
            ['code' => '5300', 'name' => 'Other Revenue', 'description' => 'Pendapatan lainnya', 'category' => 'other_revenue']
        ];

        foreach ($revenueAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'revenue',
                'category' => $account['category'],
                'parent_id' => $revenueId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 2,
                'path' => $revenueId
            ]);
        }
    }

    private function generateExpenseAccounts()
    {
        $expenseId = ChartOfAccount::where('setting_id', $this->settingId)
            ->where('code', '6000')
            ->value('id');

        // Operating Expenses
        $operatingExpenseId = $this->addAccount([
            'code' => '6100',
            'name' => 'OPERATING EXPENSES',
            'type' => 'expense',
            'category' => 'operating_expense',
            'parent_id' => $expenseId,
            'description' => 'Beban operasional',
            'is_active' => true,
            'level' => 2,
            'path' => $expenseId
        ]);

        $operatingExpenseAccounts = [
            ['code' => '6110', 'name' => 'Salary Expense', 'description' => 'Beban gaji'],
            ['code' => '6120', 'name' => 'Rent Expense', 'description' => 'Beban sewa'],
            ['code' => '6130', 'name' => 'Utilities Expense', 'description' => 'Beban utilitas'],
            ['code' => '6140', 'name' => 'Office Supplies', 'description' => 'Perlengkapan kantor']
        ];

        foreach ($operatingExpenseAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'expense',
                'category' => 'operating_expense',
                'parent_id' => $operatingExpenseId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 3,
                'path' => "$expenseId/$operatingExpenseId"
            ]);
        }

        // Other Expenses
        $otherExpenseId = $this->addAccount([
            'code' => '6200',
            'name' => 'OTHER EXPENSES',
            'type' => 'expense',
            'category' => 'other_expense',
            'parent_id' => $expenseId,
            'description' => 'Beban lainnya',
            'is_active' => true,
            'level' => 2,
            'path' => $expenseId
        ]);

        $otherExpenseAccounts = [
            ['code' => '6210', 'name' => 'Interest Expense', 'description' => 'Beban bunga'],
            ['code' => '6220', 'name' => 'Depreciation Expense', 'description' => 'Beban penyusutan']
        ];

        foreach ($otherExpenseAccounts as $account) {
            $this->addAccount([
                'code' => $account['code'],
                'name' => $account['name'],
                'type' => 'expense',
                'category' => 'other_expense',
                'parent_id' => $otherExpenseId,
                'description' => $account['description'],
                'is_active' => true,
                'level' => 3,
                'path' => "$expenseId/$otherExpenseId"
            ]);
        }
    }
}
