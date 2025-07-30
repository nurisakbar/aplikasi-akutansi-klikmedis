<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProfitLossRepositoryInterface;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Support\Collection;

class ProfitLossRepository implements ProfitLossRepositoryInterface
{
    public function getProfitLossData(?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection
    {
        $categories = [
            'revenue' => ['operating_revenue', 'other_revenue'],
            'expense' => ['operating_expense', 'other_expense'],
        ];
        $result = collect();
        foreach ($categories as $type => $catList) {
            $accounts = ChartOfAccount::where('type', $type)
                ->whereIn('category', $catList)
                ->orderBy('code')
                ->get();
            $accountsData = [];
            $total = 0;
            foreach ($accounts as $account) {
                $debit = JournalEntryLine::where('chart_of_account_id', $account->id)
                    ->whereHas('journalEntry', function($q) use ($status, $dateFrom, $dateTo) {
                        $q->where('status', $status ?? 'posted');
                        if ($dateFrom) $q->where('date', '>=', $dateFrom);
                        if ($dateTo) $q->where('date', '<=', $dateTo);
                    })->sum('debit');
                $credit = JournalEntryLine::where('chart_of_account_id', $account->id)
                    ->whereHas('journalEntry', function($q) use ($status, $dateFrom, $dateTo) {
                        $q->where('status', $status ?? 'posted');
                        if ($dateFrom) $q->where('date', '>=', $dateFrom);
                        if ($dateTo) $q->where('date', '<=', $dateTo);
                    })->sum('credit');
                $saldo = $type === 'revenue' ? $credit - $debit : $debit - $credit;
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