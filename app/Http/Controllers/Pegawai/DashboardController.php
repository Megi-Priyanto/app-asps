<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pegawai $pegawai */
        $pegawai    = Auth::guard('pegawai')->user();
        $allLaporan = $pegawai->laporan()->with('aspirasi')->get();

        $stats = [
            'total'    => $allLaporan->count(),
            'menunggu' => $allLaporan->filter(fn($l) => !$l->aspirasi || $l->aspirasi->status === 'menunggu')->count(),
            'proses'   => $allLaporan->filter(fn($l) => $l->aspirasi && $l->aspirasi->status === 'proses')->count(),
            'selesai'  => $allLaporan->filter(fn($l) => $l->aspirasi && $l->aspirasi->status === 'selesai')->count(),
        ];

        $laporanTerbaru = $pegawai->laporan()
            ->with(['kategori', 'aspirasi'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->status = $item->aspirasi ? $item->aspirasi->status : 'menunggu';
                return $item;
            });

        return view('pegawai.dashboard', compact('stats', 'laporanTerbaru'));
    }
}