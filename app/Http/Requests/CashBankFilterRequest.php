<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashBankFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => 'nullable|uuid|exists:akuntansi_chart_of_accounts,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'type' => 'nullable|in:in,out,transfer',
            'status' => 'nullable|in:draft,posted',
        ];
    }
}
