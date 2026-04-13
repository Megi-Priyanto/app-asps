<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        return view('pegawai.akun', [
            'pegawai' => Auth::guard('pegawai')->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Pegawai $pegawai */
        $pegawai = Auth::guard('pegawai')->user();

        $request->validate([
            'nama'    => 'required|string|max:100',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $pegawai->update([
            'nama'    => $request->input('nama'),
            'jabatan' => $request->input('jabatan'),
        ]);

        return redirect()
            ->route('pegawai.akun')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\Pegawai $pegawai */
        $pegawai = Auth::guard('pegawai')->user();

        $request->validate([
            'password_lama'         => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (! Hash::check($request->password_lama, $pegawai->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $pegawai->update(['password' => $request->password]);

        return redirect()
            ->route('pegawai.akun')
            ->with('success', 'Password berhasil diubah.');
    }
}
