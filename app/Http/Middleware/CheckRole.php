<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin') or middleware('role:admin,pimpinan')
     */
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
