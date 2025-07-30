<?php

namespace App\Repositories;

use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ChartOfAccountRepository
{
    protected $model;

    public function __construct(ChartOfAccount $model)
    {
        $this->model = $model;
    }

    /**
     * Get all accounts
     */
    public function all(string $settingId): Collection
    {
        return $this->model->getBySettingId($settingId)->get();
    }

    /**
     * Get paginated accounts
     */
    public function paginate(string $settingId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->getBySettingId($settingId)->paginate($perPage);
    }

    /**
     * Find account by ID
     */
    public function find(string $id): ?ChartOfAccount
    {
        return $this->model->find($id);
    }

    /**
     * Create new account
     */
    public function create(array $data): ChartOfAccount
    {
        return $this->model->create($data);
    }

    /**
     * Update account
     */
    public function update(ChartOfAccount $account, array $data): bool
    {
        return $account->update($data);
    }

    /**
     * Delete account
     */
    public function delete(ChartOfAccount $account): bool
    {
        return $account->delete();
    }

    /**
     * Get root accounts
     */
    public function getRootAccounts(string $settingId): Collection
    {
        return $this->model->getBySettingId($settingId)
            ->whereNull('parent_id')
            ->get();
    }

    /**
     * Get accounts by type
     */
    public function getByType(string $settingId, string $type): Collection
    {
        return $this->model->getBySettingId($settingId)
            ->where('type', $type)
            ->get();
    }

    /**
     * Get accounts by category
     */
    public function getByCategory(string $settingId, string $category): Collection
    {
        return $this->model->getBySettingId($settingId)
            ->where('category', $category)
            ->get();
    }

    /**
     * Get active accounts
     */
    public function getActive(string $settingId): Collection
    {
        return $this->model->getBySettingId($settingId)
            ->where('is_active', true)
            ->get();
    }
} 