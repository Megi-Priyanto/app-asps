<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanBarangController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();

        $peminjamans = PeminjamanBarang::with(['barang.kategoriBarang'])
            ->where('borrower_type', \App\Models\Guru::class)
            ->where('borrower_id', $guru->id)
            ->latest()
            ->paginate(10);

        return view('guru.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $barangs = Barang::with('kategoriBarang')
            ->where('is_pinjaman', true)
            ->get()
            ->filter(fn($b) => $b->stok_tersedia > 0);

        return view('guru.peminjaman.create', compact('barangs'));
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

                $guru = Auth::guard('guru')->user();

                PeminjamanBarang::create([
                    ...$validated,
                    'nomor_transaksi' => PeminjamanBarang::generateNomorTransaksi(),
                    'borrower_type'   => \App\Models\Guru::class,
                    'borrower_id'     => $guru->id,
                    'status'          => 'Menunggu',
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['jumlah_pinjam' => $e->getMessage()])->withInput();
        }

        return redirect()->route('guru.peminjaman-barang.index')
            ->with('success', 'Permintaan peminjaman berhasil dikirim. Tunggu persetujuan Petugas Sarpras.');
    }

    public function show(PeminjamanBarang $peminjamanBarang)
    {
        $guru = Auth::guard('guru')->user();

        if ($peminjamanBarang->borrower_type !== \App\Models\Guru::class
            || $peminjamanBarang->borrower_id !== $guru->id) {
            abort(403);
        }

        $peminjamanBarang->load('barang.kategoriBarang');
        return view('guru.peminjaman.show', compact('peminjamanBarang'));
    }
}
