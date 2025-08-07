<?php

namespace App\Http\Requests\AccountancyJournalEntry;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountancyCompany;
use App\Models\AccountancyChartOfAccount;

class UpdateJournalEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'lines' => 'required|array|min:1',
            'lines.*.chart_of_account_id' => 'required|uuid|exists:accountancy_chart_of_accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
            'lines.*.description' => 'nullable|string|max:255',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $lines = $this->input('lines', []);
            
            if (empty($lines)) {
                $validator->errors()->add('lines', 'Minimal harus ada satu baris jurnal.');
                return;
            }

            // Calculate totals
            $totalDebit = 0;
            $totalCredit = 0;
            
            foreach ($lines as $line) {
                $totalDebit += (float) ($line['debit'] ?? 0);
                $totalCredit += (float) ($line['credit'] ?? 0);
            }

            // Check if debit equals credit
            if (abs($totalDebit - $totalCredit) >= 0.01) {
                $validator->errors()->add('lines', 'Total debit dan kredit harus sama.');
            }

            // Check if chart of accounts belong to user's company
            $userCompanyId = Auth::user()->accountancy_company_id;
            
            foreach ($lines as $index => $line) {
                $chartOfAccountId = $line['chart_of_account_id'] ?? null;
                
                if ($chartOfAccountId) {
                    $chartOfAccount = AccountancyChartOfAccount::find($chartOfAccountId);
                    
                    if (!$chartOfAccount || $chartOfAccount->accountancy_company_id !== $userCompanyId) {
                        $validator->errors()->add("lines.{$index}.chart_of_account_id", 'Akun tidak valid atau tidak termasuk dalam perusahaan Anda.');
                    }
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'lines.required' => 'Baris jurnal harus diisi.',
            'lines.min' => 'Minimal harus ada satu baris jurnal.',
            'lines.*.chart_of_account_id.required' => 'Akun harus dipilih.',
            'lines.*.chart_of_account_id.exists' => 'Akun yang dipilih tidak valid.',
            'lines.*.debit.required' => 'Nilai debit harus diisi.',
            'lines.*.debit.numeric' => 'Nilai debit harus berupa angka.',
            'lines.*.debit.min' => 'Nilai debit tidak boleh negatif.',
            'lines.*.credit.required' => 'Nilai kredit harus diisi.',
            'lines.*.credit.numeric' => 'Nilai kredit harus berupa angka.',
            'lines.*.credit.min' => 'Nilai kredit tidak boleh negatif.',
        ];
    }
}
