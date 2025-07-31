<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_province' => 'nullable|string|max:100',
            'company_city' => 'nullable|string|max:100',
            'company_district' => 'nullable|string|max:100',
            'company_postal_code' => 'nullable|string|max:10',
            'company_email' => 'required|email|unique:companies,email',
            'company_phone' => 'nullable|string|max:20',
            'company_website' => 'nullable|url|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
