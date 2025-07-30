<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFixedAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:fixed_assets,code',
            'name' => 'required|string',
            'category' => 'nullable|string',
            'acquisition_date' => 'required|date',
            'acquisition_value' => 'required|numeric|min:1',
            'useful_life' => 'required|integer|min:1',
            'depreciation_method' => 'required|in:straight_line,declining',
            'residual_value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }
} 