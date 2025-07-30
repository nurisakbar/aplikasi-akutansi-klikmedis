<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface AccountsReceivablePaymentRepositoryInterface
{
    public function getPayments(string $receivableId): Collection;
    public function createPayment(array $data);
} 