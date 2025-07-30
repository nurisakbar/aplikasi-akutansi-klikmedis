<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'document_number' => 'nullable|string',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'status' => 'required|in:unpaid,paid',
            'description' => 'nullable|string',
        ];
    }
} 