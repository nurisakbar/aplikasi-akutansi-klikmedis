<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface AccountsReceivableRepositoryInterface
{
    public function getReceivables(?string $customerId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection;
    public function getReceivableById(string $id);
    public function getTotalReceivable(?string $customerId = null): float;
    public function getAging(?string $customerId = null): array;
} 