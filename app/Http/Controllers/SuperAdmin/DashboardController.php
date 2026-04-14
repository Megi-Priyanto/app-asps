<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\LaporanPengaduan;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total keseluruhan data
        $totalSiswa = Siswa::count();
        $totalLaporan = LaporanPengaduan::count();

        // Menghitung laporan dengan status 'proses' melalui relasi 'aspirasi'
        $laporanProses = LaporanPengaduan::whereHas('aspirasi', function ($q) {
            $q->where('status', 'proses');
        })->count();

        // Menghitung laporan dengan status 'selesai' melalui relasi 'aspirasi'
        $laporanSelesai = LaporanPengaduan::whereHas('aspirasi', function ($q) {
            $q->where('status', 'selesai');
        })->count();

        // Mengambil 5 laporan terbaru beserta relasinya
        $laporanTerbaru = LaporanPengaduan::with(['siswa', 'kategori', 'aspirasi'])
            ->latest()
            ->take(5)
            ->get();

        // Grafik Donat Kategori
        $kategoriSebaran = \Illuminate\Support\Facades\DB::table('laporan_pengaduans')
            ->join('kategoris', 'laporan_pengaduans.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.nama_kategori as kategori', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('kategoris.nama_kategori')
            ->get();

        return view('superadmin.dashboard', compact(
            'totalSiswa',
            'totalLaporan',
            'laporanProses',
            'laporanSelesai',
            'laporanTerbaru',
            'kategoriSebaran'
        ));
    }
}
