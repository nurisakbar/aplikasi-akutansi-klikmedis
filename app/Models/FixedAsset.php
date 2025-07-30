<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FixedAsset extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'akuntansi_fixed_assets';
    protected $fillable = [
        'code', 'name', 'category', 'acquisition_date', 'acquisition_value', 'useful_life', 'depreciation_method', 'residual_value', 'description'
    ];
} 