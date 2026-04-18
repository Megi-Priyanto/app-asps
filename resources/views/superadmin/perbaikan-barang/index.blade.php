@extends('layouts.superadmin')

@section('title', 'Perbaikan & Biaya (Seluruh Lokasi)')

@section('content')

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="lokasi" class="form-select">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasis as $lok)
                        <option value="{{ $lok->id }}" {{ request('lokasi')==$lok->id?'selected':'' }}>{{ $lok->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['Menunggu','Dalam Perbaikan','Selesai'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('superadmin.perbaikan-barang.cetak', request()->all()) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Rekap
                </a>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">Rekap Perbaikan Barang</div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>No. Perbaikan</th>
                    <th>Lokasi</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tingkat</th>
                    <th>Status</th>
                    <th>Foto Nota</th>
                    <th>Biaya (Rp)</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perbaikans as $p)
                @php
                    $sc = match($p->status) {
                        'Menunggu'=>'badge bg-warning','Dalam Perbaikan'=>'badge bg-info','Selesai'=>'badge bg-success', default=>'badge bg-secondary'
                    };
                    $tkClass = $p->tingkat_kerusakan === 'Rusak Ringan' ? 'text-warning' : 'text-danger';
                @endphp
                <tr>
                    <td><code style="font-size:11px;color:#2563EB;">{{ $p->nomor_perbaikan }}</code></td>
                    <td>{{ $p->barang->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>
                        <div style=" font-weight:600;">{{ $p->barang->nama_barang ?? '-' }}</div>
                    </td>
                    <td>{{ $p->jumlah_rusak }} {{ $p->barang->satuan ?? '' }}</td>
                    <td><span class="{{ $tkClass }} fw-bold"><i class="bi bi-circle-fill" style="font-size:8px;"></i> {{ $p->tingkat_kerusakan }}</span></td>
                    <td><span class="{{ $sc }}">{{ $p->status }}</span></td>
                    <td>
                        @if($p->foto_nota)
                            <a href="{{ asset('storage/'.$p->foto_nota) }}" target="_blank" class="btn btn-sm btn-light">
                                <i class="bi bi-image" style="color:#2563EB;"></i> Lihat
                            </a>
                        @else
                            <span class="text-muted" style="font-size:12px;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'Selesai')
                            @if($p->biaya_perbaikan !== null)
                                <div class="text-success fw-bold">Rp {{ number_format($p->biaya_perbaikan, 0, ',', '.') }}</div>
                            @else
                                <span class="text-danger" style="font-size:11px;"><i class="bi bi-exclamation-circle me-1"></i>Belum Diinput</span>
                            @endif
                        @else
                            <span class="text-muted" style="font-size:11px;">Menunggu Selesai</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($p->status === 'Selesai')
                        <button type="button" class="btn btn-sm btn-primary-light" style="background:#EFF6FF;color:#2563EB;border:none;"
                            data-bs-toggle="modal" data-bs-target="#biayaModal{{ $p->id }}">
                            <i class="bi bi-currency-dollar"></i> Set Biaya
                        </button>

                        {{-- Modal Update Biaya --}}
                        <div class="modal fade" id="biayaModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header" style="border-bottom:1px solid #F1F5F9;">
                                        <h6 class="modal-title fw-bold">Tetapkan Biaya Perbaikan</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('superadmin.perbaikan-barang.update-biaya', $p) }}">
                                        @csrf @method('PATCH')
                                        <div class="modal-body text-start">
                                            @if($p->foto_nota)
                                                <div class="mb-3 text-center">
                                                    <label class="form-label d-block text-start">Bukti Nota / Kuitansi</label>
                                                    <img src="{{ asset('storage/'.$p->foto_nota) }}" alt="Nota" style="max-height: 200px; border-radius: 8px; border:1px solid #E2E8F0;">
                                                </div>
                                            @else
                                                <div class="alert alert-warning py-2 mb-3" style="font-size:12px;">
                                                    <i class="bi bi-info-circle me-1"></i> Admin tidak menyertakan foto nota.
                                                </div>
                                                @if($p->catatan_perbaikan)
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted">Catatan Admin:</label>
                                                        <div class="p-2" style="background:#F8FAFC; border-radius:8px; font-size:12px;">{{ $p->catatan_perbaikan }}</div>
                                                    </div>
                                                @endif
                                            @endif

                                            <div class="mb-3">
                                                <label class="form-label">Biaya Perbaikan (Rp)</label>
                                                <input type="number" name="biaya_perbaikan" class="form-control form-control-lg fw-bold text-primary" placeholder="Misal: 50000 atau 0" min="0" value="{{ $p->biaya_perbaikan ?? '' }}" required>
                                                <small class="text-muted" style="font-size:11px;">Isi 0 jika tidak ada pengeluaran kas.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid #F1F5F9;">
                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Simpan Biaya</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled style="opacity: 0.5;"><i class="bi bi-lock-fill"></i></button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5" style="color:#94A3B8;">
                        <i class="bi bi-tools fs-2 d-block mb-2"></i>Belum ada data.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($perbaikans->hasPages())
    <div class="card-footer">{{ $perbaikans->links() }}</div>
    @endif
</div>

@endsection
