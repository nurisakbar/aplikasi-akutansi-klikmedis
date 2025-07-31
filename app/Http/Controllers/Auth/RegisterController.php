<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show the register form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('chart-of-accounts.index');
        }
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(RegisterRequest $request)
    {
        try {
            if ($request->ajax()) {
                // Prepare company data using database column names
                $companyData = [
                    'name' => $request->company_name,
                    'address' => $request->company_address,
                    'province' => $request->company_province,
                    'city' => $request->company_city,
                    'district' => $request->company_district,
                    'postal_code' => $request->company_postal_code,
                    'email' => $request->company_email,
                    'phone' => $request->company_phone,
                    'website' => $request->company_website,
                ];

                // Prepare user data using database column names
                $userData = [
                    'name' => $request->owner_name,
                    'email' => $request->owner_email,
                    'password' => $request->password,
                ];

                $result = $this->authService->registerCompany($companyData, $userData);

                if ($result['success']) {
                    // Auto login after successful registration
                    Auth::login($result['user']);

                    return response()->json([
                        'success' => true,
                        'message' => 'Pendaftaran berhasil! Selamat datang di sistem akuntansi kami.',
                        'redirect' => route('chart-of-accounts.index')
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message']
                    ], 422);
                }
            }

            // Non-AJAX fallback
            $companyData = [
                'name' => $request->company_name,
                'address' => $request->company_address,
                'province' => $request->company_province,
                'city' => $request->company_city,
                'district' => $request->company_district,
                'postal_code' => $request->company_postal_code,
                'email' => $request->company_email,
                'phone' => $request->company_phone,
                'website' => $request->company_website,
            ];

            $userData = [
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => $request->password,
            ];

            $result = $this->authService->registerCompany($companyData, $userData);

            if ($result['success']) {
                Auth::login($result['user']);
                return redirect()->route('chart-of-accounts.index')
                    ->with('success', 'Pendaftaran berhasil! Selamat datang di sistem akuntansi kami.');
            }

            return back()->withErrors([
                'error' => $result['message']
            ])->withInput();
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
