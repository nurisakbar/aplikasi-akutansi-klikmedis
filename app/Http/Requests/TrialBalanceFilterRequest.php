<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrialBalanceFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'status' => 'nullable|in:draft,posted',
        ];
    }
} 