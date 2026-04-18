<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $query = Barang::with('kategoriBarang')
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

        $barangs   = $query->latest()->paginate(12)->withQueryString();
        $kategoris = KategoriBarang::all();

        $statsBase      = Barang::where('lokasi_id', $admin->lokasi_id);
        $totalBarang    = (clone $statsBase)->count();
        $barangBaik     = (clone $statsBase)->where('kondisi', 'Baik')->count();
        $barangRusak    = (clone $statsBase)->where('kondisi', '!=', 'Baik')->count();
        $barangPinjaman = (clone $statsBase)->where('is_pinjaman', true)->count();

        return view('admin.inventaris.index', compact(
            'barangs', 'kategoris',
            'totalBarang', 'barangBaik', 'barangRusak', 'barangPinjaman'
        ));
    }

    public function create()
    {
        $kategoris = KategoriBarang::all();
        return view('admin.inventaris.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang'       => 'required|string|max:50|unique:barangs,kode_barang',
            'nama_barang'       => 'required|string|max:150',
            'kategori_barang_id'=> 'required|exists:kategori_barangs,id',
            'lokasi_simpan'     => 'nullable|string|max:150',
            'jumlah_baik'       => 'required|integer|min:0',
            'jumlah_rusak_ringan'=> 'required|integer|min:0',
            'jumlah_rusak_berat' => 'required|integer|min:0',
            'satuan'            => 'required|string|max:20',
            'tanggal_pengadaan' => 'required|date',
            'sumber'            => 'nullable|string|max:100',
            'is_pinjaman'       => 'nullable|boolean',
            'keterangan'        => 'nullable|string',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['is_pinjaman'] = $request->boolean('is_pinjaman');
        $validated['jumlah']      = $validated['jumlah_baik'] + $validated['jumlah_rusak_ringan'] + $validated['jumlah_rusak_berat'];
        $validated['kondisi']     = $this->tentukanKondisi($validated);
        $validated['lokasi_id']   = Auth::guard('admin')->user()->lokasi_id;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('barangs', 'public');
        }

        Barang::create($validated);

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Barang berhasil ditambahkan ke inventaris.');
    }

    public function show(Barang $inventari)
    {
        abort_if($inventari->lokasi_id !== Auth::guard('admin')->user()->lokasi_id, 403, 'Barang ini bukan milik lokasi Anda.');
        $inventari->load('kategoriBarang', 'peminjamanBarangs.borrower', 'perbaikanBarangs');
        return view('admin.inventaris.show', compact('inventari'));
    }

    public function edit(Barang $inventari)
    {
        abort_if($inventari->lokasi_id !== Auth::guard('admin')->user()->lokasi_id, 403, 'Barang ini bukan milik lokasi Anda.');
        $kategoris = KategoriBarang::all();
        return view('admin.inventaris.edit', compact('inventari', 'kategoris'));
    }

    public function update(Request $request, Barang $inventari)
    {
        abort_if($inventari->lokasi_id !== Auth::guard('admin')->user()->lokasi_id, 403, 'Barang ini bukan milik lokasi Anda.');
        $validated = $request->validate([
            'kode_barang'       => 'required|string|max:50|unique:barangs,kode_barang,' . $inventari->id,
            'nama_barang'       => 'required|string|max:150',
            'kategori_barang_id'=> 'required|exists:kategori_barangs,id',
            'lokasi_simpan'     => 'nullable|string|max:150',
            'jumlah_baik'       => 'required|integer|min:0',
            'jumlah_rusak_ringan'=> 'required|integer|min:0',
            'jumlah_rusak_berat' => 'required|integer|min:0',
            'satuan'            => 'required|string|max:20',
            'tanggal_pengadaan' => 'required|date',
            'sumber'            => 'nullable|string|max:100',
            'is_pinjaman'       => 'nullable|boolean',
            'keterangan'        => 'nullable|string',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['is_pinjaman'] = $request->boolean('is_pinjaman');
        $validated['jumlah']      = $validated['jumlah_baik'] + $validated['jumlah_rusak_ringan'] + $validated['jumlah_rusak_berat'];
        $validated['kondisi']     = $this->tentukanKondisi($validated);
        $validated['lokasi_id']   = Auth::guard('admin')->user()->lokasi_id;

        if ($request->hasFile('gambar')) {
            if ($inventari->gambar) Storage::disk('public')->delete($inventari->gambar);
            $validated['gambar'] = $request->file('gambar')->store('barangs', 'public');
        }

        $inventari->update($validated);

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $inventari)
    {
        abort_if($inventari->lokasi_id !== Auth::guard('admin')->user()->lokasi_id, 403, 'Barang ini bukan milik lokasi Anda.');
        if ($inventari->gambar) Storage::disk('public')->delete($inventari->gambar);
        $inventari->delete();

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Barang berhasil dihapus dari inventaris.');
    }

    private function tentukanKondisi(array $data): string
    {
        $baik   = $data['jumlah_baik']          ?? 0;
        $ringan = $data['jumlah_rusak_ringan']   ?? 0;
        $berat  = $data['jumlah_rusak_berat']    ?? 0;

        if ($baik >= $ringan && $baik >= $berat) return 'Baik';
        if ($ringan >= $berat)                   return 'Rusak Ringan';
        return 'Rusak Berat';
    }
}
