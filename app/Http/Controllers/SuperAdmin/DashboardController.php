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
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use App\Models\PerbaikanBarang;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistik Pengguna ──────────────────────────────────────────
        $totalSiswa     = Siswa::count();
        $totalGuru      = Guru::count();
        $totalPegawai   = Pegawai::count();
        $totalAdmin     = Admin::count();
        $totalLokasi    = Lokasi::count();
        $totalTanggapan = TanggapanAplikasi::count();

        // ── Statistik Sarpras ───────────────────────────────────────────
        $totalBarang          = Barang::count();
        $totalPeminjaman      = PeminjamanBarang::count();
        $peminjamanAktif      = PeminjamanBarang::aktif()->count();
        $peminjamanMenunggu   = PeminjamanBarang::menunggu()->count();
        $totalPerbaikan       = PerbaikanBarang::count();
        $perbaikanBerjalan    = PerbaikanBarang::belumSelesai()->count();

        // Total biaya perbaikan
        $totalBiayaPerbaikan  = PerbaikanBarang::where('status', 'Selesai')->sum('biaya_perbaikan');

        // ── Grafik Batang: Peminjaman per Bulan (12 bulan terakhir) ─────
        $peminjamanPerBulan = PeminjamanBarang::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // Buat label & data lengkap 12 bulan terakhir
        $bulanLabels    = [];
        $bulanData      = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bulanLabels[] = $date->locale('id')->isoFormat('MMM YY');
            $found = $peminjamanPerBulan->first(fn($r) =>
                $r->bulan == $date->month && $r->tahun == $date->year
            );
            $bulanData[] = $found ? $found->total : 0;
        }

        // ── Grafik Batang: Laporan per Bulan (12 bulan terakhir) ────────
        $laporanPerBulan = LaporanPengaduan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $laporanData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $found = $laporanPerBulan->first(fn($r) =>
                $r->bulan == $date->month && $r->tahun == $date->year
            );
            $laporanData[] = $found ? $found->total : 0;
        }

        // ── Grafik Donat: Status Peminjaman ─────────────────────────────
        $statusPeminjaman = PeminjamanBarang::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // ── Grafik Donat: Barang per Kategori ───────────────────────────
        $barangPerKategori = KategoriBarang::withCount('barangs')
            ->orderByDesc('barangs_count')
            ->take(6)
            ->get();

        // ── Grafik Batang: Pengguna per Peran ───────────────────────────
        $penggunaPerPeran = [
            'Siswa'   => $totalSiswa,
            'Guru'    => $totalGuru,
            'Pegawai' => $totalPegawai,
            'Admin'   => $totalAdmin,
        ];

        // ── Aktivitas Terbaru ────────────────────────────────────────────
        $peminjamanTerbaru = PeminjamanBarang::with(['barang', 'borrower'])
            ->latest()
            ->take(5)
            ->get();

        $perbaikanTerbaru = PerbaikanBarang::with(['barang'])
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            // stat pengguna
            'totalSiswa', 'totalGuru', 'totalPegawai', 'totalAdmin',
            'totalLokasi', 'totalTanggapan',
            // stat sarpras
            'totalBarang', 'totalPeminjaman', 'peminjamanAktif',
            'peminjamanMenunggu', 'totalPerbaikan', 'perbaikanBerjalan',
            'totalBiayaPerbaikan',
            // grafik
            'bulanLabels', 'bulanData', 'laporanData',
            'statusPeminjaman', 'barangPerKategori', 'penggunaPerPeran',
            // aktivitas
            'peminjamanTerbaru', 'perbaikanTerbaru'
        ));
    }
}
