<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
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
    public function chartOfAccounts()
    {
        return $this->hasMany(ChartOfAccount::class);
    }

    /**
     * Get the journal entries for the company.
     */
    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Get the customers for the company.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the suppliers for the company.
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
