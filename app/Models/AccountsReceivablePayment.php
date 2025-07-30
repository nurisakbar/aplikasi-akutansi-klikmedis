<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountsReceivablePayment extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'akuntansi_accounts_receivable_payments';
    protected $fillable = [
        'accounts_receivable_id', 'date', 'amount', 'description'
    ];

    public function receivable()
    {
        return $this->belongsTo(AccountsReceivable::class, 'accounts_receivable_id');
    }
} 