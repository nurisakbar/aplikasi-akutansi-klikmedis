<?php

namespace App\Services\AccountancyCashBank;

use App\Repositories\AccountancyCashBank\CashBankRepositoryInterface;
use App\Models\AccountancyCashBankTransaction;
use App\Enums\CashBankTransactionStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashBankService
{
    public function __construct(
        private CashBankRepositoryInterface $repository
    ) {}

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
        return DB::transaction(function () use ($data) {
            // Get company ID from user
            $user = Auth::user();
            $companyId = $user->hasRole('superadmin') 
                ? \App\Models\AccountancyCompany::where('name', 'Global System')->first()->id
                : $user->accountancy_company_id;
            
            $data['accountancy_company_id'] = $companyId;
            
            // Set default status if not provided
            if (!isset($data['status'])) {
                $data['status'] = CashBankTransactionStatus::DRAFT;
            }
            
            return $this->repository->create($data);
        });
    }

    public function updateTransaction(AccountancyCashBankTransaction $transaction, array $data)
    {
        return DB::transaction(function () use ($transaction, $data) {
            return $this->repository->update($transaction, $data);
        });
    }

    public function deleteTransaction(AccountancyCashBankTransaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {
            return $this->repository->delete($transaction);
        });
    }

    public function post(AccountancyCashBankTransaction $transaction): void
    {
        if ($transaction->isPosted()) {
            throw new \Exception('Transaksi sudah diposting.');
        }

        // Update status to posted
        $transaction->update([
            'status' => CashBankTransactionStatus::POSTED
        ]);

        // Add to history if needed
        $this->addToHistory($transaction, 'posted', 'Transaksi diposting');
    }

    private function addToHistory(AccountancyCashBankTransaction $transaction, string $action, string $description): void
    {
        // For now, we can add this to reference field or create a separate history table later
        $reference = $transaction->reference ?? '';
        $historyEntry = "[" . now()->format('Y-m-d H:i:s') . "] {$action}: {$description}";
        
        $transaction->update([
            'reference' => $reference ? $reference . "\n" . $historyEntry : $historyEntry
        ]);
    }
} 