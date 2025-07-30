<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Expense extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'akuntansi_expenses';
    protected $fillable = [
        'type', 'document_number', 'date', 'amount', 'status', 'description'
    ];
} 