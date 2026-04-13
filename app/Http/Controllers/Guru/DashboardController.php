<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Guru $guru */
        $guru       = Auth::guard('guru')->user();
        $allLaporan = $guru->laporan()->with('aspirasi')->get();

        $stats = [
            'total'    => $allLaporan->count(),
            'menunggu' => $allLaporan->filter(fn($l) => !$l->aspirasi || $l->aspirasi->status === 'menunggu')->count(),
            'proses'   => $allLaporan->filter(fn($l) => $l->aspirasi && $l->aspirasi->status === 'proses')->count(),
            'selesai'  => $allLaporan->filter(fn($l) => $l->aspirasi && $l->aspirasi->status === 'selesai')->count(),
        ];

        $laporanTerbaru = $guru->laporan()
            ->with(['kategori', 'aspirasi'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->status = $item->aspirasi ? $item->aspirasi->status : 'menunggu';
                return $item;
            });

        return view('guru.dashboard', compact('stats', 'laporanTerbaru'));
    }
}