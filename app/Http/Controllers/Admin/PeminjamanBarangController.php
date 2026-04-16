<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBarang;
use App\Models\PerbaikanBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanBarang::with(['barang.kategoriBarang', 'admin'])
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nomor_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhereHasMorph('borrower', ['App\Models\Guru', 'App\Models\Siswa', 'App\Models\Pegawai'],
                    fn($q) => $q->where('nama', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans     = $query->paginate(15)->withQueryString();
        $totalMenunggu   = PeminjamanBarang::menunggu()->count();
        $totalAktif      = PeminjamanBarang::aktif()->count();
        $totalTerlambat  = PeminjamanBarang::terlambat()->count();

        return view('admin.peminjaman.index', compact(
            'peminjamans', 'totalMenunggu', 'totalAktif', 'totalTerlambat'
        ));
    }

    public function show(PeminjamanBarang $peminjamanBarang)
    {
        $peminjamanBarang->load('barang.kategoriBarang', 'admin', 'perbaikanBarang');
        return view('admin.peminjaman.show', compact('peminjamanBarang'));
    }

    /**
     * Admin ACC permintaan peminjaman — status: Menunggu → Disetujui
     */
    public function acc(Request $request, PeminjamanBarang $peminjamanBarang)
    {
        if ($peminjamanBarang->status !== 'Menunggu') {
            return back()->with('error', 'Peminjaman ini tidak dalam status Menunggu.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $peminjamanBarang) {
                // Lock row Peminjaman dan row Barang agar aman dari race condition
                $peminjamanBarang = PeminjamanBarang::lockForUpdate()->find($peminjamanBarang->id);
                $barang = Barang::lockForUpdate()->find($peminjamanBarang->barang_id);

                if (!$barang->canBeBorrowed($peminjamanBarang->jumlah_pinjam)) {
                    throw new \Exception('STOK HABIS! Tidak bisa menyetujui peminjaman ini karena stok baru saja diambil pengguna lain. Mohon tolak pengajuan ini.');
                }

                $peminjamanBarang->update([
                    'status'       => 'Sedang Dipinjam',
                    'admin_id'     => Auth::id(),
                    'catatan_admin'=> $request->catatan_admin,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Peminjaman #{$peminjamanBarang->nomor_transaksi} telah disetujui.");
    }

    /**
     * Admin tolak permintaan peminjaman
     */
    public function tolak(Request $request, PeminjamanBarang $peminjamanBarang)
    {
        $request->validate(['catatan_admin' => 'required|string|max:500']);

        if ($peminjamanBarang->status !== 'Menunggu') {
            return back()->with('error', 'Peminjaman ini tidak dalam status Menunggu.');
        }

        $peminjamanBarang->update([
            'status'        => 'Ditolak',
            'admin_id'      => Auth::id(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', "Peminjaman #{$peminjamanBarang->nomor_transaksi} telah ditolak.");
    }

    /**
     * Admin proses pengembalian normal — barang kondisi Baik
     */
    public function kembalikan(Request $request, PeminjamanBarang $peminjamanBarang)
    {
        if ($peminjamanBarang->status === 'Sudah Dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan sebelumnya.');
        }

        $request->validate(['kondisi_barang' => 'required|in:Baik,Rusak Ringan,Rusak Berat']);

        $peminjamanBarang->update([
            'tanggal_kembali_aktual' => now(),
            'status'                 => 'Sudah Dikembalikan',
            'kondisi_barang'         => $request->kondisi_barang,
            'admin_id'               => Auth::id(),
        ]);

        $barang = $peminjamanBarang->barang;

        if ($request->kondisi_barang !== 'Baik') {
            try {
                \Illuminate\Support\Facades\DB::transaction(function () use ($request, $peminjamanBarang, $barang) {
                    // Lock barang update
                    $barang = Barang::lockForUpdate()->find($barang->id);
                    
                    // Kurangi stok baik, tambah stok rusak
                    $barang->jumlah_baik = max(0, $barang->jumlah_baik - $peminjamanBarang->jumlah_pinjam);

                    if ($request->kondisi_barang === 'Rusak Ringan') {
                        $barang->jumlah_rusak_ringan += $peminjamanBarang->jumlah_pinjam;
                    } else {
                        $barang->jumlah_rusak_berat += $peminjamanBarang->jumlah_pinjam;
                    }
                    $barang->kondisi = $barang->kondisi_dominan;
                    $barang->save();

                    // Otomatis buat catatan perbaikan
                    PerbaikanBarang::create([
                        'nomor_perbaikan'      => PerbaikanBarang::generateNomor(),
                        'barang_id'            => $barang->id,
                        'peminjaman_id'        => $peminjamanBarang->id,
                        'jumlah_rusak'         => $peminjamanBarang->jumlah_pinjam,
                        'tingkat_kerusakan'    => $request->kondisi_barang,
                        'keterangan_kerusakan' => "Dikembalikan dalam kondisi {$request->kondisi_barang} oleh {$peminjamanBarang->borrower_name}",
                        'tanggal_masuk'        => now()->toDateString(),
                        'status'               => 'Menunggu',
                        'admin_id'             => Auth::id(),
                    ]);
                });
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi kesalahan sistem saat memproses pengembalian: ' . $e->getMessage());
            }

            return back()->with('warning',
                "Barang dikembalikan kondisi {$request->kondisi_barang}. Catatan perbaikan otomatis dibuat.");
        }

        return back()->with('success', 'Barang berhasil dikembalikan dalam kondisi Baik.');
    }
}
