<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChartOfAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'required|in:current_asset,fixed_asset,other_asset,current_liability,long_term_liability,equity,operating_revenue,other_revenue,operating_expense,other_expense',
            'parent_id' => 'nullable|uuid|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
