<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountancyExpense extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'type', 'document_number', 'date', 'amount', 'status', 'description'
    ];
}
