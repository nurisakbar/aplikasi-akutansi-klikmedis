<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface AccountsPayableRepositoryInterface
{
    public function getPayables(?string $supplierId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection;
    public function getPayableById(string $id);
    public function getTotalPayable(?string $supplierId = null): float;
    public function getAging(?string $supplierId = null): array;
} 