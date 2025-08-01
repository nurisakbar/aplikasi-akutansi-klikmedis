<?php

namespace App\Repositories;

use App\Models\AccountancyChartOfAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ChartOfAccountRepository
{
    protected $model;

    public function __construct(AccountancyChartOfAccount $model)
    {
        $this->model = $model;
    }

    /**
     * Get all accounts
     */
    public function all(string $companyId): Collection
    {
        return $this->model->getByCompanyId($companyId)->get();
    }

    /**
     * Get paginated accounts
     */
    public function paginate(string $companyId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->getByCompanyId($companyId)->paginate($perPage);
    }

    /**
     * Find account by ID
     */
    public function find(string $id): ?AccountancyChartOfAccount
    {
        return $this->model->find($id);
    }

    /**
     * Create new account
     */
    public function create(array $data): AccountancyChartOfAccount
    {
        return $this->model->create($data);
    }

    /**
     * Update account
     */
    public function update(AccountancyChartOfAccount $account, array $data): bool
    {
        return $account->update($data);
    }

    /**
     * Delete account
     */
    public function delete(AccountancyChartOfAccount $account): bool
    {
        return $account->delete();
    }

    /**
     * Get root accounts
     */
    public function getRootAccounts(string $companyId): Collection
    {
        return $this->model->getByCompanyId($companyId)
            ->whereNull('parent_id')
            ->get();
    }

    /**
     * Get accounts by type
     */
    public function getByType(string $companyId, string $type): Collection
    {
        return $this->model->getByCompanyId($companyId)
            ->where('type', $type)
            ->get();
    }

    /**
     * Get accounts by category
     */
    public function getByCategory(string $companyId, string $category): Collection
    {
        return $this->model->getByCompanyId($companyId)
            ->where('category', $category)
            ->get();
    }

    /**
     * Get active accounts
     */
    public function getActive(string $companyId): Collection
    {
        return $this->model->getByCompanyId($companyId)
            ->where('is_active', true)
            ->get();
    }
}
