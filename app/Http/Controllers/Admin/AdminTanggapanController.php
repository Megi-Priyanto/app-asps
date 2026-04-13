<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TanggapanAplikasi;
use Illuminate\Http\Request;

class AdminTanggapanController extends Controller
{
    /**
     * Menampilkan semua tanggapan dari seluruh pengguna.
     */
    public function index()
    {
        $tanggapan = TanggapanAplikasi::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.tanggapan.index', compact('tanggapan'));
    }

    /**
     * Menghapus tanggapan pengguna.
     */
    public function destroy(TanggapanAplikasi $tanggapan)
    {
        $tanggapan->delete();

        return back()->with('success', 'Tanggapan pengguna berhasil dihapus.');
    }

    /**
     * Mengubah status tampil tanggapan di landing page.
     */
    public function toggleStatus(TanggapanAplikasi $tanggapan)
    {
        $tanggapan->update([
            'is_tampil' => !$tanggapan->is_tampil
        ]);

        $status = $tanggapan->is_tampil ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Tanggapan berhasil {$status} untuk landing page.");
    }
}
