<?php

namespace App\Http\Requests\AccountancyCustomer;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CustomerStatus;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')->id;
        
        return [
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:accountancy_customers,email,' . $customerId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'npwp' => 'nullable|string|max:20|unique:accountancy_customers,npwp,' . $customerId,
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:' . implode(',', CustomerStatus::values()),
            'contact_person' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama customer wajib diisi.',
            'name.max' => 'Nama customer maksimal 255 karakter.',
            'company_name.max' => 'Nama perusahaan maksimal 255 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'npwp.max' => 'NPWP maksimal 20 karakter.',
            'npwp.unique' => 'NPWP sudah terdaftar.',
            'credit_limit.numeric' => 'Batas kredit harus berupa angka.',
            'credit_limit.min' => 'Batas kredit minimal 0.',
            'status.in' => 'Status tidak valid.',
            'contact_person.max' => 'Contact person maksimal 255 karakter.',
            'payment_terms.max' => 'Syarat pembayaran maksimal 100 karakter.',
        ];
    }
} 