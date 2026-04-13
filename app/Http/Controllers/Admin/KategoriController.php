<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        // Mengambil data kategori terbaru dengan paginasi 10 data per halaman
        $kategori = Kategori::latest()->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        // Menampilkan form tambah kategori
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        // Validasi input nama kategori
        $request->validate([
            'nama_kategori' => 'required|string|max:100'
        ]);

        // Menyimpan data kategori baru
        Kategori::create($request->all());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function show(Kategori $kategori)
    {
        // Menampilkan detail (di gambar diarahkan ke halaman edit)
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        // Menampilkan form edit kategori
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        // Validasi input saat update
        $request->validate([
            'nama_kategori' => 'required|string|max:100'
        ]);

        // Memperbarui data kategori
        $kategori->update($request->all());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $kategori)
    {
        // Menghapus data kategori
        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus');
    }
}