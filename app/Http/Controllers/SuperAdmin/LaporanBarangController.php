<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LaporanBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategoriBarang', 'lokasi');

        // Filter per lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi_id', $request->lokasi);
        }

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
        $lokasis   = Lokasi::withCount('barangs')->get();

        // Statistik global
        $totalBarang  = Barang::count();
        $barangBaik   = Barang::where('kondisi', 'Baik')->count();
        $barangRusak  = Barang::where('kondisi', '!=', 'Baik')->count();
        $barangPinjam = Barang::where('is_pinjaman', true)->count();

        // Ringkasan per lokasi
        $ringkasanLokasi = Lokasi::withCount([
            'barangs as total_barang',
            'barangs as barang_baik' => function ($q) {
                $q->where('kondisi', 'Baik');
            },
            'barangs as barang_rusak' => function ($q) {
                $q->where('kondisi', '!=', 'Baik');
            },
        ])->get();

        return view('superadmin.laporan-barang.index', compact(
            'barangs', 'kategoris', 'lokasis', 'ringkasanLokasi',
            'totalBarang', 'barangBaik', 'barangRusak', 'barangPinjam'
        ));
    }

    public function cetak(Request $request)
    {
        $query = Barang::with('kategoriBarang', 'lokasi');

        $lokasiFilter = null;

        if ($request->filled('lokasi')) {
            $query->where('lokasi_id', $request->lokasi);
            $lokasiFilter = Lokasi::find($request->lokasi);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_barang_id', $request->kategori);
        }

        $barangs = $query->orderBy('lokasi_id')->orderBy('kode_barang')->get();

        // Ringkasan per lokasi untuk PDF
        $ringkasanLokasi = Lokasi::withCount([
            'barangs as total_barang',
            'barangs as barang_baik' => function ($q) {
                $q->where('kondisi', 'Baik');
            },
            'barangs as barang_rusak' => function ($q) {
                $q->where('kondisi', '!=', 'Baik');
            },
        ])->get();

        return view('superadmin.laporan-barang.cetak', compact(
            'barangs', 'lokasiFilter', 'ringkasanLokasi'
        ));
    }
}
