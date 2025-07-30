<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ChartOfAccountService
{
    /**
     * Get all accounts for a given setting
     */
    public function getAccounts(string $settingId): Collection
    {
        return ChartOfAccount::getBySettingId($settingId)->get();
    }

    /**
     * Create a new account
     */
    public function createAccount(array $data): ChartOfAccount
    {
        DB::beginTransaction();
        try {
            $account = ChartOfAccount::create($data);
            $account->updatePath();
            DB::commit();
            return $account;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing account
     */
    public function updateAccount(ChartOfAccount $account, array $data): ChartOfAccount
    {
        DB::beginTransaction();
        try {
            $account->update($data);
            
            // If parent changed, update paths
            if (isset($data['parent_id']) && $data['parent_id'] !== $account->parent_id) {
                $account->updatePath();
                // Update children paths
                $account->children->each(function ($child) {
                    $child->updatePath();
                });
            }
            
            DB::commit();
            return $account;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete an account
     */
    public function deleteAccount(ChartOfAccount $account): bool
    {
        // Check if account has children
        if ($account->children()->exists()) {
            throw new \Exception('Cannot delete account with children');
        }

        // Check if account is used in transactions
        // TODO: Add check for journal entries when that module is implemented
        
        return $account->delete();
    }

    /**
     * Get account hierarchy
     */
    public function getAccountHierarchy(string $settingId): Collection
    {
        return ChartOfAccount::getBySettingId($settingId)
            ->whereNull('parent_id')
            ->with('children')
            ->get();
    }

    /**
     * Validate account code uniqueness within the same setting
     */
    public function isCodeUnique(string $code, string $settingId, ?string $excludeId = null): bool
    {
        $query = ChartOfAccount::where('setting_id', $settingId)
            ->where('code', $code);
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }
} 