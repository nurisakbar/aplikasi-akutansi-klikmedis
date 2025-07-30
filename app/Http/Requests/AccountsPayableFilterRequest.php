<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountsPayableFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'nullable|uuid|exists:suppliers,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'status' => 'nullable|in:unpaid,paid',
        ];
    }
} 