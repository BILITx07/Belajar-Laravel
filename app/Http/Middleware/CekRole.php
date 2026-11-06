<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('loginError', 'Silakan login terlebih dahulu.');
        }

        // Jika parameter role tidak diberikan, default hanya 'superadmin'
        if (empty($roles)) {
            $roles = ['superadmin'];
        }

        $userRole = strtolower(Auth::user()->role ?? 'viewer');
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            return redirect('/dashboard')->with('error', 'Akses ditolak: Menu ini hanya dapat diakses oleh ' . implode('/', array_map('ucwords', $roles)) . '.');
        }

        return $next($request);
    }
}

