<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriAspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriAspirasiController extends Controller
{
    public function index()
    {
        // Hanya tampilkan kategori di lokasi admin yang login
        $admin = Auth::guard('admin')->user();
        $kategori = KategoriAspirasi::where('lokasi_id', $admin->lokasi_id)->latest()->paginate(10);
        return view('admin.kategori-aspirasi.index', compact('kategori'));
    }

    public function create()
    {
        // Menampilkan form tambah kategori
        return view('admin.kategori-aspirasi.create');
    }

    public function store(Request $request)
    {
        // Validasi input nama kategori
        $request->validate([
            'nama_kategori' => 'required|string|max:50'
        ]);

        // Menyimpan data kategori baru dengan lokasi_id admin
        KategoriAspirasi::create([
            'nama_kategori' => $request->nama_kategori,
            'lokasi_id'     => Auth::guard('admin')->user()->lokasi_id,
        ]);

        return redirect()->route('admin.kategori-aspirasi.index')
            ->with('success', 'Kategori Aspirasi berhasil ditambahkan');
    }

    public function show(KategoriAspirasi $kategori_aspirasi)
    {
        // Menampilkan detail (di gambar diarahkan ke halaman edit)
        $kategori = $kategori_aspirasi;
        return view('admin.kategori-aspirasi.edit', compact('kategori'));
    }

    public function edit(KategoriAspirasi $kategori_aspirasi)
    {
        $kategori = $kategori_aspirasi;
        // Menampilkan form edit kategori
        return view('admin.kategori-aspirasi.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriAspirasi $kategori_aspirasi)
    {
        $kategori = $kategori_aspirasi;
        // Validasi input saat update
        $request->validate([
            'nama_kategori' => 'required|string|max:50'
        ]);

        // Memperbarui data kategori (lokasi_id tetap mengikuti admin)
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'lokasi_id'     => Auth::guard('admin')->user()->lokasi_id,
        ]);

        return redirect()->route('admin.kategori-aspirasi.index')
            ->with('success', 'Kategori Aspirasi berhasil diperbarui');
    }

    public function destroy(KategoriAspirasi $kategori_aspirasi)
    {
        $kategori = $kategori_aspirasi;
        // Menghapus data kategori
        $kategori->delete();

        return redirect()->route('admin.kategori-aspirasi.index')
            ->with('success', 'Kategori Aspirasi berhasil dihapus');
    }
}
