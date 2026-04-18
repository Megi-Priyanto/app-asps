<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanBarangController extends Controller
{
    public function index(Request $request)
    {
        $admin   = Auth::guard('admin')->user();
        $lokasi  = $admin->lokasi;

        // Hanya barang di lokasi admin
        $query = Barang::with('kategoriBarang', 'lokasi')
            ->where('lokasi_id', $admin->lokasi_id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_barang', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_barang_id', $request->kategori);
        }

        $barangs   = $query->latest()->paginate(15)->withQueryString();
        $kategoris = KategoriBarang::all();

        // Statistik di lokasi admin
        $statsQuery   = Barang::where('lokasi_id', $admin->lokasi_id);
        $totalBarang  = (clone $statsQuery)->count();
        $barangBaik   = (clone $statsQuery)->where('kondisi', 'Baik')->count();
        $barangRusak  = (clone $statsQuery)->where('kondisi', '!=', 'Baik')->count();
        $barangPinjam = (clone $statsQuery)->where('is_pinjaman', true)->count();

        return view('admin.laporan-barang.index', compact(
            'barangs', 'kategoris', 'lokasi',
            'totalBarang', 'barangBaik', 'barangRusak', 'barangPinjam'
        ));
    }

    public function cetak(Request $request)
    {
        $admin  = Auth::guard('admin')->user();
        $lokasi = $admin->lokasi;

        $query = Barang::with('kategoriBarang')
            ->where('lokasi_id', $admin->lokasi_id);

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_barang_id', $request->kategori);
        }

        $barangs = $query->orderBy('kode_barang')->get();

        return view('admin.laporan-barang.cetak', compact('barangs', 'lokasi'));
    }
}
