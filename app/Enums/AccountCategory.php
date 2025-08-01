<?php

namespace App\Enums;

enum AccountCategory: string
{
    case CURRENT_ASSET = 'current_asset';
    case FIXED_ASSET = 'fixed_asset';
    case OTHER_ASSET = 'other_asset';
    case CURRENT_LIABILITY = 'current_liability';
    case LONG_TERM_LIABILITY = 'long_term_liability';
    case EQUITY = 'equity';
    case OPERATING_REVENUE = 'operating_revenue';
    case OTHER_REVENUE = 'other_revenue';
    case OPERATING_EXPENSE = 'operating_expense';
    case OTHER_EXPENSE = 'other_expense';

    public function getLabel(): string
    {
        return match ($this) {
            self::CURRENT_ASSET => 'Current Asset',
            self::FIXED_ASSET => 'Fixed Asset',
            self::OTHER_ASSET => 'Other Asset',
            self::CURRENT_LIABILITY => 'Current Liability',
            self::LONG_TERM_LIABILITY => 'Long Term Liability',
            self::EQUITY => 'Equity',
            self::OPERATING_REVENUE => 'Operating Revenue',
            self::OTHER_REVENUE => 'Other Revenue',
            self::OPERATING_EXPENSE => 'Operating Expense',
            self::OTHER_EXPENSE => 'Other Expense',
        };
    }

    public static function getOptions(): array
    {
        return collect(self::cases())->mapWithKeys(fn($category) => [
            $category->value => $category->getLabel(),
        ])->toArray();
    }
}
