<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FixedAssetFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => 'nullable|string',
            'name' => 'nullable|string',
            'acquisition_date_from' => 'nullable|date',
            'acquisition_date_to' => 'nullable|date',
        ];
    }
} 