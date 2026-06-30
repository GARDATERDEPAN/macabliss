<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        // Kalau sudah login sebagai customer
        if (Auth::guard('customer')->check()) {

            return redirect()
                ->route('customer.beranda');

        }

        return view('customer.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $user = User::where('phone', $request->phone)
            ->where('role', 'customer')
            ->first();

        if (!$user) {

            $user = User::create([
                'name'     => $request->name,
                'phone'    => $request->phone,
                'role'     => 'customer',
                'email'    => 'customer_' . time() . '@macabliss.com',
                'password' => bcrypt('customer-login'),
            ]);

        } else {

            if ($user->name !== $request->name) {

                $user->update([
                    'name' => $request->name
                ]);

            }

        }

        // LOGIN KE GUARD CUSTOMER
        Auth::guard('customer')->login($user);

        return redirect()
            ->route('customer.beranda');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        return redirect()
            ->route('customer.showLogin');
    }
}