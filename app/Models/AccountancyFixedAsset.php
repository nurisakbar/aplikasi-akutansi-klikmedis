<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountancyFixedAsset extends Model
{
    use HasFactory;
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'code', 'name', 'category', 'acquisition_date', 'acquisition_value', 'useful_life', 'depreciation_method', 'residual_value', 'description'
    ];
}
