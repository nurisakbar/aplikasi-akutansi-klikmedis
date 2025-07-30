<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tax extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'akuntansi_taxes';
    protected $fillable = [
        'type', 'document_number', 'date', 'amount', 'status', 'description'
    ];
} 