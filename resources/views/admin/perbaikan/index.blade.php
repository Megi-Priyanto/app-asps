@extends('layouts.admin')

@section('title', 'Perbaikan Barang')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-label">Menunggu</div><div class="stat-value">{{ $totalMenunggu }}</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-wrench-adjustable"></i></div>
            <div><div class="stat-label">Dalam Perbaikan</div><div class="stat-value">{{ $totalProses }}</div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div><div class="stat-label">Selesai</div><div class="stat-value">{{ $totalSelesai }}</div></div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(['Menunggu','Dalam Perbaikan','Selesai'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">Daftar Catatan Perbaikan Barang</div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Perbaikan</th>
                    <th>Barang</th>
                    <th>Dari Peminjaman</th>
                    <th>Jumlah Rusak</th>
                    <th>Tingkat</th>
                    <th>Status</th>
                    <th>Tgl Masuk</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perbaikans as $p)
                @php
                    $sc = match($p->status) {
                        'Menunggu'=>'pmj-badge-warning','Dalam Perbaikan'=>'pmj-badge-blue','Selesai'=>'pmj-badge-success', default=>'pmj-badge-muted'
                    };
                    $tkClass = $p->tingkat_kerusakan === 'Rusak Ringan' ? 'pmj-badge-warning' : 'pmj-badge-danger';
                @endphp
                <tr>
                    <td><code style="font-size:11px;color:#2563EB;">{{ $p->nomor_perbaikan }}</code></td>
                    <td>
                        <div style=" font-weight:600;">{{ $p->barang->nama_barang ?? '-' }}</div>
                        <small class="text-muted">{{ $p->barang->kode_barang ?? '' }}</small>
                    </td>
                    <td style="font-size:12px;">
                        @if($p->peminjaman)
                            <div>{{ $p->peminjaman->nomor_transaksi }}</div>
                            <div class="text-muted">{{ $p->peminjaman->borrower_name }}</div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $p->jumlah_rusak }} {{ $p->barang->satuan ?? '' }}</td>
                    <td><span class="pmj-badge {{ $tkClass }}">{{ $p->tingkat_kerusakan }}</span></td>
                    <td><span class="pmj-badge {{ $sc }}">{{ $p->status }}</span></td>
                    <td style="font-size:12.5px;">{{ $p->tanggal_masuk?->format('d M Y') }}</td>
                    <td class="text-center">
                        @if($p->status !== 'Selesai')
                        <button type="button" class="btn btn-sm" style="background:#EFF6FF;color:#2563EB;border:none;border-radius:8px;font-size:12px;padding:5px 12px;"
                            data-bs-toggle="modal" data-bs-target="#updateModal{{ $p->id }}">
                            <i class="bi bi-wrench me-1"></i>Update
                        </button>

                        {{-- Modal Update Status --}}
                        <div class="modal fade" id="updateModal{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius:16px;">
                                    <div class="modal-header" style="border-bottom:1px solid #F1F5F9;">
                                        <h6 class="modal-title fw-bold">Update Status Perbaikan</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.perbaikan-barang.update-status', $p) }}">
                                        @csrf @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Status Baru</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Menunggu" {{ $p->status=='Menunggu'?'selected':'' }}>Menunggu</option>
                                                    <option value="Dalam Perbaikan" {{ $p->status=='Dalam Perbaikan'?'selected':'' }}>Dalam Perbaikan</option>
                                                    <option value="Selesai">✅ Selesai</option>
                                                </select>
                                            </div>
                                            <div class="mb-3" id="selesaiFields{{ $p->id }}" style="display:none;">
                                                <label class="form-label">Tanggal Selesai</label>
                                                <input type="date" name="tanggal_selesai" class="form-control" value="{{ date('Y-m-d') }}">
                                                <label class="form-label mt-2">Biaya Perbaikan (Rp)</label>
                                                <input type="number" name="biaya_perbaikan" class="form-control" placeholder="0" min="0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan</label>
                                                <textarea name="catatan_perbaikan" class="form-control" rows="2">{{ $p->catatan_perbaikan }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid #F1F5F9;">
                                            <button type="button" class="btn btn-sm" style="background:#F1F5F9;color:#64748B;border:none;" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const modal{{ $p->id }} = document.getElementById('updateModal{{ $p->id }}');
                            if (modal{{ $p->id }}) {
                                modal{{ $p->id }}.querySelector('select[name=status]').addEventListener('change', function() {
                                    document.getElementById('selesaiFields{{ $p->id }}').style.display =
                                        this.value === 'Selesai' ? 'block' : 'none';
                                });
                            }
                        });
                        </script>
                        @else
                            <span class="pmj-badge pmj-badge-success"><i class="bi bi-check2-circle me-1"></i>Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94A3B8;">
                        <i class="bi bi-tools fs-2 d-block mb-2"></i>Belum ada catatan perbaikan.
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

@push('css')
<style>
.pmj-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;}
.pmj-badge-warning{background:#FFFBEB;color:#78350F;}.pmj-badge-blue{background:#EFF6FF;color:#1D4ED8;}
.pmj-badge-success{background:#ECFDF5;color:#065F46;}.pmj-badge-danger{background:#FEF2F2;color:#991B1B;}
.pmj-badge-muted{background:#F1F5F9;color:#64748B;}
</style>
@endpush
