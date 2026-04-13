<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Aspirasi;

class LaporanAspirasiController extends Controller
{
    public function index(Request $request)
    {
        // Memulai query dengan eager loading untuk optimasi
        $query = LaporanPengaduan::with(['kategori', 'aspirasi', 'reporter', 'siswa'])
            ->latest();

        // Logika Filter Status
        if ($request->filled('status')) {
            if ($request->status === 'belum') {
                // Mencari laporan yang belum memiliki aspirasi atau statusnya masih 'menunggu'
                $query->where(function ($q) {
                    $q->whereDoesntHave('aspirasi')
                      ->orWhereHas('aspirasi', function ($sub) {
                          $sub->where('status', 'menunggu');
                      });
                });
            } else {
                // Filter berdasarkan status spesifik lainnya
                $query->whereHas('aspirasi', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
            }
        }

        // Logika Filter Peran
        if ($request->filled('role')) {
            if ($request->role === 'siswa') {
                $query->whereNotNull('siswa_id');
            } elseif ($request->role === 'guru') {
                $query->where('reporter_type', 'guru');
            } elseif ($request->role === 'pegawai') {
                $query->where('reporter_type', 'pegawai');
            }
        }

        // Paginasi dengan mempertahankan query string filter
        $laporan = $query->paginate(10)->withQueryString();

        // Pemetaan nilai kepuasan (Feedback)
        $kepuasan = [
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Cukup Puas',
            4 => 'Puas',
            5 => 'Sangat Puas',
        ];

        // Transformasi data untuk memudahkan tampilan di Blade
        $laporan->getCollection()->transform(function ($item) use ($kepuasan) {
            $item->status = $item->aspirasi?->status;

            $nilai = $item->aspirasi?->feedback ?? null;
            $item->feedback = $nilai 
                ? ($kepuasan[$nilai] ?? '-') 
                : 'Belum ada feedback';

            return $item;
        });

        return view('admin.laporan.index', compact('laporan'));
    }

    public function show(LaporanPengaduan $laporan)
    {
        // Memuat relasi yang diperlukan
        $laporan->load(['kategori', 'aspirasi', 'komentar.sender', 'reporter', 'siswa']);

        // Tandai komentar dari siswa sebagai sudah dibaca
        $laporan->komentar()
            ->where('sender_type', 'siswa')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $kepuasan = [
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Cukup Puas',
            4 => 'Puas',
            5 => 'Sangat Puas',
        ];

        // Mapping feedback untuk detail laporan
        if ($laporan->aspirasi?->feedback) {
            $laporan->feedback = $kepuasan[$laporan->aspirasi->feedback] ?? '-';
        } else {
            $laporan->feedback = 'Belum ada feedback';
        }

        return view('admin.laporan.show', compact('laporan'));
    }

    public function update(Request $request, LaporanPengaduan $laporan)
    {
        // Validasi perubahan status oleh admin
        $request->validate([
            'status' => 'required|in:proses,selesai',
        ]);

        // Menggunakan updateOrCreate agar lebih fleksibel
        Aspirasi::updateOrCreate(
            ['laporan_id' => $laporan->id], // Cari berdasarkan laporan_id
            [
                'admin_id' => Auth::guard('admin')->id(),
                'status'   => $request->status,
            ]
        );

        return redirect()
            ->route('admin.laporan.show', $laporan->id)
            ->with('success', 'Status aspirasi berhasil diperbarui.');
    }

    public function cetakPdf(Request $request)
    {
        $jenis  = $request->query('jenis', 'bulanan');
        $bulan  = $request->query('bulan', date('m'));
        $tahun  = $request->query('tahun', date('Y'));
        $tanggal = $request->query('tanggal', date('Y-m-d'));

        $query = LaporanPengaduan::with(['kategori', 'siswa', 'reporter', 'aspirasi']);

        if ($jenis === 'harian') {
            // Filter berdasarkan tanggal spesifik
            $query->whereDate('created_at', $tanggal);

        } elseif ($jenis === 'tahunan') {
            // Filter hanya berdasarkan tahun
            $query->whereYear('created_at', $tahun);

        } else {
            // Default: bulanan — filter bulan + tahun
            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        }

        $laporan = $query->orderBy('created_at')->get();

        return view('admin.laporan.cetak', compact('laporan', 'jenis', 'bulan', 'tahun', 'tanggal'));
    }

    public function storeKomentar(Request $request, LaporanPengaduan $laporan)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000'
        ]);

        \App\Models\KomentarLaporan::create([
            'laporan_id'  => $laporan->id,
            'sender_type' => 'admin',
            'sender_id'   => Auth::guard('admin')->id(),
            'pesan'       => $request->pesan,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim');
    }
}