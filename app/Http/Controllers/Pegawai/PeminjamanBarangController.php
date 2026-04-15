<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanBarangController extends Controller
{
    public function index()
    {
        $pegawai = Auth::guard('pegawai')->user();

        $peminjamans = PeminjamanBarang::with(['barang.kategoriBarang'])
            ->where('borrower_type', \App\Models\Pegawai::class)
            ->where('borrower_id', $pegawai->id)
            ->latest()
            ->paginate(10);

        return view('pegawai.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $barangs = Barang::with('kategoriBarang')
            ->where('is_pinjaman', true)
            ->get()
            ->filter(fn($b) => $b->stok_tersedia > 0);

        return view('pegawai.peminjaman.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id'               => 'required|exists:barangs,id',
            'jumlah_pinjam'           => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keperluan'               => 'required|string|max:500',
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);

        if (!$barang->canBeBorrowed($validated['jumlah_pinjam'])) {
            return back()->withErrors(['jumlah_pinjam' => "Stok tersedia hanya {$barang->stok_tersedia} {$barang->satuan}."])->withInput();
        }

        $pegawai = Auth::guard('pegawai')->user();

        PeminjamanBarang::create([
            ...$validated,
            'nomor_transaksi' => PeminjamanBarang::generateNomorTransaksi(),
            'borrower_type'   => \App\Models\Pegawai::class,
            'borrower_id'     => $pegawai->id,
            'status'          => 'Menunggu',
        ]);

        return redirect()->route('pegawai.peminjaman-barang.index')
            ->with('success', 'Permintaan peminjaman berhasil dikirim. Tunggu persetujuan Petugas Sarpras.');
    }
}
