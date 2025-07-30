<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\ChartOfAccount;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class JournalEntrySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $accounts = ChartOfAccount::inRandomOrder()->limit(10)->get();
        if ($accounts->count() < 4) {
            $this->command->warn('Seeder membutuhkan minimal 4 akun COA.');
            return;
        }
        for ($i = 1; $i <= 30; $i++) {
            DB::transaction(function () use ($i, $user, $accounts) {
                $date = now()->subDays(rand(0, 60));
                $desc = 'Transaksi dummy #' . $i;
                $reference = 'REF-' . Str::upper(Str::random(6));
                $entry = JournalEntry::create([
                    'date' => $date,
                    'description' => $desc,
                    'reference' => $reference,
                    'created_by' => $user ? $user->id : null,
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
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'chart_of_account_id' => $coaIds[$j],
                        'debit' => $isDebit ? $amounts[$j] : 0,
                        'credit' => !$isDebit ? $amounts[$j] : 0,
                        'description' => 'Line ' . ($j + 1) . ' for ' . $desc,
                    ]);
                }
            });
        }
    }
} 