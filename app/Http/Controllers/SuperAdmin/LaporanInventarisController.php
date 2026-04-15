<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use App\Models\PerbaikanBarang;
use App\Models\KategoriBarang;

class LaporanInventarisController extends Controller
{
    public function index()
    {
        $totalBarang     = Barang::count();
        $barangBaik      = Barang::where('kondisi', 'Baik')->count();
        $barangRusak     = Barang::where('kondisi', '!=', 'Baik')->count();
        $barangPinjaman  = Barang::where('is_pinjaman', true)->count();

        $totalPeminjaman      = PeminjamanBarang::count();
        $peminjamanAktif      = PeminjamanBarang::aktif()->count();
        $peminjamanMenunggu   = PeminjamanBarang::menunggu()->count();
        $peminjamanTerlambat  = PeminjamanBarang::terlambat()->count();

        $totalPerbaikan   = PerbaikanBarang::count();
        $perbaikanProses  = PerbaikanBarang::whereIn('status', ['Menunggu', 'Dalam Perbaikan'])->count();
        $perbaikanSelesai = PerbaikanBarang::where('status', 'Selesai')->count();

        // Barang paling sering dipinjam
        $barangPopuler = Barang::withCount('peminjamanBarangs')
            ->orderByDesc('peminjaman_barangs_count')
            ->take(5)->get();

        // Perbaikan terbaru
        $perbaikanTerbaru = PerbaikanBarang::with(['barang', 'peminjaman'])
            ->latest()->take(5)->get();

        // Peminjaman terbaru
        $peminjamanTerbaru = PeminjamanBarang::with('barang')
            ->latest()->take(5)->get();

        return view('superadmin.inventaris.index', compact(
            'totalBarang', 'barangBaik', 'barangRusak', 'barangPinjaman',
            'totalPeminjaman', 'peminjamanAktif', 'peminjamanMenunggu', 'peminjamanTerlambat',
            'totalPerbaikan', 'perbaikanProses', 'perbaikanSelesai',
            'barangPopuler', 'perbaikanTerbaru', 'peminjamanTerbaru'
        ));
    }
}
