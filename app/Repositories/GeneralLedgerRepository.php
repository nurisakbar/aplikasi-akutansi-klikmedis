<?php

namespace App\Repositories;

use App\Repositories\Interfaces\GeneralLedgerRepositoryInterface;
use App\Models\JournalEntryLine;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeneralLedgerRepository implements GeneralLedgerRepositoryInterface
{
    public function getLedgerData(string $accountId, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection
    {
        $query = JournalEntryLine::with(['journalEntry'])
            ->where('chart_of_account_id', $accountId)
            ->whereHas('journalEntry', function($q) use ($status, $dateFrom, $dateTo) {
                if ($status) $q->where('status', $status);
                if ($dateFrom) $q->where('date', '>=', $dateFrom);
                if ($dateTo) $q->where('date', '<=', $dateTo);
            })
            ->orderBy('journal_entry_id')
            ->orderBy('id');
        return $query->get();
    }

    public function getOpeningBalance(string $accountId, string $dateFrom): float
    {
        $debit = JournalEntryLine::where('chart_of_account_id', $accountId)
            ->whereHas('journalEntry', function($q) use ($dateFrom) {
                $q->where('status', 'posted')->where('date', '<', $dateFrom);
            })->sum('debit');
        $credit = JournalEntryLine::where('chart_of_account_id', $accountId)
            ->whereHas('journalEntry', function($q) use ($dateFrom) {
                $q->where('status', 'posted')->where('date', '<', $dateFrom);
            })->sum('credit');
        return $debit - $credit;
    }
} 