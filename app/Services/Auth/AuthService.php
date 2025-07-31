<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthService implements AuthServiceInterface
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function registerCompany(array $companyData, array $userData)
    {
        try {
            DB::beginTransaction();

            // Create company
            $company = $this->authRepository->createCompany($companyData);

            // Create user with company_id
            $userData['company_id'] = $company->id;
            $user = $this->authRepository->createUser($userData);

            DB::commit();

            return [
                'success' => true,
                'company' => $company,
                'user' => $user
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Gagal mendaftarkan perusahaan: ' . $e->getMessage()
            ];
        }
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            return [
                'success' => true,
                'user' => Auth::user()
            ];
        }

        return [
            'success' => false,
            'message' => 'Email atau password salah'
        ];
    }

    public function logout()
    {
        Auth::logout();
        return [
            'success' => true,
            'message' => 'Berhasil logout'
        ];
    }
}
