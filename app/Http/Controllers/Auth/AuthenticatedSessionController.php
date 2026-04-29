<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 🔥 LOGIN DULU
        $request->authenticate();

        // 🔥 CEK ROLE ADMIN
        if (auth()->user()->role !== 'admin') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akses hanya untuk admin',
            ]);
        }

        // 🔥 REGENERATE SESSION
        $request->session()->regenerate();

        // 🔥 REDIRECT KE DASHBOARD
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}