<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AccountancyChartOfAccount;
use App\Models\AccountancyCompany;

class FixChartOfAccountsHierarchy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chart-of-accounts:fix-hierarchy {--company-id= : ID perusahaan tertentu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki hierarki Chart of Accounts yang sudah ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai perbaikan hierarki Chart of Accounts...');

        $companyId = $this->option('company-id');

        if ($companyId) {
            // Perbaiki untuk company tertentu
            $this->fixHierarchyForCompany($companyId);
        } else {
            // Perbaiki untuk semua company
            $companies = AccountancyCompany::all();
            
            foreach ($companies as $company) {
                $this->info("Memperbaiki hierarki untuk perusahaan: {$company->name}");
                $this->fixHierarchyForCompany($company->id);
            }
        }

        $this->info('Perbaikan hierarki selesai!');
    }

    /**
     * Perbaiki hierarki untuk company tertentu
     */
    private function fixHierarchyForCompany(string $companyId): void
    {
        // Ambil semua akun root untuk company ini
        $rootAccounts = AccountancyChartOfAccount::whereNull('parent_id')
            ->where('accountancy_company_id', $companyId)
            ->get();

        $this->info("Ditemukan {$rootAccounts->count()} akun root");

        foreach ($rootAccounts as $rootAccount) {
            $this->info("Memperbaiki hierarki untuk akun: {$rootAccount->name}");
            $this->updateAccountPathRecursively($rootAccount);
        }
    }

    /**
     * Update path secara rekursif untuk akun dan semua child-nya
     */
    private function updateAccountPathRecursively(AccountancyChartOfAccount $account): void
    {
        // Update path untuk akun ini
        $account->updatePath();
        $this->line("  - Updated: {$account->name} (Level: {$account->level}, Path: {$account->path})");
        
        // Update path untuk semua child
        $children = AccountancyChartOfAccount::where('parent_id', $account->id)
            ->where('accountancy_company_id', $account->accountancy_company_id)
            ->get();
            
        foreach ($children as $child) {
            $this->updateAccountPathRecursively($child);
        }
    }
}
