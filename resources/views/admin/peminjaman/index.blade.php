@extends('layouts.admin')

@section('title', 'Kelola Peminjaman Barang')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Menunggu ACC</div>
                <div class="stat-value">{{ $totalMenunggu }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-label">Sedang Dipinjam</div>
                <div class="stat-value">{{ $totalAktif }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-label">Terlambat</div>
                <div class="stat-value">{{ $totalTerlambat }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.peminjaman-barang.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text" style="background:#F8FAFC;border-color:#E8EDF5;"><i class="bi bi-search" style="color:#94A3B8;"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor transaksi atau nama peminjam…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['Menunggu','Disetujui','Sedang Dipinjam','Sudah Dikembalikan','Terlambat','Ditolak'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('admin.peminjaman-barang.index') }}" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;border:1px solid #E8EDF5;">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if(session('warning'))
    <div class="alert mb-3" style="background:#FFFBEB;color:#78350F;border:none;"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mb-3"><i class="bi bi-x-circle me-2"></i>{{ session('error') }}</div>
@endif

{{-- Table --}}
<div class="card">
    <div class="card-header">Daftar Permintaan & Peminjaman Barang</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Jml</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th width="160" class="text-end text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                @php
                    $statusClass = match($p->status) {
                        'Menunggu'          => 'pmj-badge-warning',
                        'Disetujui'         => 'pmj-badge-info',
                        'Sedang Dipinjam'   => 'pmj-badge-blue',
                        'Sudah Dikembalikan'=> 'pmj-badge-success',
                        'Terlambat'         => 'pmj-badge-danger',
                        'Ditolak'           => 'pmj-badge-muted',
                        default             => 'pmj-badge-muted',
                    };
                @endphp
                <tr>
                    <td><code style="font-size:11px;color:#2563EB;">{{ $p->nomor_transaksi }}</code></td>
                    <td>
                        <div style="font-weight:600;">{{ $p->borrower_name }}</div>
                        <small class="text-muted">{{ $p->borrower_role }}</small>
                    </td>
                    <td>
                        <div>{{ $p->barang->nama_barang ?? '-' }}</div>
                        <small class="text-muted">{{ $p->barang->kode_barang ?? '' }}</small>
                    </td>
                    <td>{{ $p->jumlah_pinjam }} {{ $p->barang->satuan ?? '' }}</td>
                    <td style="font-size:12.5px;">{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                    <td style="font-size:12.5px;">
                        {{ $p->tanggal_kembali_rencana?->format('d M Y') }}
                        @if($p->terlambat)
                            <br><small class="text-danger"><i class="bi bi-clock me-1"></i>{{ $p->hari_terlambat }} hari terlambat</small>
                        @endif
                    </td>
                    <td><span class="pmj-badge {{ $statusClass }}">{{ $p->status }}</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1 flex-wrap">
                            <a href="{{ route('admin.peminjaman-barang.show', $p) }}" class="btn btn-sm btn-secondary">Detail</a>

                            @if($p->status === 'Menunggu')
                                {{-- Tombol ACC --}}
                                <form method="POST" action="{{ route('admin.peminjaman-barang.acc', $p) }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                {{-- Tombol Tolak --}}
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal{{ $p->id }}">Tolak</button>
                            @endif

                            @if(in_array($p->status, ['Sedang Dipinjam','Terlambat','Disetujui']))
                                {{-- Tombol Kembalikan --}}
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kembaliModal{{ $p->id }}">Kembalikan</button>
                            @endif
                        </div>

                        {{-- Modal Tolak --}}
                        @if($p->status === 'Menunggu')
                        <div class="modal fade" id="tolakModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header" style="border-bottom:1px solid #F1F5F9;">
                                        <h6 class="modal-title fw-bold">Tolak Peminjaman</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.peminjaman-barang.tolak', $p) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted">Alasan penolakan untuk <strong>{{ $p->nomor_transaksi }}</strong>:</p>
                                            <textarea name="catatan_admin" class="form-control" rows="3" required placeholder="Tulis alasan penolakan…"></textarea>
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid #F1F5F9;">
                                            <button type="button" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;border:none;" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak Permintaan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Modal Kembalikan --}}
                        @if(in_array($p->status, ['Sedang Dipinjam','Terlambat','Disetujui']))
                        <div class="modal fade" id="kembaliModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header" style="border-bottom:1px solid #F1F5F9;">
                                        <h6 class="modal-title fw-bold">Proses Pengembalian Barang</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.peminjaman-barang.kembalikan', $p) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted">Periksa kondisi fisik <strong>{{ $p->barang->nama_barang }}</strong> yang dikembalikan:</p>
                                            <div class="row g-2">
                                                @foreach(['Baik','Rusak Ringan','Rusak Berat'] as $k)
                                                <div class="col-4">
                                                    <input type="radio" class="btn-check" name="kondisi_barang" id="k{{ $p->id }}{{ Str::slug($k) }}" value="{{ $k }}" required>
                                                    <label class="btn btn-outline-secondary w-100" for="k{{ $p->id }}{{ Str::slug($k) }}" style="font-size:12px;border-radius:10px;">
                                                        {!! $k === 'Baik' ? '✅' : ($k === 'Rusak Ringan' ? '⚠️' : '❌') !!} {{ $k }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                            <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i>Jika kondisi rusak, catatan perbaikan akan dibuat otomatis.</small>
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid #F1F5F9;">
                                            <button type="button" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;border:none;" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Konfirmasi Pengembalian</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94A3B8;">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada data peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjamans->hasPages())
    <div class="card-footer">{{ $peminjamans->links() }}</div>
    @endif
</div>

@endsection

@push('css')
<style>
.pmj-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:700; }
.pmj-badge-warning { background:#FFFBEB; color:#78350F; }
.pmj-badge-info    { background:#F0F9FF; color:#0C4A6E; }
.pmj-badge-blue    { background:#EFF6FF; color:#1D4ED8; }
.pmj-badge-success { background:#ECFDF5; color:#065F46; }
.pmj-badge-danger  { background:#FEF2F2; color:#991B1B; }
.pmj-badge-muted   { background:#F1F5F9; color:#64748B; }

.inv-btn-detail { background:#EFF6FF;color:#2563EB;border:none;border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center; }
.inv-btn-detail:hover { background:#DBEAFE;color:#1D4ED8; }
.inv-btn-hapus  { background:#FEF2F2;color:#DC2626;border:none;border-radius:8px;width:32px;height:32px;display:flex;align-items:center;justify-content:center; }
.inv-btn-hapus:hover { background:#FEE2E2;color:#991B1B; }
</style>
@endpush
