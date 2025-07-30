<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Customer extends Model
{
    use HasUuids;

    protected $table = 'akuntansi_customers';
    protected $fillable = [
        'name', 'email'
    ];
} 