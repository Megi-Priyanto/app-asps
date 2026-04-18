<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\LaporanPengaduan;

class DashboardController extends Controller
{
    public function index()
    {
        $adminLokasi = \Illuminate\Support\Facades\Auth::guard('admin')->user()->lokasi_id;

        // Mengambil total keseluruhan data
        $totalSiswa = Siswa::count();
        $totalLaporan = LaporanPengaduan::where('lokasi', \App\Models\Lokasi::find($adminLokasi)->nama_lokasi ?? '')->count();

        // Menghitung laporan dengan status 'proses' melalui relasi 'aspirasi'
        $laporanProses = LaporanPengaduan::where('lokasi', \App\Models\Lokasi::find($adminLokasi)->nama_lokasi ?? '')->whereHas('aspirasi', function ($q) {
            $q->where('status', 'proses');
        })->count();

        // Menghitung laporan dengan status 'selesai' melalui relasi 'aspirasi'
        $laporanSelesai = LaporanPengaduan::where('lokasi', \App\Models\Lokasi::find($adminLokasi)->nama_lokasi ?? '')->whereHas('aspirasi', function ($q) {
            $q->where('status', 'selesai');
        })->count();

        // Mengambil 5 laporan terbaru beserta relasinya
        $laporanTerbaru = LaporanPengaduan::where('lokasi', \App\Models\Lokasi::find($adminLokasi)->nama_lokasi ?? '')->with(['siswa', 'kategoriAspirasi', 'aspirasi'])
            ->latest()
            ->take(5)
            ->get();

        // Grafik Donat Kategori
        $kategoriSebaran = \Illuminate\Support\Facades\DB::table('laporan_pengaduans')
            ->join('kategori_aspirasis', 'laporan_pengaduans.kategori_aspirasi_id', '=', 'kategori_aspirasis.id')
            ->where('laporan_pengaduans.lokasi', \App\Models\Lokasi::find($adminLokasi)->nama_lokasi ?? '')
            ->select('kategori_aspirasis.nama_kategori as kategori', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('kategori_aspirasis.nama_kategori')
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalLaporan',
            'laporanProses',
            'laporanSelesai',
            'laporanTerbaru',
            'kategoriSebaran'
        ));
    }
}
