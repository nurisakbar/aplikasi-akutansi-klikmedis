<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface GeneralLedgerRepositoryInterface
{
    public function getLedgerData(string $accountId, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection;
    public function getOpeningBalance(string $accountId, string $dateFrom): float;
} 