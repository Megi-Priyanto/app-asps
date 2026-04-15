@extends('layouts.superadmin')

@section('title', 'Laporan Inventaris Barang')

@section('content')

<div class="mb-4">
    <h5 class="mb-0 fw-bold">Laporan Inventaris Barang</h5>
    <div style="font-size:12px;color:#94A3B8;">Ringkasan kondisi inventaris dan aktivitas peminjaman sekolah</div>
</div>

{{-- Stats Inventaris --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-archive-fill"></i></div>
            <div><div class="stat-label">Total Barang</div><div class="stat-value">{{ $totalBarang }}</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div><div class="stat-label">Kondisi Baik</div><div class="stat-value">{{ $barangBaik }}</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div><div class="stat-label">Perlu Perbaikan</div><div class="stat-value">{{ $barangRusak }}</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hand-index-thumb-fill"></i></div>
            <div><div class="stat-label">Bisa Dipinjam</div><div class="stat-value">{{ $barangPinjaman }}</div></div>
        </div>
    </div>
</div>

{{-- Stats Peminjaman --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
            <div><div class="stat-label">Total Peminjaman</div><div class="stat-value">{{ $totalPeminjaman }}</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-label">Menunggu ACC</div><div class="stat-value">{{ $peminjamanMenunggu }}</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-box-arrow-up-right"></i></div>
            <div><div class="stat-label">Aktif Dipinjam</div><div class="stat-value">{{ $peminjamanAktif }}</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-clock-history"></i></div>
            <div><div class="stat-label">Terlambat</div><div class="stat-value">{{ $peminjamanTerlambat }}</div></div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Barang Populer --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Barang Paling Sering Dipinjam</div>
            <div class="card-body p-0">
                @forelse($barangPopuler as $b)
                <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #F1F5F9;">
                    <div style="width:36px;height:36px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#2563EB;">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:13.5px;">{{ $b->nama_barang }}</div>
                        <div style="font-size:12px;color:#94A3B8;">{{ $b->kode_barang }}</div>
                    </div>
                    <div style="font-weight:700;color:#2563EB;font-size:15px;">{{ $b->peminjaman_barangs_count }}x</div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">Belum ada data peminjaman</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Perbaikan Terbaru --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Perbaikan Terbaru</div>
            <div class="card-body p-0">
                @forelse($perbaikanTerbaru as $p)
                @php
                    $sc = match($p->status) {
                        'Menunggu'=>'pmj-badge-warning','Dalam Perbaikan'=>'pmj-badge-blue','Selesai'=>'pmj-badge-success', default=>'pmj-badge-muted'
                    };
                @endphp
                <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid #F1F5F9;">
                    <div class="flex-1" style="flex:1;">
                        <div style="font-weight:600;font-size:13.5px;">{{ $p->barang->nama_barang ?? '-' }}</div>
                        <div style="font-size:12px;color:#94A3B8;">{{ $p->tingkat_kerusakan }} · {{ $p->tanggal_masuk?->format('d M Y') }}</div>
                    </div>
                    <span class="pmj-badge {{ $sc }}">{{ $p->status }}</span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">Belum ada catatan perbaikan</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Peminjaman Terakhir --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">Peminjaman Terbaru</div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>No. Transaksi</th><th>Peminjam</th><th>Barang</th><th>Status</th><th>Tgl</th></tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanTerbaru as $p)
                        @php
                            $sc = match($p->status) {
                                'Menunggu'=>'pmj-badge-warning','Sedang Dipinjam'=>'pmj-badge-blue',
                                'Sudah Dikembalikan'=>'pmj-badge-success','Terlambat'=>'pmj-badge-danger',
                                'Ditolak'=>'pmj-badge-muted', default=>'pmj-badge-muted'
                            };
                        @endphp
                        <tr>
                            <td><code style="font-size:11px;color:#2563EB;">{{ $p->nomor_transaksi }}</code></td>
                            <td><div style="font-weight:600;">{{ $p->borrower_name }}</div><small>{{ $p->borrower_role }}</small></td>
                            <td>{{ $p->barang->nama_barang ?? '-' }}</td>
                            <td><span class="pmj-badge {{ $sc }}">{{ $p->status }}</span></td>
                            <td style="font-size:12px;">{{ $p->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada peminjaman</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
