<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface BalanceSheetRepositoryInterface
{
    public function getBalanceSheetData(?string $dateTo = null, ?string $status = null): Collection;
} 