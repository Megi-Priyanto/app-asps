<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBarang::withCount('barangs')->latest()->paginate(10);
        return view('superadmin.kategori-barang.index', compact('kategoris'));
    }

    public function create()
    {
        return view('superadmin.kategori-barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_barangs,nama_kategori',
        ]);

        KategoriBarang::create($request->all());

        return redirect()->route('superadmin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil ditambahkan.');
    }

    public function edit(KategoriBarang $kategoriBarang)
    {
        return view('superadmin.kategori-barang.edit', compact('kategoriBarang'));
    }

    public function update(Request $request, KategoriBarang $kategoriBarang)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_barangs,nama_kategori,' . $kategoriBarang->id,
        ]);

        $kategoriBarang->update($request->all());

        return redirect()->route('superadmin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil diperbarui.');
    }

    public function destroy(KategoriBarang $kategoriBarang)
    {
        if ($kategoriBarang->barangs()->count() > 0) {
            return redirect()->route('superadmin.kategori-barang.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa barang.');
        }

        $kategoriBarang->delete();

        return redirect()->route('superadmin.kategori-barang.index')
            ->with('success', 'Kategori Barang berhasil dihapus.');
    }
}
