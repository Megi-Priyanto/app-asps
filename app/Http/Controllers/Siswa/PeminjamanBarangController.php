<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanBarangController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();

        $peminjamans = PeminjamanBarang::with(['barang.kategoriBarang'])
            ->where('borrower_type', \App\Models\Siswa::class)
            ->where('borrower_id', $siswa->id)
            ->latest()
            ->paginate(10);

        return view('siswa.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $barangs = Barang::with('kategoriBarang')
            ->where('is_pinjaman', true)
            ->get()
            ->filter(fn($b) => $b->stok_tersedia > 0);

        return view('siswa.peminjaman.create', compact('barangs'));
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

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
                $barang = Barang::lockForUpdate()->findOrFail($validated['barang_id']);

                if (!$barang->canBeBorrowed($validated['jumlah_pinjam'])) {
                    throw new \Exception("Stok tersedia hanya {$barang->stok_tersedia} {$barang->satuan}.");
                }

                $siswa = Auth::guard('siswa')->user();

                PeminjamanBarang::create([
                    ...$validated,
                    'nomor_transaksi' => PeminjamanBarang::generateNomorTransaksi(),
                    'borrower_type'   => \App\Models\Siswa::class,
                    'borrower_id'     => $siswa->id,
                    'status'          => 'Menunggu',
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_pinjam' => $e->getMessage()])->withInput();
        }

        return redirect()->route('siswa.peminjaman-barang.index')
            ->with('success', 'Permintaan peminjaman berhasil dikirim. Tunggu persetujuan Petugas Sarpras.');
    }
}
