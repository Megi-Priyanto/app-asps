<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('superadmin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::guard('superadmin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()
                ->route('superadmin.dashboard')
                ->with('success', 'Berhasil login sebagai Super Admin');
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('superadmin.login')
            ->with('success', 'Berhasil logout');
    }
}
