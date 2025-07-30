<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CashBankRepositoryInterface;
use App\Models\CashBankTransaction;
use Illuminate\Support\Collection;

class CashBankRepository implements CashBankRepositoryInterface
{
    public function getTransactions(?string $accountId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $type = null, ?string $status = null): Collection
    {
        $query = CashBankTransaction::query();
        if ($accountId) $query->where('account_id', $accountId);
        if ($dateFrom) $query->where('date', '>=', $dateFrom);
        if ($dateTo) $query->where('date', '<=', $dateTo);
        if ($type) $query->where('type', $type);
        if ($status) $query->where('status', $status);
        return $query->orderBy('date')->orderBy('created_at')->get();
    }

    public function getBalance(string $accountId, ?string $dateTo = null): float
    {
        $query = CashBankTransaction::where('account_id', $accountId);
        if ($dateTo) $query->where('date', '<=', $dateTo);
        $in = (clone $query)->where('type', 'in')->sum('amount');
        $out = (clone $query)->where('type', 'out')->sum('amount');
        // Untuk transfer, bisa diatur logika sesuai kebutuhan
        return $in - $out;
    }
} 