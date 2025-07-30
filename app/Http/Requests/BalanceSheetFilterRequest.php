<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BalanceSheetFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_to' => 'nullable|date',
            'status' => 'nullable|in:draft,posted',
        ];
    }
} 