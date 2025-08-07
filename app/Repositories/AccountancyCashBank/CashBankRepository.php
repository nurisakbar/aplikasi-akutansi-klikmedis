<?php

namespace App\Repositories\AccountancyCashBank;

use App\Models\AccountancyCashBankTransaction;
use Illuminate\Support\Collection;

class CashBankRepository implements CashBankRepositoryInterface
{
    public function getTransactions(?string $accountId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $type = null, ?string $status = null): Collection
    {
        $query = AccountancyCashBankTransaction::query();
        
        if ($accountId) {
            $query->where('accountancy_chart_of_account_id', $accountId);
        }
        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }
        if ($type) {
            $query->where('type', $type);
        }
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->orderBy('date')->orderBy('created_at')->get();
    }

    public function getBalance(string $accountId, ?string $dateTo = null): float
    {
        $query = AccountancyCashBankTransaction::where('accountancy_chart_of_account_id', $accountId);
        
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }
        
        $in = (clone $query)->where('type', 'in')->sum('amount');
        $out = (clone $query)->where('type', 'out')->sum('amount');
        
        // Untuk transfer, bisa diatur logika sesuai kebutuhan
        return $in - $out;
    }

    public function create(array $data): AccountancyCashBankTransaction
    {
        return AccountancyCashBankTransaction::create($data);
    }

    public function update(AccountancyCashBankTransaction $transaction, array $data): AccountancyCashBankTransaction
    {
        $transaction->update($data);
        return $transaction->fresh();
    }

    public function delete(AccountancyCashBankTransaction $transaction): bool
    {
        return $transaction->delete();
    }

    public function findById(string $id): ?AccountancyCashBankTransaction
    {
        return AccountancyCashBankTransaction::find($id);
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return AccountancyCashBankTransaction::all();
    }
} 