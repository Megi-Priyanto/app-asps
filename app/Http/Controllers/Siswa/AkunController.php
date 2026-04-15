<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;


class AkunController extends Controller
{
    public function edit()
    {
        return view('siswa.akun', [
            'siswa' => Auth::guard('siswa')->user()
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        $request->validate([
            'nama'  => 'required|string|max:50',
            'kelas' => 'required|string|max:20',
            'foto'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama'  => $request->input('nama'),
            'kelas' => $request->input('kelas'),
        ];

        // Handle Upload Foto
        if ($request->hasFile('foto')) {
            if ($siswa->foto) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $siswa->nis . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profiles', $filename, 'public');
            $data['foto'] = $path;
        }

        $siswa->update($data);

        return redirect()
            ->route('siswa.akun.edit')
            ->with('success', 'Data akun berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->max(10)],
        ]);

        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        if (!Hash::check($request->password_lama, $siswa->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $siswa->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
