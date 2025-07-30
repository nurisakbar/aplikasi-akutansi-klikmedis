<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBankTransaction extends Model
{
    use HasFactory;
    
    protected $table = 'akuntansi_cash_bank_transactions';
}
