<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountsReceivable extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'akuntansi_accounts_receivable';
    protected $fillable = [
        'customer_id', 'date', 'due_date', 'amount', 'status', 'description'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(AccountsReceivablePayment::class, 'accounts_receivable_id');
    }
} 