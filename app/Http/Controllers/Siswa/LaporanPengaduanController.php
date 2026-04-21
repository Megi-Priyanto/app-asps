<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanPengaduan;
use App\Models\KategoriAspirasi;
use App\Models\Aspirasi;
use Illuminate\Support\Facades\Auth;
use App\Models\KomentarLaporan;

class LaporanPengaduanController extends Controller
{
    /**
     * Tampilkan daftar semua laporan milik siswa (dengan filter & search).
     */
    public function index(Request $request)
    {
        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();

        // Statistik (untuk pills)
        $allLaporan = $siswa->laporan()->with('aspirasi')->get();
        $stats = [
            'total'    => $allLaporan->count(),
            'menunggu' => $allLaporan->filter(
                fn($l) =>
                !$l->aspirasi || $l->aspirasi->status === 'menunggu'
            )->count(),
            'proses'   => $allLaporan->filter(
                fn($l) =>
                $l->aspirasi && $l->aspirasi->status === 'proses'
            )->count(),
            'selesai'  => $allLaporan->filter(
                fn($l) =>
                $l->aspirasi && $l->aspirasi->status === 'selesai'
            )->count(),
        ];

        $query = $siswa->laporan()->with(['kategoriAspirasi', 'aspirasi']);

        // Filter: search keterangan
        if ($request->filled('search')) {
            $query->where('ket', 'like', '%' . $request->search . '%');
        }

        // Filter: kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_aspirasi_id', $request->kategori);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'menunggu') {
                $query->where(function ($q) {
                    $q->whereDoesntHave('aspirasi')
                      ->orWhereHas('aspirasi', fn($sub) => $sub->where('status', 'menunggu'));
                });
            } else {
                $query->whereHas('aspirasi', fn($q) => $q->where('status', $status));
            }
        }

        // Sort
        $sort = $request->input('sort', 'terbaru');
        $query->orderBy('created_at', $sort === 'terlama' ? 'asc' : 'desc');

        // Paginate
        $laporan = $query->paginate(10)->withQueryString();

        // Tambahkan atribut status & feedback ke setiap item
        $kepuasan = [
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Cukup Puas',
            4 => 'Puas',
            5 => 'Sangat Puas',
        ];

        $laporan->getCollection()->transform(function ($item) use ($kepuasan) {
            $item->status = $item->aspirasi ? $item->aspirasi->status : 'menunggu';

            $nilai = $item->aspirasi->feedback ?? null;
            $item->feedback = $nilai ? ($kepuasan[$nilai] ?? '-') : null;

            return $item;
        });

        // Ambil semua kategori untuk dropdown filter
        $kategori = KategoriAspirasi::all();

        return view('siswa.laporan.index', compact('laporan', 'stats', 'kategori'));
    }

    /**
     * Form buat laporan baru.
     */
    public function create()
    {
        $lokasi = \App\Models\Lokasi::whereHas('admins')->orderBy('nama_lokasi')->get();
        $kategori = KategoriAspirasi::all();
        return view('siswa.laporan.create', compact('kategori', 'lokasi'));
    }

    /**
     * Simpan laporan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_aspirasi_id' => 'required|exists:kategori_aspirasis,id',
            'ket'         => 'required|string',
            'lokasi'      => 'required|string|max:255',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $fotoName = $request->file('foto')->store('laporan', 'public');
        }

        /** @var \App\Models\Siswa $siswa */
        $siswa = Auth::guard('siswa')->user();
        $siswa->laporanSebagaiReporter()->create([
            'siswa_id'             => $siswa->id, // Pertahankan untuk kompatibilitas filter yang lama
            'kategori_aspirasi_id' => $request->kategori_aspirasi_id,
            'ket'                  => $request->ket,
            'lokasi'               => $request->lokasi,
            'foto'                 => $fotoName,
        ]);

        return redirect()
            ->route('siswa.dashboard')
            ->with('success', 'Laporan berhasil dikirim');
    }

    public function show(LaporanPengaduan $laporan)
    {
        abort_if($laporan->siswa_id !== Auth::guard('siswa')->id(), 403, 'Akses ditolak. Bukan laporan Anda.');
        $laporan->load(['siswa', 'aspirasi', 'kategoriAspirasi', 'komentar.sender']);

        $laporan->komentar()
            ->whereIn('sender_type', ['admin', 'superadmin'])
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('siswa.laporan.show', ['laporan' => $laporan]);
    }

    public function destroy(LaporanPengaduan $laporan)
    {
        abort_if($laporan->siswa_id !== Auth::guard('siswa')->id(), 403, 'Akses ditolak.');
        $laporan->delete();

        return redirect()
            ->route('siswa.laporan.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    public function feedback(Request $request, Aspirasi $aspirasi)
    {
        abort_if($aspirasi->laporan->siswa_id !== Auth::guard('siswa')->id(), 403, 'Akses ditolak.');
        $rules = ['feedback' => 'required|integer|min:1|max:5'];

        if (in_array((int) $request->feedback, [1, 2])) {
            $rules['alasan'] = 'required|string|max:1000';
        } else {
            $rules['alasan'] = 'nullable|string|max:1000';
        }

        $request->validate($rules, [
            'alasan.required' => 'Alasan wajib diisi jika Anda memilih Tidak Puas atau Kurang Puas.',
        ]);

        $aspirasi->update([
            'feedback' => $request->feedback,
            'alasan'   => $request->alasan,
        ]);

        return redirect()
            ->route('siswa.dashboard')
            ->with('success', 'Terima kasih atas feedback Anda.');
    }

    public function storeKomentar(Request $request, LaporanPengaduan $laporan)
    {
        abort_if($laporan->siswa_id !== Auth::guard('siswa')->id(), 403, 'Akses ditolak.');
        $request->validate(['pesan' => 'required|string|max:1000']);

        KomentarLaporan::create([
            'laporan_id'  => $laporan->id,
            'sender_type' => 'siswa',
            'sender_id'   => Auth::guard('siswa')->id(),
            'pesan'       => $request->pesan,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim');
    }
}
