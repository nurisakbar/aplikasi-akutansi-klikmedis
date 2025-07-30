<?php

namespace App\Repositories\Interfaces;

use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ChartOfAccountRepositoryInterface
{
    public function all(string $settingId): Collection;
    
    public function paginate(string $settingId, int $perPage = 15): LengthAwarePaginator;
    
    public function find(string $id): ?ChartOfAccount;
    
    public function create(array $data): ChartOfAccount;
    
    public function update(ChartOfAccount $account, array $data): bool;
    
    public function delete(ChartOfAccount $account): bool;
    
    public function getRootAccounts(string $settingId): Collection;
    
    public function getByType(string $settingId, string $type): Collection;
    
    public function getByCategory(string $settingId, string $category): Collection;
    
    public function getActive(string $settingId): Collection;
} 