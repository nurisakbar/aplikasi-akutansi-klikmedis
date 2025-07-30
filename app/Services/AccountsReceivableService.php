<?php

namespace App\Services;

use App\Repositories\Interfaces\AccountsReceivableRepositoryInterface;
use App\Models\AccountsReceivable;
use Illuminate\Support\Facades\DB;

class AccountsReceivableService
{
    protected $repository;
    public function __construct(AccountsReceivableRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createReceivable(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'unpaid';
            return AccountsReceivable::create($data);
        });
    }

    public function payReceivable(AccountsReceivable $receivable, float $amount)
    {
        return DB::transaction(function () use ($receivable, $amount) {
            $receivable->amount -= $amount;
            if ($receivable->amount <= 0) {
                $receivable->status = 'paid';
                $receivable->amount = 0;
            }
            $receivable->save();
            return $receivable;
        });
    }

    public function addPayment($receivableId, array $data)
    {
        return \DB::transaction(function () use ($receivableId, $data) {
            $receivable = \App\Models\AccountsReceivable::findOrFail($receivableId);
            $payment = app(\App\Repositories\Interfaces\AccountsReceivablePaymentRepositoryInterface::class)
                ->createPayment(array_merge($data, ['accounts_receivable_id' => $receivableId]));
            $receivable->amount -= $data['amount'];
            if ($receivable->amount <= 0) {
                $receivable->status = 'paid';
                $receivable->amount = 0;
            }
            $receivable->save();
            return $payment;
        });
    }

    public function getAging(?string $customerId = null)
    {
        return $this->repository->getAging($customerId);
    }

    public function getTotalReceivable(?string $customerId = null)
    {
        return $this->repository->getTotalReceivable($customerId);
    }

    public function getReceivables(?string $customerId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null)
    {
        return $this->repository->getReceivables($customerId, $dateFrom, $dateTo, $status);
    }
} 