<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface TrialBalanceRepositoryInterface
{
    public function getTrialBalanceData(?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection;
} 