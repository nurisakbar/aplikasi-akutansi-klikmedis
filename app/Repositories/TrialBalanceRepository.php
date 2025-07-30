<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TrialBalanceRepositoryInterface;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Support\Collection;

class TrialBalanceRepository implements TrialBalanceRepositoryInterface
{
    public function getTrialBalanceData(?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection
    {
        $accounts = ChartOfAccount::orderBy('code')->get();
        $result = collect();
        foreach ($accounts as $account) {
            // Saldo awal
            $openingDebit = JournalEntryLine::where('chart_of_account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($status, $dateFrom) {
                    $q->where('status', $status ?? 'posted');
                    if ($dateFrom) $q->where('date', '<', $dateFrom);
                })->sum('debit');
            $openingCredit = JournalEntryLine::where('chart_of_account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($status, $dateFrom) {
                    $q->where('status', $status ?? 'posted');
                    if ($dateFrom) $q->where('date', '<', $dateFrom);
                })->sum('credit');
            $opening = $openingDebit - $openingCredit;
            // Mutasi periode
            $mutasiDebit = JournalEntryLine::where('chart_of_account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($status, $dateFrom, $dateTo) {
                    $q->where('status', $status ?? 'posted');
                    if ($dateFrom) $q->where('date', '>=', $dateFrom);
                    if ($dateTo) $q->where('date', '<=', $dateTo);
                })->sum('debit');
            $mutasiCredit = JournalEntryLine::where('chart_of_account_id', $account->id)
                ->whereHas('journalEntry', function($q) use ($status, $dateFrom, $dateTo) {
                    $q->where('status', $status ?? 'posted');
                    if ($dateFrom) $q->where('date', '>=', $dateFrom);
                    if ($dateTo) $q->where('date', '<=', $dateTo);
                })->sum('credit');
            $closing = $opening + $mutasiDebit - $mutasiCredit;
            $result->push((object) [
                'account' => $account,
                'opening' => $opening,
                'mutasi_debit' => $mutasiDebit,
                'mutasi_kredit' => $mutasiCredit,
                'closing' => $closing,
            ]);
        }
        return $result;
    }
} 