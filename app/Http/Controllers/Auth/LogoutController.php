<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        try {
            $result = $this->authService->logout();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'] ?? 'Logout berhasil',
                    'redirect' => route('login')
                ]);
            }

            return redirect()->route('login')->with('success', $result['message'] ?? 'Logout berhasil');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat logout: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat logout: ' . $e->getMessage());
        }
    }
}
