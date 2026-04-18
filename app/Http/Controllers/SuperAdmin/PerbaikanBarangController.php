<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PerbaikanBarang;
use Illuminate\Http\Request;

class PerbaikanBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = PerbaikanBarang::with(['barang.kategoriBarang', 'barang.lokasi', 'admin'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('lokasi')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('lokasi_id', $request->lokasi);
            });
        }

        $perbaikans = $query->paginate(15)->withQueryString();
        $lokasis = \App\Models\Lokasi::all();

        return view('superadmin.perbaikan-barang.index', compact('perbaikans', 'lokasis'));
    }

    public function updateBiaya(Request $request, PerbaikanBarang $perbaikanBarang)
    {
        $request->validate([
            'biaya_perbaikan' => 'required|numeric|min:0'
        ]);

        $perbaikanBarang->update([
            'biaya_perbaikan' => $request->biaya_perbaikan
        ]);

        return back()->with('success', 'Biaya perbaikan berhasil ditetapkan.');
    }

    public function cetakPdf(Request $request)
    {
        $query = PerbaikanBarang::with(['barang.kategoriBarang', 'barang.lokasi', 'admin'])->where('status', 'Selesai')->latest();

        if ($request->filled('lokasi')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('lokasi_id', $request->lokasi);
            });
        }
        
        $perbaikans = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('superadmin.perbaikan-barang.cetak', compact('perbaikans'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan_biaya_perbaikan_' . date('Ymd_His') . '.pdf');
    }
}
