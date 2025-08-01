<?php

namespace App\Repositories\Interfaces;

use App\Models\AccountancyChartOfAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ChartOfAccountRepositoryInterface
{
    public function all(string $companyId): Collection;

    public function paginate(string $companyId, int $perPage = 15): LengthAwarePaginator;

    public function find(string $id): ?AccountancyChartOfAccount;

    public function create(array $data): AccountancyChartOfAccount;

    public function update(AccountancyChartOfAccount $account, array $data): bool;

    public function delete(AccountancyChartOfAccount $account): bool;

    public function getRootAccounts(string $companyId): Collection;

    public function getByType(string $companyId, string $type): Collection;

    public function getByCategory(string $companyId, string $category): Collection;

    public function getActive(string $companyId): Collection;
}
