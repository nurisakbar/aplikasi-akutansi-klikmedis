<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AccountsPayableRepositoryInterface;
use App\Models\AccountsPayable;
use Illuminate\Support\Collection;

class AccountsPayableRepository implements AccountsPayableRepositoryInterface
{
    public function getPayables(?string $supplierId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): Collection
    {
        $query = AccountsPayable::query();
        if ($supplierId) $query->where('supplier_id', $supplierId);
        if ($dateFrom) $query->where('date', '>=', $dateFrom);
        if ($dateTo) $query->where('date', '<=', $dateTo);
        if ($status) $query->where('status', $status);
        return $query->orderBy('date')->orderBy('created_at')->get();
    }

    public function getPayableById(string $id)
    {
        return AccountsPayable::find($id);
    }

    public function getTotalPayable(?string $supplierId = null): float
    {
        $query = AccountsPayable::query();
        if ($supplierId) $query->where('supplier_id', $supplierId);
        return $query->where('status', 'unpaid')->sum('amount');
    }

    public function getAging(?string $supplierId = null): array
    {
        $query = AccountsPayable::query();
        if ($supplierId) $query->where('supplier_id', $supplierId);
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