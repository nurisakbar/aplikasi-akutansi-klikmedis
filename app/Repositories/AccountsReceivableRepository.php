<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AccountsReceivableRepositoryInterface;
use App\Models\AccountsReceivable;
use Illuminate\Support\Collection;

class AccountsReceivableRepository implements AccountsReceivableRepositoryInterface
{
    public function getReceivables(?string $customerId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection
    {
        $query = AccountsReceivable::query();
        if ($customerId) $query->where('customer_id', $customerId);
        if ($dateFrom) $query->where('date', '>=', $dateFrom);
        if ($dateTo) $query->where('date', '<=', $dateTo);
        if ($status) $query->where('status', $status);
        return $query->orderBy('date')->orderBy('created_at')->get();
    }

    public function getReceivableById(string $id)
    {
        return AccountsReceivable::find($id);
    }

    public function getTotalReceivable(?string $customerId = null): float
    {
        $query = AccountsReceivable::query();
        if ($customerId) $query->where('customer_id', $customerId);
        return $query->where('status', 'unpaid')->sum('amount');
    }

    public function getAging(?string $customerId = null): array
    {
        $query = AccountsReceivable::query();
        if ($customerId) $query->where('customer_id', $customerId);
        $now = now();
        $aging = [
            'current' => (clone $query)->where('due_date', '>=', $now)->sum('amount'),
            'overdue_1_30' => (clone $query)->where('due_date', '<', $now)->where('due_date', '>=', $now->copy()->subDays(30))->sum('amount'),
            'overdue_31_60' => (clone $query)->where('due_date', '<', $now->copy()->subDays(30))->where('due_date', '>=', $now->copy()->subDays(60))->sum('amount'),
            'overdue_61_90' => (clone $query)->where('due_date', '<', $now->copy()->subDays(60))->where('due_date', '>=', $now->copy()->subDays(90))->sum('amount'),
            'overdue_91_plus' => (clone $query)->where('due_date', '<', $now->copy()->subDays(90))->sum('amount'),
        ];
        return $aging;
    }
} 