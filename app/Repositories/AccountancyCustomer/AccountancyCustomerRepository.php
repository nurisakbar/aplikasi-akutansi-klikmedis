<?php

namespace App\Repositories\AccountancyCustomer;

use App\Models\AccountancyCustomer;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\CustomerStatus;

class AccountancyCustomerRepository implements AccountancyCustomerRepositoryInterface
{
    public function getCustomers(?string $search = null, ?string $status = null): Collection
    {
        $query = AccountancyCustomer::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->orderBy('name')->get();
    }

    public function create(array $data): AccountancyCustomer
    {
        return AccountancyCustomer::create($data);
    }

    public function update(AccountancyCustomer $customer, array $data): AccountancyCustomer
    {
        $customer->update($data);
        return $customer->fresh();
    }

    public function delete(AccountancyCustomer $customer): bool
    {
        return $customer->delete();
    }

    public function findById(string $id): ?AccountancyCustomer
    {
        return AccountancyCustomer::find($id);
    }

    public function findByCode(string $code): ?AccountancyCustomer
    {
        return AccountancyCustomer::where('code', $code)->first();
    }

    public function getAll(): Collection
    {
        return AccountancyCustomer::orderBy('name')->get();
    }

    public function getActiveCustomers(): Collection
    {
        return AccountancyCustomer::active()->orderBy('name')->get();
    }

    public function getCustomersByCompany(string $companyId): Collection
    {
        return AccountancyCustomer::byCompany($companyId)->orderBy('name')->get();
    }
} 