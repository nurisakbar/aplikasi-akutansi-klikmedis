<?php

namespace App\Services;

use App\Repositories\Interfaces\GeneralLedgerRepositoryInterface;
use App\Models\AccountancyChartOfAccount;
use Illuminate\Support\Collection;

class GeneralLedgerService
{
    protected $repository;
    public function __construct(GeneralLedgerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getLedger(
        string $accountId,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $status = 'posted'
    ): array {
        $account = AccountancyChartOfAccount::findOrFail($accountId);
        $opening = $this->repository->getOpeningBalance($accountId, $dateFrom ?? '1900-01-01');
        $lines = $this->repository->getLedgerData($accountId, $dateFrom, $dateTo, $status);
        $mutasiDebit = $lines->sum('debit');
        $mutasiKredit = $lines->sum('credit');
        $closing = $opening + $mutasiDebit - $mutasiKredit;
        return [
            'account' => $account,
            'opening' => $opening,
            'lines' => $lines,
            'mutasi_debit' => $mutasiDebit,
            'mutasi_kredit' => $mutasiKredit,
            'closing' => $closing,
        ];
    }
}
