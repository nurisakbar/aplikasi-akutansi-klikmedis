<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountsPayable extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'akuntansi_accounts_payable';
    protected $fillable = [
        'supplier_id', 'date', 'due_date', 'amount', 'status', 'description'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payments()
    {
        return $this->hasMany(AccountsPayablePayment::class, 'accounts_payable_id');
    }
} 