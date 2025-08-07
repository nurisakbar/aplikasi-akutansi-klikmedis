<?php

namespace App\Http\Requests\AccountancyCashBank;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CashBankTransactionType;
use App\Enums\CashBankTransactionStatus;

class StoreCashBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'accountancy_chart_of_account_id' => 'required|uuid|exists:accountancy_chart_of_accounts,id',
            'date' => 'required|date',
            'type' => 'required|in:' . implode(',', CashBankTransactionType::values()),
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', CashBankTransactionStatus::values()),
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'reference' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'accountancy_chart_of_account_id.required' => 'Akun harus dipilih.',
            'accountancy_chart_of_account_id.exists' => 'Akun yang dipilih tidak valid.',
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'type.required' => 'Tipe transaksi harus dipilih.',
            'type.in' => 'Tipe transaksi tidak valid.',
            'amount.required' => 'Nominal harus diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Nominal minimal 1.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
            'bukti.file' => 'Bukti harus berupa file.',
            'bukti.mimes' => 'Bukti harus berformat JPG, PNG, atau PDF.',
            'bukti.max' => 'Ukuran bukti maksimal 2MB.',
        ];
    }
} 