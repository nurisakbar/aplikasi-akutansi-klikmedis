<?php

namespace App\Services;

use App\Repositories\Interfaces\AccountsPayableRepositoryInterface;
use App\Models\AccountsPayable;
use Illuminate\Support\Facades\DB;

class AccountsPayableService
{
    protected $repository;
    public function __construct(AccountsPayableRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createPayable(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'unpaid';
            return AccountsPayable::create($data);
        });
    }

    public function payPayable(AccountsPayable $payable, float $amount)
    {
        return DB::transaction(function () use ($payable, $amount) {
            $payable->amount -= $amount;
            if ($payable->amount <= 0) {
                $payable->status = 'paid';
                $payable->amount = 0;
            }
            $payable->save();
            return $payable;
        });
    }

    public function addPayment($payableId, array $data)
    {
        return \DB::transaction(function () use ($payableId, $data) {
            $payable = \App\Models\AccountsPayable::findOrFail($payableId);
            $payment = app(\App\Repositories\Interfaces\AccountsPayablePaymentRepositoryInterface::class)
                ->createPayment(array_merge($data, ['accounts_payable_id' => $payableId]));
            $payable->amount -= $data['amount'];
            if ($payable->amount <= 0) {
                $payable->status = 'paid';
                $payable->amount = 0;
            }
            $payable->save();
            return $payment;
        });
    }

    public function getAging(?string $supplierId = null)
    {
        return $this->repository->getAging($supplierId);
    }

    public function getTotalPayable(?string $supplierId = null)
    {
        return $this->repository->getTotalPayable($supplierId);
    }

    public function getPayables(?string $supplierId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null)
    {
        return $this->repository->getPayables($supplierId, $dateFrom, $dateTo, $status);
    }
} 