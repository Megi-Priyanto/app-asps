<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        return view('guru.akun', [
            'guru' => Auth::guard('guru')->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Guru $guru */
        $guru = Auth::guard('guru')->user();

        $request->validate([
            'nama'    => 'required|string|max:100',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $guru->update([
            'nama'    => $request->input('nama'),
            'jabatan' => $request->input('jabatan'),
        ]);

        return redirect()
            ->route('guru.akun')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\Guru $guru */
        $guru = Auth::guard('guru')->user();

        $request->validate([
            'password_lama'         => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (! Hash::check($request->password_lama, $guru->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $guru->update(['password' => $request->password]);

        return redirect()
            ->route('guru.akun')
            ->with('success', 'Password berhasil diubah.');
    }
}
