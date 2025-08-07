<?php

namespace App\Repositories\AccountancyCustomer;

use App\Models\AccountancyCustomer;
use Illuminate\Database\Eloquent\Collection;

interface AccountancyCustomerRepositoryInterface
{
    public function getCustomers(?string $search = null, ?string $status = null): Collection;
    public function create(array $data): AccountancyCustomer;
    public function update(AccountancyCustomer $customer, array $data): AccountancyCustomer;
    public function delete(AccountancyCustomer $customer): bool;
    public function findById(string $id): ?AccountancyCustomer;
    public function findByCode(string $code): ?AccountancyCustomer;
    public function getAll(): Collection;
    public function getActiveCustomers(): Collection;
    public function getCustomersByCompany(string $companyId): Collection;
} 