<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\LaporanPengaduan;
use App\Models\Guru;
use App\Models\Pegawai;
use App\Models\Admin;
use App\Models\Lokasi;
use App\Models\TanggapanAplikasi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalPegawai = Pegawai::count();
        $totalAdmin = Admin::count();
        $totalLokasi = Lokasi::count();
        $totalTanggapan = TanggapanAplikasi::count();

        return view('superadmin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalPegawai',
            'totalAdmin',
            'totalLokasi',
            'totalTanggapan'
        ));
    }
}
