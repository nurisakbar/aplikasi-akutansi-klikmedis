<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface ProfitLossRepositoryInterface
{
    public function getProfitLossData(?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection;
} 