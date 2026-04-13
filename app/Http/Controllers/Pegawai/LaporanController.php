<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\KomentarLaporan;
use App\Models\LaporanPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    private function getUser()
    {
        return Auth::guard('pegawai')->user();
    }

    public function index(Request $request)
    {
        /** @var \App\Models\Pegawai $user */
        $user       = $this->getUser();
        $allLaporan = $user->laporan()->with('aspirasi')->get();

        $stats = [
            'total'    => $allLaporan->count(),
            'menunggu' => $allLaporan->filter(fn($l) => !$l->aspirasi || $l->aspirasi->status === 'menunggu')->count(),
            'proses'   => $allLaporan->filter(fn($l) => $l->aspirasi && $l->aspirasi->status === 'proses')->count(),
            'selesai'  => $allLaporan->filter(fn($l) => $l->aspirasi && $l->aspirasi->status === 'selesai')->count(),
        ];

        $query = $user->laporan()->with(['kategori', 'aspirasi']);

        if ($request->filled('search')) {
            $query->where('ket', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
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

        $sort = $request->input('sort', 'terbaru');
        $query->orderBy('created_at', $sort === 'terlama' ? 'asc' : 'desc');

        $laporan  = $query->paginate(10)->withQueryString();
        $kategori = Kategori::all();

        $kepuasan = [1 => 'Tidak Puas', 2 => 'Kurang Puas', 3 => 'Cukup Puas', 4 => 'Puas', 5 => 'Sangat Puas'];
        $laporan->getCollection()->transform(function ($item) use ($kepuasan) {
            $item->status   = $item->aspirasi ? $item->aspirasi->status : 'menunggu';
            $nilai          = $item->aspirasi->feedback ?? null;
            $item->feedback = $nilai ? ($kepuasan[$nilai] ?? '-') : null;
            return $item;
        });

        return view('pegawai.laporan.index', compact('laporan', 'stats', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('pegawai.laporan.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'ket'         => 'required|string',
            'lokasi'      => 'required|string|max:255',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto     = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/laporan'), $fotoName);
        }

        /** @var \App\Models\Pegawai $user */
        $user = $this->getUser();

        // Gunakan relasi create() agar morph type mengikuti morph map ('pegawai')
        $user->laporan()->create([
            'kategori_id' => $request->kategori_id,
            'ket'         => $request->ket,
            'lokasi'      => $request->lokasi,
            'foto'        => $fotoName,
        ]);

        return redirect()->route('pegawai.laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(LaporanPengaduan $laporan)
    {
        $user = $this->getUser();
        if ($laporan->reporter_type !== 'pegawai' || $laporan->reporter_id !== $user->id) {
            abort(403);
        }

        $laporan->load(['aspirasi', 'kategori', 'komentar.sender']);
        $laporan->komentar()->where('sender_type', 'admin')->where('is_read', false)->update(['is_read' => true]);
        return view('pegawai.laporan.show', compact('laporan'));
    }

    public function destroy(LaporanPengaduan $laporan)
    {
        $user = $this->getUser();
        if ($laporan->reporter_type !== 'pegawai' || $laporan->reporter_id !== $user->id) {
            abort(403);
        }

        $laporan->delete();
        return redirect()->route('pegawai.laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    public function feedback(Request $request, Aspirasi $aspirasi)
    {
        // Pastikan aspirasi ini milik laporan user yang login
        $user = $this->getUser();
        $laporan = $aspirasi->laporan;
        if ($laporan->reporter_type !== 'pegawai' || $laporan->reporter_id !== $user->id) {
            abort(403);
        }

        $rules = ['feedback' => 'required|integer|min:1|max:5'];
        if (in_array((int) $request->feedback, [1, 2])) {
            $rules['alasan'] = 'required|string|max:1000';
        } else {
            $rules['alasan'] = 'nullable|string|max:1000';
        }
        $request->validate($rules, ['alasan.required' => 'Alasan wajib diisi jika Anda memilih Tidak Puas atau Kurang Puas.']);
        $aspirasi->update(['feedback' => $request->feedback, 'alasan' => $request->alasan]);
        return redirect()->route('pegawai.laporan.index')->with('success', 'Terima kasih atas feedback Anda.');
    }

    public function storeKomentar(Request $request, LaporanPengaduan $laporan)
    {
        /** @var \App\Models\Pegawai $user */
        $user = $this->getUser();
        if ($laporan->reporter_type !== $user->getMorphClass() || $laporan->reporter_id !== $user->id) {
            abort(403);
        }

        $request->validate(['pesan' => 'required|string|max:1000']);
        KomentarLaporan::create([
            'laporan_id'  => $laporan->id,
            'sender_type' => 'pegawai',
            'sender_id'   => Auth::guard('pegawai')->id(),
            'pesan'       => $request->pesan,
        ]);
        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}