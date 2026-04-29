<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🔥 CEK SUDAH LOGIN ATAU BELUM
        if (!auth()->check()) {
            return redirect('/login');
        }

        // 🔥 CEK ROLE ADMIN
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses hanya untuk admin');
        }

        return $next($request);
    }
}