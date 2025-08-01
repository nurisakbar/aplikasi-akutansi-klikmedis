<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => 'required|uuid|exists:accountancy_chart_of_accounts,id',
            'date' => 'required|date',
            'type' => 'required|in:in,out,transfer',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,posted',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}
