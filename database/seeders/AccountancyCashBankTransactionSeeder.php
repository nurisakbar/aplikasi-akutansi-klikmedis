<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountancyCashBankTransaction;
use App\Models\AccountancyChartOfAccount;
use App\Models\AccountancyCompany;
use App\Enums\CashBankTransactionType;
use App\Enums\CashBankTransactionStatus;

class AccountancyCashBankTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies
        $companies = AccountancyCompany::all();
        
        foreach ($companies as $company) {
            // Get cash/bank accounts for this company
            $cashAccounts = AccountancyChartOfAccount::where('accountancy_company_id', $company->id)
                ->where('type', 'asset')
                ->where('category', 'current_asset')
                ->whereIn('code', ['1111', '1112', '1113']) // Cash on Hand, Bank BCA, Bank Mandiri
                ->get();
            
            if ($cashAccounts->isEmpty()) {
                // If no specific cash accounts, get any current asset accounts
                $cashAccounts = AccountancyChartOfAccount::where('accountancy_company_id', $company->id)
                    ->where('type', 'asset')
                    ->where('category', 'current_asset')
                    ->limit(3)
                    ->get();
            }
            
            if ($cashAccounts->isEmpty()) {
                continue; // Skip if no cash accounts found
            }
            
            // Create sample transactions
            $transactionTypes = CashBankTransactionType::cases();
            $transactionStatuses = CashBankTransactionStatus::cases();
            $descriptions = [
                'Penerimaan kas dari penjualan',
                'Pembayaran supplier',
                'Transfer antar rekening',
                'Setoran modal',
                'Pembayaran gaji karyawan',
                'Penerimaan piutang',
                'Pembayaran biaya operasional',
                'Transfer ke deposito'
            ];
            
            for ($i = 0; $i < 20; $i++) {
                $account = $cashAccounts->random();
                $type = $transactionTypes[array_rand($transactionTypes)];
                $status = $transactionStatuses[array_rand($transactionStatuses)];
                $amount = rand(100000, 5000000);
                
                // Add attachment for some transactions (30% chance)
                $bukti = null;
                if (rand(1, 100) <= 30) {
                    $attachments = [
                        'bukti_transaksi_001.pdf',
                        'bukti_transaksi_002.jpg',
                        'bukti_transaksi_003.png',
                        'bukti_transaksi_004.pdf',
                        'bukti_transaksi_005.jpg'
                    ];
                    $bukti = $attachments[array_rand($attachments)];
                }
                
                AccountancyCashBankTransaction::create([
                    'accountancy_chart_of_account_id' => $account->id,
                    'accountancy_company_id' => $company->id,
                    'date' => now()->subDays(rand(0, 30)),
                    'type' => $type,
                    'amount' => $amount,
                    'description' => $descriptions[array_rand($descriptions)],
                    'status' => $status,
                    'bukti' => $bukti
                ]);
            }
        }
    }
}
