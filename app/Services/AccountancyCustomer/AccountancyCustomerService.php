<?php

namespace App\Services\AccountancyCustomer;

use App\Repositories\AccountancyCustomer\AccountancyCustomerRepositoryInterface;
use App\Models\AccountancyCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountancyCustomerService
{
    public function __construct(
        private AccountancyCustomerRepositoryInterface $repository
    ) {}

    public function getCustomers(?string $search = null, ?string $status = null)
    {
        return $this->repository->getCustomers($search, $status);
    }

    public function createCustomer(array $data): AccountancyCustomer
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
                $data['status'] = \App\Enums\CustomerStatus::ACTIVE;
            }
            
            return $this->repository->create($data);
        });
    }

    public function updateCustomer(AccountancyCustomer $customer, array $data): AccountancyCustomer
    {
        return DB::transaction(function () use ($customer, $data) {
            return $this->repository->update($customer, $data);
        });
    }

    public function deleteCustomer(AccountancyCustomer $customer): bool
    {
        return DB::transaction(function () use ($customer) {
            // Check if customer has outstanding receivables
            if ($customer->outstanding_balance > 0) {
                throw new \Exception('Customer tidak dapat dihapus karena masih memiliki piutang yang belum dibayar.');
            }
            
            return $this->repository->delete($customer);
        });
    }

    public function getCustomerById(string $id): ?AccountancyCustomer
    {
        return $this->repository->findById($id);
    }

    public function getCustomerByCode(string $code): ?AccountancyCustomer
    {
        return $this->repository->findByCode($code);
    }

    public function getActiveCustomers()
    {
        return $this->repository->getActiveCustomers();
    }

    public function getCustomersByCompany(string $companyId)
    {
        return $this->repository->getCustomersByCompany($companyId);
    }

    public function validateCreditLimit(AccountancyCustomer $customer, float $amount): bool
    {
        return $customer->hasAvailableCredit($amount);
    }

    public function getCustomerSummary(AccountancyCustomer $customer): array
    {
        return [
            'total_receivables' => $customer->accountsReceivables()->sum('amount'),
            'outstanding_balance' => $customer->outstanding_balance,
            'credit_limit' => $customer->credit_limit,
            'available_credit' => $customer->available_credit,
            'total_transactions' => $customer->accountsReceivables()->count(),
            'unpaid_transactions' => $customer->accountsReceivables()->where('status', 'unpaid')->count(),
        ];
    }
} 