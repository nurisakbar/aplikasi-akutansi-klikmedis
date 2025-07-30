<?php

namespace App\Services;

use App\Repositories\Interfaces\CashBankRepositoryInterface;

class CashBankService
{
    protected $repository;
    public function __construct(CashBankRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getTransactions(?string $accountId = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $type = null, ?string $status = null)
    {
        $rows = $this->repository->getTransactions($accountId, $dateFrom, $dateTo, $type, $status);
        $saldo = $accountId ? $this->repository->getBalance($accountId, $dateTo) : null;
        return [
            'rows' => $rows,
            'saldo' => $saldo,
        ];
    }

    public function createTransaction(array $data)
    {
        return \DB::transaction(function () use ($data) {
            $buktiPath = null;
            if (isset($data['bukti']) && $data['bukti']) {
                $buktiPath = $data['bukti']->store('bukti_kasbank', 'public');
                $data['bukti'] = $buktiPath;
            }
            return \App\Models\CashBankTransaction::create($data);
        });
    }
} 