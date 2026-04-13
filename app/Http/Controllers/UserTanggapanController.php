<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TanggapanAplikasi;
use Illuminate\Support\Facades\Auth;

class UserTanggapanController extends Controller
{
    /**
     * Menampilkan riwayat tanggapan milik user yang sedang login.
     */
    public function index()
    {
        $user = $this->getCurrentUser();
        $tanggapan = TanggapanAplikasi::where('user_type', get_class($user))
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Menentukan role untuk view (siswa, guru, pegawai)
        $role = $this->getCurrentRole();

        return view($role . '.tanggapan.index', compact('tanggapan'));
    }

    /**
     * Menyimpan tanggapan aplikasi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'catatan' => 'required|string|min:5|max:2000',
        ]);

        $user = $this->getCurrentUser();

        TanggapanAplikasi::create([
            'user_id'   => $user->id,
            'user_type' => get_class($user),
            'catatan'   => $request->catatan,
        ]);

        return back()->with('success', 'Tanggapan Anda berhasil dikirim. Terima kasih atas masukannya!');
    }

    /**
     * Helper untuk mendapatkan user berdasarkan guard aktif.
     */
    private function getCurrentUser()
    {
        if (Auth::guard('siswa')->check())   return Auth::guard('siswa')->user();
        if (Auth::guard('guru')->check())    return Auth::guard('guru')->user();
        if (Auth::guard('pegawai')->check()) return Auth::guard('pegawai')->user();
        
        return null;
    }

    /**
     * Helper untuk mendapatkan nama folder view berdasarkan guard.
     */
    private function getCurrentRole()
    {
        if (Auth::guard('siswa')->check())   return 'siswa';
        if (Auth::guard('guru')->check())    return 'guru';
        if (Auth::guard('pegawai')->check()) return 'pegawai';
        
        return 'siswa'; // Default
    }
}
