<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountsReceivablePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ];
    }
} 