<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Pegawai;
use App\Models\LaporanPengaduan;
use App\Models\Lokasi;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use App\Models\PerbaikanBarang;
use App\Models\KategoriBarang;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $admin      = Auth::guard('admin')->user();
        $adminLokasi = $admin->lokasi_id;
        $namaLokasi  = Lokasi::find($adminLokasi)->nama_lokasi ?? '';

        // ── Stat Pengguna ────────────────────────────────────────────────
        $totalSiswa   = Siswa::count();
        $totalGuru    = Guru::count();
        $totalPegawai = Pegawai::count();

        // ── Stat Laporan (di lokasi admin) ───────────────────────────────
        $baseQuery = fn() => LaporanPengaduan::where('lokasi', $namaLokasi);

        $totalLaporan  = $baseQuery()->count();
        $laporanBaru   = $baseQuery()->whereDoesntHave('aspirasi')->count();
        $laporanProses = $baseQuery()->whereHas('aspirasi', fn($q) => $q->where('status', 'proses'))->count();
        $laporanSelesai= $baseQuery()->whereHas('aspirasi', fn($q) => $q->where('status', 'selesai'))->count();

        // ── Stat Sarpras (di lokasi admin) ──────────────────────────────
        $totalBarang       = Barang::where('lokasi_id', $adminLokasi)->count();
        $totalPeminjaman   = PeminjamanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))->count();
        $peminjamanAktif   = PeminjamanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))->aktif()->count();
        $peminjamanMenunggu= PeminjamanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))->menunggu()->count();
        $totalPerbaikan    = PerbaikanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))->count();
        $perbaikanBerjalan = PerbaikanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))->belumSelesai()->count();
        $totalBiayaPerbaikan = PerbaikanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))
            ->where('status', 'Selesai')->sum('biaya_perbaikan');

        // ── Grafik Donat: Sebaran Laporan per Kategori ──────────────────
        $kategoriSebaran = DB::table('laporan_pengaduans')
            ->join('kategori_aspirasis', 'laporan_pengaduans.kategori_aspirasi_id', '=', 'kategori_aspirasis.id')
            ->where('laporan_pengaduans.lokasi', $namaLokasi)
            ->select('kategori_aspirasis.nama_kategori as kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori_aspirasis.nama_kategori')
            ->get();

        // ── Grafik Batang: Tren Peminjaman + Laporan per Bulan ──────────
        $bulanLabels = [];
        $bulanDataPmj = [];
        $bulanDataLap = [];

        $peminjamanPerBulan = PeminjamanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('YEAR(created_at) as tahun'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')->orderBy('tahun')->orderBy('bulan')->get();

        $laporanPerBulan = LaporanPengaduan::where('lokasi', $namaLokasi)
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('YEAR(created_at) as tahun'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')->orderBy('tahun')->orderBy('bulan')->get();

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bulanLabels[] = $date->locale('id')->isoFormat('MMM YY');

            $pmj = $peminjamanPerBulan->first(fn($r) => $r->bulan == $date->month && $r->tahun == $date->year);
            $bulanDataPmj[] = $pmj ? $pmj->total : 0;

            $lap = $laporanPerBulan->first(fn($r) => $r->bulan == $date->month && $r->tahun == $date->year);
            $bulanDataLap[] = $lap ? $lap->total : 0;
        }

        // ── Grafik Donat: Status Peminjaman ─────────────────────────────
        $statusPeminjaman = PeminjamanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // ── Grafik Batang: Barang per Kategori ──────────────────────────
        $barangPerKategori = KategoriBarang::withCount(['barangs' => fn($q) => $q->where('lokasi_id', $adminLokasi)])
            ->having('barangs_count', '>', 0)
            ->orderByDesc('barangs_count')
            ->take(6)
            ->get();

        // ── Aktivitas Terbaru ────────────────────────────────────────────
        $laporanTerbaru = $baseQuery()
            ->with(['reporter', 'siswa', 'kategoriAspirasi', 'aspirasi'])
            ->latest()->take(5)->get();

        $peminjamanTerbaru = PeminjamanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))
            ->with(['barang', 'borrower'])
            ->latest()->take(5)->get();

        $perbaikanTerbaru = PerbaikanBarang::whereHas('barang', fn($q) => $q->where('lokasi_id', $adminLokasi))
            ->with(['barang'])
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            // identitas
            'admin', 'namaLokasi',
            // stat pengguna
            'totalSiswa', 'totalGuru', 'totalPegawai',
            // stat laporan
            'totalLaporan', 'laporanBaru', 'laporanProses', 'laporanSelesai',
            // stat sarpras
            'totalBarang', 'totalPeminjaman', 'peminjamanAktif', 'peminjamanMenunggu',
            'totalPerbaikan', 'perbaikanBerjalan', 'totalBiayaPerbaikan',
            // grafik
            'kategoriSebaran', 'bulanLabels', 'bulanDataPmj', 'bulanDataLap',
            'statusPeminjaman', 'barangPerKategori',
            // aktivitas
            'laporanTerbaru', 'peminjamanTerbaru', 'perbaikanTerbaru'
        ));
    }
}
