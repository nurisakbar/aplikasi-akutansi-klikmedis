<?php

namespace App\Repositories\AccountancyCashBank;

use App\Models\AccountancyCashBankTransaction;

interface CashBankRepositoryInterface
{
    public function getTransactions(?string $accountId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $type = null, ?string $status = null);
    
    public function getBalance(string $accountId, ?string $dateTo = null): float;
    
    public function create(array $data): AccountancyCashBankTransaction;
    
    public function update(AccountancyCashBankTransaction $transaction, array $data): AccountancyCashBankTransaction;
    
    public function delete(AccountancyCashBankTransaction $transaction): bool;
    
    public function findById(string $id): ?AccountancyCashBankTransaction;
    
    public function getAll(): \Illuminate\Database\Eloquent\Collection;
} 