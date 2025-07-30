<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AccountsReceivablePaymentRepositoryInterface;
use App\Models\AccountsReceivablePayment;
use Illuminate\Support\Collection;

class AccountsReceivablePaymentRepository implements AccountsReceivablePaymentRepositoryInterface
{
    public function getPayments(string $receivableId): Collection
    {
        return AccountsReceivablePayment::where('accounts_receivable_id', $receivableId)->orderBy('date')->get();
    }

    public function createPayment(array $data)
    {
        return AccountsReceivablePayment::create($data);
    }
} 