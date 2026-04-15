<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerbaikanBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerbaikanBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = PerbaikanBarang::with(['barang.kategoriBarang', 'peminjaman', 'admin'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perbaikans = $query->paginate(15)->withQueryString();

        $totalMenunggu   = PerbaikanBarang::where('status', 'Menunggu')->count();
        $totalProses     = PerbaikanBarang::where('status', 'Dalam Perbaikan')->count();
        $totalSelesai    = PerbaikanBarang::where('status', 'Selesai')->count();

        return view('admin.perbaikan.index', compact(
            'perbaikans', 'totalMenunggu', 'totalProses', 'totalSelesai'
        ));
    }

    public function show(PerbaikanBarang $perbaikanBarang)
    {
        $perbaikanBarang->load('barang', 'peminjaman.borrower', 'admin');
        return view('admin.perbaikan.show', compact('perbaikanBarang'));
    }

    /**
     * Update status perbaikan (Menunggu → Dalam Perbaikan → Selesai)
     */
    public function updateStatus(Request $request, PerbaikanBarang $perbaikanBarang)
    {
        $request->validate([
            'status'            => 'required|in:Menunggu,Dalam Perbaikan,Selesai',
            'catatan_perbaikan' => 'nullable|string|max:500',
            'biaya_perbaikan'   => 'nullable|numeric|min:0',
            'tanggal_selesai'   => 'nullable|date',
        ]);

        $data = [
            'status'            => $request->status,
            'admin_id'          => Auth::id(),
            'catatan_perbaikan' => $request->catatan_perbaikan,
        ];

        if ($request->status === 'Selesai') {
            $data['tanggal_selesai'] = $request->tanggal_selesai ?? now()->toDateString();
            $data['biaya_perbaikan'] = $request->biaya_perbaikan;

            // Kembalikan unit ke stok baik
            $barang = $perbaikanBarang->barang;
            $jumlah = $perbaikanBarang->jumlah_rusak;

            if ($perbaikanBarang->tingkat_kerusakan === 'Rusak Ringan') {
                $barang->jumlah_rusak_ringan = max(0, $barang->jumlah_rusak_ringan - $jumlah);
            } else {
                $barang->jumlah_rusak_berat = max(0, $barang->jumlah_rusak_berat - $jumlah);
            }
            $barang->jumlah_baik += $jumlah;
            $barang->jumlah      = $barang->jumlah_baik + $barang->jumlah_rusak_ringan + $barang->jumlah_rusak_berat;
            $barang->kondisi     = $barang->kondisi_dominan;
            $barang->save();
        }

        $perbaikanBarang->update($data);

        return back()->with('success', "Status perbaikan diperbarui menjadi: {$request->status}.");
    }
}
