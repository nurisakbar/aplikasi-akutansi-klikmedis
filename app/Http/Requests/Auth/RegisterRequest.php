<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
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
            // Company fields
            'company_name' => 'required|string|min:3|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_province' => 'nullable|string|max:100',
            'company_city' => 'nullable|string|max:100',
            'company_district' => 'nullable|string|max:100',
            'company_postal_code' => 'nullable|numeric|digits_between:1,10',
            'company_email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:accountancy_companies,email'
            ],
            'company_phone' => 'nullable|string|max:20',
            'company_website' => 'nullable|url|max:255',

            // Owner fields
            'owner_name' => 'required|string|min:2|max:255',
            'owner_email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'company_name' => 'nama perusahaan',
            'company_address' => 'alamat perusahaan',
            'company_province' => 'provinsi',
            'company_city' => 'kota/kabupaten',
            'company_district' => 'kecamatan',
            'company_postal_code' => 'kode pos',
            'company_email' => 'email perusahaan',
            'company_phone' => 'nomor telepon',
            'company_website' => 'website',
            'owner_name' => 'nama pemilik',
            'owner_email' => 'email pemilik',
            'password' => 'password',
        ];
    }
}
