<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountancyCompany extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'address',
        'province',
        'city',
        'district',
        'postal_code',
        'email',
        'phone',
        'website',
    ];

    /**
     * Get the users for the company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the chart of accounts for the company.
     */
    public function accountancyChartOfAccounts()
    {
        return $this->hasMany(AccountancyChartOfAccount::class, 'accountancy_company_id');
    }

    // Note: Journal entries, customers, and suppliers are not directly linked to companies
    // via foreign keys in the current database schema. If needed, these relationships
    // would require adding accountancy_company_id columns to those tables.
}
