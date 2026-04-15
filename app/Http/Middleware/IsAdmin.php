<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * Hanya izinkan user dengan role = 'admin'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // Jika belum login, arahkan ke halaman login admin
            if (!auth()->check()) {
                return redirect()->route('admin.login')
                    ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
            }

            // Jika login tapi bukan admin, logout dan arahkan ke login admin
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}
