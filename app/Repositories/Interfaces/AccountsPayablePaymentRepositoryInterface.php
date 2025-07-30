<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface AccountsPayablePaymentRepositoryInterface
{
    public function getPayments(string $payableId): Collection;
    public function createPayment(array $data);
} 