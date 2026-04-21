<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $lokasiId = $admin->lokasi_id;

        // Kategori yang sudah ditambahkan di lokasi ini
        $kategoris = KategoriBarang::whereHas('lokasis', function ($q) use ($lokasiId) {
            $q->where('lokasis.id', $lokasiId);
        })->withCount(['barangs' => fn($q) => $q->where('lokasi_id', $lokasiId)])->latest()->get();

        return view('admin.kategori-barang.index', compact('kategoris'));
    }

    public function create()
    {
        $admin = Auth::guard('admin')->user();
        $lokasiId = $admin->lokasi_id;

        // Kategori yang belum ditambahkan di lokasi ini
        $kategoriTersedia = KategoriBarang::whereDoesntHave('lokasis', function ($q) use ($lokasiId) {
            $q->where('lokasis.id', $lokasiId);
        })->orderBy('nama_kategori')->get();

        return view('admin.kategori-barang.create', compact('kategoriTersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_barang_id' => 'required|exists:kategori_barangs,id',
        ]);

        $admin = Auth::guard('admin')->user();
        $lokasi = $admin->lokasi;

        // Attach kategori ke lokasi (jika belum ada)
        $lokasi->kategoriBarangs()->syncWithoutDetaching([$request->kategori_barang_id]);

        return redirect()->route('admin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil ditambahkan ke lokasi Anda.');
    }

    public function destroy(KategoriBarang $kategoriBarang)
    {
        $admin = Auth::guard('admin')->user();
        $lokasi = $admin->lokasi;

        // Detach kategori dari lokasi
        $lokasi->kategoriBarangs()->detach($kategoriBarang->id);

        return redirect()->route('admin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil dihapus dari lokasi Anda.');
    }
}
