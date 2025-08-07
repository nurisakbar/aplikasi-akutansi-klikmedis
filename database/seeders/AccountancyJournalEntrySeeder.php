<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountancyJournalEntry;
use App\Models\AccountancyJournalEntryLine;
use App\Models\AccountancyChartOfAccount;
use App\Models\AccountancyCompany;
use App\Models\User;
use App\Enums\JournalEntryStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AccountancyJournalEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Journal Entries...');

        // Get companies
        $companies = AccountancyCompany::all();
        if ($companies->count() === 0) {
            $this->command->warn('No companies found. Please run AccountancyCompanySeeder first.');
            return;
        }

        foreach ($companies as $company) {
            $this->command->info('Creating Journal Entries for company: ' . $company->name);
            
            // Get accounts for this company
            $accounts = AccountancyChartOfAccount::where('accountancy_company_id', $company->id)
                ->inRandomOrder()
                ->limit(10)
                ->get();
                
            if ($accounts->count() < 4) {
                $this->command->warn('Company ' . $company->name . ' needs at least 4 COA accounts.');
                continue;
            }

            // Get users for this company
            $users = User::where('accountancy_company_id', $company->id)->get();
            if ($users->count() === 0) {
                $this->command->warn('No users found for company ' . $company->name);
                continue;
            }

            for ($i = 1; $i <= 10; $i++) {
                DB::transaction(function () use ($i, $company, $accounts, $users) {
                    $date = now()->subDays(rand(0, 60));
                    $desc = 'Transaksi dummy #' . $i . ' - ' . $company->name;
                    $reference = 'REF-' . Str::upper(Str::random(6));
                    
                    // Random status: 70% draft, 30% posted
                    $status = rand(1, 10) <= 7 ? JournalEntryStatus::DRAFT : JournalEntryStatus::POSTED;
                    
                    $entry = AccountancyJournalEntry::create([
                        'id' => (string) Str::uuid(),
                        'accountancy_company_id' => $company->id,
                        'date' => $date,
                        'description' => $desc,
                        'reference' => $reference,
                        'status' => $status,
                        'created_by' => $users->random()->id,
                    ]);

                    // Buat lines (2-4 baris, balance)
                    $lineCount = rand(2, 4);
                    $amounts = [];
                    $total = 0;
                    for ($j = 0; $j < $lineCount - 1; $j++) {
                        $amt = rand(10000, 100000);
                        $amounts[] = $amt;
                        $total += $amt;
                    }
                    $amounts[] = $total; // agar balance

                    // Random: separuh debit, separuh kredit
                    $coaIds = $accounts->random($lineCount)->pluck('id')->toArray();
                    for ($j = 0; $j < $lineCount; $j++) {
                        $isDebit = $j < ($lineCount / 2);
                        AccountancyJournalEntryLine::create([
                            'id' => (string) Str::uuid(),
                            'journal_entry_id' => $entry->id,
                            'chart_of_account_id' => $coaIds[$j],
                            'debit' => $isDebit ? $amounts[$j] : 0,
                            'credit' => !$isDebit ? $amounts[$j] : 0,
                            'description' => 'Line ' . ($j + 1) . ' for ' . $desc,
                        ]);
                    }

                    $this->command->info('Created Journal Entry: ' . $entry->reference . ' (' . $entry->formatted_status . ') for ' . $company->name);
                });
            }
        }

        $this->command->info('Journal Entries seeding completed!');
    }
}
