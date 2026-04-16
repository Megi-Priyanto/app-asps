<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::latest()->paginate(10);
        return view('superadmin.lokasi.index', compact('lokasi'));
    }

    public function create()
    {
        return view('superadmin.lokasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:50'
        ]);

        Lokasi::create($request->all());

        return redirect()->route('superadmin.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function show(Lokasi $lokasi)
    {
        return view('superadmin.lokasi.edit', compact('lokasi'));
    }

    public function edit(Lokasi $lokasi)
    {
        return view('superadmin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:50'
        ]);

        $lokasi->update($request->all());

        return redirect()->route('superadmin.lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->admins()->count() > 0) {
            return redirect()->route('superadmin.lokasi.index')
                ->with('error', 'Lokasi tidak dapat dihapus karena masih menugaskan Admin. Silakan pindahkan admin terlebih dahulu.');
        }

        $lokasi->delete();

        return redirect()->route('superadmin.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }
}
