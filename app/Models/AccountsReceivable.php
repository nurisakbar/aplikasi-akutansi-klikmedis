<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountsReceivable extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'accountancy_accounts_receivable';

    protected $fillable = [
        'customer_id', 'date', 'due_date', 'amount', 'status', 'description'
    ];

    public function accountancyCustomer()
    {
        return $this->belongsTo(AccountancyCustomer::class, 'customer_id');
    }

    public function accountsReceivablePayments()
    {
        return $this->hasMany(AccountsReceivablePayment::class, 'accounts_receivable_id');
    }
}
