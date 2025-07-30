<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable|string',
            'status' => 'nullable|in:unpaid,paid',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ];
    }
} 