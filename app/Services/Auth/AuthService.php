<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Spatie\Permission\Models\Role;

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

                    // Create user with accountancy_company_id
        $userData['company_id'] = $company->id;
            $user = $this->authRepository->createUser($userData);

            // Assign company-admin role
            $companyAdminRole = Role::where('name', 'company-admin')->first();
            if ($companyAdminRole) {
                $user->assignRole($companyAdminRole);
            }

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
            $user = Auth::user();
            
            // Create Sanctum token for the user
            $token = $user->createToken('web-token')->plainTextToken;
            
            return [
                'success' => true,
                'user' => $user,
                'token' => $token,
                'message' => 'Login berhasil'
            ];
        }

        return [
            'success' => false,
            'message' => 'Email atau password salah'
        ];
    }

    public function logout()
    {
        $user = Auth::user();
        
        if ($user) {
            // Revoke all tokens for the user
            $user->tokens()->delete();
        }
        
        Auth::logout();
        
        return [
            'success' => true,
            'message' => 'Berhasil logout'
        ];
    }
}
