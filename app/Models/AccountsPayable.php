<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountsPayable extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'supplier_id', 'date', 'due_date', 'amount', 'status', 'description'
    ];

    public function accountancySupplier()
    {
        return $this->belongsTo(AccountancySupplier::class, 'supplier_id');
    }

    public function accountsPayablePayments()
    {
        return $this->hasMany(AccountsPayablePayment::class, 'accounts_payable_id');
    }
}
