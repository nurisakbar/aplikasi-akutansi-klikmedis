<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BalanceSheetRepositoryInterface;
use App\Models\AccountancyChartOfAccount;
use App\Models\AccountancyJournalEntryLine;
use Illuminate\Support\Collection;

class BalanceSheetRepository implements BalanceSheetRepositoryInterface
{
    public function getBalanceSheetData(?string $dateTo = null, ?string $status = null): Collection
    {
        $categories = [
            'asset' => ['current_asset', 'fixed_asset', 'other_asset'],
            'liability' => ['current_liability', 'long_term_liability'],
            'equity' => ['equity'],
        ];
        $result = collect();
        foreach ($categories as $type => $catList) {
            $accounts = AccountancyChartOfAccount::where('type', $type)
                ->whereIn('category', $catList)
                ->orderBy('code')
                ->get();
            $accountsData = [];
            $total = 0;
            foreach ($accounts as $account) {
                $debit = AccountancyJournalEntryLine::where('chart_of_account_id', $account->id)
                    ->whereHas('accountancyJournalEntry', function($q) use ($status, $dateTo) {
                        $q->where('status', $status ?? 'posted');
                        if ($dateTo) $q->where('date', '<=', $dateTo);
                    })->sum('debit');
                $credit = AccountancyJournalEntryLine::where('chart_of_account_id', $account->id)
                    ->whereHas('accountancyJournalEntry', function($q) use ($status, $dateTo) {
                        $q->where('status', $status ?? 'posted');
                        if ($dateTo) $q->where('date', '<=', $dateTo);
                    })->sum('credit');
                $saldo = $debit - $credit;
                $accountsData[] = (object) [
                    'account' => $account,
                    'saldo' => $saldo,
                ];
                $total += $saldo;
            }
            $result->push((object) [
                'type' => $type,
                'accounts' => $accountsData,
                'total' => $total,
            ]);
        }
        return $result;
    }
}
