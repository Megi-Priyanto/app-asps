<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('siswa.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nis'      => 'required|string',
            'password' => 'required|string',
        ], [
            'nis.required'      => 'NIS wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::guard('siswa')->attempt([
            'nis'      => $request->nis,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended(route('siswa.dashboard'));
        }

        return back()
            ->withInput($request->only('nis'))
            ->withErrors(['nis' => 'NIS atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('siswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
