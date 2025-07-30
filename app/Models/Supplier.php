<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Supplier extends Model
{
    use HasUuids;

    protected $table = 'akuntansi_suppliers';
    protected $fillable = [
        'name', 'email'
    ];
} 