<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response
    {
        // Belum login sebagai customer
        if (!Auth::guard('customer')->check()) {

            return redirect()
                ->route('customer.showLogin');

        }

        // Bukan role customer
        if (
            Auth::guard('customer')->user()->role
            !== 'customer'
        ) {

            Auth::guard('customer')->logout();

            return redirect()
                ->route('customer.showLogin');

        }

        return $next($request);
    }
}