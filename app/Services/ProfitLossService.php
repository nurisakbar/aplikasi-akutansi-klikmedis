<?php

namespace App\Services;

use App\Repositories\Interfaces\ProfitLossRepositoryInterface;

class ProfitLossService
{
    protected $repository;
    public function __construct(ProfitLossRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getProfitLoss(?string $dateFrom = null, ?string $dateTo = null, ?string $status = 'posted')
    {
        $rows = $this->repository->getProfitLossData($dateFrom, $dateTo, $status);
        $revenue = $rows->firstWhere('type', 'revenue');
        $expense = $rows->firstWhere('type', 'expense');
        $totalRevenue = $revenue ? $revenue->total : 0;
        $totalExpense = $expense ? $expense->total : 0;
        $profit = $totalRevenue - $totalExpense;
        return [
            'rows' => $rows,
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'profit' => $profit,
        ];
    }
} 