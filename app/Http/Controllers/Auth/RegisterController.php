<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * Show the registration form
     */
    public function showRegisterForm(): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect()->route('chart-of-accounts.index');
        }

        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(RegisterRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $registrationData = $this->prepareRegistrationData($request);
            $result = $this->authService->registerCompany(
                $registrationData['company'],
                $registrationData['user']
            );

            if (!$result['success']) {
                return $this->handleRegistrationFailure($request, $result['message']);
            }

            // Auto login after successful registration
            Auth::login($result['user']);

            return $this->handleRegistrationSuccess($request);

        } catch (ValidationException $e) {
            return $this->handleValidationException($request, $e);
        } catch (\Exception $e) {
            return $this->handleGeneralException($request, $e);
        }
    }

    /**
     * Prepare registration data from request
     */
    private function prepareRegistrationData(RegisterRequest $request): array
    {
        return [
            'company' => [
                'name' => $request->company_name,
                'address' => $request->company_address,
                'province' => $request->company_province,
                'city' => $request->company_city,
                'district' => $request->company_district,
                'postal_code' => $request->company_postal_code,
                'email' => $request->company_email,
                'phone' => $request->company_phone,
                'website' => $request->company_website,
            ],
            'user' => [
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => $request->password,
            ]
        ];
    }

    /**
     * Handle successful registration
     */
    private function handleRegistrationSuccess(Request $request): JsonResponse|RedirectResponse
    {
        $successMessage = 'Selamat datang di Sistem Akuntansi Klik Medis! Pendaftaran perusahaan Anda telah berhasil dan siap digunakan.';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('chart-of-accounts.index')
            ]);
        }

        return redirect()->route('chart-of-accounts.index')
            ->with('success', $successMessage);
    }

    /**
     * Handle registration failure
     */
    private function handleRegistrationFailure(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 422);
        }

        return back()->withErrors(['error' => $message])->withInput();
    }

    /**
     * Handle validation exceptions
     */
    private function handleValidationException(Request $request, ValidationException $e): JsonResponse
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        }

        throw $e;
    }

    /**
     * Handle general exceptions
     */
    private function handleGeneralException(Request $request, \Exception $e): JsonResponse|RedirectResponse
    {
        $errorMessage = 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage();

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }

        return back()->withErrors(['error' => $errorMessage])->withInput();
    }
}
