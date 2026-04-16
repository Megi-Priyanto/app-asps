@extends('layouts.admin')

@section('title', 'Detail Peminjaman - ' . $peminjamanBarang->nomor_transaksi)

@section('content')

@if(session('success'))
    <div class="alert alert-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header">Informasi Peminjaman</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Peminjam</div>
                        <div style="font-weight:700;font-size:15px;">{{ $peminjamanBarang->borrower_name }}</div>
                        <div style="font-size:12px;color:#64748B;">{{ $peminjamanBarang->borrower_role }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Status</div>
                        @php
                            $sc = match($peminjamanBarang->status) {
                                'Menunggu'=>'pmj-badge-warning','Sedang Dipinjam'=>'pmj-badge-blue',
                                'Sudah Dikembalikan'=>'pmj-badge-success','Terlambat'=>'pmj-badge-danger',
                                'Ditolak'=>'pmj-badge-muted','Disetujui'=>'pmj-badge-info', default=>'pmj-badge-muted'
                            };
                        @endphp
                        <span class="pmj-badge {{ $sc }}" style="font-size:13px;padding:5px 14px;">{{ $peminjamanBarang->status }}</span>
                    </div>
                    <div class="col-12"><hr style="border-color:#F1F5F9;"></div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Barang</div>
                        <div style="font-weight:600;">{{ $peminjamanBarang->barang->nama_barang ?? '-' }}</div>
                        <div style="font-size:12px;color:#2563EB;">{{ $peminjamanBarang->barang->kode_barang ?? '' }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Jumlah Pinjam</div>
                        <div style="font-weight:700;font-size:18px;">{{ $peminjamanBarang->jumlah_pinjam }}
                            <span style="font-size:13px;font-weight:400;color:#64748B;">{{ $peminjamanBarang->barang->satuan ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Tgl Pinjam</div>
                        <div>{{ $peminjamanBarang->tanggal_pinjam?->format('d M Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Rencana Kembali</div>
                        <div>{{ $peminjamanBarang->tanggal_kembali_rencana?->format('d M Y') }}</div>
                        @if($peminjamanBarang->terlambat)
                            <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Terlambat {{ $peminjamanBarang->hari_terlambat }} hari</small>
                        @endif
                    </div>
                    @if($peminjamanBarang->tanggal_kembali_aktual)
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Tgl Kembali Aktual</div>
                        <div>{{ $peminjamanBarang->tanggal_kembali_aktual->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Kondisi Dikembalikan</div>
                        <div>{{ $peminjamanBarang->kondisi_barang ?? '-' }}</div>
                    </div>
                    @endif
                    <div class="col-12">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Keperluan</div>
                        <div>{{ $peminjamanBarang->keperluan ?? '-' }}</div>
                    </div>
                    @if($peminjamanBarang->catatan_admin)
                    <div class="col-12">
                        <div style="font-size:11.5px;color:#94A3B8;font-weight:600;text-transform:uppercase;">Catatan Petugas</div>
                        <div>{{ $peminjamanBarang->catatan_admin }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        @if($peminjamanBarang->perbaikanBarang)
        <div class="card" style="border-left:4px solid #F59E0B;">
            <div class="card-header" style="color:#78350F;background:#FFFBEB;">
                <i class="bi bi-tools me-2"></i>Catatan Perbaikan
            </div>
            <div class="card-body p-4">
                <div class="mb-2">
                    <span style="font-size:11.5px;color:#94A3B8;font-weight:600;">No. Perbaikan</span>
                    <div><code>{{ $peminjamanBarang->perbaikanBarang->nomor_perbaikan }}</code></div>
                </div>
                <div class="mb-2">
                    <span style="font-size:11.5px;color:#94A3B8;font-weight:600;">Tingkat Kerusakan</span>
                    <div>{{ $peminjamanBarang->perbaikanBarang->tingkat_kerusakan }}</div>
                </div>
                <div class="mb-2">
                    <span style="font-size:11.5px;color:#94A3B8;font-weight:600;">Status Perbaikan</span>
                    <div><strong>{{ $peminjamanBarang->perbaikanBarang->status }}</strong></div>
                </div>
                <div>
                    <span style="font-size:11.5px;color:#94A3B8;font-weight:600;">Keterangan</span>
                    <div style="font-size:13px;">{{ $peminjamanBarang->perbaikanBarang->keterangan_kerusakan }}</div>
                </div>
            </div>
        </div>
        @else
        <div class="card h-100" style="display:flex;align-items:center;justify-content:center;min-height:200px;">
            <div class="text-center p-4" style="color:#94A3B8;">
                <i class="bi bi-tools fs-2 d-block mb-2"></i>
                <div>Tidak ada catatan perbaikan</div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('css')
<style>
.pmj-badge { display:inline-block;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700; }
.pmj-badge-warning{background:#FFFBEB;color:#78350F;}.pmj-badge-info{background:#F0F9FF;color:#0C4A6E;}
.pmj-badge-blue{background:#EFF6FF;color:#1D4ED8;}.pmj-badge-success{background:#ECFDF5;color:#065F46;}
.pmj-badge-danger{background:#FEF2F2;color:#991B1B;}.pmj-badge-muted{background:#F1F5F9;color:#64748B;}
</style>
@endpush
