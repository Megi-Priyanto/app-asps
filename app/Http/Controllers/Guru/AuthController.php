<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nip'      => 'required|string',
            'password' => 'required|string',
        ], [
            'nip.required'      => 'NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::guard('guru')->attempt([
            'nip'      => $request->nip,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended(route('guru.dashboard'));
        }

        return back()
            ->withInput($request->only('nip'))
            ->withErrors(['nip' => 'NIP atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('guru')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('siswa.login');
    }
}
