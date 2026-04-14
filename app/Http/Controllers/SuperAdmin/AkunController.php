<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('superadmin')->user();
        return view('superadmin.akun', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('superadmin')->user();

        // Validasi input profil
        $request->validate([
            'nama' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:super_admins,username,' . $admin->id,
        ]);

        // Update data nama dan username
        $admin->update([
            'nama' => $request->nama,
            'username' => $request->username,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input password
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|max:10|confirmed',
        ]);

        $admin = Auth::guard('superadmin')->user();

        // Cek apakah password lama yang diinput sesuai dengan di database
        if (!Hash::check($request->password_lama, $admin->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai',
            ]);
        }

        // Update password baru dengan enkripsi Hash
        $admin->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }
}
