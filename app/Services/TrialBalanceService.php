<?php

namespace App\Services;

use App\Repositories\Interfaces\TrialBalanceRepositoryInterface;

class TrialBalanceService
{
    protected $repository;
    public function __construct(TrialBalanceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getTrialBalance(?string $dateFrom = null, ?string $dateTo = null, ?string $status = 'posted')
    {
        $rows = $this->repository->getTrialBalanceData($dateFrom, $dateTo, $status);
        $totalDebit = $rows->sum('mutasi_debit');
        $totalCredit = $rows->sum('mutasi_kredit');
        return [
            'rows' => $rows,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ];
    }
} 