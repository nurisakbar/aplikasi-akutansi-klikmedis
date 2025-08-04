<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreChartOfAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = Auth::user();
        $companyId = $user->accountancy_company_id;

        $rules = [
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:3',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'required|in:current_asset,fixed_asset,other_asset,current_liability,long_term_liability,equity,operating_revenue,other_revenue,operating_expense,other_expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        // Add unique constraint and parent_id validation
        $rules['code'] .= '|unique:accountancy_chart_of_accounts,code';
        $rules['parent_id'] = 'nullable|uuid|exists:accountancy_chart_of_accounts,id';

        // Remove parent_id validation if it's empty
        if (empty($this->input('parent_id'))) {
            unset($rules['parent_id']);
        }

        return $rules;
    }
}
