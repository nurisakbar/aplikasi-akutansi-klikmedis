<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(LoginRequest $request)
    {
        try {
            if ($request->ajax()) {
                $result = $this->authService->login($request->only(['email', 'password']));

                if ($result['success']) {
                    return response()->json([
                        'success' => true,
                        'message' => $result['message'] ?? 'Login berhasil',
                        'redirect' => $this->getRedirectUrl()
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'] ?? 'Email atau password salah'
                    ], 422);
                }
            }

            // Non-AJAX fallback
            $result = $this->authService->login($request->only(['email', 'password']));

            if ($result['success']) {
                return redirect()->intended($this->getRedirectUrl())
                    ->with('success', $result['message'] ?? 'Login berhasil');
            }

            return back()->withErrors([
                'email' => $result['message'] ?? 'Email atau password salah',
            ])->withInput($request->only('email'));
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
                    'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat login: ' . $e->getMessage()
            ])->withInput($request->only('email'));
        }
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole()
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin')) {
            return redirect()->route('chart-of-accounts.index');
        }

        return redirect()->route('chart-of-accounts.index');
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl()
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin')) {
            return route('chart-of-accounts.index');
        }

        return route('chart-of-accounts.index');
    }
}
