<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountsReceivableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|uuid|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'amount' => 'required|numeric|min:1',
            'status' => 'nullable|in:unpaid,paid',
            'description' => 'nullable|string',
        ];
    }
} 