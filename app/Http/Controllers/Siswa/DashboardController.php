<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        // Ambil semua laporan untuk statistik
        $allLaporan = $siswa->laporan()->with('aspirasi')->get();

        $stats = [
            'total'    => $allLaporan->count(),
            'menunggu' => $allLaporan->filter(
                fn($l) =>
                !$l->aspirasi || $l->aspirasi->status === 'menunggu'
            )->count(),
            'proses'   => $allLaporan->filter(
                fn($l) =>
                $l->aspirasi && $l->aspirasi->status === 'proses'
            )->count(),
            'selesai'  => $allLaporan->filter(
                fn($l) =>
                $l->aspirasi && $l->aspirasi->status === 'selesai'
            )->count(),
        ];

        // Ambil 5 laporan terbaru untuk aktivitas di dashboard
        $laporanTerbaru = $siswa->laporan()
            ->with(['kategoriAspirasi', 'aspirasi'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->status = $item->aspirasi
                    ? $item->aspirasi->status
                    : 'menunggu';
                return $item;
            });

        return view('siswa.dashboard', compact('stats', 'laporanTerbaru'));
    }
}
