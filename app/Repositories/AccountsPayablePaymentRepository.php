<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AccountsPayablePaymentRepositoryInterface;
use App\Models\AccountsPayablePayment;
use Illuminate\Support\Collection;

class AccountsPayablePaymentRepository implements AccountsPayablePaymentRepositoryInterface
{
    public function getPayments(string $payableId): Collection
    {
        return AccountsPayablePayment::where('accounts_payable_id', $payableId)->orderBy('date')->get();
    }

    public function createPayment(array $data)
    {
        return AccountsPayablePayment::create($data);
    }
} 