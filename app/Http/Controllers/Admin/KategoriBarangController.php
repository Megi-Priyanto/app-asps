<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBarang::latest()->paginate(10);
        return view('admin.kategori-barang.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori-barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_barangs,nama_kategori',
            'deskripsi'     => 'nullable|string'
        ]);

        KategoriBarang::create($request->all());

        return redirect()->route('admin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil ditambahkan.');
    }

    public function edit(KategoriBarang $kategoriBarang)
    {
        return view('admin.kategori-barang.edit', compact('kategoriBarang'));
    }

    public function update(Request $request, KategoriBarang $kategoriBarang)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_barangs,nama_kategori,' . $kategoriBarang->id,
            'deskripsi'     => 'nullable|string'
        ]);

        $kategoriBarang->update($request->all());

        return redirect()->route('admin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil diperbarui.');
    }

    public function destroy(KategoriBarang $kategoriBarang)
    {
        // Pastikan tidak ada barang yang terkait dengan kategori ini sebelum dihapus
        if ($kategoriBarang->barangs()->count() > 0) {
            return redirect()->route('admin.kategori-barang.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa barang.');
        }

        $kategoriBarang->delete();

        return redirect()->route('admin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil dihapus.');
    }
}
