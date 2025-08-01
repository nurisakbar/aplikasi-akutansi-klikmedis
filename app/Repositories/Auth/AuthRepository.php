<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Models\AccountancyCompany;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'accountancy_company_id' => $data['company_id'],
        ]);
    }

    public function findUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function createCompany(array $data)
    {
        return AccountancyCompany::create([
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'province' => $data['province'] ?? null,
            'city' => $data['city'] ?? null,
            'district' => $data['district'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'website' => $data['website'] ?? null,
        ]);
    }
}
