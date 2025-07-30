<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface CashBankRepositoryInterface
{
    public function getTransactions(?string $accountId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $type = null, ?string $status = null): Collection;
    public function getBalance(string $accountId, ?string $dateTo = null): float;
} 