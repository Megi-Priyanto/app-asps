@extends('layouts.pegawai')

@section('title', 'Peminjaman Barang')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Peminjaman Barang</h5>
        <div style="font-size:12px;color:#94A3B8;">Riwayat dan status permintaan peminjaman Anda</div>
    </div>
    <a href="{{ route('pegawai.peminjaman-barang.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Buat Permintaan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        @if($peminjamans->isEmpty())
            <div class="text-center py-5" style="color:#94A3B8;">
                <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                <div style="font-size:15px;font-weight:600;color:#64748B;">Belum ada permintaan peminjaman</div>
                <a href="{{ route('pegawai.peminjaman-barang.create') }}" class="btn btn-primary btn-sm mt-3">
                    <i class="bi bi-plus-lg me-1"></i>Buat Sekarang
                </a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>No. Transaksi</th><th>Barang</th><th>Jml</th><th>Tgl Pinjam</th><th>Rencana Kembali</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($peminjamans as $p)
                    @php
                        $sc = match($p->status) {
                            'Menunggu'=>'pmj-badge-warning','Disetujui'=>'pmj-badge-info',
                            'Sedang Dipinjam'=>'pmj-badge-blue','Sudah Dikembalikan'=>'pmj-badge-success',
                            'Terlambat'=>'pmj-badge-danger','Ditolak'=>'pmj-badge-muted', default=>'pmj-badge-muted'
                        };
                    @endphp
                    <tr>
                        <td><code style="font-size:11px;color:#2563EB;">{{ $p->nomor_transaksi }}</code></td>
                        <td><div style="font-weight:600;">{{ $p->barang->nama_barang ?? '-' }}</div></td>
                        <td>{{ $p->jumlah_pinjam }} {{ $p->barang->satuan ?? '' }}</td>
                        <td style="font-size:12.5px;">{{ $p->tanggal_pinjam?->format('d M Y') }}</td>
                        <td style="font-size:12.5px;">{{ $p->tanggal_kembali_rencana?->format('d M Y') }}
                            @if($p->terlambat)<br><small class="text-danger">Terlambat {{ $p->hari_terlambat }} hari</small>@endif
                        </td>
                        <td><span class="pmj-badge {{ $sc }}">{{ $p->status }}</span>
                            @if($p->status === 'Ditolak' && $p->catatan_admin)
                                <br><small class="text-muted">{{ $p->catatan_admin }}</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @if($peminjamans->hasPages())
    <div class="card-footer">{{ $peminjamans->links() }}</div>
    @endif
</div>

@endsection

@push('css')
<style>
.pmj-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;}
.pmj-badge-warning{background:#FFFBEB;color:#78350F;}.pmj-badge-info{background:#F0F9FF;color:#0C4A6E;}
.pmj-badge-blue{background:#EFF6FF;color:#1D4ED8;}.pmj-badge-success{background:#ECFDF5;color:#065F46;}
.pmj-badge-danger{background:#FEF2F2;color:#991B1B;}.pmj-badge-muted{background:#F1F5F9;color:#64748B;}
</style>
@endpush
