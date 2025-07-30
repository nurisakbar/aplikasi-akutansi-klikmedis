<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountsPayableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|uuid|exists:suppliers,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'amount' => 'required|numeric|min:1',
            'status' => 'nullable|in:unpaid,paid',
            'description' => 'nullable|string',
        ];
    }
} 