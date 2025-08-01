<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountsPayablePayment extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'accounts_payable_id', 'date', 'amount', 'description'
    ];

    public function accountsPayable()
    {
        return $this->belongsTo(AccountsPayable::class, 'accounts_payable_id');
    }
}
