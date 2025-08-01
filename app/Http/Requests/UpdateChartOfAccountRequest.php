<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateChartOfAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = Auth::user()->accountancy_company_id;

        $rules = [
            'name' => 'required|string|max:100',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'required|in:current_asset,fixed_asset,other_asset,current_liability,long_term_liability,equity,operating_revenue,other_revenue,operating_expense,other_expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];

                    // Add unique constraint only if user has accountancy_company_id
        if ($companyId) {
            $rules['code'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('accountancy_chart_of_accounts', 'code')
                    ->ignore($this->route('chart_of_account'))
                    ->where('accountancy_company_id', $companyId),
            ];
            $rules['parent_id'] = 'nullable|uuid|exists:accountancy_chart_of_accounts,id,accountancy_company_id,' . $companyId;
        } else {
            $rules['code'] = [
                'required',
                'string',
                'max:20',
                Rule::unique('accountancy_chart_of_accounts', 'code')
                    ->ignore($this->route('chart_of_account')),
            ];
            $rules['parent_id'] = 'nullable|uuid|exists:accountancy_chart_of_accounts,id';
        }

        return $rules;
    }
}
