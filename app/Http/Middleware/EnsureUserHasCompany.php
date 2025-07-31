<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Superadmin tidak perlu company_id
            if ($user->hasRole('superadmin')) {
                return $next($request);
            }

            // User lain harus memiliki company_id
            if (!$user->company_id) {
                Auth::logout();
                return redirect()->route('auth.login')
                    ->with('error', 'Akun Anda tidak terkait dengan perusahaan manapun. Silakan hubungi administrator.');
            }
        }

        return $next($request);
    }
}
