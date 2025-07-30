<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountsReceivableFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|uuid|exists:customers,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'status' => 'nullable|in:unpaid,paid',
        ];
    }
} 